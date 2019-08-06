<?php

namespace Stanford\AppointmentScheduler;

use REDCap;

include_once 'emLoggerTrait.php';
include_once 'Participant.php';
include_once 'CalendarEmail.php';
include_once 'Survey.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    // Required if your environment does not handle autoloading
    require __DIR__ . '/vendor/autoload.php';
}

use Twilio\Rest\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
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
define('COMPLEMENTARY_EMAIL', 'complementary_email');
define('COMPLEMENTARY_NAME', 'complementary_name');
define('COMPLEMENTARY_MOBILE', 'complementary_mobile');
define('COMPLEMENTARY_NOTES', 'complementary_notes');
define('COMPLEMENTARY_PROJECT_ID', 'complementary_project_id');


define('COMPLEMENTARY_SUFFIX', 'complementary_suffix');

/**
 * Class AppointmentScheduler
 * @package Stanford\AppointmentScheduler
 * @property \CalendarEmail $emailClient
 * @property Client $twilioClient
 * @property  array $instances
 * @property int $eventId
 * @property array $eventInstance
 * @property array $calendarParams
 * @property \Stanford\AppointmentScheduler\Participant $participant
 * @property \Monolog\Logger $logger
 * @property string $suffix
 */
class AppointmentScheduler extends \ExternalModules\AbstractExternalModule
{


    use emLoggerTrait;

    /**
     * @var \CalendarEmail|null
     */
    private $emailClient = null;

    /**
     * @var Client|null
     */
    private $twilioClient = null;

    /**
     * @var array of all instances in the project
     */
    private $instances;

    /**
     * @var array for specific instance
     */
    private $eventInstance;

    /**
     * @var int
     */
    private $eventId;

    /**
     * @var array
     */
    private $calendarParams;

    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var \Participant;
     */
    private $participant;

    /**
     * @var string
     */
    private $suffix;

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
            if ($_GET) {
                /**
                 * This call must be done after parent constructor is called
                 */
                $this->setInstances();
            }

            /*
             * Initiate PSR logger
             */
            $this->setLogger();


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
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Initiate logger object
     */
    public function setLogger()
    {
        try {
            $this->logger = new Logger(MODULE_NAME);
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../../logs/' . MODULE_NAME . '.log',
                Logger::DEBUG));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            echo $e->getMessage();
        }
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
        foreach ($this->instances as $instance) {
            if ($instance['event_id'] == $eventId) {
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
        $this->instances = $this->getSubSettings('instance');;
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
            $this->logger->error($e->getMessage());
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
                        strtotime($date . ' + 1 DAY')) . "'";
                $param = array(
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => REDCap::getEventNames(true, false, $event_id)
                );
                return REDCap::getData($param);
            } else {
                throw new \LogicException('Not a valid date, Aborting!');
            }
        } catch (\LogicException $e) {
            $this->logger->error($e->getMessage());
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
            $this->logger->error($e->getMessage());
            echo $e->getMessage();
        }
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
                    $date = "$year-$month-01";
                    $filter = "[$variable] > '" . date('Y-m-01',
                            strtotime($date)) . "' AND " . "[$variable] < '" . date('Y-m-t', strtotime($date)) . "'";
                } else {
                    $filter = "[$variable] > '" . date('Y-m-d') . "' AND " . "[$variable] < '" . date('Y-m-d',
                            strtotime('first day of next month')) . "'";
                }

                $param = array(
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => REDCap::getEventNames(true, false, $eventId)
                );
                return REDCap::getData($param);
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
            }
        } catch (\LogicException $e) {
            $this->logger->error($e->getMessage());
            echo $e->getMessage();
        }
    }

    /**
     * @return array
     */
    public function getAllOpenSlots($suffix)
    {
        try {
            /*
                 * TODO Check if date within allowed window
                 */
            $filter = "[start$suffix] > '" . date('Y-m-d') . "' AND " . "[slot_status$suffix] != '" . CANCELED . "'";
            $param = array(
                'filterLogic' => $filter,
                'return_format' => 'array'
            );
            return REDCap::getData($param);
        } catch (\LogicException $e) {
            $this->logger->error($e->getMessage());
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
                            $events[$event_id] = REDCap::getEventNames(false, false, $event_id);
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
            $this->logger->error($e->getMessage());
            echo $e->getMessage();
        }
    }

    /**
     * @param array $user
     */
    public function notifyUser($user)
    {

        $this->calendarParams['calendarOrganizerEmail'] = 'ihabz@stanford.edu';
        $this->calendarParams['calendarOrganizer'] = 'Ihab Zeedia';
        $this->calendarParams['calendarDescription'] = 'Appointment for Office visit';
        $this->calendarParams['calendarDate'] = preg_replace("([^0-9/])", "", $_POST['calendarDate']);
        $this->calendarParams['calendarStartTime'] = preg_replace("([^0-9/])", "", $_POST['calendarStartTime']);
        $this->calendarParams['calendarEndTime'] = preg_replace("([^0-9/])", "", $_POST['calendarEndTime']);
        $this->calendarParams['calendarParticipants'] = array($user['name'] => $user['email']);
        $this->calendarParams['calendarSubject'] = '--CONFIRMATION-- This message to confirm your appointment at ' . date('m/d/Y',
                strtotime($this->calendarParams['calendarDate'])) . ' from ' . date('h:i',
                strtotime($this->calendarParams['calendarStartTime'])) . ' to ' . date('h:i',
                strtotime($this->calendarParams['calendarEndTime']));
        $this->sendEmail($user);

        if ($user['mobile'] && $this->twilioClient) {
            $message = array(
                'from' => '+' . $this->eventInstance['phone_number_country_code'] . $this->eventInstance['twilio_sender_number'],
                'body' => '--CONFIRMATION-- This message to confirm your appointment at ' . date('m/d/Y',
                        strtotime($this->calendarParams['calendarDate'])) . ' from ' . date('h:i',
                        strtotime($this->calendarParams['calendarStartTime'])) . ' to ' . date('h:i',
                        strtotime($this->calendarParams['calendarEndTime']))
            );
            $this->sendTextMessage($user, $message);
        }
    }

    /**
     * @param $user
     * @param $message
     * @throws \Twilio\Exceptions\TwilioException
     */
    private function sendTextMessage($user, $message)
    {
        try {
            $result = $this->twilioClient->messages->create(
                $user['mobile'],
                $message
            );


            /**
             * log sent message.
             */
            if ($result->errorCode == null) {
                $sql = sprintf("insert into redcap_user_received_text_messages (user_id, sender, receiver, message, created_at) values (" . UI_ID . ", '$result->from', '$result->to', '$result->body', " . time() . ")"
                );

                if (!db_query($sql)) {
                    throw new \LogicException('cant save sent text message ');
                }
            } elseif ($result->errorCode) {
                throw new \Twilio\Exceptions\TwilioException('Cant send message');
            }
        } catch (\LogicException $e) {
            $this->logger->error($e->getMessage());
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        } catch (\Twilio\Exceptions\TwilioException $e) {
            $this->logger->error($e->getMessage());
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        }
    }

    /**
     * send both regular and calendar email
     * @param array $user
     */
    private function sendEmail($user)
    {
        $this->emailClient->setTo($user['email']);
        $this->emailClient->setFrom('ihabz@stanford.edu');
        $this->emailClient->setFromName('Ihab Zeedia');
        $this->emailClient->setSubject('Appointment for Office visit');
        $this->emailClient->setBody('Appointment for Office visit');
        $this->emailClient->sendCalendarEmail($this->calendarParams);
        $this->emailClient->send();
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
        $data['slot_id' . $this->getSuffix()] = filter_var($_POST['record_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['private' . $this->getSuffix()] = filter_var($_POST['private'], FILTER_SANITIZE_NUMBER_INT);
        $data['participant_location' . $this->getSuffix()] = filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);

        /**
         * For Event data you do not need to append suffix info because it will not be saved.
         */
        $data['event_id'] = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true,
            filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT));
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
        $participants = $this->participant->getSlotActualReservedSpots($slotId, $eventId);
        foreach ($participants as $participant) {
            $result = end($participant);
            $this->emailClient->setTo($result['email']);
            $this->emailClient->setFrom('ihabz@stanford.edu');
            $this->emailClient->setFromName($result['email']);
            $this->emailClient->setSubject($message['subject']);
            $this->emailClient->setBody($message['body']);
            $this->emailClient->send();
            $this->forceCancellation($result['record_id'], $eventId);
        }
    }

    public function forceCancellation($recordId, $eventId)
    {
        $data['participant_status'] = CANCELED;
        $data['record_id'] = $recordId;
        $data['redcap_event_name'] = \REDCap::getEventNames(true, true, $eventId);
        $response = \REDCap::saveData('json', json_encode(array($data)));
    }

    /**
     * @param int $event_id
     * @param int $record_id
     * @return array
     */
    public static function getSlot($record_id, $event_id)
    {
        try {
            if ($event_id) {

                /*
                 * TODO Check if date within allowed window
                 */
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


    public function testCron()
    {

        $this->logger->debug('Logging from cron. this is so easy');
    }


    public function getNextRecordsId($eventId, $projectId)
    {
        $sql = sprintf("SELECT max(record) as record_id from redcap_data WHERE project_id = $projectId AND event_id = $eventId");

        $result = db_query($sql);
        if (!$result) {
            throw new \LogicException('cant save sent text message ');
        }

        $data = db_fetch_assoc($result);
        return (int)$data['record_id'] + 1;
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
    public function redcap_survey_complete()
    {
        $instances = $this->getInstances();
        $survey = filter_var($_GET['page'], FILTER_SANITIZE_STRING);
        $uri = '';
        foreach ($instances as $instance) {
            if ($instance['instrument_id_for_complementary_appointment'] == $survey) {
                $uri = $this->buildSurveyComplementaryInputsURI($instance);
                break;
            }
        }
        $url = $this->getUrl('src/types.php', false, true) . $uri;
        echo "<a href='$url'>Click Here</a> If you want to scheule a followup regarding your survey input. ";
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

    public function getNoteLabel()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['note_textarea_label'];
    }

    public function showProjectIds()
    {
        $instance = $this->identifyCurrentInstance($this->getEventId());
        return $instance['show_projects'];
    }

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
}