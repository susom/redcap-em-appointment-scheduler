<?php
namespace Stanford\AppointmentScheduler;
/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

if(isset($_GET['date'])){
    /*
     * Sanitize your dates
     */
    $date = preg_replace("([^0-9/])", "", $_GET['date']);
    $event_id = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    $slots = $module->getDateAvailableSlots($date, $event_id);
    $primary = \REDCap::getRecordIdField();
    if(!empty($slots)){
        foreach ($slots as $record){
            /**
             * Get first array element
             */
            $slot = array_pop(array_reverse($record));

            /**
             * get appointment type
             */
            $typeText = $module->getTypeText($slot['type']);


            ?>
            <button type="button"
                    data-record-id="<?php echo $slot[$primary] ?>" <?php echo $slot['booked'] ? 'disabled' : '' ?>
                    data-date="<?php echo date('Ymd', strtotime($slot['start'])) ?>"
                    data-event-id="<?php echo $event_id ?>"
                    data-notes-label="<?php echo $module->getNoteLabel(); ?>"
                    data-show-projects="<?php echo $module->showProjectIds(); ?>"
                    data-show-notes="<?php echo $module->showNotes(); ?>"
                    data-start="<?php echo date('Hi', strtotime($slot['start'])) ?>"
                    data-end="<?php echo date('Hi', strtotime($slot['end'])) ?>"
                    data-modal-title="<?php echo date('h:i A',
                        strtotime($slot['start'])) ?> – <?php echo date('h:i A', strtotime($slot['end'])) ?>"
                    class="time-slot btn btn-block <?php echo $slot['booked'] ? 'disabled btn-secondary' : 'btn-success' ?>"><?php echo $typeText . '<br>' . date('h:i A',
                        strtotime($slot['start'])) ?> – <?php echo date('h:i A', strtotime($slot['end'])) ?></button>
            <?php
        }
    }else{
        echo 'No Available time slots found!';
    }

}else{
    echo 'Invalid request for time slots';
}