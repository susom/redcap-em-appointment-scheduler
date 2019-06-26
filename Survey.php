<?php

namespace Stanford\AppointmentScheduler;

class Survey
{

    /**
     * @param $eventId
     * @param $email
     * @return bool
     */
    public function assignSurvey($eventId, $email)
    {
        $participantId = $this->getFirstAvailableSurvey($eventId);
        if (!is_null($participantId)) {
            $sql = sprintf("UPDATE  redcap_surveys_participants SET participant_email = $email WHERE participant_id = $participantId");

            if (!db_query($sql)) {
                throw new \LogicException('cant update survey participant');
            } else {
                return true;
            }
        }
    }

    /**
     * @param int $eventId
     * @return int|null
     */
    private function getFirstAvailableSurvey($eventId)
    {
        $sql = sprintf("SELECT participant_id FROM redcap_surveys_participants WHERE event_id = $eventId");

        $result = db_query($sql);
        if (!db_query($sql)) {
            throw new \LogicException('cant update participant');
        } else {
            $data = mysqli_fetch_row($result);
            return $data['participant_id'];
        }
    }
}