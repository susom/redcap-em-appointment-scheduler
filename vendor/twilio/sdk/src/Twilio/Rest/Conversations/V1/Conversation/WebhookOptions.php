<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Conversations\V1\Conversation;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class WebhookOptions
{
    /**
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     * @param int $configurationReplayAfter The message index for which and it's
     *                                      successors the webhook will be replayed.
     * @return CreateWebhookOptions Options builder
     */
    public static function create(
        $configurationUrl = Values::NONE,
        $configurationMethod = Values::NONE,
        $configurationFilters = Values::NONE,
        $configurationTriggers = Values::NONE,
        $configurationFlowSid = Values::NONE,
        $configurationReplayAfter = Values::NONE
    ) {
        return new CreateWebhookOptions($configurationUrl, $configurationMethod, $configurationFilters,
            $configurationTriggers, $configurationFlowSid, $configurationReplayAfter);
    }

    /**
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     * @return UpdateWebhookOptions Options builder
     */
    public static function update(
        $configurationUrl = Values::NONE,
        $configurationMethod = Values::NONE,
        $configurationFilters = Values::NONE,
        $configurationTriggers = Values::NONE,
        $configurationFlowSid = Values::NONE
    ) {
        return new UpdateWebhookOptions($configurationUrl, $configurationMethod, $configurationFilters,
            $configurationTriggers, $configurationFlowSid);
    }
}

class CreateWebhookOptions extends Options
{
    /**
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     * @param int $configurationReplayAfter The message index for which and it's
     *                                      successors the webhook will be replayed.
     */
    public function __construct(
        $configurationUrl = Values::NONE,
        $configurationMethod = Values::NONE,
        $configurationFilters = Values::NONE,
        $configurationTriggers = Values::NONE,
        $configurationFlowSid = Values::NONE,
        $configurationReplayAfter = Values::NONE
    ) {
        $this->options['configurationUrl'] = $configurationUrl;
        $this->options['configurationMethod'] = $configurationMethod;
        $this->options['configurationFilters'] = $configurationFilters;
        $this->options['configurationTriggers'] = $configurationTriggers;
        $this->options['configurationFlowSid'] = $configurationFlowSid;
        $this->options['configurationReplayAfter'] = $configurationReplayAfter;
    }

    /**
     * The absolute url the webhook request should be sent to.
     *
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @return $this Fluent Builder
     */
    public function setConfigurationUrl($configurationUrl)
    {
        $this->options['configurationUrl'] = $configurationUrl;
        return $this;
    }

    /**
     * The HTTP method to be used when sending a webhook request.
     *
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @return $this Fluent Builder
     */
    public function setConfigurationMethod($configurationMethod)
    {
        $this->options['configurationMethod'] = $configurationMethod;
        return $this;
    }

    /**
     * The list of events, firing webhook event for this Conversation.
     *
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @return $this Fluent Builder
     */
    public function setConfigurationFilters($configurationFilters)
    {
        $this->options['configurationFilters'] = $configurationFilters;
        return $this;
    }

    /**
     * The list of keywords, firing webhook event for this Conversation.
     *
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @return $this Fluent Builder
     */
    public function setConfigurationTriggers($configurationTriggers)
    {
        $this->options['configurationTriggers'] = $configurationTriggers;
        return $this;
    }

    /**
     * The studio flow sid, where the webhook should be sent to.
     *
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     * @return $this Fluent Builder
     */
    public function setConfigurationFlowSid($configurationFlowSid)
    {
        $this->options['configurationFlowSid'] = $configurationFlowSid;
        return $this;
    }

    /**
     * The message index for which and it's successors the webhook will be replayed. Not set by default
     *
     * @param int $configurationReplayAfter The message index for which and it's
     *                                      successors the webhook will be replayed.
     * @return $this Fluent Builder
     */
    public function setConfigurationReplayAfter($configurationReplayAfter)
    {
        $this->options['configurationReplayAfter'] = $configurationReplayAfter;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Conversations.V1.CreateWebhookOptions ' . \implode(' ', $options) . ']';
    }
}

class UpdateWebhookOptions extends Options
{
    /**
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     */
    public function __construct(
        $configurationUrl = Values::NONE,
        $configurationMethod = Values::NONE,
        $configurationFilters = Values::NONE,
        $configurationTriggers = Values::NONE,
        $configurationFlowSid = Values::NONE
    ) {
        $this->options['configurationUrl'] = $configurationUrl;
        $this->options['configurationMethod'] = $configurationMethod;
        $this->options['configurationFilters'] = $configurationFilters;
        $this->options['configurationTriggers'] = $configurationTriggers;
        $this->options['configurationFlowSid'] = $configurationFlowSid;
    }

    /**
     * The absolute url the webhook request should be sent to.
     *
     * @param string $configurationUrl The absolute url the webhook request should
     *                                 be sent to.
     * @return $this Fluent Builder
     */
    public function setConfigurationUrl($configurationUrl)
    {
        $this->options['configurationUrl'] = $configurationUrl;
        return $this;
    }

    /**
     * The HTTP method to be used when sending a webhook request.
     *
     * @param string $configurationMethod The HTTP method to be used when sending a
     *                                    webhook request.
     * @return $this Fluent Builder
     */
    public function setConfigurationMethod($configurationMethod)
    {
        $this->options['configurationMethod'] = $configurationMethod;
        return $this;
    }

    /**
     * The list of events, firing webhook event for this Conversation.
     *
     * @param string $configurationFilters The list of events, firing webhook event
     *                                     for this Conversation.
     * @return $this Fluent Builder
     */
    public function setConfigurationFilters($configurationFilters)
    {
        $this->options['configurationFilters'] = $configurationFilters;
        return $this;
    }

    /**
     * The list of keywords, firing webhook event for this Conversation.
     *
     * @param string $configurationTriggers The list of keywords, firing webhook
     *                                      event for this Conversation.
     * @return $this Fluent Builder
     */
    public function setConfigurationTriggers($configurationTriggers)
    {
        $this->options['configurationTriggers'] = $configurationTriggers;
        return $this;
    }

    /**
     * The studio flow sid, where the webhook should be sent to.
     *
     * @param string $configurationFlowSid The studio flow sid, where the webhook
     *                                     should be sent to.
     * @return $this Fluent Builder
     */
    public function setConfigurationFlowSid($configurationFlowSid)
    {
        $this->options['configurationFlowSid'] = $configurationFlowSid;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString()
    {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Conversations.V1.UpdateWebhookOptions ' . \implode(' ', $options) . ']';
    }
}