<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    $id = filter_var($_GET['participation_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($id == '') {
        throw new \LogicException('Participation ID is missing');
    } else {

        $data['status'] = CANCELED;
        $module->updateParticipation($data, $id);
        echo json_encode(array('status' => 'ok', 'message' => 'Appointment canceled successfully!'));

    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}