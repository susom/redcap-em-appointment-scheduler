<?php


namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

try {
    /**
     * check if user still logged in
     */
    if (!SUPER_USER) {
        throw new \LogicException('You cant be here');
    }
    $suffix = $module->getSuffix();
    $recordId = filter_var($_GET['record_id'], FILTER_SANITIZE_NUMBER_INT);
    $eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
    $reservationEventId = $module->getReservationEventIdViaSlotEventId($eventId);
    $participants = $module->getParticipant()->getSlotParticipants($recordId, $reservationEventId, $suffix);
    if (!empty($participants)) {
        foreach ($participants as $record) {
            $participant = $record[$reservationEventId];
            ?>
            <div class="row">
                <div class="p-3 mb-2 col-lg-4 text-dark">
                    <strong><?php echo $participant['name' . $suffix] ?></strong></div>
                <div class="p-3 mb-2 col-lg-4 text-dark">
                    <a href="mailto:<?php echo $participant['email' . $suffix] ?>"><?php echo $participant['email' . $suffix] ?></a>
                </div>
                <div class="p-3 mb-2 col-lg-4 text-dark">
                    <?php
                    if ($participant['participant_status' . $suffix] == RESERVED) {
                        ?>
                        <button type="button"
                                data-participant-id="<?php echo $participant['record_id'] ?>"
                                data-event-id="<?php echo $reservationEventId ?>"
                                class="participants-no-show btn btn-block btn-danger">No Show
                        </button>
                        <?php
                    } elseif ($participant['participant_status' . $suffix] == CANCELED) {
                        ?>
                        User cancelled this appointment!
                        <?php
                    } elseif ($participant['participant_status' . $suffix] == NO_SHOW) {
                        ?>
                        Instructor Marked this Participant as no show
                        <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="row">
            <div class="p-3 mb-2 col-lg-12 text-dark">
                <strong>No Participants in this appointment</strong></div>
        </div>
        <?php
    }

} catch (\LogicException $e) {
    echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
}