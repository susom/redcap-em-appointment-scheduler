<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */


$event_id = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$data = $module->getMonthSummary($event_id);
$days = array();
foreach ($data as $slot) {
    $slot = array_pop($slot);
    /**
     * group by day
     */
    $day = date('d', strtotime($slot['start']));

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