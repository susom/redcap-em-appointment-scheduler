<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\TwiML\Voice;

use Twilio\TwiML\TwiML;

class Conference extends TwiML
{
    /**
     * Conference constructor.
     *
     * @param string $name Conference name
     * @param array $attributes Optional attributes
     */
    public function __construct($name, $attributes = array())
    {
        parent::__construct('Conference', $name, $attributes);
    }

    /**
     * Add Muted attribute.
     *
     * @param bool $muted Join the conference muted
     * @return static $this.
     */
    public function setMuted($muted)
    {
        return $this->setAttribute('muted', $muted);
    }

    /**
     * Add Beep attribute.
     *
     * @param string $beep Play beep when joining
     * @return static $this.
     */
    public function setBeep($beep)
    {
        return $this->setAttribute('beep', $beep);
    }

    /**
     * Add StartConferenceOnEnter attribute.
     *
     * @param bool $startConferenceOnEnter Start the conference on enter
     * @return static $this.
     */
    public function setStartConferenceOnEnter($startConferenceOnEnter)
    {
        return $this->setAttribute('startConferenceOnEnter', $startConferenceOnEnter);
    }

    /**
     * Add EndConferenceOnExit attribute.
     *
     * @param bool $endConferenceOnExit End the conferenceon exit
     * @return static $this.
     */
    public function setEndConferenceOnExit($endConferenceOnExit)
    {
        return $this->setAttribute('endConferenceOnExit', $endConferenceOnExit);
    }

    /**
     * Add WaitUrl attribute.
     *
     * @param string $waitUrl Wait URL
     * @return static $this.
     */
    public function setWaitUrl($waitUrl)
    {
        return $this->setAttribute('waitUrl', $waitUrl);
    }

    /**
     * Add WaitMethod attribute.
     *
     * @param string $waitMethod Wait URL method
     * @return static $this.
     */
    public function setWaitMethod($waitMethod)
    {
        return $this->setAttribute('waitMethod', $waitMethod);
    }

    /**
     * Add MaxParticipants attribute.
     *
     * @param int $maxParticipants Maximum number of participants
     * @return static $this.
     */
    public function setMaxParticipants($maxParticipants)
    {
        return $this->setAttribute('maxParticipants', $maxParticipants);
    }

    /**
     * Add Record attribute.
     *
     * @param string $record Record the conference
     * @return static $this.
     */
    public function setRecord($record)
    {
        return $this->setAttribute('record', $record);
    }

    /**
     * Add Region attribute.
     *
     * @param string $region Conference region
     * @return static $this.
     */
    public function setRegion($region)
    {
        return $this->setAttribute('region', $region);
    }

    /**
     * Add Coach attribute.
     *
     * @param string $coach Call coach
     * @return static $this.
     */
    public function setCoach($coach)
    {
        return $this->setAttribute('coach', $coach);
    }

    /**
     * Add Trim attribute.
     *
     * @param string $trim Trim the conference recording
     * @return static $this.
     */
    public function setTrim($trim)
    {
        return $this->setAttribute('trim', $trim);
    }

    /**
     * Add StatusCallbackEvent attribute.
     *
     * @param string $statusCallbackEvent Events to call status callback URL
     * @return static $this.
     */
    public function setStatusCallbackEvent($statusCallbackEvent)
    {
        return $this->setAttribute('statusCallbackEvent', $statusCallbackEvent);
    }

    /**
     * Add StatusCallback attribute.
     *
     * @param string $statusCallback Status callback URL
     * @return static $this.
     */
    public function setStatusCallback($statusCallback)
    {
        return $this->setAttribute('statusCallback', $statusCallback);
    }

    /**
     * Add StatusCallbackMethod attribute.
     *
     * @param string $statusCallbackMethod Status callback URL method
     * @return static $this.
     */
    public function setStatusCallbackMethod($statusCallbackMethod)
    {
        return $this->setAttribute('statusCallbackMethod', $statusCallbackMethod);
    }

    /**
     * Add RecordingStatusCallback attribute.
     *
     * @param string $recordingStatusCallback Recording status callback URL
     * @return static $this.
     */
    public function setRecordingStatusCallback($recordingStatusCallback)
    {
        return $this->setAttribute('recordingStatusCallback', $recordingStatusCallback);
    }

    /**
     * Add RecordingStatusCallbackMethod attribute.
     *
     * @param string $recordingStatusCallbackMethod Recording status callback URL
     *                                              method
     * @return static $this.
     */
    public function setRecordingStatusCallbackMethod($recordingStatusCallbackMethod)
    {
        return $this->setAttribute('recordingStatusCallbackMethod', $recordingStatusCallbackMethod);
    }

    /**
     * Add RecordingStatusCallbackEvent attribute.
     *
     * @param string $recordingStatusCallbackEvent Recording status callback events
     * @return static $this.
     */
    public function setRecordingStatusCallbackEvent($recordingStatusCallbackEvent)
    {
        return $this->setAttribute('recordingStatusCallbackEvent', $recordingStatusCallbackEvent);
    }

    /**
     * Add EventCallbackUrl attribute.
     *
     * @param string $eventCallbackUrl Event callback URL
     * @return static $this.
     */
    public function setEventCallbackUrl($eventCallbackUrl)
    {
        return $this->setAttribute('eventCallbackUrl', $eventCallbackUrl);
    }
}