<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $primary = \REDCap::getRecordIdField();
    $data[$primary] = $_GET[$primary];
    $eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($data[$primary] == '') {
        throw new \LogicException('Participation ID is missing');
    } else {

        $data['participant_status' . $module->getSuffix()] = CANCELED;
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