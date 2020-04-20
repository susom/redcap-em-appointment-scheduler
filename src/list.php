<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

$suffix = $module->getSuffix();
$eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$data = $module->getMonthSlots($eventId);
$url = $module->getUrl('src/calendar.php', true, true);
$instance = $module->getEventInstance();
?>


    <div class="row p-3 mb-2">
        <div class="col-8">
            <?php echo $instance['instance_description'] ?>
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
        <div class="p-3 mb-2 col-lg-3 text-light bg-dark"><?php echo $instance['title'] ?> Slots</div>
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
            $slot['record_id'] = array_pop(array_reverse($slot));
        }

        $days[$day][$slot['record_id']]['date' . $suffix] = date('Y-m-d', strtotime($slot['start' . $suffix]));
        $days[$day][$slot['record_id']]['start' . $suffix] = date('H:i', strtotime($slot['start' . $suffix]));
        $days[$day][$slot['record_id']]['end' . $suffix] = date('H:i', strtotime($slot['end' . $suffix]));
        $days[$day][$slot['record_id']]['location' . $suffix] = $module->getLocationLabel($slot['location' . $suffix]);
        $days[$day][$slot['record_id']]['attending_options' . $suffix] = $slot['attending_options' . $suffix];
        $days[$day][$slot['record_id']]['number_of_participants' . $suffix] = $slot['number_of_participants' . $suffix];

        //get number of seats booked for this slot
        $counter = $module->getParticipant()->getSlotActualCountReservedSpots($slot['record_id'],
            $reservationEventId, $suffix, $module->getProjectId());
        $days[$day][$slot['record_id']]['booked_slots' . $suffix] = $counter['counter'];
        /**
         * check if we have slots available
         */
        if ($days[$day][$slot['record_id']]['number_of_participants' . $suffix] > $days[$day][$slot['record_id']]['booked_slots' . $suffix]) {
            $days[$day][$slot['record_id']]['booked' . $suffix] = false;
            $days[$day][$slot['record_id']]['available' . $suffix] = $days[$day][$slot['record_id']]['number_of_participants' . $suffix] - $days[$day][$slot['record_id']]['booked_slots' . $suffix];

            //build admin section is reservation exists for this slot
            if ($counter['userBookThisSlot']) {
                if ($module::isUserHasManagePermission()) {
                    foreach ($counter['userBookThisSlot'] as $reservation) {
                        $days[$day][$slot['record_id']]['admin' . $suffix] .= '<div class="alert alert-primary" role="alert">
                            ' . $reservation['name'] . '<button type="button"
                                                                      data-participation-id="' . $reservation[$module->getPrimaryRecordFieldName()] . '"
                                                                      data-event-id="' . $reservationEventId . '"
                                                                      class="cancel-appointment btn btn-block btn-danger">Cancel
                            </button>
                        </div>';
                    }
                } else {

                    $reservation = end($counter['userBookThisSlot']);
                    $days[$day][$slot['record_id']]['admin' . $suffix] = '<div class="alert alert-primary" role="alert">
                            ' . $reservation['name'] . '<button type="button"
                                                                      data-participation-id="' . $reservation[$module->getPrimaryRecordFieldName()] . '"
                                                                      data-event-id="' . $reservationEventId . '"
                                                                      class="cancel-appointment btn btn-block btn-danger">Cancel
                            </button>
                        </div>';
                }
            }

        } else {
            $days[$day][$slot['record_id']]['booked' . $suffix] = true;

            //if logged in user booked this slot give the option to cancel their reservation.
            if ($counter['userBookThisSlot']) {

                //for admin few display user name and option to cancel
                if ($module::isUserHasManagePermission()) {
                    foreach ($counter['userBookThisSlot'] as $reservation) {
                        $days[$day][$slot['record_id']]['notes' . $suffix] .= '<div class="alert alert-primary" role="alert">
                            ' . $reservation['name'] . '<button type="button"
                                                                      data-participation-id="' . $reservation[$module->getPrimaryRecordFieldName()] . '"
                                                                      data-event-id="' . $reservationEventId . '"
                                                                      class="cancel-appointment btn btn-block btn-danger">Cancel
                            </button>
                        </div>';
                    }
                } else {
                    //if not admin regular user will have only one record!
                    $reservation = end($counter['userBookThisSlot']);
                    $days[$day][$slot['record_id']]['notes' . $suffix] = '<div class="alert alert-primary" role="alert">
                            ' . $reservation['name'] . '<button type="button"
                                                                      data-participation-id="' . $reservation[$module->getPrimaryRecordFieldName()] . '"
                                                                      data-event-id="' . $reservationEventId . '"
                                                                      class="cancel-appointment btn btn-block btn-danger">Cancel
                            </button>
                        </div>';
                }
            } else {
                $days[$day][$slot['record_id']]['notes' . $suffix] = 'Full <br>';
            }

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
                        <div class="p-3 mb-2 col-lg-4 text-dark"><?php echo date('h:i A',
                                strtotime($record['start' . $suffix])) ?> – <?php echo date('h:i A',
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
                                        data-show-attending-options="<?php echo $module->showAttendingOptions(); ?>"
                                        data-show-location-options="<?php echo $module->showLocationOptions(); ?>"
                                        data-show-attending-default="<?php echo $module->getDefaultAttendingOption(); ?>"
                                        data-show-locations="<?php echo(empty($record['attending_options' . $suffix]) ? CAMPUS_AND_VIRTUAL : $record['attending_options' . $suffix]); ?>"
                                        data-show-notes="<?php echo $module->showNotes(); ?>"
                                        data-date="<?php echo date('Ymd', strtotime($record['date' . $suffix])) ?>"
                                        data-start="<?php echo date('Hi', strtotime($record['start' . $suffix])) ?>"
                                        data-end="<?php echo date('Hi', strtotime($record['end' . $suffix])) ?>"
                                        data-modal-title="<?php echo date('h:i A',
                                            strtotime($record['start' . $suffix])) ?> – <?php echo date('h:i A',
                                            strtotime($record['end' . $suffix])) ?>"
                                        class="time-slot btn btn-block btn-success">Book
                                </button>
                                <?php
                                //for now this is for admin
                                if ($record['admin' . $suffix]) {
                                    echo $record['admin' . $suffix];
                                }
                                ?>
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