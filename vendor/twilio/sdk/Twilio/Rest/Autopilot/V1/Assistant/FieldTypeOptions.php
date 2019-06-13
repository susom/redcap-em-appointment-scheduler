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
abstract class FieldTypeOptions
{
    /**
     * @param string $friendlyName A string to describe the new resource
     * @return CreateFieldTypeOptions Options builder
     */
    public static function create($friendlyName = Values::NONE)
    {
        return new CreateFieldTypeOptions($friendlyName);
    }

    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the resource
     * @return UpdateFieldTypeOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $uniqueName = Values::NONE)
    {
        return new UpdateFieldTypeOptions($friendlyName, $uniqueName);
    }
}

class CreateFieldTypeOptions extends Options
{
    /**
     * @param string $friendlyName A string to describe the new resource
     */
    public function __construct($friendlyName = Values::NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
    }

    /**
     * A descriptive string that you create to describe the new resource. It is not unique and can be up to 255 characters long.
     *
     * @param string $friendlyName A string to describe the new resource
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName)
    {
        $this->options['friendlyName'] = $friendlyName;
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
        return '[Twilio.Autopilot.V1.CreateFieldTypeOptions ' . implode(' ', $options) . ']';
    }
}

class UpdateFieldTypeOptions extends Options
{
    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the resource
     */
    public function __construct($friendlyName = Values::NONE, $uniqueName = Values::NONE)
    {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['uniqueName'] = $uniqueName;
    }

    /**
     * A descriptive string that you create to describe the resource. It is not unique and can be up to 255 characters long.
     *
     * @param string $friendlyName A string to describe the resource
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName)
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * An application-defined string that uniquely identifies the resource. It can be used as an alternative to the `sid` in the URL path to address the resource. The first 64 characters must be unique.
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
        return '[Twilio.Autopilot.V1.UpdateFieldTypeOptions ' . implode(' ', $options) . ']';
    }
}