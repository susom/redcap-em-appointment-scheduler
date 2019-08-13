<?php

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
        $this->setCalendarDate($param['calendarDate']);
        $this->setCalendarStartTime($param['calendarStartTime']);
        $this->setCalendarEndTime($param['calendarEndTime']);
        $this->setCalendarParticipants($param['calendarParticipants']);
    }

    /**
     * Generate header and body of the calendar event
     */
    public function buildCalendarBody(){
        $headers = 'Content-Type:text/calendar; Content-Disposition: inline; charset=utf-8;\r\n';
        $headers .= "Content-Type: text/plain;charset=\"utf-8\"\r\n"; #EDIT: TYPO

        $participants = '';
        foreach ($this->getCalendarParticipants() as $name => $email){
            $participants.= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN".$name.";X-NUM-GUESTS=0:MAILTO:".$email."\r\n";
        }

        $message = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Deathstar-mailer//theforce/NONSGML v1.0//EN\r\nMETHOD:REQUEST\r\nBEGIN:VEVENT\r\nUID:" . md5(uniqid(mt_rand(),
                true)) . "example.com\r\nDTSTAMP:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z\r\nDTSTART:" . $this->getCalendarDate() . "T" . $this->getCalendarStartTime() . "00\r\nDTEND:" . $this->getCalendarDate() . "T" . $this->getCalendarEndTime() . "00\r\nSUMMARY:" . $this->getCalendarSubject() . "\r\nORGANIZER;CN=" . $this->getCalendarOrganizer() . ":mailto:" . $this->getCalendarOrganizerEmail() . "\r\nLOCATION:" . $this->getCalendarLocation() . "\r\nDESCRIPTION:" . str_replace(array(
                "\r",
                "\n"
            ), '', $this->getCalendarDescription()) . "\r\n$participants.END:VEVENT\r\nEND:VCALENDAR\r\n";

        $headers .= $message;
        $this->setHeaders($headers);
        $this->setCalendarBody($message);
    }

    /**
     * send calendar event email
     * @param array $param
     * @return bool
     */
    public function sendCalendarEmail($param){
        $this->prepareCalendarData($param);
        $this->buildCalendarBody();
        return mail($this->getTo(), $this->getCalendarSubject(), $this->getCalendarBody(), $this->getHeaders());
    }
}
