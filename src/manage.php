<?php


namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

try {
    /**
     * check if user still logged in
     */
    if (!isset($user_email)) {
        throw new \LogicException('Please login.');
    }

    $records = $module->getUserParticipation($user_email);
    if ($records) {
        ?>
        <div class="container">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active nav-item">
                    <a class="nav-link active" href="#<?php echo RESERVED_TEXT ?>" role="tab" data-toggle="tab">
                        <?php echo RESERVED_TEXT ?>
                    </a>
                </li>
                <li><a class="nav-link" href="#<?php echo CANCELED_TEXT ?>" role="tab" data-toggle="tab">
                        <?php echo CANCELED_TEXT ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#<?php echo NO_SHOW_TEXT ?>" role="tab" data-toggle="tab">
                        <?php echo NO_SHOW_TEXT ?>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade active in show" id="<?php echo RESERVED_TEXT ?>">
                    <?php
                    $reservedRecords = $module->getUserParticipation($user_email, RESERVED);
                    if ($reservedRecords->num_rows > 0) {
                        foreach ($reservedRecords as $reserved) {
                            $slot = $module->getParticipationSlotData($reserved['record_id']);

                            $record = array_pop($slot);
                            ?>
                            <div class="row">
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo $record['location'] ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark">
                                    <?php echo date('m/d/Y',
                                        strtotime($record['start'])) ?>
                                    <br><?php echo date('H:i',
                                        strtotime($record['start'])) ?> – <?php echo date('H:i',
                                        strtotime($record['end'])) ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark">
                                    <button type="button"
                                            data-participation-id="<?php echo $reserved['id'] ?>"
                                            class="cancel-appointment btn btn-block btn-danger">Cancel
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo 'No Active Reserved Appointment at this time';
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="<?php echo CANCELED_TEXT ?>">
                    <?php
                    $canceledRecords = $module->getUserParticipation($user_email, CANCELED);
                    if ($canceledRecords->num_rows > 0) {
                        foreach ($canceledRecords as $canceled) {
                            $slot = $module->getParticipationSlotData($canceled['record_id']);

                            $record = array_pop($slot);
                            ?>
                            <div class="row">
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo $record['location'] ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark">
                                    <?php echo date('m/d/Y',
                                        strtotime($record['start'])) ?>
                                    <br><?php echo date('H:i',
                                        strtotime($record['start'])) ?> – <?php echo date('H:i',
                                        strtotime($record['end'])) ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php
                                    $canceled['notes'] . ($record['notes'] != '' ? '<br>Instructor Notes:' . $record['notes'] : '') ?></div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="row">No Canceled Appointment</div>
                        <?php
                    }
                    ?>
                </div>
                <div class="tab-pane fade" id="<?php echo NO_SHOW_TEXT ?>">
                    <?php
                    $noShowRecords = $module->getUserParticipation($user_email, NO_SHOW);
                    if ($noShowRecords->num_rows > 0) {
                        foreach ($noShowRecords as $noShow) {
                            $slot = $module->getParticipationSlotData($noShow['record_id']);

                            $record = array_pop($slot);
                            ?>
                            <div class="row">
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo $record['location'] ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo date('m/d/Y',
                                        strtotime($record['start'])) ?>
                                    <br>
                                    <?php echo date('H:i',
                                        strtotime($record['start'])) ?> – <?php echo date('H:i',
                                        strtotime($record['end'])) ?></div>
                                <div class="p-3 mb-2 col-lg-4 text-dark"><?php
                                    $noShow['notes'] . ($record['notes'] != '' ? '<br>Instructor Notes:' . $record['notes'] : '') ?></div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="row">No Appointments has NO SHOW status.</div>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>
        <?php
    } else {
        echo 'No saved participation for you';
    }
} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}
?>
<!-- LOAD JS -->
<script src="<?php echo $module->getUrl('src/js/manage.js') ?>"></script>

