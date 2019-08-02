<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


$event_id = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = filter_var($_GET['month'], FILTER_SANITIZE_NUMBER_INT);
    $year = filter_var($_GET['year'], FILTER_SANITIZE_NUMBER_INT);
    $data = $module->getMonthSlots($event_id, $year, $month);
} else {
    $data = $module->getMonthSlots($event_id);
}
$days = array();
foreach ($data as $slot) {
    $slot = array_pop($slot);
    /**
     * group by day
     */
    $day = (int)date('d', strtotime($slot['start']));

    /**
     * if we have available slots on that day
     */
    if ($slot['booked'] == '') {
        $days[$day]['available']++;
        /**
         * no need to show more than three available slots
         */
        if ($days[$day]['available'] <= 3) {
            $days[$day]['availableText'] .= 'REDCap Appt ' . date('H:i',
                    strtotime($slot['start'])) . ' - ' . date('H:i', strtotime($slot['end'])) . ' ';
        }
    } else {
        $days[$day]['booked']++;
    }

    /**
     *
     */
}
echo \GuzzleHttp\json_encode($days);