<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use Kigkonsult\Icalcreator\Vcalendar;
// Load Composer's autoloader
require 'vendor/autoload.php';
/**
 * Class CalendarEmail
 * @property string $headers
 * @property string $calendarBody
 * @property string $calendarOrganizer
 * @property string $calendarOrganizerEmail
 * @property string $calendarLocation
 * @property string $calendarDate
 * @property string $calendarStartTime
 * @property string $calendarEndTime
 * @property string $calendarSubject
 * @property string $calendarDescription
 * @property array $calendarParticipants
 * @property string $urlString
 * @property PHPMailer $mail
 *
 */
class CalendarEmail extends Message
{

    private $headers;
    private $calendarBody;
    private $calendarOrganizer;
    private $calendarOrganizerEmail;
    private $calendarParticipants = array();
    private $calendarLocation;
    private $calendarDate;
    private $calendarStartTime;
    private $calendarEndTime;
    private $calendarSubject;
    private $calendarDescription;
    private $urlString;
    private $mail;

    /**
     * @return PHPMailer
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param PHPMailer $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }


    /**
     * @return string
     */
    public function getUrlString()
    {
        return $this->urlString;
    }

    /**
     * @param string $urlString
     */
    public function setUrlString($urlString)
    {
        $this->urlString = $urlString;
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getCalendarBody()
    {
        return $this->calendarBody;
    }

    /**
     * @param string $calendarBody
     */
    public function setCalendarBody($calendarBody)
    {
        $this->calendarBody = $calendarBody;
    }

    /**
     * @return string
     */
    public function getCalendarOrganizer()
    {
        return $this->calendarOrganizer;
    }

    /**
     * @param string $calendarOrganizer
     */
    public function setCalendarOrganizer($calendarOrganizer)
    {
        $this->calendarOrganizer = $calendarOrganizer;
    }

    /**
     * @return string
     */
    public function getCalendarOrganizerEmail()
    {
        return $this->calendarOrganizerEmail;
    }

    /**
     * @param string $calendarOrganizerEmail
     */
    public function setCalendarOrganizerEmail($calendarOrganizerEmail)
    {
        $this->calendarOrganizerEmail = $calendarOrganizerEmail;
    }

    /**
     * @return string
     */
    public function getCalendarLocation()
    {
        return $this->calendarLocation;
    }

    /**
     * @param string $calendarLocation
     */
    public function setCalendarLocation($calendarLocation)
    {
        $this->calendarLocation = $calendarLocation;
    }

    /**
     * @return string
     */
    public function getCalendarDate()
    {
        return $this->calendarDate;
    }

    /**
     * @param string $calendarDate
     */
    public function setCalendarDate($calendarDate)
    {
        $this->calendarDate = $calendarDate;
    }

    /**
     * @return string
     */
    public function getCalendarStartTime()
    {
        return $this->calendarStartTime;
    }

    /**
     * @param string $calendarStartTime
     */
    public function setCalendarStartTime($calendarStartTime)
    {
        $this->calendarStartTime = $calendarStartTime;
    }

    /**
     * @return string
     */
    public function getCalendarEndTime()
    {
        return $this->calendarEndTime;
    }

    /**
     * @param string $calendarEndTime
     */
    public function setCalendarEndTime($calendarEndTime)
    {
        $this->calendarEndTime = $calendarEndTime;
    }

    /**
     * @return string
     */
    public function getCalendarSubject()
    {
        return $this->calendarSubject;
    }

    /**
     * @param string $calendarSubject
     */
    public function setCalendarSubject($calendarSubject)
    {
        $this->calendarSubject = $calendarSubject;
    }

    /**
     * @return string
     */
    public function getCalendarDescription()
    {
        return $this->calendarDescription;
    }

    /**
     * @param string $calendarDescription
     */
    public function setCalendarDescription($calendarDescription)
    {
        $this->calendarDescription = $calendarDescription;
    }

    /**
     * @return array
     */
    public function getCalendarParticipants()
    {
        return $this->calendarParticipants;
    }

    /**
     * @param array $calendarParticipants
     */
    public function setCalendarParticipants($calendarParticipants)
    {
        foreach ($calendarParticipants as $name => $participant){
            $this->calendarParticipants[$name] = $participant;
        }
    }


    /**
     * @param array $param
     */
    public function prepareCalendarData($param){
        $this->setCalendarOrganizerEmail($param['calendarOrganizerEmail']);
        $this->setCalendarOrganizer($param['calendarOrganizer']);
        $this->setCalendarSubject($param['calendarSubject']);
        $this->setCalendarDescription($param['calendarDescription']);
        $this->setcalendarLocation($param['calendarLocation']);
        $this->setCalendarDate($param['calendarDate']);
        $this->setCalendarStartTime($param['calendarStartTime']);
        $this->setCalendarEndTime($param['calendarEndTime']);
        $this->setCalendarParticipants($param['calendarParticipants']);
    }

    /**
     * Generate header and body of the calendar event
     */
    public function buildCalendarBody(){
        $from = $this->getFrom();
        $headers = "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "From: " . $from . PHP_EOL;
        if ($this->getCc() != "") {
            $headers .= "Cc: " . $this->getCc() . PHP_EOL;
        }
        if ($this->getBcc() != "") {
            $headers .= "Bcc: " . $this->getBcc() . PHP_EOL;
        }
        $headers .= "Reply-To: " . $this->getFrom() . PHP_EOL;
        $headers .= "Return-Path: " . $this->getFrom() . PHP_EOL;
        $headers .= "Content-Type: text/calendar;\
method=REQUEST;\
";
        $headers .= '        charset="UTF-8"';
        $headers .= "\
";
        $headers .= "Content-Transfer-Encoding: 7bit";
        //$headers .= "Content-Transfer-Encoding: 7bit";
        //$headers .= "Content-Type: text/plain;charset=\"utf-8\"\r\n"; #EDIT: TYPO

        $participants = '';
        foreach ($this->getCalendarParticipants() as $name => $email){
            $participants.= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN".$name.";X-NUM-GUESTS=0:MAILTO:".$email."\r\n";
        }


        //Create Mime Boundry
        $mime_boundary = "----Meeting Booking----" . MD5(TIME());

        //Create Email Body (HTML)
        $message = "--$mime_boundary\\r\
";
        $message .= "Content-Type: text/html; charset=UTF-8\
";
        $message .= "Content-Transfer-Encoding: 8bit\
\
";
        $message .= "<html>\
";
        $message .= "<body>\
";
        $message .= '<p>Dear ' . $this->getTo() . ',</p>';
        $message .= '<p>' . $this->getBody() . $this->getUrlString() . '</p>';
        $message .= "</body>\
";
        $message .= "</html>\
";
        $message .= "--$mime_boundary\\r\
";
        $calendar = 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\
';
        $calendar .= "Content-Transfer-Encoding: 8bit\
\
";
        $calendar .= "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Deathstar-mailer//theforce/NONSGML v1.0//EN\r\nMETHOD:REQUEST\r\nBEGIN:VEVENT\r\nUID:" . md5(uniqid(mt_rand(),
                true)) . "example.com\r\nDTSTAMP:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z\r\nDTSTART:" . $this->getCalendarDate() . "T" . $this->getCalendarStartTime() . "00\r\nDTEND:" . $this->getCalendarDate() . "T" . $this->getCalendarEndTime() . "00\r\nSUMMARY:" . $this->getCalendarSubject() . "\r\nORGANIZER;CN=" . $this->getCalendarOrganizer() . ":mailto:" . $this->getCalendarOrganizerEmail() . "\r\nLOCATION:" . $this->getCalendarLocation() . "\r\nDESCRIPTION:" . str_replace(array(
                "\r",
                "\n"
            ), '', $this->getCalendarDescription()) . "\r\n" . $participants . "END:VEVENT\r\nEND:VCALENDAR\r\n";
        $message .= $calendar;
        $this->setHeaders($headers);
        $this->setBody($message);
    }

    /**
     * send calendar event email
     * @param array $param
     * @return bool
     */
    public function sendCalendarEmail($param){
        try {
            $boundary = "--" . md5(time());
            $this->prepareCalendarData($param);
            $email = new PHPMailer();
            $email->SetFrom($this->getCalendarOrganizerEmail(), $this->getCalendarOrganizer()); //Name is optional
            $email->IsHTML(true);
            $email->addCustomHeader('MIME-version', "1.0");
            $email->addCustomHeader('Content-type',
                "text/calendar; method=REQUEST;boundary=" . $boundary . " charset=UTF-8");
            $email->addCustomHeader('Content-Transfer-Encoding', "7bit");
            $email->addCustomHeader('X-Mailer', "Microsoft Office Outlook 12.0");
            $email->addCustomHeader("Content-class: urn:content-classes:calendarmessage");
            $email->ContentType = 'application/ics;';
            $participants = '';
            foreach ($this->getCalendarParticipants() as $name => $e) {
                $participants .= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN" . $name . ";X-NUM-GUESTS=0:MAILTO:" . $e . "\r\n";
            }
            // create a new calendar
            $vcalendar = Vcalendar::factory([Vcalendar::UNIQUE_ID => mt_rand(),])
                // with calendaring info
                ->setMethod(Vcalendar::PUBLISH)
                ->setXprop(
                    Vcalendar::X_WR_CALNAME,
                    "Calendar Sample"
                )
                ->setXprop(
                    Vcalendar::X_WR_CALDESC,
                    "This is a demo calendar"
                )
                ->setXprop(
                    Vcalendar::X_WR_RELCALID,
                    "3E26604A-50F4-4449-8B3E-E4F4932D05B5"
                )
                ->setXprop(
                    Vcalendar::X_WR_TIMEZONE,
                    "America/Los_Angeles"
                );

            $event1 = $vcalendar->newVevent()
                ->setTransp(Vcalendar::OPAQUE)
                ->setClass(Vcalendar::P_BLIC)
                ->setSequence(1)
                // describe the event
                ->setSummary('Scheduled meeting with five occurrences')
                ->setDescription(
                    $this->getCalendarDescription(),
                    [
                        Vcalendar::ALTREP =>
                            'CID:<FFFF__=0ABBE548DFE235B58f9e8a93d@coffeebean.com>'
                    ]
                )
                //->setComment( 'It\'s going to be fun..' )
                // place the event
                //->setLocation( 'Kafé Ekorren Stockholm' )
                //->setGeo( '59.32206', '18.12485' )
                // set the time
                ->setDtstart(
                    new DateTime(
                        $this->getCalendarDate() . "T" . $this->getCalendarStartTime(),
                        new DateTimezone('America/Los_Angeles')
                    )
                )
                ->setDtend(
                    new DateTime(
                        $this->getCalendarDate() . "T" . $this->getCalendarEndTime(),
                        new DateTimezone("America/Los_Angeles")
                    )
                )
                // with recurrence rule
                /* ->setRrule(
                     [
                         Vcalendar::FREQ  => Vcalendar::WEEKLY,
                         Vcalendar::COUNT => 5,
                     ]
                 )*/
                // and set another on a specific date
                /* ->setRdate(
                     [
                         new DateTime(
                             '20190609T090000',
                             new DateTimezone( 'Europe/Stockholm' )
                         ),
                         new DateTime(
                             '20190609T110000',
                             new DateTimezone( 'Europe/Stockholm' )
                         ),
                     ],
                     [ Vcalendar::VALUE => Vcalendar::PERIOD ]
                 )
                 // and revoke a recurrence date
                 ->setExdate(
                     new DateTime(
                         '2019-05-12 09:00:00',
                         new DateTimezone( 'Europe/Stockholm' )
                     )
                 )*/
                // organizer, chair and some participants
                ->setOrganizer(
                    $this->getCalendarOrganizerEmail(),
                    [Vcalendar::CN => 'Secretary CoffeeBean']
                )
                ->setAttendee(
                    'ihab.zeedia@gmail.com',
                    [
                        Vcalendar::ROLE => Vcalendar::CHAIR,
                        Vcalendar::PARTSTAT => Vcalendar::ACCEPTED,
                        Vcalendar::RSVP => Vcalendar::FALSE,
                        Vcalendar::CN => 'President CoffeeBean',
                    ]
                )
                ->setAttendee(
                    'ihab.zeedia@stanford.edu',
                    [
                        Vcalendar::ROLE => Vcalendar::REQ_PARTICIPANT,
                        Vcalendar::PARTSTAT => Vcalendar::NEEDS_ACTION,
                        Vcalendar::RSVP => Vcalendar::TRUE,
                        Vcalendar::CN => 'Participant1 CoffeeBean',
                    ]
                );

            $vcalendarString =
                // apply appropriate Vtimezone with Standard/DayLight components
                $vcalendar->vtimezonePopulate()
                    // and create the (string) calendar
                    ->createCalendar();


            if (!empty($vcalendarString)) {
                // $email->addStringAttachment($vcalendarString,'ical2.ics','base64','text/calendar; charset=utf-8; method=REQUEST');
                $email->addStringAttachment($vcalendarString, 'ical.ics', 'base64', 'application/ics');

            }

            //$email->msgHTML($this->getBody());
            //$email->addCustomHeader('Content-type',"text/calendar; charset=utf-8; method=REQUEST");

            $email->Ical = $vcalendarString;
            $email->AltBody = $vcalendarString;
            $email->msgHTML("Zeft hena");
            $email->Subject = $this->getSubject();
            $email->AddAddress($this->getTo());

            if (!$email->Send()) {
                throw new \LogicException($email->ErrorInfo);
            }
        } catch (\LogicException $e) {
            $e->getMessage();
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
