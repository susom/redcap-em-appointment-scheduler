<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string $accountSid
 * @property string $authorizeRedirectUrl
 * @property string $companyName
 * @property string $deauthorizeCallbackMethod
 * @property string $deauthorizeCallbackUrl
 * @property string $description
 * @property string $friendlyName
 * @property string $homepageUrl
 * @property string $permissions
 * @property string $sid
 * @property string $uri
 */
class ConnectAppInstance extends InstanceResource
{
    /**
     * Initialize the ConnectAppInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $accountSid The SID of the Account that created the resource
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Api\V2010\Account\ConnectAppInstance
     */
    public function __construct(Version $version, array $payload, $accountSid, $sid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'authorizeRedirectUrl' => Values::array_get($payload, 'authorize_redirect_url'),
            'companyName' => Values::array_get($payload, 'company_name'),
            'deauthorizeCallbackMethod' => Values::array_get($payload, 'deauthorize_callback_method'),
            'deauthorizeCallbackUrl' => Values::array_get($payload, 'deauthorize_callback_url'),
            'description' => Values::array_get($payload, 'description'),
            'friendlyName' => Values::array_get($payload, 'friendly_name'),
            'homepageUrl' => Values::array_get($payload, 'homepage_url'),
            'permissions' => Values::array_get($payload, 'permissions'),
            'sid' => Values::array_get($payload, 'sid'),
            'uri' => Values::array_get($payload, 'uri'),
        );

        $this->solution = array('accountSid' => $accountSid, 'sid' => $sid ?: $this->properties['sid'],);
    }

    /**
     * Fetch a ConnectAppInstance
     *
     * @return ConnectAppInstance Fetched ConnectAppInstance
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
     * @return \Twilio\Rest\Api\V2010\Account\ConnectAppContext Context for this
     *                                                          ConnectAppInstance
     */
    protected function proxy()
    {
        if (!$this->context) {
            $this->context = new ConnectAppContext(
                $this->version,
                $this->solution['accountSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Update the ConnectAppInstance
     *
     * @param array|Options $options Optional Arguments
     * @return ConnectAppInstance Updated ConnectAppInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($options = array())
    {
        return $this->proxy()->update($options);
    }

    /**
     * Deletes the ConnectAppInstance
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
        return '[Twilio.Api.V2010.ConnectAppInstance ' . implode(' ', $context) . ']';
    }
}