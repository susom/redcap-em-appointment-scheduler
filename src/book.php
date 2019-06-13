<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $data = $module->sanitizeInput();
    if ($data['email'] == '' || $data['name'] == '' || $data['mobile'] == '' || $data['redcap_event_name'] == '') {
        throw new \LogicException('Data cant be missing');
    } else {
        $response = \REDCap::saveData('json', json_encode(array($data)));
        if (!empty($response['errors'])) {
            throw new \LogicException(implode("\n", $response['errors']));
        } else {

            $module->notifyUser($data);
            echo json_encode(array('status' => 'ok', 'message' => 'Appointment saved successfully!'));

        }
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}