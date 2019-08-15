<?php

namespace Stanford\AppointmentScheduler;


class Participant
{

    /**
     * @param string $email
     * @param string $date
     * @param int $project_id
     * @param int $event_id
     * @return bool
     */
    public function isUserBookedSlotForThatDay($email, $date, $project_id, $event_id)
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
    public function getParticipationSlotData($recodId, $projectId, $primary)
    {
        try {
            $filter = "[$primary] = '" . $recodId . "'";
            $param = array(
                'project_id' => $projectId,
                'filterLogic' => $filter,
                'return_format' => 'array',
            );
            $record = \REDCap::getData($param);
            return $record[$recodId];
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param int $record_id
     * @return int
     */
    public function getSlotActualCountReservedSpots($slotId, $eventId, $suffix, $projectId)
    {
        try {
            $counter = 0;
            $param = array(
                'project_id' => $projectId,
                'return_format' => 'array',
                'events' => $eventId
            );
            $records = \REDCap::getData($param);
            foreach ($records as $record) {
                if ($record[$eventId]["slot_id$suffix"] == $slotId && $record[$eventId]["participant_status$suffix"] == RESERVED) {
                    $counter++;
                }
            }
            return $counter;
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param int $record_id
     * @return bool|\mysqli_result
     */
    public function getSlotActualReservedSpots($slotId, $eventId, $projectId)
    {
        try {

            $filter = "[slot_id] = '" . $slotId . "' AND [participant_status] ='" . RESERVED . "'";
            $param = array(
                'project_id' => $projectId,
                'filterLogic' => $filter,
                'return_format' => 'array',
                'events' => $eventId
            );
            $record = \REDCap::getData($param);
            return $record;
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * @param int $record_id
     * @return bool|\mysqli_result
     */
    public function getSlotParticipants($recordId, $eventId, $suffix, $projectId)
    {
        try {

            $filter = "[slot_id$suffix] = '" . $recordId . "'";
            $param = array(
                'project_id' => $projectId,
                'filterLogic' => $filter,
                'return_format' => 'array',
                'events' => $eventId
            );
            $record = \REDCap::getData($param);
            return $record;
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }
    /**
     * @param int $event_id
     * @param int $record_id
     * @return bool
     */
    public function isThereAvailableSpotsInAppointment($event_id, $record_id, $projectId, $primary)
    {
        $slot = AppointmentScheduler::getSlot($record_id, $event_id, $projectId, $primary);
        if ($slot['number_of_participants'] > $this->getSlotActualCountReservedSpots($record_id)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param $sunetID
     * @param $suffix
     * @param $projectId
     * @param null $status
     * @return mixed
     */
    public function getUserParticipation($sunetID, $suffix, $projectId, $status = null)
    {
        try {
            if (is_null($status)) {
                $filter = "[sunet_id$suffix] = '" . $sunetID . "'";
            } else {
                $filter = "[sunet_id$suffix] = '" . $sunetID . "' AND [participant_status$suffix] = $status";
            }
            $param = array(
                'project_id' => $projectId,
                'filterLogic' => $filter,
                'return_format' => 'array'
            );
            $records = \REDCap::getData($param);
            return $records;
        } catch (\LogicException $e) {
            echo $e->getMessage();
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

    public function getUserParticipationViaStatus($records, $status, $suffix)
    {
        $result = array();
        foreach ($records as $record) {
            $participation = end($record);
            $eventId = key($record);
            $participation['event_id'] = $eventId;
            if ($participation['participant_status' . $suffix] == $status) {
                $result[] = $participation;
            }
        }
        return $result;
    }
}