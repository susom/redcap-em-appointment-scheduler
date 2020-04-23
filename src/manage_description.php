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
    $suffix = $module->getSuffix();
    $records = $module->getAllOpenSlots($suffix);
    $data = $module->prepareInstructorsSlots($records, $suffix);
    $instructors = array_keys($data);
    $instance = $module->getEventInstance();
    if ($instructors) {
        ?>
        <div class="container">
            <form>
                <div class="form-group">
                    <textarea class="form-control" id="instance_description" rows="3" name="instance_description"><?php
                        echo $instance['instance_description']
                        ?></textarea>
                </div>

                <button class="btn btn-primary mb-2" type="submit">Update</button>
            </form>
        </div>
        <?php
    } else {
        echo 'No saved participation for you';
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
?>

