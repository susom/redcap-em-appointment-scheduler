<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Values;
use Twilio\Version;

class ItemAssignmentContext extends InstanceContext
{
    /**
     * Initialize the ItemAssignmentContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $bundleSid The unique string that identifies the resource.
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Numbers\V2\RegulatoryCompliance\Bundle\ItemAssignmentContext
     */
    public function __construct(Version $version, $bundleSid, $sid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('bundleSid' => $bundleSid, 'sid' => $sid,);

        $this->uri = '/RegulatoryCompliance/Bundles/' . \rawurlencode($bundleSid) . '/ItemAssignments/' . \rawurlencode($sid) . '';
    }

    /**
     * Fetch a ItemAssignmentInstance
     *
     * @return ItemAssignmentInstance Fetched ItemAssignmentInstance
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

        return new ItemAssignmentInstance(
            $this->version,
            $payload,
            $this->solution['bundleSid'],
            $this->solution['sid']
        );
    }

    /**
     * Deletes the ItemAssignmentInstance
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
        return '[Twilio.Numbers.V2.ItemAssignmentContext ' . \implode(' ', $context) . ']';
    }
}