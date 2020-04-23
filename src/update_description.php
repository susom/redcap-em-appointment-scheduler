<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */


try {
    /**
     * check if user still logged in
     */
    if (!$module::isUserHasManagePermission()) {
        throw new \LogicException('You cant be here');
    }

    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);


    $module->setProjectSetting('instance_description', $description, $module->getProjectId());

    echo json_encode(array('status' => 'ok', 'message' => 'Instance Description Updated Successfully!'));

} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}