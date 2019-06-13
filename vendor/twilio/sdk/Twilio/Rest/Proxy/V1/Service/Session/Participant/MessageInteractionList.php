<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Proxy\V1\Service\Session\Participant;

use Twilio\Exceptions\TwilioException;
use Twilio\ListResource;
use Twilio\Options;
use Twilio\Serialize;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
class MessageInteractionList extends ListResource
{
    /**
     * Construct the MessageInteractionList
     *
     * @param Version $version Version that contains the resource
     * @param string $serviceSid The SID of the resource's parent Service
     * @param string $sessionSid The SID of the resource's parent Session
     * @param string $participantSid The SID of the Participant resource
     * @return \Twilio\Rest\Proxy\V1\Service\Session\Participant\MessageInteractionList
     */
    public function __construct(Version $version, $serviceSid, $sessionSid, $participantSid)
    {
        parent::__construct($version);

        // Path Solution
        $this->solution = array(
            'serviceSid' => $serviceSid,
            'sessionSid' => $sessionSid,
            'participantSid' => $participantSid,
        );

        $this->uri = '/Services/' . rawurlencode($serviceSid) . '/Sessions/' . rawurlencode($sessionSid) . '/Participants/' . rawurlencode($participantSid) . '/MessageInteractions';
    }

    /**
     * Create a new MessageInteractionInstance
     *
     * @param array|Options $options Optional Arguments
     * @return MessageInteractionInstance Newly created MessageInteractionInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function create($options = array())
    {
        $options = new Values($options);

        $data = Values::of(array(
            'Body' => $options['body'],
            'MediaUrl' => Serialize::map($options['mediaUrl'], function ($e) {
                return $e;
            }),
        ));

        $payload = $this->version->create(
            'POST',
            $this->uri,
            array(),
            $data
        );

        return new MessageInteractionInstance(
            $this->version,
            $payload,
            $this->solution['serviceSid'],
            $this->solution['sessionSid'],
            $this->solution['participantSid']
        );
    }

    /**
     * Reads MessageInteractionInstance records from the API as a list.
     * Unlike stream(), this operation is eager and will load `limit` records into
     * memory before returning.
     *
     * @param int $limit Upper limit for the number of records to return. read()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, read()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return MessageInteractionInstance[] Array of results
     */
    public function read($limit = null, $pageSize = null)
    {
        return iterator_to_array($this->stream($limit, $pageSize), false);
    }

    /**
     * Streams MessageInteractionInstance records from the API as a generator
     * stream.
     * This operation lazily loads records as efficiently as possible until the
     * limit
     * is reached.
     * The results are returned as a generator, so this operation is memory
     * efficient.
     *
     * @param int $limit Upper limit for the number of records to return. stream()
     *                   guarantees to never return more than limit.  Default is no
     *                   limit
     * @param mixed $pageSize Number of records to fetch per request, when not set
     *                        will use the default value of 50 records.  If no
     *                        page_size is defined but a limit is defined, stream()
     *                        will attempt to read the limit with the most
     *                        efficient page size, i.e. min(limit, 1000)
     * @return \Twilio\Stream stream of results
     */
    public function stream($limit = null, $pageSize = null)
    {
        $limits = $this->version->readLimits($limit, $pageSize);

        $page = $this->page($limits['pageSize']);

        return $this->version->stream($page, $limits['limit'], $limits['pageLimit']);
    }

    /**
     * Retrieve a single page of MessageInteractionInstance records from the API.
     * Request is executed immediately
     *
     * @param mixed $pageSize Number of records to return, defaults to 50
     * @param string $pageToken PageToken provided by the API
     * @param mixed $pageNumber Page Number, this value is simply for client state
     * @return \Twilio\Page Page of MessageInteractionInstance
     */
    public function page($pageSize = Values::NONE, $pageToken = Values::NONE, $pageNumber = Values::NONE)
    {
        $params = Values::of(array(
            'PageToken' => $pageToken,
            'Page' => $pageNumber,
            'PageSize' => $pageSize,
        ));

        $response = $this->version->page(
            'GET',
            $this->uri,
            $params
        );

        return new MessageInteractionPage($this->version, $response, $this->solution);
    }

    /**
     * Retrieve a specific page of MessageInteractionInstance records from the API.
     * Request is executed immediately
     *
     * @param string $targetUrl API-generated URL for the requested results page
     * @return \Twilio\Page Page of MessageInteractionInstance
     */
    public function getPage($targetUrl)
    {
        $response = $this->version->getDomain()->getClient()->request(
            'GET',
            $targetUrl
        );

        return new MessageInteractionPage($this->version, $response, $this->solution);
    }

    /**
     * Constructs a MessageInteractionContext
     *
     * @param string $sid The unique string that identifies the resource
     * @return \Twilio\Rest\Proxy\V1\Service\Session\Participant\MessageInteractionContext
     */
    public function getContext($sid)
    {
        return new MessageInteractionContext(
            $this->version,
            $this->solution['serviceSid'],
            $this->solution['sessionSid'],
            $this->solution['participantSid'],
            $sid
        );
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        return '[Twilio.Proxy.V1.MessageInteractionList]';
    }
}