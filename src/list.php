<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

$suffix = $module->getSuffix();
$eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$data = $module->getMonthSlots($eventId);
$url = $module->getUrl('src/calendar.php', true, true);


?>


    <div class="row p-3 mb-2">
        <div class="col-8">
            <?php echo $module->getInstanceDescription($eventId) ?>
        </div>
        <div class="col-4 text-right">
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
    foreach ($data as $record_id => $slot) {
        $slot = array_pop($slot);

        /**
         * group by day
         */
        $day = date('d', strtotime($slot['start' . $suffix]));

        /**
         * if the record id has different name just use whatever is provided.
         */
        if (!isset($slot['record_id'])) {
            $slot['record_id'] = $record_id;
        }

        $days[$day][$slot['record_id']]['date' . $suffix] = date('Y-m-d', strtotime($slot['start' . $suffix]));
        $days[$day][$slot['record_id']]['start' . $suffix] = date('H:i', strtotime($slot['start' . $suffix]));
        $days[$day][$slot['record_id']]['end' . $suffix] = date('H:i', strtotime($slot['end' . $suffix]));
        $days[$day][$slot['record_id']]['location' . $suffix] = $slot['location' . $suffix];
        $days[$day][$slot['record_id']]['number_of_participants' . $suffix] = $slot['number_of_participants' . $suffix];
        $days[$day][$slot['record_id']]['booked_slots' . $suffix] = $module->getParticipant()->getSlotActualCountReservedSpots($slot['record_id'],
            $reservationEventId, $suffix);
        /**
         * check if we have slots available
         */
        if ($days[$day][$slot['record_id']]['number_of_participants' . $suffix] > $days[$day][$slot['record_id']]['booked_slots' . $suffix]) {
            $days[$day][$slot['record_id']]['booked' . $suffix] = false;
            $days[$day][$slot['record_id']]['available' . $suffix] = $days[$day][$slot['record_id']]['number_of_participants' . $suffix] - $days[$day][$slot['record_id']]['booked_slots' . $suffix];
        } else {
            $days[$day][$slot['record_id']]['booked' . $suffix] = true;
            $days[$day][$slot['record_id']]['notes' . $suffix] = 'No available spots <br>\\TODO WHAT DO DISPLAY HERE!';
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
                        strtotime($dayName['date' . $suffix])) . '.)' ?></div>
            <div class=" col-lg-9">
                <?php
                foreach ($days[$key] as $record_id => $record) {
                    ?>
                    <div class="row border">
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo $record['location' . $suffix] ?></div>
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo date('H:i',
                                strtotime($record['start' . $suffix])) ?> – <?php echo date('H:i',
                                strtotime($record['end' . $suffix])) ?></div>
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php
                            if ($record['booked' . $suffix]) {
                                echo $record['notes' . $suffix];
                            } else {
                                ?>
                                <button type="button"
                                        data-record-id="<?php echo $record_id ?>"
                                        data-event-id="<?php echo $eventId ?>"
                                        data-notes-label="<?php echo $module->getNoteLabel(); ?>"
                                        data-show-projects="<?php echo $module->showProjectIds(); ?>"
                                        data-show-notes="<?php echo $module->showNotes(); ?>"
                                        data-date="<?php echo date('Ymd', strtotime($record['date' . $suffix])) ?>"
                                        data-start="<?php echo date('Hi', strtotime($record['start' . $suffix])) ?>"
                                        data-end="<?php echo date('Hi', strtotime($record['end' . $suffix])) ?>"
                                        data-modal-title="<?php echo date('H:i',
                                            strtotime($record['start' . $suffix])) ?> – <?php echo date('H:i',
                                            strtotime($record['end' . $suffix])) ?>"
                                        class="time-slot btn btn-block btn-success">Book
                                </button>
                                <small>* (<?php echo $record['available' . $suffix] ?>) seats is still available</small>
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