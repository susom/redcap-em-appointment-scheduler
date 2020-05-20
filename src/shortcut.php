<?php

namespace Stanford\CovidAppointmentScheduler;

/** @var \Stanford\CovidAppointmentScheduler\CovidAppointmentScheduler $module */

use REDCap;


$url = $module->getUrl('src/types.php', false, true, true);
$url = str_replace('pid', 'projectid', $url);
?>
<h3>Authenticated Appointment Scheduler Page</h3>
<a href="<?php echo $url ?>"><?php echo $url ?></a>
