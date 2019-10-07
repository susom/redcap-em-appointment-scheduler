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
<link rel="stylesheet" href="<?php echo $module->getUrl('src/css/types.css') ?>">
    <div class="container">
        <nav class="navbar navbar-expand-sm bg-light navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Logged in
                        as: <?php echo(defined('USERID') ? USERID : ' NOT LOGGED IN') ?></a>
                </li>
            </ul>
            <div class="collapse navbar-collapse justify-content-end" id="navbarCollapse">
                <?php
                if (defined('USERID')) {
                        ?>
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
                        <?php
                    }
                ?>
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
            ?>

            <div class="card">
                <input type="hidden" id="<?php echo $slotsEventId ?>-reservation-event-id"
                       value="<?php echo $reservationEventId ?>"
                       class="hidden"/>
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="type btn btn-link collapsed" type="button"
                                data-toggle="collapse-<?php echo $slotsEventId ?>"
                                data-target="#collapse-<?php echo $slotsEventId ?>" aria-expanded="true"
                                aria-controls="collapse-<?php echo $slotsEventId ?>"
                                data-url="<?php echo $url . '&event_id=' . $slotsEventId . '&' . COMPLEMENTARY_SUFFIX . '=' . $module->getSuffix() . '&' . PROJECTID . '=' . $module->getProjectId() . '&NOAUTH' ?>"
                                data-key="<?php echo $slotsEventId ?>" data-name="<?php echo $title ?>">
                            <?php echo $title ?>
                        </button>
                    </h2>
                </div>

                <div id="collapse-<?php echo $slotsEventId ?>" class="collapse" aria-labelledby="headingOne"
                     data-parent="#accordionExample">
                    <div class="card-body" id="<?php echo $slotsEventId ?>-calendar">
                    </div>
                </div>
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
<div class="loader"><!-- Place at bottom of page --></div>
