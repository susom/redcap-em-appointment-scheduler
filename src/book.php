<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {

    if (!defined('USERID')) {
        throw new \LogicException('Please login.');
    }

    $data = $module->sanitizeInput();
    if ($data['email' . $module->getSuffix()] == '' || $data['name' . $module->getSuffix()] == '' || $data['mobile' . $module->getSuffix()] == '') {
        throw new \LogicException('Data cant be missing');
    } else {


        $data['participant_status' . $module->getSuffix()] = RESERVED;
        $data['sunet_id' . $module->getSuffix()] = USERID;
        $reservationEventId = $module->getReservationEventIdViaSlotEventId($data['event_id']);
        $slot = $module::getSlot(filter_var($_POST['record_id'], FILTER_SANITIZE_STRING), $data['event_id'],
            $module->getProjectId(), $module->getPrimaryRecordFieldName());
        //$module->doesUserHaveSameDateReservation($date, USERID, $module->getSuffix(),$data['event_id'], $reservationEventId);
        /**
         * let mark it as complete so we can send the survey if needed.
         * Complete status has different naming convention based on the instrument name. so you need to get instrument name and append _complete to it.
         */
        $labels = \REDCap::getValidFieldsByEvents($module->getProjectId(), array($reservationEventId));
        $completed = preg_grep('/_complete$/', $labels);
        $second = array_slice($completed, 1, 1);  // array("status" => 1)

        $data[$second] = REDCAP_COMPLETE;

        $data['redcap_event_name'] = $module->getUniqueEventName($reservationEventId);
        if (!isset($_POST['survey_record_id'])) {
            $data[$module->getPrimaryRecordFieldName()] = $module->getNextRecordsId($reservationEventId,
                $module->getProjectId());
        } else {
            $data[$module->getPrimaryRecordFieldName()] = filter_var($_POST['survey_record_id'],
                FILTER_SANITIZE_STRING);
        }

        $response = \REDCap::saveData($module->getProjectId(), 'json', json_encode(array($data)));
        if (empty($response['errors'])) {

            //if slot has instructor identified then send email to the instructor
            if (!empty($slot['instructor'])) {
                $data['instructor'] = $slot['instructor'];
            }
            $return = $module->notifyUser($data);
            echo json_encode(array(
                'status' => 'ok',
                'message' => 'Appointment saved successfully!' . (isset($return['error']) ? ' with following errors' . $return['message'] : ''),
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