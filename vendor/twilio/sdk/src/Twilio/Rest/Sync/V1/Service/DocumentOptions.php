<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Sync\V1\Service;

use Twilio\Options;
use Twilio\Values;

/**
 * PLEASE NOTE that this class contains beta products that are subject to change. Use them with caution.
 */
abstract class DocumentOptions
{
    /**
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the Sync Document
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @param int $ttl How long, in seconds, before the Sync Document expires and
     *                 is deleted
     * @return CreateDocumentOptions Options builder
     */
    public static function create($uniqueName = Values::NONE, $data = Values::NONE, $ttl = Values::NONE)
    {
        return new CreateDocumentOptions($uniqueName, $data, $ttl);
    }

    /**
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @param int $ttl How long, in seconds, before the Document resource expires
     *                 and is deleted
     * @return UpdateDocumentOptions Options builder
     */
    public static function update($data = Values::NONE, $ttl = Values::NONE)
    {
        return new UpdateDocumentOptions($data, $ttl);
    }
}

class CreateDocumentOptions extends Options
{
    /**
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the Sync Document
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @param int $ttl How long, in seconds, before the Sync Document expires and
     *                 is deleted
     */
    public function __construct($uniqueName = Values::NONE, $data = Values::NONE, $ttl = Values::NONE)
    {
        $this->options['uniqueName'] = $uniqueName;
        $this->options['data'] = $data;
        $this->options['ttl'] = $ttl;
    }

    /**
     * An application-defined string that uniquely identifies the Sync Document
     *
     * @param string $uniqueName An application-defined string that uniquely
     *                           identifies the Sync Document
     * @return $this Fluent Builder
     */
    public function setUniqueName($uniqueName)
    {
        $this->options['uniqueName'] = $uniqueName;
        return $this;
    }

    /**
     * A JSON string that represents an arbitrary, schema-less object that the Sync Document stores. Can be up to 16KB in length.
     *
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @return $this Fluent Builder
     */
    public function setData($data)
    {
        $this->options['data'] = $data;
        return $this;
    }

    /**
     * How long, in seconds, before the Sync Document expires and is deleted (the Sync Document's time-to-live). Can be an integer from 0 to 31,536,000 (1 year). The default value is `0`, which means the Sync Document does not expire. The Sync Document will be deleted automatically after it expires, but there can be a delay between the expiration time and the resources's deletion.
     *
     * @param int $ttl How long, in seconds, before the Sync Document expires and
     *                 is deleted
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
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
        return '[Twilio.Sync.V1.CreateDocumentOptions ' . \implode(' ', $options) . ']';
    }
}

class UpdateDocumentOptions extends Options
{
    /**
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @param int $ttl How long, in seconds, before the Document resource expires
     *                 and is deleted
     */
    public function __construct($data = Values::NONE, $ttl = Values::NONE)
    {
        $this->options['data'] = $data;
        $this->options['ttl'] = $ttl;
    }

    /**
     * A JSON string that represents an arbitrary, schema-less object that the Sync Document stores. Can be up to 16KB in length.
     *
     * @param array $data A JSON string that represents an arbitrary, schema-less
     *                    object that the Sync Document stores
     * @return $this Fluent Builder
     */
    public function setData($data)
    {
        $this->options['data'] = $data;
        return $this;
    }

    /**
     * How long, in seconds, before the Sync Document expires and is deleted (time-to-live). Can be an integer from 0 to 31,536,000 (1 year). The default value is `0`, which means the Document resource does not expire. The Document resource will be deleted automatically after it expires, but there can be a delay between the expiration time and the resources's deletion.
     *
     * @param int $ttl How long, in seconds, before the Document resource expires
     *                 and is deleted
     * @return $this Fluent Builder
     */
    public function setTtl($ttl)
    {
        $this->options['ttl'] = $ttl;
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
        return '[Twilio.Sync.V1.UpdateDocumentOptions ' . \implode(' ', $options) . ']';
    }
}