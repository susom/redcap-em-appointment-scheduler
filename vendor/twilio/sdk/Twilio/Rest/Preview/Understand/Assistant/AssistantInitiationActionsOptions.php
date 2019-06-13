<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\Understand\Assistant;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 */
abstract class AssistantInitiationActionsOptions
{
    /**
     * @param array $initiationActions The initiation_actions
     * @return UpdateAssistantInitiationActionsOptions Options builder
     */
    public static function update($initiationActions = Values::NONE)
    {
        return new UpdateAssistantInitiationActionsOptions($initiationActions);
    }
}

class UpdateAssistantInitiationActionsOptions extends Options
{
    /**
     * @param array $initiationActions The initiation_actions
     */
    public function __construct($initiationActions = Values::NONE)
    {
        $this->options['initiationActions'] = $initiationActions;
    }

    /**
     * The initiation_actions
     *
     * @param array $initiationActions The initiation_actions
     * @return $this Fluent Builder
     */
    public function setInitiationActions($initiationActions)
    {
        $this->options['initiationActions'] = $initiationActions;
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
        return '[Twilio.Preview.Understand.UpdateAssistantInitiationActionsOptions ' . implode(' ', $options) . ']';
    }
}