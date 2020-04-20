<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1;

use Twilio\Options;
use Twilio\Values;

abstract class RecordingOptions
{
    /**
     * @param string $status Read only the recordings that have this status
     * @param string $sourceSid Read only the recordings that have this source_sid
     * @param string $groupingSid Read only recordings that have this grouping_sid
     * @param \DateTime $dateCreatedAfter Read only recordings that started on or
     *                                    after this [ISO
     *                                    8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @param \DateTime $dateCreatedBefore Read only recordings that started before
     *                                     this [ISO
     *                                     8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @param string $mediaType Read only recordings that have this media type
     * @return ReadRecordingOptions Options builder
     */
    public static function read(
        $status = Values::NONE,
        $sourceSid = Values::NONE,
        $groupingSid = Values::NONE,
        $dateCreatedAfter = Values::NONE,
        $dateCreatedBefore = Values::NONE,
        $mediaType = Values::NONE
    ) {
        return new ReadRecordingOptions($status, $sourceSid, $groupingSid, $dateCreatedAfter, $dateCreatedBefore,
            $mediaType);
    }
}

class ReadRecordingOptions extends Options
{
    /**
     * @param string $status Read only the recordings that have this status
     * @param string $sourceSid Read only the recordings that have this source_sid
     * @param string $groupingSid Read only recordings that have this grouping_sid
     * @param \DateTime $dateCreatedAfter Read only recordings that started on or
     *                                    after this [ISO
     *                                    8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @param \DateTime $dateCreatedBefore Read only recordings that started before
     *                                     this [ISO
     *                                     8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @param string $mediaType Read only recordings that have this media type
     */
    public function __construct(
        $status = Values::NONE,
        $sourceSid = Values::NONE,
        $groupingSid = Values::NONE,
        $dateCreatedAfter = Values::NONE,
        $dateCreatedBefore = Values::NONE,
        $mediaType = Values::NONE
    ) {
        $this->options['status'] = $status;
        $this->options['sourceSid'] = $sourceSid;
        $this->options['groupingSid'] = $groupingSid;
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        $this->options['mediaType'] = $mediaType;
    }

    /**
     * Read only the recordings that have this status. Can be: `processing`, `completed`, or `deleted`.
     *
     * @param string $status Read only the recordings that have this status
     * @return $this Fluent Builder
     */
    public function setStatus($status)
    {
        $this->options['status'] = $status;
        return $this;
    }

    /**
     * Read only the recordings that have this `source_sid`.
     *
     * @param string $sourceSid Read only the recordings that have this source_sid
     * @return $this Fluent Builder
     */
    public function setSourceSid($sourceSid)
    {
        $this->options['sourceSid'] = $sourceSid;
        return $this;
    }

    /**
     * Read only recordings with this `grouping_sid`, which may include a `participant_sid` and/or a `room_sid`.
     *
     * @param string $groupingSid Read only recordings that have this grouping_sid
     * @return $this Fluent Builder
     */
    public function setGroupingSid($groupingSid)
    {
        $this->options['groupingSid'] = $groupingSid;
        return $this;
    }

    /**
     * Read only recordings that started on or after this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone.
     *
     * @param \DateTime $dateCreatedAfter Read only recordings that started on or
     *                                    after this [ISO
     *                                    8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @return $this Fluent Builder
     */
    public function setDateCreatedAfter($dateCreatedAfter)
    {
        $this->options['dateCreatedAfter'] = $dateCreatedAfter;
        return $this;
    }

    /**
     * Read only recordings that started before this [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone, given as `YYYY-MM-DDThh:mm:ss+|-hh:mm` or `YYYY-MM-DDThh:mm:ssZ`.
     *
     * @param \DateTime $dateCreatedBefore Read only recordings that started before
     *                                     this [ISO
     *                                     8601](https://en.wikipedia.org/wiki/ISO_8601) date-time with time zone
     * @return $this Fluent Builder
     */
    public function setDateCreatedBefore($dateCreatedBefore)
    {
        $this->options['dateCreatedBefore'] = $dateCreatedBefore;
        return $this;
    }

    /**
     * Read only recordings that have this media type. Can be either `audio` or `video`.
     *
     * @param string $mediaType Read only recordings that have this media type
     * @return $this Fluent Builder
     */
    public function setMediaType($mediaType)
    {
        $this->options['mediaType'] = $mediaType;
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
        return '[Twilio.Video.V1.ReadRecordingOptions ' . \implode(' ', $options) . ']';
    }
}