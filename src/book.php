<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $data = $module->sanitizeInput();
    if ($data['email'] == '' || $data['name'] == '' || $data['mobile'] == '') {
        throw new \LogicException('Data cant be missing');
    } else {

        $data['participant_status'] = RESERVED;

        /**
         * let mark it as complete so we can send the survey if needed.
         */
        $data['reservation_complete'] = REDCAP_COMPLETE;
        $data['event_id'] = $module->getReservationEventIdViaSlotEventId($data['event_id']);
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $data['event_id']);
        $data['record_id'] = $module->getNextRecordsId($data['event_id'], PROJECT_ID);
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (empty($response['errors'])) {
            //$module->notifyUser($data);
            echo json_encode(array('status' => 'ok', 'message' => 'Appointment saved successfully!'));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}