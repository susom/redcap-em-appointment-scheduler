<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\LanguageEditor\LanguageEditor $this */

use REDCap;


trait Participant
{

    /**
     * @param string $email
     * @param string $date
     * @param int $project_id
     * @param int $event_id
     * @return bool
     */
    private function isUserBookedSlotForThatDay($email, $date, $project_id, $event_id)
    {
        /**
         * Let see if user booked something else for same date, we will validate via email
         * TODO we can verify via user_id or SUNet ID if we decided to do so
         */

        $range = "rd.value > '" . date('Y-m-d', strtotime($date)) . "' AND " . "rd.value < '" . date('Y-m-d',
                strtotime($date . ' + 1 DAY')) . "'";

        $sql = sprintf("SELECT id from redcap_appointment_participant ra JOIN redcap_data rd ON ra.record_id = rd.record WHERE rd.project_id = $project_id AND event_id = $event_id AND $range AND ra.email = '$email'");

        $r = db_query($sql);
        $count = db_num_rows($r);

        if ($count > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return array
     */
    public function getParticipationSlotData($record_id)
    {
        try {
            /*
                 * TODO Check if date within allowed window
                 */
            $filter = "[record_id] = '" . $record_id . "'";
            $param = array(
                'filterLogic' => $filter,
                'return_format' => 'array',
            );
            $record = REDCap::getData($param);
            return $record[$record_id];
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param int $record_id
     * @return int
     */
    public function getSlotActualCountReservedSpots($record_id)
    {
        /**
         * Get how many reserved spots
         */
        $sql = sprintf("SELECT id from redcap_appointment_participant ra WHERE ra.record_id = $record_id AND ra.status = " . RESERVED . " ");

        $r = db_query($sql);
        $count = db_num_rows($r);

        if ($count == 0 || is_null($count)) {
            return 0;
        } else {
            return $count;
        }
    }

    /**
     * @param int $record_id
     * @return bool|\mysqli_result
     */
    public function getSlotActualReservedSpots($record_id)
    {
        /**
         * Get how many reserved spots
         */
        $sql = sprintf("SELECT email, name from redcap_appointment_participant ra WHERE ra.record_id = $record_id AND ra.status = " . RESERVED . " ");

        $result = db_query($sql);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return bool
     */
    private function isThereAvailableSpotsInAppointment($event_id, $record_id)
    {
        $slot = $this->getSlot($record_id, $event_id);
        if ($slot['number_of_participants'] > $this->getSlotActualCountReservedSpots($record_id)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $email
     * @return bool|mysqli_result
     */
    public function getUserParticipation($email, $status = null)
    {

        if (is_null($status)) {
            $sql = sprintf("SELECT * from redcap_appointment_participant ra WHERE ra.email = '$email'");
        } else {
            $sql = sprintf("SELECT * from redcap_appointment_participant ra WHERE ra.email = '$email' AND ra.status = $status");

        }

        if ($result = db_query($sql)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param array $data
     * @param int $id
     */
    public function updateParticipation($data, $id)
    {
        $filters = '';
        foreach ($data as $key => $value) {
            $filters = " $key = '$value' ,";
        }

        $filters = rtrim($filters, ",");
        $sql = sprintf("UPDATE  redcap_appointment_participant SET $filters WHERE id = $id");

        if (!db_query($sql)) {
            throw new \LogicException('cant update participant');
        }
    }
}