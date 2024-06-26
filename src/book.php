<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    /**
     * if survey booking with NOAUTH ignore login validation.
     */
    if (!defined('USERID') && !defined('NOAUTH')) {
        throw new \LogicException('Please login.');
    }

    $data = $module->sanitizeInput();
    if ($data['email' . $module->getSuffix()] == '' || $data['name' . $module->getSuffix()] == '') {
        throw new \LogicException('Data cant be missing');
    } else {


        $data['participant_status' . $module->getSuffix()] = RESERVED;
        if (defined('USERID') && USERID != '[survey respondent]') {
            $data['sunet_id' . $module->getSuffix()] = USERID;
        }

        // this use case surveys in different arms that needs scheduler.
        if (!isset($_POST['reservation_event_id']) || $_POST['reservation_event_id'] == '') {
            $reservationEventId = $module->getReservationEventIdViaSlotEventId($data['event_id']);
        } else {
            $reservationEventId = filter_var($_POST['reservation_event_id'], FILTER_SANITIZE_NUMBER_INT);
        }
        $slot = $module::getSlot(filter_var($_POST['record_id'], FILTER_SANITIZE_STRING), $data['event_id'],
            $module->getProjectId(), $module->getPrimaryRecordFieldName());

        // check if any slot is available
        $counter = $module->getParticipant()->getSlotActualCountReservedSpots(filter_var($_POST['record_id'],
            FILTER_SANITIZE_STRING),
            $reservationEventId, '', $module->getProjectId());
        if ((int)($slot['number_of_participants'] - $counter['counter']) <= 0) {
            throw new \Exception("All time slots are booked please try different time");
        }

        $date = date('Y-m-d', strtotime(preg_replace("([^0-9/])", "", $_POST['calendarDate'])));
        // no need if user not signed in.
        if (defined('USERID') && USERID != '[survey respondent]') {
            $module->doesUserHaveSameDateReservation($date, USERID, $module->getSuffix(), $data['event_id'],
                $reservationEventId);
        }

        /**
         * let mark it as complete so we can send the survey if needed.
         * Complete status has different naming convention based on the instrument name. so you need to get instrument name and append _complete to it.
         */
         $reservation = end($module->getProject()->eventsForms[$reservationEventId]);
         $data[$reservation . '_complete'] = REDCAP_COMPLETE;

        $data['redcap_event_name'] = $module->getUniqueEventName($reservationEventId);
        if (!isset($_POST['survey_record_id']) || (isset($_POST['survey_record_id']) && $_POST['survey_record_id'] == "")) {
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
        } else {
            if (is_array($response['errors'])) {
                throw new \Exception(implode(",", $response['errors']));
            } else {
                throw new \Exception($response['errors']);
            }

        }
    }
} catch (\LogicException $e) {
    $module->emError($e->getMessage());
    http_response_code(404);
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
} catch (\Exception $e) {
    $module->emError($e->getMessage());
    http_response_code(404);
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}