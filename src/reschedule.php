<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $suffix = $module->getSuffix();
    $data['record_id'] = filter_var($_POST['record_id'], FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($data['record_id'] == '') {
        throw new \LogicException('Record ID is missing');
    }
    if ($eventId == '') {
        throw new \LogicException('Event ID is missing');
    }
    if (!SUPER_USER) {
        throw new \LogicException('You should not be here');
    } else {
        $data['start' . $suffix] = date('Y-m-d H:i:s', strtotime(preg_replace("([^0-9/])", "", $_POST['start'])));
        $data['end' . $suffix] = date('Y-m-d H:i:s', strtotime(preg_replace("([^0-9/])", "", $_POST['end'])));
        $data['notes' . $suffix] = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
        $data['instructor' . $suffix] = filter_var($_POST['instructor'], FILTER_SANITIZE_STRING);
        $data['location' . $suffix] = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $eventId);
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (!empty($response['errors'])) {
            throw new \LogicException(implode("\n", $response['errors']));
        } else {
            //TODO notify participants about the cancellation
            echo json_encode(array('status' => 'ok', 'message' => 'Slot Updated successfully!'));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}