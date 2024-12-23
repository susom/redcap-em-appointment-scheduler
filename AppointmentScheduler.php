<?php

namespace Stanford\AppointmentScheduler;

use REDCap;

include_once 'emLoggerTrait.php';
include_once 'Participant.php';
include_once 'CalendarEmail.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    // Required if your environment does not handle autoloading
    require __DIR__ . '/vendor/autoload.php';
}



/**
 * Constants where appointment  is located
 */
define('CAMPUS_AND_VIRTUAL', 0);
define('VIRTUAL_ONLY', 1);
define('CAMPUS_ONLY', 2);

/**
 * Constants for appointment location text
 */
define('CAMPUS_AND_VIRTUAL_TEXT', 'Redwood City Campus , or Virtual via Zoom Meeting.');
define('VIRTUAL_ONLY_TEXT', 'Virtual via Zoom Meeting.');
define('CAMPUS_ONLY_TEXT', 'Redwood City Campus');


/**
 * Constants for participation statuses
 */
define('AVAILABLE', 0);
define('RESERVED', 1);
define('CANCELED', 2);
define('NO_SHOW', 3);

/**
 * Constants for statuses  text
 */
define('AVAILABLE_TEXT', 'Available');
define('RESERVED_TEXT', 'Reserved');
define('CANCELED_TEXT', 'Canceled');
define('NO_SHOW_TEXT', 'No_Show');


define('MODULE_NAME', 'Appointment_scheduler');


/**
 * REDCap constants
 */
define('REDCAP_INCOMPLETE', 0);
define('REDCAP_UNVERIFIED', 1);
define('REDCAP_COMPLETE', 2);


/**
 * Complementary Constants (if you change in config.json you MUST update below constants accordingly)
 */
define('COMPLEMENTARY_EMAIL', 'email');
define('COMPLEMENTARY_NAME', 'name');
define('COMPLEMENTARY_MOBILE', 'mobile');
define('COMPLEMENTARY_NOTES', 'complementary_notes');
define('COMPLEMENTARY_PROJECT_ID', 'complementary_project_id');


define('COMPLEMENTARY_SUFFIX', 'complementary_suffix');
define('PROJECTID', 'projectid');

define("SURVEY_RESERVATION_FIELD", "survey_reservation_id");
define("RESERVATION_SLOT_FIELD", "slot_id");
define("DEFAULT_EMAIL", "redcap-scheduler@stanford.edu");
define("DEFAULT_NAME", "REDCap Admin");

/**
 * Class AppointmentScheduler
 * @package Stanford\AppointmentScheduler
 * @property \CalendarEmail $emailClient
 * @property  array $instances
 * @property int $eventId
 * @property array $eventInstance
 * @property array $calendarParams
 * @property \Stanford\AppointmentScheduler\Participant $participant
 * @property \Monolog\Logger $logger
 * @property string $suffix
 * @property int $mainSurveyId
 * @property int $projectId
 * @property int $recordId
 * @property \Project $project
 * @property string $surveyField
 */
class AppointmentScheduler extends \ExternalModules\AbstractExternalModule
{


    use emLoggerTrait;

    /**
     * @var \CalendarEmail|null
     */
    private $emailClient = null;

    /**
     * @var array of all instances in the project
     */
    private $instances;

    /**
     * @var array for specific instance
     */
    private $eventInstance;


    private $mainSurveyId;
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var array
     */
    private $calendarParams;

    /**
     * @var \Participant;
     */
    private $participant;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @var int
     */
    private $projectId;

    /**
     * @var
     */
    private $recordId;
    /**
     * @var
     */
    private $project;


    private $surveyField;

    /**
     * AppointmentScheduler constructor.
     */
    public function __construct()
    {
        try {

            parent::__construct();

            /**
             * so when you enable this it does not throw an error !!
             */
            if ($_GET && ($_GET['projectid'] != null || $_GET['pid'] != null)) {

                $projectId = ($_GET['projectid'] != null ? filter_var($_GET['projectid'],
                    FILTER_SANITIZE_NUMBER_INT) : filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT));
                $this->setProjectId($projectId);
                /**
                 * This call must be done after parent constructor is called
                 */
                $this->setInstances();


                $this->setProject(new \Project($this->getProjectId()));

                //when loaded for first time cache user name and is super user
                if (defined('USERID')) {
                    $this->setCachedUsername(USERID);
                }
                if (defined('SUPER_USER')) {
                    $this->setCachedIsSuperUser(SUPER_USER);
                }
            }


            /**
             * Initiate suffix if exists
             */
            $this->setSuffix();

            /**
             * Initiate Email Client
             */
            $this->setEmailClient();


            /**
             * Initiate Email Participant
             */
            $this->setParticipant(new  \Stanford\AppointmentScheduler\Participant());

            /**
             * Only call this class when event is provided.
             */
            if (isset($_GET['event_id']) || isset($_POST['event_id'])) {

                $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : $_POST['event_id'];
                /**
                 * sanitize variable and save it
                 */
                $this->setEventId(filter_var($eventId, FILTER_SANITIZE_NUMBER_INT));

                /**
                 * when event id exist lets find its instance
                 */
                $this->setEventInstance($this->getEventId());
            }


        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return int
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @param int $recordId
     */
    public function setRecordId()
    {
        $temp = func_get_args();
        $recordId = $temp[0];
        $this->recordId = $recordId;
    }

    /**
     * @return \Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return int
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }



    /**
     * @return array
     */
    public function getCalendarParams()
    {
        return $this->calendarParams;
    }

    /**
     * @param array $calendarParams
     */
    public function setCalendarParams($calendarParams)
    {
        $this->calendarParams = $calendarParams;
    }


    /**
     * @return mixed
     */
    public function getMainSurveyId()
    {
        return $this->mainSurveyId;
    }

    /**
     * @param mixed $mainSurveyId
     */
    public function setMainSurveyId($mainSurveyId)
    {
        $this->mainSurveyId = $mainSurveyId;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    public function setSuffix()
    {
        $this->suffix = (isset($_GET['complementary_suffix']) ? filter_var($_GET['complementary_suffix'],
            FILTER_SANITIZE_STRING) : '');
    }

    /**
     * @return \CalendarEmail
     */
    public function getEmailClient()
    {
        return $this->emailClient;
    }

    /**
     * @param \CalendarEmail $emailClient
     */
    public function setEmailClient()
    {
        $this->emailClient = new \CalendarEmail;
    }


    /**
     * @return \Stanford\AppointmentScheduler\Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param \Stanford\AppointmentScheduler\Participant $participant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;
    }


    /**
     * @return array
     */
    public function getEventInstance()
    {
        return $this->eventInstance;
    }

    /**
     * Pass event id and search for it in the instances array
     * @param int $eventId
     */
    public function setEventInstance($eventId)
    {
        foreach ($this->getInstances() as $instance) {
            if ($instance['slot_event_id'] == $eventId) {
                $this->eventInstance = $instance;
            }
        }
    }

    /**
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return mixed
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * save $instances
     */
    public function setInstances()
    {
        $this->instances = $this->getSubSettings('instance', $this->getProjectId());;
    }

    /**
     * @param int $configId
     * @return mixed
     */
    public function getSlots($configId)
    {
        $types = $this->getInstances();

        try {
            if (!empty($types[$configId])) {
                $instance = $types[$configId];
                $eventId = $instance['event_id'];
                return REDCap::getData('array', null, null, $eventId);
            } else {
                throw new \Exception('No Type exists');
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Get available time slots for specific date
     * @param string $date
     * @param int $event_id
     * @return array
     */
    public function getDateAvailableSlots($date, $event_id)
    {
        try {
            if (!empty($date)) {

                /*
                 * TODO Check if date within allowed window
                 */
                $filter = "[start] > '" . date('Y-m-d', strtotime($date)) . "' AND " . "[start] < '" . date('Y-m-d',
                        strtotime($date . ' + 1 DAY')) . "' AND [slot_status] != '" . CANCELED . "'";
                $param = array(
                    'project_id' => $this->getProjectId(),
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => $event_id
                );
                return REDCap::getData($param);
            } else {
                throw new \LogicException('Not a valid date, Aborting!');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param int $event_id
     * @return array
     */
    public function getTimeSlot($record_id, $event_id)
    {
        try {
            if ($event_id) {

                $filter = "[record_id] = '" . $record_id . "'";
                $param = array(
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => REDCap::getEventNames(true, false, $event_id)
                );
                $record = REDCap::getData($param);
                return $record[$record_id][$event_id];
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    private function sortRecordsByDate($records, $eventId)
    {
        $temp = array();
        $result = array();
        foreach ($records as $record) {
            $date = date('Y-m-d H:i:s', strtotime($record[$eventId]['start']));
            $temp[$date][] = $record;
        }
        ksort($temp);
        foreach ($temp as $timestamp) {
            if (empty($result)) {
                $result = $timestamp;
            } else {
                $result = array_merge($result, $timestamp);
            }
        }
        return $result;
    }

    /**
     * @param $eventId
     * @param null $month
     * @param null $year
     * @return mixed
     */
    public function getMonthSlots($eventId, $year = null, $month = null)
    {
        try {
            if ($eventId) {

                $variable = 'start' . $this->getSuffix();
                if ($month != '' && $year != '') {
                    $start = "$year-$month-01";
                    $end = date('Y-m-t', strtotime($start));
                } else {
                    $start = date('Y-m-d');
                    // next 30 days
                    $end = date('Y-m-d', strtotime('+30 days'));
                }

                $param = array(
                    'project_id' => $this->getProjectId(),
                    'return_format' => 'array',
                    'events' => $eventId
                );
                $data = array();
                $records = REDCap::getData($param);
                foreach ($records as $record) {
                    if (strtotime($record[$eventId][$variable]) > time() && strtotime($record[$eventId][$variable]) < strtotime($end) && $record[$eventId]['slot_status'] != CANCELED) {
                        $data[] = $record;
                    }
                }
                return $this->sortRecordsByDate($data, $eventId);
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
            }
        } catch (\LogicException $e) {
            //error($e->getMessage());
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function getAllOpenSlots($suffix = '')
    {
        try {
            /*
                 * TODO Check if date within allowed window
                 */
            $filter = "[start$suffix] > '" . date('Y-m-d') . "' AND " . "[slot_status$suffix] != '" . CANCELED . "'";
            $param = array(
                'project_id' => $this->getProjectId(),
                'filterLogic' => $filter,
                'return_format' => 'array'
            );
            return REDCap::getData($param);
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function getBookedSlots($suffix)
    {
        try {
            /*
                 * TODO Check if date within allowed window
                 */
            $filter = "[start$suffix] > '" . date('Y-m-d') . "' AND " . "[slot_status$suffix] = '" . RESERVED . "'";
            $param = array(
                'project_id' => $this->getProjectId(),
                'filterLogic' => $filter,
                'return_format' => 'array'
            );
            return REDCap::getData($param);
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function prepareInstructorsSlots($records, $suffix)
    {
        $result = array();
        /**
         * just to reduce load on DB
         */
        $events = array();
        try {
            if (!empty($records)) {
                foreach ($records as $slots) {
                    foreach ($slots as $event_id => $slot) {
                        if (!isset($events[$event_id])) {
                            $events[$event_id] = $this->getUniqueEventName($event_id);
                        }

                        $slot['event_name'] = $events[$event_id];
                        $slot['event_id'] = $event_id;
                        $result[] = $slot;
                    }
                }

                return $result;
            } else {
                throw new \LogicException('No slots found');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param array $user
     */
    public function notifyUser($user)
    {
        $instance = $this->getEventInstance();
        $this->calendarParams['calendarOrganizerEmail'] = ($instance['sender_email'] != '' ? $instance['sender_email'] : DEFAULT_EMAIL);
        $this->calendarParams['calendarOrganizer'] = ($instance['sender_name'] != '' ? $instance['sender_name'] : DEFAULT_NAME);
        $this->calendarParams['calendarDescription'] = $instance['calendar_body'];
        $this->calendarParams['calendarLocation'] = $user['participant_location'];
        $this->calendarParams['calendarDate'] = preg_replace("([^0-9/])", "", $_POST['calendarDate']);
        $this->calendarParams['calendarStartTime'] = preg_replace("([^0-9/])", "", $_POST['calendarStartTime']);
        $this->calendarParams['calendarEndTime'] = preg_replace("([^0-9/])", "", $_POST['calendarEndTime']);
        $this->calendarParams['calendarParticipants'] = array($user['name'] => $user['email']);
        $this->calendarParams['calendarSubject'] = '--CONFIRMATION-- This message to confirm your appointment at ' . date('m/d/Y',
                strtotime($this->calendarParams['calendarDate'])) . ' from ' . date('h:i',
                strtotime($this->calendarParams['calendarStartTime'])) . ' to ' . date('h:i',
                strtotime($this->calendarParams['calendarEndTime']));
        $this->sendEmail($user['email'],
            ($instance['sender_email'] != '' ? $instance['sender_email'] : DEFAULT_EMAIL),
            ($instance['sender_name'] != '' ? $instance['sender_name'] : DEFAULT_NAME),
            '--CONFIRMATION-- This message to confirm your appointment at ' . date('m/d/Y',
                strtotime($this->calendarParams['calendarDate'])) . ' from ' . date('h:i',
                strtotime($this->calendarParams['calendarStartTime'])) . ' to ' . date('h:i',
                strtotime($this->calendarParams['calendarEndTime'])),
            $instance['calendar_body'],
            true
        );

        if ($user['instructor']) {
            $this->sendEmail($user['instructor'] . '@stanford.edu',
                ($instance['sender_email'] != '' ? $instance['sender_email'] : DEFAULT_EMAIL),
                ($instance['sender_name'] != '' ? $instance['sender_name'] : DEFAULT_NAME),
                '--CONFIRMATION-- ' . $user['email'] . ' scheduled an appointment at ' . date('m/d/Y',
                    strtotime($this->calendarParams['calendarDate'])) . ' from ' . date('h:i',
                    strtotime($this->calendarParams['calendarStartTime'])) . ' to ' . date('h:i',
                    strtotime($this->calendarParams['calendarEndTime'])),
                $instance['calendar_body'],
                true
            );
        }

    }


    /**
     * send calendar or regular emails
     * @param string $email
     * @param string $senderEmail
     * @param string $senderName
     * @param string $subject
     * @param string $body
     * @param bool $calendar
     * @param string $url
     */
    private function sendEmail($email, $senderEmail, $senderName, $subject, $body, $calendar = false, $url = '')
    {
        $this->emailClient->setTo($email);
        $this->emailClient->setFrom($senderEmail);
        $this->emailClient->setFromName($senderName);
        $this->emailClient->setSubject($subject);
        $this->emailClient->setBody($body);
        $this->emailClient->setUrlString("<a href='" . $this->getSchedulerURL() . "'>View Appointment Scheduler</a>");
        if ($calendar) {
            $this->emailClient->sendCalendarEmail($this->calendarParams);
        } else {
            $this->emailClient->send();
        }

    }

    /**
     * @return array
     */
    public function sanitizeInput()
    {
        $data = array();
        $data['email' . $this->getSuffix()] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $data['name' . $this->getSuffix()] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $data['mobile' . $this->getSuffix()] = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
        $data['participant_notes' . $this->getSuffix()] = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
        $data['project_id' . $this->getSuffix()] = filter_var($_POST['project_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['slot_id' . $this->getSuffix()] = filter_var($_POST['record_id'], FILTER_SANITIZE_STRING);
        $data['private' . $this->getSuffix()] = filter_var($_POST['private'], FILTER_SANITIZE_NUMBER_INT);
        $data['participant_location' . $this->getSuffix()] = filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);

        /**
         * For Event data you do not need to append suffix info because it will not be saved.
         */
        $data['event_id'] = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['redcap_event_name'] = $this->getUniqueEventName($data['event_id']);
        // $data['date'] = date('Y-m-d', strtotime(filter_var($_POST['date'], FILTER_SANITIZE_NUMBER_INT)));

        return $data;
    }

    /**
     * @param $type
     * @return string
     */
    public function getTypeText($type)
    {
        switch ($type) {
            case VIRTUAL_ONLY:
                $typeText = VIRTUAL_ONLY_TEXT;
                break;
            case CAMPUS_ONLY:
                $typeText = CAMPUS_ONLY_TEXT;
                break;
            default:
                $typeText = CAMPUS_AND_VIRTUAL_TEXT;
        }
        return $typeText;
    }

    /**
     * @param array $data
     * @param int $id
     */
    public function updateTimeSLot($data, $id)
    {
        $filters = '';
        foreach ($data as $key => $value) {
            $filters = " $key = '$value' ,";
        }

        $filters = rtrim($filters, ",");
        $sql = sprintf("UPDATE  redcap_appointment_participant SET $filters WHERE id = $id");

        if (!db_query($sql)) {
            throw new \LogicException('cant update participant');
        }
    }

    public function notifyParticipants($slotId, $eventId, $message)
    {
        $instance = $this->getEventInstance();
        $participants = $this->participant->getSlotActualReservedSpots($slotId, $eventId, $this->getProjectId());
        foreach ($participants as $participant) {
            $result = end($participant);
            $this->emailClient->setCalendarOrganizerEmail(($instance['sender_email'] != '' ? $instance['sender_email'] : DEFAULT_EMAIL));
            $this->emailClient->setCalendarOrganizer(($instance['sender_name'] != '' ? $instance['sender_name'] : DEFAULT_NAME));
            $this->emailClient->setTo($result['email']);
            $this->emailClient->setFrom(($instance['sender_name'] != '' ? $instance['sender_name'] : DEFAULT_NAME));
            $this->emailClient->setFromName($result['email']);
            $this->emailClient->setSubject($message['subject']);
            $this->emailClient->setBody($message['body']);
            $this->emailClient->send();
            $this->forceCancellation($result[$this->getPrimaryRecordFieldName()], $eventId);
        }
    }

    public function forceCancellation($recordId, $eventId)
    {
        $data['participant_status'] = CANCELED;
        $data[$this->getPrimaryRecordFieldName()] = $recordId;
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $eventId);
        $response = \REDCap::saveData('json', json_encode(array($data)));
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return array
     */
    public static function getSlot($record_id, $event_id, $projectId, $primary)
    {
        try {
            if ($event_id) {
                /*
                 * TODO Check if date within allowed window
                 */
                $filter = "[$primary] = '" . $record_id . "'";
                $param = array(
                    'project_id' => $projectId,
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => $event_id
                );
                $record = REDCap::getData($param);
                return $record[$record_id][$event_id];
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
            }
        } catch (\LogicException $e) {
            echo $e->getMessage();
        }
    }


    public function getNextRecordsId($eventId, $projectId)
    {
        $data_table = method_exists('\REDCap', 'getDataTable') ? \REDCap::getDataTable($projectId) : "redcap_data";
        $sql = sprintf("SELECT max(cast(record as SIGNED)) as record_id from $data_table WHERE project_id = '$projectId' AND event_id = '$eventId'");

        $this->emLog("SQL Statement:", $sql);
        $result = db_query($sql);
        if (!$result) {
            throw new \LogicException('cant find next record ');
        }

        $data = db_fetch_assoc($result);
        $this->emLog("Resulted Data:", $data);
        $id = $data['record_id'];
        $id++;
        $this->emLog("Return ID", $id);
        return $id;
    }

    /**
     * @param array $instances
     * @param int $slotEventId
     * @return bool
     */
    public function getReservationEventIdViaSlotEventId($slotEventId)
    {
        $instances = $this->getInstances();
        foreach ($instances as $instance) {

            /**
             * If its regular appointment
             */
            if ($this->getSuffix() == '') {
                if ($instance['slot_event_id'] == $slotEventId) {
                    return $instance['reservation_event_id'];
                }
            } else {
                if ($instance['survey_complementary_slot_event_id'] == $slotEventId) {
                    return $instance['survey_complementary_reservation_event_id'];
                }
            }

        }
        return false;
    }

    /**
     * call REDCap hook
     */
    public function redcap_survey_complete(
        $project_id,
        $record = null,
        $instrument,
        $event_id,
        $group_id = null,
        $survey_hash,
        $response_id = null,
        $repeat_instance = 1
    )
    {
        /*$instances = $this->getInstances();
        $survey = filter_var($_GET['page'], FILTER_SANITIZE_STRING);
        $uri = '';
        foreach ($instances as $instance) {
            if ($instance['instrument_id_for_complementary_appointment'] == $survey) {
                $uri = $this->buildSurveyComplementaryInputsURI($instance);
                break;
            }
        }
        $url = $this->getUrl('src/types.php', false, true) . $uri;*/
        /**
         * get survey record
         */
        $this->setMainSurveyId($instrument);
        $primary = $this->getPrimaryRecordFieldName();
        $filter = "[$primary] = '" . $record . "'";
        $param = array(
            'project_id' => $this->getProjectId(),
            'filterLogic' => $filter,
            'return_format' => 'array',
            'events' => $event_id
        );
        $survey = REDCap::getData($param);
        if (!empty($survey)) {
            /**
             * get reservation record for the survey
             */
            $reservationId = $survey[$record][$event_id][SURVEY_RESERVATION_FIELD];
            $reservationEventId = $this->getReservationEventId();
            $filter = "[$primary] = '" . $reservationId . "'";
            $param = array(
                'project_id' => $this->getProjectId(),
                'filterLogic' => $filter,
                'return_format' => 'array',
                'events' => $reservationEventId
            );
            $reservation = REDCap::getData($param);

            if (!empty($reservation)) {
                /**
                 * get slot record for the reservation.
                 */
                $slotId = $reservation[$reservationId][$reservationEventId][RESERVATION_SLOT_FIELD];
                $slotEventId = $this->getSlotsEventId();

                $filter = "[$primary] = '" . $slotId . "'";
                $param = array(
                    'project_id' => $this->getProjectId(),
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => $slotEventId
                );
                $slot = REDCap::getData($param);
                if (!empty($slot)) {
                    require __DIR__ . '/src/survey.php';
                    $record = $slot[$slotId][$slotEventId];
                    $status = $reservation[$reservationId][$reservationEventId]['participant_status'];
                    switch ($status) {
                        case CANCELED:
                            echo "Your reservation at " . date('M/d/Y', strtotime($record['start'])) . ' was canceled';
                            break;
                        case NO_SHOW:
                            echo "You missed your reservation at " . date('M/d/Y',
                                    strtotime($record['start'])) . ' and Marked as No Show';
                            break;
                        default:
                            require __DIR__ . '/src/survey.php';
                            echo "You have a reservation on " . date('M/d/Y',
                                    strtotime($record['start'])) . " between " . date('H:i',
                                    strtotime($record['start'])) . " and " . date('H:i',
                                    strtotime($record['end'])) . " <a class='manage' href='javascript:;'>Click Here</a> edit your reservation.";
                            break;
                    }

                }
            }
        }
    }

    /**
     * @param array $instance
     * @return string
     */
    private function buildSurveyComplementaryInputsURI($instance)
    {
        $email = filter_var($_POST[$instance[COMPLEMENTARY_EMAIL]], FILTER_SANITIZE_STRING);
        $result = '&' . COMPLEMENTARY_EMAIL . '=' . $email;
        $name = filter_var($_POST[$instance[COMPLEMENTARY_NAME]], FILTER_SANITIZE_STRING);
        $result .= '&' . COMPLEMENTARY_NAME . '=' . $name;
        $mobile = filter_var($_POST[$instance[COMPLEMENTARY_MOBILE]], FILTER_SANITIZE_NUMBER_INT);
        $result .= '&' . COMPLEMENTARY_MOBILE . '=' . $mobile;
        $notes = filter_var($_POST[$instance[COMPLEMENTARY_NOTES]], FILTER_SANITIZE_STRING);
        $result .= '&' . COMPLEMENTARY_NOTES . '=' . $notes;
        $result .= '&complementary=true';
        $result .= '&complementary_suffix=' . $instance[COMPLEMENTARY_SUFFIX];

        return $result;
    }

    /**
     * @param int $eventId
     * @return string
     */
    public function getSuffixViaEventId($eventId)
    {
        $instances = $this->getInstances();
        foreach ($instances as $instance) {
            /**
             * if the event id passed is survey_complementary_slot_event_id or survey_complementary_reservation_event_id
             */
            if ($instance['survey_complementary_slot_event_id'] == $eventId || $instance['survey_complementary_reservation_event_id'] == $eventId) {
                return $instance['complementary_suffix'];
            }
        }
        return '';
    }

    /**
     * @param string $user
     * @return bool|\mysqli_result
     */
    public function getUserProjects($user)
    {
        // Retrieve the projects that the user has access to
        $query = "select pr.project_id, pr.app_title " .
            " from redcap_user_rights ur, redcap_projects pr " .
            " where ur.username = '" . $user . "'" .
            " and ur.project_id = pr.project_id order by pr.project_id";
        return db_query($query);
    }

    /**
     * @return boolean
     */
    public function getNoteLabel()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['note_textarea_label'];
    }

    /**
     * @return boolean
     */
    public function showLocationsOptions()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['location_options'];
    }

    /**
     * @return boolean
     */
    public function showProjectIds()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['show_projects'];
    }

    /**
     * @return boolean
     */
    public function showAttendingOptions()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['show_attending_options'];
    }

    /**
     * @return int
     */
    public function getDefaultAttendingOption()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['show_attending_default'];
    }

    /**
     * @return boolean
     */
    public function showNotes()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['show_notes'];
    }

    /**
     * @param int $eventId
     * @return bool|array
     */
    private function identifyCurrentInstance($eventId)
    {
        foreach ($this->getInstances() as $instance) {
            if ($instance['slot_event_id'] == $eventId) {
                return $instance;
            }
        }
        return false;
    }

    /**
     * @param $project_id
     * @param null $record
     * @param $instrument
     * @param $event_id
     * @param null $group_id
     * @param $survey_hash
     * @param null $response_id
     * @param int $repeat_instance
     */
    public function redcap_survey_page_top(
        $project_id,
        $record = null,
        $instrument,
        $event_id,
        $group_id = null,
        $survey_hash,
        $response_id = null,
        $repeat_instance = 1
    )
    {

        // check if the instrument is defined as survey instrument in EM
        $surveyInstruments = $this->getProjectSetting("instrument_id_for_complementary_appointment");

        if (!empty($surveyInstruments) && in_array($instrument, $surveyInstruments)) {
            $this->setInstances();
            $this->setRecordId($record);
            $this->setMainSurveyId($instrument);
            $index = array_search($instrument, $surveyInstruments);
            $fields = $this->getProjectSetting("survey_record_id_field");
            $this->setSurveyField($fields[$index]);
            //this included for ajax loader
            echo '<style>';
            require __DIR__ . '/src/css/types.css';
            echo '</style>';
            require __DIR__ . '/src/survey.php';
        }

    }

    /**
     * @return int
     */
    public function getSlotsEventId()
    {
        foreach ($this->getInstances() as $instance) {
            if ($instance['instrument_id_for_complementary_appointment'] == $this->getMainSurveyId()) {
                return $instance['slot_event_id'];
            }
        }
        throw new \LogicException("No Event is assigned");
    }

    /**
     * @return int
     */
    public function getReservationEventId()
    {
        if ($this->getInstances()) {
            foreach ($this->getInstances() as $instance) {
                if ($instance['instrument_id_for_complementary_appointment'] == $this->getMainSurveyId()) {
                    return $instance['reservation_event_id'];
                }
            }
            // throw new \LogicException("No Event is assigned");
        }
    }

    /**
     * @param $pid
     * @param int|null $month
     * @param int|null $year
     * @return bool|\mysqli_result
     */
    public function getProjectREDCapCalendar($pid, $year = null, $month = null)
    {
        if ($month != '' && $year != '') {
            $date = "$year-$month-01";
            $filter = "event_date BETWEEN '" . date('Y-m-01', strtotime($date)) . "' AND " . " '" . date('Y-m-t',
                    strtotime($date)) . "'";
        } else {
            $filter = "event_date BETWEEN '" . date('Y-m-d') . "' AND " . " '" . date('Y-m-d',
                    strtotime('+30 days')) . "'";
        }
        $sql = sprintf("SELECT * from redcap_events_calendar WHERE project_id = $pid AND $filter");

        return db_query($sql);
    }

    /**
     * @param string $slotDate
     * @param string $email
     * @param string $suffix
     * @param int $slotEventId
     * @param int $reservationEventId
     */
    public function doesUserHaveSameDateReservation($slotDate, $sunetId, $suffix, $slotEventId, $reservationEventId)
    {
        $reservations = $this->participant->getUserParticipation($sunetId, $suffix, $this->getProjectId(), RESERVED);

        foreach ($reservations as $reservation) {
            $record = $reservation[$reservationEventId];
            $reservationSlot = self::getSlot($record['slot_id'], $slotEventId, $this->getProjectId(),
                $this->getPrimaryRecordFieldName());
            $reservationSlotDate = date('Y-m-d', strtotime($reservationSlot['start']));
            if ($reservationSlotDate == $slotDate) {
                throw new \LogicException("you cant book more than one reservation on same date. please select another date");
            }
        }
    }

    public function redcap_module_link_check_display($project_id, $link)
    {
        $link['url'] .= '&projectid=' . $project_id;
        return $link;
    }

    /**
     * @param $eventId
     * @return array|mixed|null
     */
    public function getUniqueEventName($eventId)
    {
        return $this->getProject()->getUniqueEventNames($eventId);
    }

    /**
     * @return mixed
     */
    public function getPrimaryRecordFieldName()
    {
        return $this->getProject()->table_pk;
    }

    public function getSchedulerURL()
    {
        return $this->getUrl('src/type.php', true,
                false) . '&' . $this->getSuffix() . '&' . PROJECTID . '=' . $this->getProjectId();
    }

    /**
     * @return bool
     */
    public static function isUserHasManagePermission()
    {
        if (defined('PROJECT_ID') && (!defined('NOAUTH') || NOAUTH == false)) {

            //this function return right for main user when hit it with survey respondent!!!!!
            $right = REDCap::getUserRights();
            (new AppointmentScheduler())->emLog($right);
            $user = $right[USERID];

            if ($user['design'] === "1") {
                return true;
            }
        } elseif (defined('SUPER_USER') && SUPER_USER == "1") {
            return true;
        }

        return false;
    }


    /**
     * get project name
     * @param $projectId
     * @return mixed
     */
    public static function getProjectName($projectId)
    {
        try {
            $project = new \Project($projectId);
            $name = $project->project['app_title'];
            unset($project);
            return $name;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    public function setCachedUsername($username)
    {
        if (!$_SESSION['APPOINTMENT_SCHEDULER_USERNAME']) {
            $_SESSION['APPOINTMENT_SCHEDULER_USERNAME'] = $username;
        }
    }

    public function setCachedIsSuperUser($bool)
    {
        if (!$_SESSION['APPOINTMENT_SCHEDULER_IS_SUPER_USER']) {
            $_SESSION['APPOINTMENT_SCHEDULER_IS_SUPER_USER'] = $bool;
        }
    }

    public function getCachedUsername()
    {
        return $_SESSION['APPOINTMENT_SCHEDULER_USERNAME'];
    }

    public function getCachedIsSuperUser()
    {
        return $_SESSION['APPOINTMENT_SCHEDULER_IS_SUPER_USER'];
    }

    /**
     * @return string
     */
    public function getSurveyField(): string
    {
        return $this->surveyField;
    }

    /**
     * @param string $surveyField
     */
    public function setSurveyField(string $surveyField): void
    {
        $this->surveyField = $surveyField;
    }


}