<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $data = $module->sanitizeInput();
    if ($data['email'] == '' || $data['name'] == '' || $data['mobile'] == '') {
        throw new \LogicException('Data cant be missing');
    } else {

        $data['status'] = RESERVED;
        $response = $module->saveParticipant($data);
        $module->notifyUser($data);
        echo json_encode(array('status' => 'ok', 'message' => 'Appointment saved successfully!'));

    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}