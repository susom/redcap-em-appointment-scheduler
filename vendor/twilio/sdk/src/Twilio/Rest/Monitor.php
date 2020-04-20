<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest;

use Twilio\Domain;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Monitor\V1;

/**
 * @property \Twilio\Rest\Monitor\V1 $v1
 * @property \Twilio\Rest\Monitor\V1\AlertList $alerts
 * @property \Twilio\Rest\Monitor\V1\EventList $events
 * @method \Twilio\Rest\Monitor\V1\AlertContext alerts(string $sid)
 * @method \Twilio\Rest\Monitor\V1\EventContext events(string $sid)
 */
class Monitor extends Domain
{
    protected $_v1 = null;

    /**
     * Construct the Monitor Domain
     *
     * @param \Twilio\Rest\Client $client Twilio\Rest\Client to communicate with
     *                                    Twilio
     * @return \Twilio\Rest\Monitor Domain for Monitor
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->baseUrl = 'https://monitor.twilio.com';
    }

    /**
     * @return \Twilio\Rest\Monitor\V1 Version v1 of monitor
     */
    protected function getV1()
    {
        if (!$this->_v1) {
            $this->_v1 = new V1($this);
        }
        return $this->_v1;
    }

    /**
     * Magic getter to lazy load version
     *
     * @param string $name Version to return
     * @return \Twilio\Version The requested version
     * @throws TwilioException For unknown versions
     */
    public function __get($name)
    {
        $method = 'get' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return $this->$method();
        }

        throw new TwilioException('Unknown version ' . $name);
    }

    /**
     * Magic caller to get resource contexts
     *
     * @param string $name Resource to return
     * @param array $arguments Context parameters
     * @return \Twilio\InstanceContext The requested resource context
     * @throws TwilioException For unknown resource
     */
    public function __call($name, $arguments)
    {
        $method = 'context' . \ucfirst($name);
        if (\method_exists($this, $method)) {
            return \call_user_func_array(array($this, $method), $arguments);
        }

        throw new TwilioException('Unknown context ' . $name);
    }

    /**
     * @return \Twilio\Rest\Monitor\V1\AlertList
     */
    protected function getAlerts()
    {
        return $this->v1->alerts;
    }

    /**
     * @param string $sid The SID that identifies the resource to fetch
     * @return \Twilio\Rest\Monitor\V1\AlertContext
     */
    protected function contextAlerts($sid)
    {
        return $this->v1->alerts($sid);
    }

    /**
     * @return \Twilio\Rest\Monitor\V1\EventList
     */
    protected function getEvents()
    {
        return $this->v1->events;
    }

    /**
     * @param string $sid The SID that identifies the resource to fetch
     * @return \Twilio\Rest\Monitor\V1\EventContext
     */
    protected function contextEvents($sid)
    {
        return $this->v1->events($sid);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Monitor]';
    }
}