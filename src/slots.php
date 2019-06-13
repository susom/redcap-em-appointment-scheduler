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
    if(!empty($slots)){
        foreach ($slots as $record){
            /**
             * Get first array element
             */
            $slot = array_pop(array_reverse($record));
            ?>
            <button type="button" data-record-id="<?php echo $slot['record_id'] ?>" data-date="<?php echo date('Ymd', strtotime($slot['start'])) ?>" data-start="<?php echo date('Hi', strtotime($slot['start'])) ?>" data-end="<?php echo date('Hi', strtotime($slot['end'])) ?>" class="time-slot btn btn-success btn-block"><?php echo date('H:i', strtotime($slot['start'])) ?> – <?php echo date('H:i', strtotime($slot['end'])) ?></button>
            <?php
        }
    }else{
        echo 'No Available time slots found!';
    }

}else{
    echo 'Invalid request for time slots';
}