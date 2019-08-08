<?php

namespace Stanford\AppointmentScheduler;

/** @var \Stanford\AppointmentScheduler\AppointmentScheduler $module */

use REDCap;


$url = $module->getUrl('src/list.php', false, true, true);
$instances = $module->getInstances();
?>


    <?php

//JS and CSS with inputs URLs
require_once 'urls.php';
?>
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
        foreach ($instances as $instance) {
            $title = $instance['title'];
            if (isset($_GET['complementary']) && $_GET['complementary'] == 'true') {
                $slotsEventId = $instance['survey_complementary_slot_event_id'];
                $reservationEventId = $instance['survey_complementary_reservation_event_id'];
            } else {
                $slotsEventId = $instance['slot_event_id'];
                $reservationEventId = $instance['reservation_event_id'];
            }
            $slotEvent = REDCap::getEventNames(false, false, $slotsEventId);
            ?>
            <div class="row  p-3 mb-2">
                <a class="type" data-key="<?php echo $slotsEventId ?>" data-name="<?php echo $slotEvent ?>"
                   href="javascript:;"
                   data-url="<?php echo $url . '&event_id=' . $slotsEventId . '&' . COMPLEMENTARY_SUFFIX . '=' . $module->getSuffix() ?>">
                    <div class="btn btn-block btn-info"><?php echo $title ?></div>
                </a>
            </div>
            <input type="hidden" id="<?php echo $slotsEventId ?>-reservation-event-id"
                   value="<?php echo $reservationEventId ?>"
                   class="hidden"/>
            <div class="row">
                <div class="slots-container" id="<?php echo $slotsEventId ?>-calendar"
                     style="width: 100%;"></div>
            </div>
            <?php
        }
        ?>

    </div>

    <!-- LOAD JS -->
    <script src="<?php echo $module->getUrl('src/js/types.js') ?>"></script>

    <!-- LOAD MODALS -->
    <?php
require_once 'models.php';
?>