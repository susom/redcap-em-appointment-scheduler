<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Fax\V1\Fax;

use Twilio\Deserialize;
use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 *
 * @property string $sid
 * @property string $accountSid
 * @property string $faxSid
 * @property string $contentType
 * @property \DateTime $dateCreated
 * @property \DateTime $dateUpdated
 * @property string $url
 */
class FaxMediaInstance extends InstanceResource
{
    /**
     * Initialize the FaxMediaInstance
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $faxSid The SID of the fax the FaxMedia resource is associated
     *                       with
     * @param string $sid The unique string that identifies the resource to fetch
     * @return \Twilio\Rest\Fax\V1\Fax\FaxMediaInstance
     */
    public function __construct(Version $version, array $payload, $faxSid, $sid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'sid' => Values::array_get($payload, 'sid'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'faxSid' => Values::array_get($payload, 'fax_sid'),
            'contentType' => Values::array_get($payload, 'content_type'),
            'dateCreated' => Deserialize::dateTime(Values::array_get($payload, 'date_created')),
            'dateUpdated' => Deserialize::dateTime(Values::array_get($payload, 'date_updated')),
            'url' => Values::array_get($payload, 'url'),
        );

        $this->solution = array('faxSid' => $faxSid, 'sid' => $sid ?: $this->properties['sid'],);
    }

    /**
     * Fetch a FaxMediaInstance
     *
     * @return FaxMediaInstance Fetched FaxMediaInstance
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
     * @return \Twilio\Rest\Fax\V1\Fax\FaxMediaContext Context for this
     *                                                 FaxMediaInstance
     */
    protected function proxy()
    {
        if (!$this->context) {
            $this->context = new FaxMediaContext(
                $this->version,
                $this->solution['faxSid'],
                $this->solution['sid']
            );
        }

        return $this->context;
    }

    /**
     * Deletes the FaxMediaInstance
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
        return '[Twilio.Fax.V1.FaxMediaInstance ' . implode(' ', $context) . ']';
    }
}