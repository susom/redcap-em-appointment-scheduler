<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


$eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$data = $module->getCurrentMonthSlots($eventId);
$url = $module->getUrl('src/calendar.php', true, true);

?>


    <div class="row p-3 mb-2">
        <div class="col text-right">
            <a class="btn btn-danger calendar-view" data-key="<?php echo $eventId ?>" href="javascript:;"
               data-url="<?php echo $url . '&event_id=' . $eventId ?>" role="button">Calendar
                View</a>
        </div>
    </div>
    <div class="row ">
        <div class="p-3 mb-2 col-lg-3 text-light bg-dark">Date (mm/dd/yyyy)</div>
        <div class="p-3 mb-2 col-lg-3 text-light bg-dark">Location</div>
        <div class="p-3 mb-2 col-lg-3 text-light bg-dark">Time (PDT)</div>
        <div class="p-3 mb-2 col-lg-3 text-light bg-dark">Office Hour Slots</div>
    </div>
    <?php

$days = array();
if (empty($data)) {
    ?>
    <div class="row p-3 mb-2">
        <div class="p-3 mb-2 col-lg-12  "><h3>No Scheduled time this month</h3></div>
    </div>
    <?php
} else {
    $reservationEventId = $module->getReservationEventIdViaSlotEventId($eventId);
    /**
     * prepare data
     */
    foreach ($data as $slot) {
        $slot = array_pop($slot);
        /**
         * group by day
         */
        $day = date('d', strtotime($slot['start']));


        $days[$day][$slot['record_id']]['date'] = date('Y-m-d', strtotime($slot['start']));
        $days[$day][$slot['record_id']]['start'] = date('H:i', strtotime($slot['start']));
        $days[$day][$slot['record_id']]['end'] = date('H:i', strtotime($slot['end']));
        $days[$day][$slot['record_id']]['location'] = $slot['location'];
        $days[$day][$slot['record_id']]['number_of_participants'] = $slot['number_of_participants'];
        $days[$day][$slot['record_id']]['booked_slots'] = $module->getParticipant()->getSlotActualCountReservedSpots($slot['record_id'],
            $reservationEventId);
        /**
         * check if we have slots available
         */
        if ($days[$day][$slot['record_id']]['number_of_participants'] > $days[$day][$slot['record_id']]['booked_slots']) {
            $days[$day][$slot['record_id']]['booked'] = false;
            $days[$day][$slot['record_id']]['available'] = $days[$day][$slot['record_id']]['number_of_participants'] - $days[$day][$slot['record_id']]['booked_slots'];
        } else {
            $days[$day][$slot['record_id']]['booked'] = true;
            $days[$day][$slot['record_id']]['notes'] = 'No available spots <br>\\TODO WHAT DO DISPLAY HERE!';
        }
    }

    /**
     * now display the list
     */

    foreach ($days as $key => $day) {
        $dayName = array_pop($day);
        ?>
        <div class="border row ">
            <div class="p-3 mb-2 col-lg-3 text-dark"><?php echo date('m') . '/' . $key . '/' . date('Y') . ' (' . date('D',
                        strtotime($dayName['date'])) . '.)' ?></div>
            <div class=" col-lg-9">
                <?php
                foreach ($days[$key] as $record_id => $record) {
                    ?>
                    <div class="row border">
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo $record['location'] ?></div>
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo date('H:i',
                                strtotime($record['start'])) ?> – <?php echo date('H:i',
                                strtotime($record['end'])) ?></div>
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php
                            if ($record['booked']) {
                                echo $record['notes'];
                            } else {
                                ?>
                                <button type="button"
                                        data-record-id="<?php echo $record_id ?>"
                                        data-event-id="<?php echo $eventId ?>"
                                        data-date="<?php echo date('Ymd', strtotime($record['start'])) ?>"
                                        data-start="<?php echo date('Hi', strtotime($record['start'])) ?>"
                                        data-end="<?php echo date('Hi', strtotime($record['end'])) ?>"
                                        data-modal-title="<?php echo date('H:i',
                                            strtotime($record['start'])) ?> – <?php echo date('H:i',
                                            strtotime($record['end'])) ?>"
                                        class="time-slot btn btn-block btn-success">Book
                                </button>
                                <small>* (<?php echo $record['available'] ?>) seats is still available</small>
                                <?php
                            }
                            ?></div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
}