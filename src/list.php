<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */

$suffix = $module->getSuffix();
$eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$data = $module->getMonthSlots($eventId);
$url = $module->getUrl('src/calendar.php', true, true);
$instance = $module->getEventInstance();
$instance = $module->getEventInstance();
$result = array();
$result['data'] = array();
if (!empty($data)) {
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
            $slot['record_id'] = array_pop(array_reverse($slot));
        }


        $counter = $module->getParticipant()->getSlotActualCountReservedSpots($slot['record_id'],
            $reservationEventId, $suffix, $module->getProjectId());

        $available = (int)($slot['number_of_participants' . $suffix] - $counter['counter']);;

        if ($available == 0) {
            continue;
        }
        $row = array();
        $row[] = date('Y-m-d', strtotime($slot['start' . $suffix]));
        $row[] = $module->getLocationLabel($slot['location' . $suffix]);;
        $row[] = date('h:i A', strtotime($slot['start' . $suffix])) . ' - ' . date('h:i A',
                strtotime($slot['end' . $suffix]));;
        $row[] = $available;;
        $row[] = '<button type="button"
                                        data-record-id="' . $slot['record_id'] . '"
                                        data-event-id="' . $eventId . '"
                                        data-notes-label="' . $module->getNoteLabel() . '"
                                        data-show-projects="' . $module->showProjectIds() . '"
                                        data-show-attending-options="' . $module->showAttendingOptions() . '"
                                        data-show-location-options="' . $module->showLocationOptions() . '"
                                        data-show-attending-default="' . $module->getDefaultAttendingOption() . '"
                                        data-show-locations="' . (empty($slot['attending_options' . $suffix]) ? CAMPUS_AND_VIRTUAL : $slot['attending_options' . $suffix]) . '"
                                        data-show-notes="' . $module->showNotes() . '"
                                        data-date="' . date('Ymd', strtotime($slot['start' . $suffix])) . '"
                                        data-start="' . date('Hi', strtotime($slot['start' . $suffix])) . '"
                                        data-end="' . date('Hi', strtotime($slot['end' . $suffix])) . '"
                                        data-modal-title="' . date('M/d/Y',
                strtotime($slot['start' . $suffix])) . ' ' . date('h:i A',
                strtotime($slot['start' . $suffix])) . ' - ' . date('h:i A', strtotime($slot['end' . $suffix])) . '"
                                        class="time-slot btn btn-block btn-success">Book
                                </button>';;

        $result['data'][] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($result);
}