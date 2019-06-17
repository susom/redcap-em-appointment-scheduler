<?php

$eventId = filter_var($_GET['event_id'], FILTER_SANITIZE_NUMBER_INT);
$url = $module->getUrl('src/list.php', true, true);

?>
<link rel="stylesheet" href="<?php echo $module->getUrl('src/css/calendar.css') ?>">

<div class="container">
    <div class="row p-3 mb-2">
        <div class="col text-right">
            <a class="btn btn-danger list-view" data-key="<?php echo $eventId ?>" href="javascript:;"
               data-url="<?php echo $url . '&config=' . $key . '&event_id=' . $eventId ?>" role="button">List View</a>
        </div>

    </div>

    <div class="row">
        <div class="date-picker-2" data-toggle="popover" data-html="true" data-content=""
             placeholder="Recipient's username" id="ttry" aria-describedby="basic-addon2"></div>
        <span class="" id="example-popover-2"></span>
    </div>
</div>

<input type="hidden" name="selected-date" id="selected-date"/>
<input type="hidden" name="selected-time" id="selected-time"/>

<input type="hidden" id="slots-url" value="<?php echo $module->getUrl('src/slots.php', TRUE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="book-slot-url" value="<?php echo $module->getUrl('src/slots.php', TRUE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="book-submit-url" value="<?php echo $module->getUrl('src/book.php', FALSE, TRUE) ?>" class="hidden"/>
<input type="hidden" id="summary-url" value="<?php echo $module->getUrl('src/summary.php', true, true) ?>"
       class="hidden"/>
<input type="hidden" id="list-view-url" value="<?php echo $module->getUrl('src/list.php', true, true) ?>"
       class="hidden"/>
<input type="hidden" id="event-id" value="<?php echo $eventId ?>" class="hidden"/>


<!-- LOAD JS -->
<script src="<?php echo $module->getUrl('src/js/calendar.js') ?>"></script>