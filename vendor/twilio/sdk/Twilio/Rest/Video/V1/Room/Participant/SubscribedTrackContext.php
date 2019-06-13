<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room\Participant;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

class SubscribedTrackContext extends InstanceContext
{
    /**
     * Initialize the SubscribedTrackContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $roomSid Unique Room identifier where this Track is subscribed.
     * @param string $participantSid Unique Participant identifier that subscribes
     *                               to this Track.
     * @param string $sid A 34 character string that uniquely identifies this
     *                    resource.
     * @return \Twilio\Rest\Video\V1\Room\Participant\SubscribedTrackContext
     */
    public function __construct(Version $version, $roomSid, $participantSid, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('roomSid' => $roomSid, 'participantSid' => $participantSid, 'sid' => $sid,);

        $this->uri = '/Rooms/' . rawurlencode($roomSid) . '/Participants/' . rawurlencode($participantSid) . '/SubscribedTracks/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a SubscribedTrackInstance
     *
     * @return SubscribedTrackInstance Fetched SubscribedTrackInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch()
    {
        $params = Values::of(array());

        $payload = $this->version->fetch(
            'GET',
            $this->uri,
            $params
        );

        return new SubscribedTrackInstance(
            $this->version,
            $payload,
            $this->solution['roomSid'],
            $this->solution['participantSid'],
            $this->solution['sid']
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Video.V1.SubscribedTrackContext ' . implode(' ', $context) . ']';
    }
}