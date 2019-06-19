<?php

namespace Stanford\AppointmentScheduler;

use REDcap;


include_once 'emLoggerTrait.php';
include_once 'Participant.php';
include_once 'CalendarEmail.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    // Required if your environment does not handle autoloading
    require __DIR__ . '/vendor/autoload.php';


}

use Twilio\Rest\Client;

/**
 * Constants where appointment  is located
 */
define('CAMPUS_AND_VIRTUAL', 0);
define('VIRTUAL_ONLY', 1);
define('CAMPUS_ONLY', 2);


define('CAMPUS_AND_VIRTUAL_TEXT', 'Redwood City Campus , or Virtual via Zoom Meeting.');
define('VIRTUAL_ONLY_TEXT', 'Virtual via Zoom Meeting.');
define('CAMPUS_ONLY_TEXT', 'Redwood City Campus');
/**
 * Class AppointmentScheduler
 * @package Stanford\AppointmentScheduler
 * @property \CalendarEmail $emailClient
 * @property Client $twilioClient
 * @property  array $instances
 * @property int $eventId
 * @property array $eventInstance
 * @property array $calendarParams
 * @property \Participant $participant
 */
class AppointmentScheduler extends \ExternalModules\AbstractExternalModule
{

    use emLoggerTrait;
    use Participant;

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

                /**
                 * initiate Calendar email client
                 */
                $this->emailClient = new \CalendarEmail;

                /**
                 * Define Twilio client using event instance
                 */
                $this->twilioClient = new Client($this->eventInstance['twilio_sid'],
                    $this->eventInstance['twilio_token']);
            }
        } catch (\Exception $e) {
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
            echo $e->getMessage();
        }
    }


    public function getCurrentMonthSlots($event_id)
    {
        try {
            if ($event_id) {

                /*
                 * TODO Check if date within allowed window
                 */
                $filter = "[start] > '" . date('Y-m-d') . "' AND " . "[start] < '" . date('Y-m-d',
                        strtotime('first day of next month')) . "'";
                $param = array(
                    'filterLogic' => $filter,
                    'return_format' => 'array',
                    'events' => REDCap::getEventNames(true, false, $event_id)
                );
                return REDCap::getData($param);
            } else {
                throw new \LogicException('Not event id passed, Aborting!');
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
            echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
        } catch (\Twilio\Exceptions\TwilioException $e) {
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
        $data['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $data['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $data['mobile'] = filter_var($_POST['mobile'], FILTER_SANITIZE_STRING);
        $data['notes'] = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
        $data['record_id'] = filter_var($_POST['record_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['private'] = filter_var($_POST['private'], FILTER_SANITIZE_NUMBER_INT);
        $data['type'] = filter_var($_POST['type'], FILTER_SANITIZE_NUMBER_INT);
        $data['project_id'] = filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);
        $data['event_id'] = filter_var($_POST['event_id'], FILTER_SANITIZE_NUMBER_INT);
        $data['date'] = date('Y-m-d', strtotime(filter_var($_POST['date'], FILTER_SANITIZE_NUMBER_INT)));

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
}