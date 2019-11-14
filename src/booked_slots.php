<?php


namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

try {
    /**
     * check if user still logged in
     */
    if (!$module::isUserHasManagePermission()) {
        throw new \LogicException('You cant be here');
    }

    //get records for all reservations.
    $records = $module->getParticipant()->getAllReservedSlots($module->getProjectId());

    //get all open time slots so we can exclude past reservations.
    $slots = $module->getAllOpenSlots();
    if ($records) {
        ?>
        <div class="container">
            <table id="calendar-datatable" class="display">
                <thead>
                <tr>
                    <th>SUNetID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Notes</th>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($records as $id => $events) {
                    foreach ($events as $eventId => $record) {
                        //if past reservation we do not want to see it.
                        if (!array_key_exists($record['slot_id'], $slots)) {
                            continue;
                        } else {
                            $slot = end($slots[$record['slot_id']]);
                        }
                        ?>
                        <tr>
                            <td><?php echo $record['sunet_id'] ?></td>
                            <td><?php echo $record['name'] ?></td>
                            <td><?php echo $record['email'] ?></td>
                            <td><?php echo $record['mobile'] ?></td>
                            <td><?php echo $record['notes'] ?></td>
                            <td><?php echo $module::getProjectName($record['project_id']) ?></td>
                            <td><?php echo date('m/d/Y', strtotime($slot['start'])) ?></td>
                            <td><?php echo date('H:i', strtotime($slot['start'])) ?></td>
                            <td><?php echo date('H:i', strtotime($slot['end'])) ?></td>
                            <td>
                                <button type="button"
                                        data-participant-id="<?php echo $id ?>"
                                        data-event-id="<?php echo $eventId ?>"
                                        class="participants-no-show">No Show
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
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

