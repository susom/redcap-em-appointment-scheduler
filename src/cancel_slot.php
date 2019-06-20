<?php

namespace Stanford\AppointmentScheduler;

use REDCap;

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

            $slot = $module->getSlot($data['record_id'], $data['event_id']);
            $message['subject'] = $message['body'] = 'Your ' . REDCap::getEventNames(false, false,
                    $data['event_id']) . ' at' . date('m/d/Y', strtotime($slot['start'])) . ' at ' . date('H:i',
                    strtotime($slot['start'])) . ' to ' . date('H:i', strtotime($slot['end'])) . ' has been canceled';
            $module->notifyParticipants($data['record_id'], $message);
            echo json_encode(array('status' => 'ok', 'message' => 'Slot canceled successfully!'));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}