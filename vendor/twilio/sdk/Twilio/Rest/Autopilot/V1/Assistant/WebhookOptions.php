<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Autopilot\V1\Assistant;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class WebhookOptions
{
    /**
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     * @return CreateWebhookOptions Options builder
     */
    public static function create($webhookMethod = Values::NONE)
    {
        return new CreateWebhookOptions($webhookMethod);
    }

    /**
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the resource
     * @param string $events The list of space-separated events that this Webhook
     *                       will subscribe to.
     * @param string $webhookUrl The URL associated with this Webhook.
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     * @return UpdateWebhookOptions Options builder
     */
    public static function update(
        $uniqueName = Values::NONE,
        $events = Values::NONE,
        $webhookUrl = Values::NONE,
        $webhookMethod = Values::NONE
    ) {
        return new UpdateWebhookOptions($uniqueName, $events, $webhookUrl, $webhookMethod);
    }
}

class CreateWebhookOptions extends Options
{
    /**
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     */
    public function __construct($webhookMethod = Values::NONE)
    {
        $this->options['webhookMethod'] = $webhookMethod;
    }

    /**
     * The method to be used when calling the webhook's URL.
     *
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     * @return $this Fluent Builder
     */
    public function setWebhookMethod($webhookMethod)
    {
        $this->options['webhookMethod'] = $webhookMethod;
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
        return '[Twilio.Autopilot.V1.CreateWebhookOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateWebhookOptions extends Options
{
    /**
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the resource
     * @param string $events The list of space-separated events that this Webhook
     *                       will subscribe to.
     * @param string $webhookUrl The URL associated with this Webhook.
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     */
    public function __construct(
        $uniqueName = Values::NONE,
        $events = Values::NONE,
        $webhookUrl = Values::NONE,
        $webhookMethod = Values::NONE
    ) {
        $this->options['uniqueName'] = $uniqueName;
        $this->options['events'] = $events;
        $this->options['webhookUrl'] = $webhookUrl;
        $this->options['webhookMethod'] = $webhookMethod;
    }

    /**
     * An application-defined string that uniquely identifies the new resource. It can be used as an alternative to the `sid` in the URL path to address the resource. This value must be unique and 64 characters or less in length.
     *
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the resource
     * @return $this Fluent Builder
     */
    public function setUniqueName($uniqueName)
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * The list of space-separated events that this Webhook will subscribe to.
     *
     * @param string $events The list of space-separated events that this Webhook
     *                       will subscribe to.
     * @return $this Fluent Builder
     */
    public function setEvents($events)
    {
        $this->options['events'] = $events;
        return $this;
    }

    /**
     * The URL associated with this Webhook.
     *
     * @param string $webhookUrl The URL associated with this Webhook.
     * @return $this Fluent Builder
     */
    public function setWebhookUrl($webhookUrl)
    {
        $this->options['webhookUrl'] = $webhookUrl;
        return $this;
    }

    /**
     * The method to be used when calling the webhook's URL.
     *
     * @param string $webhookMethod The method to be used when calling the
     *                              webhook's URL.
     * @return $this Fluent Builder
     */
    public function setWebhookMethod($webhookMethod)
    {
        $this->options['webhookMethod'] = $webhookMethod;
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
        return '[Twilio.Autopilot.V1.UpdateWebhookOptions ' . implode(' ', $options) . ']';
    }
}