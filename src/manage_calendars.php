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

    $records = $module->getAllOpenSlots();
    $data = $module->prepareInstructorsSlots($records);
    $instructors = array_keys($data);
    $pointer = 0;
    if ($instructors) {
        ?>
        <div class="container">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <?php
                foreach ($instructors as $instructor) {
                    ?>
                    <li class="active nav-item">
                        <a class="nav-link <?php echo($pointer == 0 ? 'active' : '') ?>"
                           href="#<?php echo $instructor ?>" role="tab" data-toggle="tab">
                            <?php echo $instructor ?>
                        </a>
                    </li>
                    <?php
                    $pointer++;
                }
                ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <?php
                $pointer = 0;
                foreach ($data as $instructor => $instructorSlots) {
                    ?>
                    <div class="tab-pane fade <?php echo($pointer == 0 ? 'active in show' : '') ?>"
                         id="<?php echo $instructor ?>">
                        <?php
                        if (!empty($instructorSlots)) {
                            foreach ($instructorSlots as $slot) {

                                ?>
                                <div class="row">
                                    <div class="p-3 mb-2 col-lg-4 text-dark">
                                        <strong><?php echo $slot['event_name'] ?></strong>
                                        - <?php echo $slot['location'] ?></div>
                                    <div class="p-3 mb-2 col-lg-4 text-dark">
                                        <?php echo date('m/d/Y',
                                            strtotime($slot['start'])) ?>
                                        <br><?php echo date('H:i',
                                            strtotime($slot['start'])) ?> – <?php echo date('H:i',
                                            strtotime($slot['end'])) ?></div>
                                    <div class="p-3 mb-2 col-lg-4 text-dark">
                                        <button type="button"
                                                data-record-id="<?php echo $slot['record_id'] ?>"
                                                data-event-id="<?php echo $slot['event_id'] ?>"
                                                class="cancel-slot btn btn-block btn-danger">Cancel
                                        </button>
                                        <button type="button"
                                                data-record-id="<?php echo $slot['record_id'] ?>"
                                                data-event-id="<?php echo $slot['event_id'] ?>"
                                                data-location="<?php echo $slot['location'] ?>"
                                                data-date="<?php echo date('m/d/Y', strtotime($slot['start'])) ?>"
                                                data-start="<?php echo date('H:i', strtotime($slot['start'])) ?>"
                                                data-end="<?php echo date('H:i', strtotime($slot['end'])) ?>"
                                                data-instructor="<?php echo $instructor ?>"
                                                class="reschedule-slot btn btn-block btn-info">Reschedule
                                        </button>
                                        <button type="button"
                                                data-record-id="<?php echo $slot['record_id'] ?>"
                                                data-event-id="<?php echo $slot['event_id'] ?>"
                                                data-modal-title="<?php echo date('m/d/Y',
                                                    strtotime($slot['start'])) ?> <?php echo date('H:i',
                                                    strtotime($slot['start'])) ?> – <?php echo date('H:i',
                                                    strtotime($slot['end'])) ?>"
                                                class="participants-list btn btn-block btn-success">Participants List
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo 'No Available time slots for ' . $instructor;
                        }
                        ?>
                    </div>
                    <?php
                    $pointer++;
                }
                ?>
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
<script src="<?php echo $module->getUrl('src/js/manage_calendar.js') ?>"></script>

