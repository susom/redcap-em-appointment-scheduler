<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property string $sid
 * @property string $accountSid
 * @property string $friendlyName
 * @property int $codeLength
 * @property bool $lookupEnabled
 * @property bool $psd2Enabled
 * @property bool $skipSmsToLandlines
 * @property bool $dtmfInputRequired
 * @property string $ttsName
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $url
 * @property array $links
 */
class ServiceInstance extends InstanceResource
{
    protected $_verifications = null;
    protected $_verificationChecks = null;
    protected $_rateLimits = null;

    /**
     * Initialize the ServiceInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Verify\V2\ServiceInstance
     */
    public function __construct(Version $version, array $payload, $sid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'sid' => Values::array_get($payload, 'sid'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'friendlyName' => Values::array_get($payload, 'friendly_name'),
            'codeLength' => Values::array_get($payload, 'code_length'),
            'lookupEnabled' => Values::array_get($payload, 'lookup_enabled'),
            'psd2Enabled' => Values::array_get($payload, 'psd2_enabled'),
            'skipSmsToLandlines' => Values::array_get($payload, 'skip_sms_to_landlines'),
            'dtmfInputRequired' => Values::array_get($payload, 'dtmf_input_required'),
            'ttsName' => Values::array_get($payload, 'tts_name'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'url' => Values::array_get($payload, 'url'),
            'links' => Values::array_get($payload, 'links'),
        );

        $this->solution = array('sid' => $sid ?: $this->properties['sid'],);
    }

    /**
     * Fetch a ServiceInstance
     *
     * @return ServiceInstance Fetched ServiceInstance
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
     * @return \Twilio\Rest\Verify\V2\ServiceContext Context for this
     *                                               ServiceInstance
     */
    protected function proxy()
    {
        if (!$this->context) {
            $this->context = new ServiceContext($this->version, $this->solution['sid']);
        }

        return $this->context;
    }

    /**
     * Deletes the ServiceInstance
     *
     * @return boolean True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete()
    {
        return $this->proxy()->delete();
    }

    /**
     * Update the ServiceInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ServiceInstance Updated ServiceInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array())
    {
        return $this->proxy()->update($options);
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
        return '[Twilio.Verify.V2.ServiceInstance ' . implode(' ', $context) . ']';
    }

    /**
     * Access the verifications
     *
     * @return \Twilio\Rest\Verify\V2\Service\VerificationList
     */
    protected function getVerifications()
    {
        return $this->proxy()->verifications;
    }

    /**
     * Access the verificationChecks
     *
     * @return \Twilio\Rest\Verify\V2\Service\VerificationCheckList
     */
    protected function getVerificationChecks()
    {
        return $this->proxy()->verificationChecks;
    }

    /**
     * Access the rateLimits
     *
     * @return \Twilio\Rest\Verify\V2\Service\RateLimitList
     */
    protected function getRateLimits()
    {
        return $this->proxy()->rateLimits;
    }
}