<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\TrustedComms;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 *
 * @property string $sid
 * @property string $accountSid
 * @property string $from
 * @property string $to
 * @property string $reason
 * @property \DateTime $createdAt
 * @property string $url
 */
class PhoneCallInstance extends InstanceResource
{
    /**
     * Initialize the PhoneCallInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @return \Twilio\Rest\Preview\TrustedComms\PhoneCallInstance
     */
    public function __construct(Version $version, array $payload)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'sid' => Values::array_get($payload, 'sid'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'from' => Values::array_get($payload, 'from'),
            'to' => Values::array_get($payload, 'to'),
            'reason' => Values::array_get($payload, 'reason'),
            'createdAt' => Deserialize::dateTime(Values::array_get($payload, 'created_at')),
            'url' => Values::array_get($payload, 'url'),
        );

        $this->solution = array();
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Preview.TrustedComms.PhoneCallInstance]';
    }
}