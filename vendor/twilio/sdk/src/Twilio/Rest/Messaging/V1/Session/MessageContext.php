<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Messaging\V1\Session;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class MessageContext extends InstanceContext
{
    /**
     * Initialize the MessageContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $sessionSid The SID of the Session with the message to fetch
     * @param string $sid The SID that identifies the resource to fetch
     * @return \Twilio\Rest\Messaging\V1\Session\MessageContext
     */
    public function __construct(Version $version, $sessionSid, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('sessionSid' => $sessionSid, 'sid' => $sid,);

        $this->uri = '/Sessions/' . \rawurlencode($sessionSid) . '/Messages/' . \rawurlencode($sid) . '';
    }

    /**
     * Fetch a MessageInstance
     *
     * @return MessageInstance Fetched MessageInstance
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

        return new MessageInstance(
            $this->version,
            $payload,
            $this->solution['sessionSid'],
            $this->solution['sid']
        );
    }

    /**
     * Update the MessageInstance
     *
     * @param array|Options $options Optional Arguments
     * @return MessageInstance Updated MessageInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'Author' => $options['author'],
            'Attributes' => $options['attributes'],
            'DateCreated' => Serialize::iso8601DateTime($options['dateCreated']),
            'DateUpdated' => Serialize::iso8601DateTime($options['dateUpdated']),
            'Body' => $options['body'],
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new MessageInstance(
            $this->version,
            $payload,
            $this->solution['sessionSid'],
            $this->solution['sid']
        );
    }

    /**
     * Deletes the MessageInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete()
    {
        return $this->version->delete('delete', $this->uri);
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
        return '[Twilio.Messaging.V1.MessageContext ' . \implode(' ', $context) . ']';
    }
}