<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


try {
    /**
     * check if user still logged in
     */
    if (!SUPER_USER) {
        throw new \LogicException('You cant be here');
    }
    $id = filter_var($_GET['participation_id'], FILTER_SANITIZE_NUMBER_INT);
    if ($id == '') {
        throw new \LogicException('Participation ID is missing');
    } else {

        $data['status'] = NO_SHOW;
        $module->updateParticipation($data, $id);

        //TODO notify instructor about the cancellation
        echo json_encode(array('status' => 'ok', 'message' => 'Participant marked as No Show!'));

    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}