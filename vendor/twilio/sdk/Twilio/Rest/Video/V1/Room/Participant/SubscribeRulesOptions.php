<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room\Participant;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class SubscribeRulesOptions
{
    /**
     * @param array $rules A JSON-encoded array of Subscribe Rules.
     * @return UpdateSubscribeRulesOptions Options builder
     */
    public static function update($rules = Values::NONE)
    {
        return new UpdateSubscribeRulesOptions($rules);
    }
}

class UpdateSubscribeRulesOptions extends Options
{
    /**
     * @param array $rules A JSON-encoded array of Subscribe Rules.
     */
    public function __construct($rules = Values::NONE)
    {
        $this->options['rules'] = $rules;
    }

    /**
     * A JSON-encoded array of Subscribe Rules. See the [Specifying Subscribe Rules](https://www.twilio.com/docs/video/api/track-subscriptions#specifying-sr) section for further information.
     *
     * @param array $rules A JSON-encoded array of Subscribe Rules.
     * @return $this Fluent Builder
     */
    public function setRules($rules)
    {
        $this->options['rules'] = $rules;
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
        return '[Twilio.Video.V1.UpdateSubscribeRulesOptions ' . implode(' ', $options) . ']';
    }
}