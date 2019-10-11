<?php
namespace Stanford\AppointmentScheduler;
/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

if(isset($_GET['date'])){
    /*
     * Sanitize your dates
     */
    $date = preg_replace("([^0-9/])", "", $_GET['date']);
    $eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    $slots = $module->getDateAvailableSlots($date, $eventId);
    if(!empty($slots)){

        $reservationEventId = $module->getReservationEventIdViaSlotEventId($eventId);

        foreach ($slots as $recordId => $record) {
            /**
             * Get first array element
             */
            $slot = array_pop(array_reverse($record));

            /**
             * get appointment type
             */
            $typeText = $module->getTypeText($slot['type']);

            if ($slot['number_of_participants'] > $module->getParticipant()->getSlotActualCountReservedSpots($recordId,
                    $reservationEventId, '', $module->getProjectId())) {

                ?>
                <button type="button"
                        data-record-id="<?php echo $recordId ?>" <?php echo $slot['booked'] ? 'disabled' : '' ?>
                        data-date="<?php echo date('Ymd', strtotime($slot['start'])) ?>"
                        data-event-id="<?php echo $eventId ?>"
                        data-notes-label="<?php echo $module->getNoteLabel(); ?>"
                        data-show-projects="<?php echo $module->showProjectIds(); ?>"
                        data-show-notes="<?php echo $module->showNotes(); ?>"
                        data-show-locations="<?php echo(empty($slot['attending_options']) ? CAMPUS_AND_VIRTUAL : $record['attending_options']); ?>"
                        data-start="<?php echo date('Hi', strtotime($slot['start'])) ?>"
                        data-end="<?php echo date('Hi', strtotime($slot['end'])) ?>"
                        data-modal-title="<?php echo date('h:i A',
                            strtotime($slot['start'])) ?> – <?php echo date('h:i A', strtotime($slot['end'])) ?>"
                        class="time-slot btn btn-block <?php echo $slot['booked'] ? 'disabled btn-secondary' : 'btn-success' ?>"><?php echo $typeText . '<br>' . date('h:i A',
                            strtotime($slot['start'])) ?> – <?php echo date('h:i A',
                        strtotime($slot['end'])) ?></button>
                <?php
            } else {
                ?>
                <div class="alert alert-warning text-center"><?php echo $typeText . '<br>' . date('h:i A',
                            strtotime($slot['start'])) ?> – <?php echo date('h:i A', strtotime($slot['end'])) ?> is FULL
                </div>
                <?php
            }
        }
    }else{
        echo 'No Available time slots found!';
    }

}else{
    echo 'Invalid request for time slots';
}