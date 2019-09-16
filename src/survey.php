<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

//this when calledn for redcap hook
if (!isset($module)) {
    $module = $this;
}
$url = $module->getUrl('src/list.php', false, true, true);
$aaaaaaaaa = $module->getProjectSetting("survey_record_id_field");
?>
<?php
try {
    require_once 'urls.php';

    require_once 'models.php';

    ?>
    <input type="hidden" value="<?php echo $module->getSlotsEventId() ?>" name="slots-events-id" id="slots-events-id">
    <input type="hidden" value="" name="reserved-email" id="reserved-email">
    <input type="hidden" value="<?php echo $module->getRecordId() ?>" name="survey-record-id" id="survey-record-id">
    <input type="hidden" value="<?php echo end($module->getProjectSetting("survey_record_id_field")) ?>"
           name="survey-record-id-field" id="survey-record-id-field">
    <input type="hidden" value="<?php echo $module->getReservationEventId() ?>" name="reservation-events-id"
           id="<?php echo $module->getSlotsEventId() ?>-reservation-event-id">
    <input type="hidden"
           value="<?php echo $url . '&event_id=' . $module->getSlotsEventId() . '&' . COMPLEMENTARY_SUFFIX . '=' . $module->getSuffix() ?>"
           id="survey-scheduler-url">
    <!-- LOAD JS -->
    <script src="<?php echo $module->getUrl('src/js/survey.js') ?>"></script>
    <script src="<?php echo $module->getUrl('src/js/types.js') ?>"></script>
    <?php
} catch (\LogicException $e) {
    echo $e->getMessage();
}
?>


