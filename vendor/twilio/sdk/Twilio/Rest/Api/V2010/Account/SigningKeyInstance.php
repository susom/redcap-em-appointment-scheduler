<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $sid
 * @property string $friendlyName
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 */
class SigningKeyInstance extends InstanceResource
{
    /**
     * Initialize the SigningKeyInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid A 34 character string that uniquely identifies
     *                           this resource.
     * @param string $sid The sid
     * @return \Twilio\Rest\Api\V2010\Account\SigningKeyInstance
     */
    public function __construct(Version $version, array $payload, $accountSid, $sid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'sid' => Values::array_get($payload, 'sid'),
            'friendlyName' => Values::array_get($payload, 'friendly_name'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
        );

        $this->solution = array('accountSid' => $accountSid, 'sid' => $sid ?: $this->properties['sid'],);
    }

    /**
     * Fetch a SigningKeyInstance
     *
     * @return SigningKeyInstance Fetched SigningKeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch()
    {
        return $this->proxy()->fetch();
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return \Twilio\Rest\Api\V2010\Account\SigningKeyContext Context for this
     *                                                          SigningKeyInstance
     */
    protected function proxy()
    {
        if (!$this->context) {
            $this->context = new SigningKeyContext(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Update the SigningKeyInstance
     *
     * @param array|Options $options Optional Arguments
     * @return SigningKeyInstance Updated SigningKeyInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array())
    {
        return $this->proxy()->update($options);
    }

    /**
     * Deletes the SigningKeyInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete()
    {
        return $this->proxy()->delete();
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
        $context = array();
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Api.V2010.SigningKeyInstance ' . implode(' ', $context) . ']';
    }
}