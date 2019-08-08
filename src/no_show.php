<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    /**
     * check if user still logged in
     */
    if (!SUPER_USER) {
        throw new \LogicException('You cant be here');
    }
    $data['record_id'] = filter_var($_GET[\REDCap::getRecordIdField()], FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($data['record_id'] == '') {
        throw new \LogicException('Participation ID is missing');
    }
    if ($eventId == '') {
        throw new \LogicException('Event ID is missing');
    } else {

        $data['participant_status'] = NO_SHOW;
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $eventId);
        $response = \REDCap::saveData('json', json_encode(array($data)));

        if (empty($response['errors'])) {
            //TODO notify instructor about the cancellation
            echo json_encode(array('status' => 'ok', 'message' => 'Appointment canceled successfully!'));
        } else {
            throw new \LogicException(implode(",", $response['errors']));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}