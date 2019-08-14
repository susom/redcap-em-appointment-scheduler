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
    $suffix = $module->getSuffix();
    $records = $module->getAllOpenSlots($suffix);
    $data = $module->prepareInstructorsSlots($records, $suffix);
    $instructors = array_keys($data);
    $pointer = 0;
    if ($instructors) {
        ?>
        <div class="container">


            <table id="calendar-datatable" class="display">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Instructor</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $pointer = 1;
                foreach ($data as $slot) {
                    ?>
                    <tr>
                        <td><?php echo $pointer ?></td>
                        <td><?php echo $slot['instructor'] ?></td>
                        <td><strong><?php echo $slot['event_name'] ?></strong>
                            - <?php echo $slot['location' . $suffix] ?></td>
                        <td>
                            <?php echo date('m/d/Y',
                                strtotime($slot['start' . $suffix])) ?>
                        </td>
                        <td><?php echo date('H:i',
                                strtotime($slot['start' . $suffix])) ?> – <?php echo date('H:i',
                                strtotime($slot['end' . $suffix])) ?></td>
                        <td><?php
                            if ($slot['slot_status' . $suffix] == CANCELED) {
                                ?>
                                Slot Canceled
                                <?php
                            } else {
                                ?>
                                <button type="button"
                                        data-record-id="<?php echo $slot['record_id'] ?>"
                                        data-event-id="<?php echo $slot['event_id'] ?>"
                                        class="cancel-slot"><i class="fas fa-power-off"></i>
                                </button>
                                <?php
                            }
                            ?>
                            <button type="button"
                                    data-record-id="<?php echo $slot['record_id'] ?>"
                                    data-event-id="<?php echo $slot['event_id'] ?>"
                                    data-location="<?php echo $slot['location' . $suffix] ?>"
                                    data-date="<?php echo date('m/d/Y',
                                        strtotime($slot['start' . $suffix])) ?>"
                                    data-start="<?php echo date('H:i',
                                        strtotime($slot['start' . $suffix])) ?>"
                                    data-end="<?php echo date('H:i', strtotime($slot['end' . $suffix])) ?>"
                                    data-instructor="<?php echo $slot['instructor'] ?>"
                                    class="reschedule-slot"><i class="fas fa-edit"></i>
                            </button>
                            <button type="button"
                                    data-record-id="<?php echo $slot['record_id'] ?>"
                                    data-event-id="<?php echo $slot['event_id'] ?>"
                                    data-modal-title="<?php echo date('m/d/Y',
                                        strtotime($slot['start' . $suffix])) ?> <?php echo date('H:i',
                                        strtotime($slot['start' . $suffix])) ?> – <?php echo date('H:i',
                                        strtotime($slot['end' . $suffix])) ?>"
                                    class="participants-list"><i class="fas fa-list"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                    $pointer++;
                }
                ?>
                </tbody>
            </table>


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

