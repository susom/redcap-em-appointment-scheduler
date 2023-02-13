<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\TrustedComms\Business\Insights;

use Twilio\ListResource;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
class SuccessRateList extends ListResource
{
    /**
     * Construct the SuccessRateList
     *
     * @param Version $version Version that contains the resource
     * @param string $businessSid A string that uniquely identifies this Business.
     * @return \Twilio\Rest\Preview\TrustedComms\Business\Insights\SuccessRateList
     */
    public function __construct(Version $version, $businessSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('businessSid' => $businessSid,);
    }

    /**
     * Constructs a SuccessRateContext
     *
     * @return \Twilio\Rest\Preview\TrustedComms\Business\Insights\SuccessRateContext
     */
    public function getContext()
    {
        return new SuccessRateContext($this->version, $this->solution['businessSid']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Preview.TrustedComms.SuccessRateList]';
    }
}