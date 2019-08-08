<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {

    $data = $module->sanitizeInput();
    if ($data['email' . $module->getSuffix()] == '' || $data['name' . $module->getSuffix()] == '' || $data['mobile' . $module->getSuffix()] == '') {
        throw new \LogicException('Data cant be missing');
    } else {


        $data['participant_status' . $module->getSuffix()] = RESERVED;
        $reservationEventId = $module->getReservationEventIdViaSlotEventId($data['event_id']);
        $date = date('Y-m-d', strtotime(preg_replace("([^0-9/])", "", $_POST['calendarDate'])));
        $module->doesUserHaveSameDateReservation($date, $data['email' . $module->getSuffix()], $module->getSuffix(),
            $data['event_id'], $reservationEventId);
        /**
         * let mark it as complete so we can send the survey if needed.
         * Complete status has different naming convention based on the instrument name. so you need to get instrument name and append _complete to it.
         */
        $labels = \REDCap::getValidFieldsByEvents(PROJECT_ID, array($reservationEventId));
        $completed = preg_grep('/_complete$/', $labels);
        $second = array_slice($completed, 1, 1);  // array("status" => 1)

        $data[$second] = REDCAP_COMPLETE;

        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $reservationEventId);
        $data[\REDCap::getRecordIdField()] = $module->getNextRecordsId($reservationEventId, PROJECT_ID);
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (empty($response['errors'])) {
            $module->notifyUser($data);
            echo json_encode(array(
                'status' => 'ok',
                'message' => 'Appointment saved successfully!',
                'id' => array_pop($response['ids']),
                'email' => $data['email']
            ));
        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
} catch (\Exception $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}