<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */

use REDCap;


$url = $module->getUrl('src/types.php', true, true, true);
$url = str_replace('pid', 'projectid', $url);
?>
<h3>Public URL to access the Appointment Scheduler.</h3>
<a href="<?php echo $url ?>"><?php echo $url ?></a>
