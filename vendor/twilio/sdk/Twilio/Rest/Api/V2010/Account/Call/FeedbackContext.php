<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account\Call;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

class FeedbackContext extends InstanceContext
{
    /**
     * Initialize the FeedbackContext
     *
     * @param \Twilio\Version $version Version that contains the resource
     * @param string $accountSid The unique sid that identifies this account
     * @param string $callSid The call sid that uniquely identifies the call
     * @return \Twilio\Rest\Api\V2010\Account\Call\FeedbackContext
     */
    public function __construct(Version $version, $accountSid, $callSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array('accountSid' => $accountSid, 'callSid' => $callSid,);

        $this->uri = '/Accounts/' . rawurlencode($accountSid) . '/Calls/' . rawurlencode($callSid) . '/Feedback.json';
    }

    /**
     * Create a new FeedbackInstance
     *
     * @param int $qualityScore The call quality expressed as an integer from 1 to 5
     * @param array|Options $options Optional Arguments
     * @return FeedbackInstance Newly created FeedbackInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create($qualityScore, $options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'QualityScore' => $qualityScore,
            'Issue' => Serialize::map($options['issue'], function ($e) {
                return $e;
            }),
        ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new FeedbackInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['callSid']
        );
    }

    /**
     * Fetch a FeedbackInstance
     *
     * @return FeedbackInstance Fetched FeedbackInstance
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

        return new FeedbackInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['callSid']
        );
    }

    /**
     * Update the FeedbackInstance
     *
     * @param int $qualityScore The call quality expressed as an integer from 1 to 5
     * @param array|Options $options Optional Arguments
     * @return FeedbackInstance Updated FeedbackInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update($qualityScore, $options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'QualityScore' => $qualityScore,
            'Issue' => Serialize::map($options['issue'], function ($e) {
                return $e;
            }),
        ));

        $payload = $this->version->update(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new FeedbackInstance(
            $this->version,
            $payload,
            $this->solution['accountSid'],
            $this->solution['callSid']
        );
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
        return '[Twilio.Api.V2010.FeedbackContext ' . implode(' ', $context) . ']';
    }
}