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
     * @param $participant
     * @return bool
     */
    public function saveParticipant($participant)
    {

        if ($this->isThereAvailableSpotsInAppointment($participant['event_id'], $participant['record_id'])) {
            if (!$this->isUserBookedSlotForThatDay($participant['email'], $participant['date'],
                $participant['project_id'], $participant['event_id'])) {
                $sql = sprintf("INSERT INTO redcap_appointment_participant (email, `name`, mobile, record_id, notes, private, `type`, `status`, created_at) VALUES ('$participant[email]', '$participant[name]', '$participant[mobile]', '$participant[record_id]', '$participant[notes]', '$participant[private]','$participant[type]','$participant[status]', " . time() . ")"
                );

                if (!db_query($sql)) {
                    throw new \LogicException('cant save participant');
                }
                return true;
            } else {
                throw new \LogicException('User already has an appointment for same date.');
            }
        } else {
            throw new \LogicException('No available spots for select time slot.');
        }
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return array
     */
    private function getSlotNumberOfParticipants($event_id, $record_id)
    {
        try {
            if ($event_id) {

                /*
                 * TODO Check if date within allowed window
                 */
                $filter = "[record_id] = '" . $record_id . "'";
                $param = array(
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'fields' => array('number_of_participants'),
                    'events' => REDCap::getEventNames(true, false, $event_id)
                );
                $record = REDCap::getData($param);
                return $record[$record_id][$event_id]['number_of_participants'];
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
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
        $sql = sprintf("SELECT id from redcap_appointment_participant ra WHERE ra.record_id = $record_id AND rs.status = " . RESERVED . " ");

        $r = db_query($sql);
        $count = db_num_rows($r);

        if ($count == 0 || is_null($count)) {
            return 0;
        } else {
            return $count;
        }
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return bool
     */
    private function isThereAvailableSpotsInAppointment($event_id, $record_id)
    {
        if ($this->getSlotNumberOfParticipants($event_id,
                $record_id) > $this->getSlotActualCountReservedSpots($record_id)) {
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