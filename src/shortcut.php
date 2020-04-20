<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */

use REDCap;


$url = $module->getUrl('src/type.php', true, true, true);
?>
<a href="<?php echo $url ?>">Appointment Scheuler NOAUTH Page</a>
