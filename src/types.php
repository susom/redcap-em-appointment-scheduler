<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

use REDCap;

$url = $module->getUrl('src/list.php', true, true);
$types = $module->getInstances();
?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>

    <div class="container">
        <nav class="navbar navbar-expand-sm bg-light navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Logged in as: <?php echo USERID ?></a>
                </li>
            </ul>
            <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="manage nav-link" href="#">Manage my Appointments</a>
                    </li>
                    <?php
                    if (SUPER_USER) {
                        ?>
                        <li class="nav-item">
                            <a class="manage-calendars nav-link" href="#">Manage Calendars</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container">
        <?php
        foreach ($types as $key => $type) {
            ?>
            <div class="row  p-3 mb-2">
                <a class="type" data-key="<?php echo $type['event_id'] ?>" href="javascript:;"
                   data-url="<?php echo $url . '&config=' . $key . '&event_id=' . $type['event_id'] ?>">
                    <div class="btn btn-block btn-info"><?php echo $type['title'] ?></div>
                </a>
            </div>
            <div class="row">
                <div class="slots-container" id="<?php echo $type['event_id'] ?>-calendar" style="width: 100%;"></div>
            </div>
            <?php
        }
        ?>

        <input type="hidden" id="slots-url" value="<?php echo $module->getUrl('src/slots.php', true, true) ?>"
               class="hidden"/>
        <input type="hidden" id="book-slot-url" value="<?php echo $module->getUrl('src/slots.php', true, true) ?>"
               class="hidden"/>
        <input type="hidden" id="book-submit-url" value="<?php echo $module->getUrl('src/book.php', false, true) ?>"
               class="hidden"/>
        <input type="hidden" id="summary-url" value="<?php echo $module->getUrl('src/summary.php', true, true) ?>"
               class="hidden"/>
        <input type="hidden" id="list-view-url" value="<?php echo $module->getUrl('src/list.php', true, true) ?>"
               class="hidden"/>
        <input type="hidden" id="manage-url" value="<?php echo $module->getUrl('src/manage.php', false, true) ?>"
               class="hidden"/>
        <input type="hidden" id="manage-calendar-url"
               value="<?php echo $module->getUrl('src/manage_calendars.php', false, true) ?>"
               class="hidden"/>
        <input type="hidden" id="cancel-appointment-url"
               value="<?php echo $module->getUrl('src/cancel.php', false, true) ?>"
               class="hidden"/>
        <input type="hidden" id="cancel-slot-url"
               value="<?php echo $module->getUrl('src/cancel_slot.php', false, true) ?>"
               class="hidden"/><input type="hidden" id="reschedule-submit-url"
                                      value="<?php echo $module->getUrl('src/reschedule.php', false, true) ?>"
                                      class="hidden"/>
        <input type="hidden" id="event-id" value="" class="hidden"/>
        <input type="hidden" id="user-email" value="<?php echo $user_email ?>" class="hidden"/>


    </div>

    <!-- LOAD JS -->
    <script src="<?php echo $module->getUrl('src/js/types.js') ?>"></script>

    <!-- LOAD MODALS -->
    <?php
require_once 'models.php';
?>