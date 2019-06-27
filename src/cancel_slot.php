<?php

namespace Stanford\AppointmentScheduler;

use REDCap;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $suffix = $module->getSuffix();
    $data['record_id'] = filter_var($_GET['record_id'], FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($data['record_id'] == '') {
        throw new \LogicException('Record ID is missing');
    }
    if ($eventId == '') {
        throw new \LogicException('Event ID is missing');
    }
    if (!SUPER_USER) {
        throw new \LogicException('You should not be here');
    } else {
        $data['slot_status' . $suffix] = CANCELED;
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $eventId);
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (!empty($response['errors'])) {
            throw new \LogicException(implode("\n", $response['errors']));
        } else {

            $slot = AppointmentScheduler::getSlot($data['record_id'], $data['event_id']);
            $message['subject'] = $message['body'] = 'Your ' . REDCap::getEventNames(false, false,
                    $data['event_id']) . ' at' . date('m/d/Y',
                    strtotime($slot['start' . $suffix])) . ' at ' . date('H:i',
                    strtotime($slot['start' . $suffix])) . ' to ' . date('H:i',
                    strtotime($slot['end' . $suffix])) . ' has been canceled';
            $reservationEventId = $module->getReservationEventIdViaSlotEventId($data['event_id']);

            $module->notifyParticipants($data['record_id'], $reservationEventId, $message);
            echo json_encode(array('status' => 'ok', 'message' => 'Slot canceled successfully!'));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}