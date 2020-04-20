<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */

use REDCap;


$url = $module->getUrl('src/types.php', true, true, true);
$url = str_replace('pid', 'projectid', $url);
?>
<a href="<?php echo $url ?>">Appointment Scheuler NOAUTH Page</a>
