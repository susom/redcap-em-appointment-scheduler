<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $data['record_id'] = filter_var($_GET['record_id'], FILTER_SANITIZE_NUMBER_INT);
    $data['event_id'] = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($data['record_id'] == '') {
        throw new \LogicException('Record ID is missing');
    }
    if ($data['event_id'] == '') {
        throw new \LogicException('Event ID is missing');
    }
    if (!SUPER_USER) {
        throw new \LogicException('You should not be here');
    } else {
        $data['slot_status'] = CANCELED;
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (!empty($response['errors'])) {
            throw new \LogicException(implode("\n", $response['errors']));
        } else {
            //TODO notify participants about the cancellation
            echo json_encode(array('status' => 'ok', 'message' => 'Slot canceled successfully!'));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}