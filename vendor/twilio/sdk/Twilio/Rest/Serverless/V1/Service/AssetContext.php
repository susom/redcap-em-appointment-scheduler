<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Serverless\V1\Service;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Rest\Serverless\V1\Service\Asset\AssetVersionList;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 *
 * @property \Twilio\Rest\Serverless\V1\Service\Asset\AssetVersionList $assetVersions
 * @method \Twilio\Rest\Serverless\V1\Service\Asset\AssetVersionContext assetVersions(string $sid)
 */
class AssetContext extends InstanceContext
{
    protected $_assetVersions = null;

    /**
     * Initialize the AssetContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $serviceSid Service Sid.
     * @param string $sid Asset Sid.
     * @return \Twilio\Rest\Serverless\V1\Service\AssetContext
     */
    public function __construct(Version $version, $serviceSid, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('serviceSid' => $serviceSid, 'sid' => $sid,);

        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Assets/' . rawurlencode($sid) . '';
    }

    /**
     * Fetch a AssetInstance
     *
     * @return AssetInstance Fetched AssetInstance
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

        return new AssetInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sid']
        );
    }

    /**
     * Update the AssetInstance
     *
     * @param string $friendlyName A human-readable description of this Asset.
     * @return AssetInstance Updated AssetInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($friendlyName)
    {
        $data = Values::of(array('FriendlyName' => $friendlyName,));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new AssetInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sid']
        );
    }

    /**
     * Magic getter to lazy load subresources
     *
     * @param string $name Subresource to return
     * @return \Twilio\ListResource The requested subresource
     * @throws TwilioException For unknown subresources
     */
    public function __get($name)
    {
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown subresource ' . $name);
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
        $property = $this->$name;
        if (method_exists($property, 'getContext')) {
            return call_user_func_array(array($property, 'getContext'), $arguments);
        }

        throw new TwilioException('Resource does not have a context');
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
        return '[Twilio.Serverless.V1.AssetContext ' . implode(' ', $context) . ']';
    }

    /**
     * Access the assetVersions
     *
     * @return \Twilio\Rest\Serverless\V1\Service\Asset\AssetVersionList
     */
    protected function getAssetVersions()
    {
        if (!$this->_assetVersions) {
            $this->_assetVersions = new AssetVersionList(
                $this->version,
                $this->solution['serviceSid'],
                $this->solution['sid']
            );
        }

        return $this->_assetVersions;
    }
}