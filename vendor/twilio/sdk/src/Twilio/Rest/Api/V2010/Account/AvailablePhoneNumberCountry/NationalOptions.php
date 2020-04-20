<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry;

use Twilio\Options;
use Twilio\Values;

abstract class NationalOptions
{
    /**
     * @param int $areaCode The area code of the phone numbers to read
     * @param string $contains The pattern on which to match phone numbers
     * @param bool $smsEnabled Whether the phone numbers can receive text messages
     * @param bool $mmsEnabled Whether the phone numbers can receive MMS messages
     * @param bool $voiceEnabled Whether the phone numbers can receive calls.
     * @param bool $excludeAllAddressRequired Whether to exclude phone numbers that
     *                                        require an Address
     * @param bool $excludeLocalAddressRequired Whether to exclude phone numbers
     *                                          that require a local address
     * @param bool $excludeForeignAddressRequired Whether to exclude phone numbers
     *                                            that require a foreign address
     * @param bool $beta Whether to read phone numbers new to the Twilio platform
     * @param string $nearNumber Given a phone number, find a geographically close
     *                           number within distance miles. (US/Canada only)
     * @param string $nearLatLong Given a latitude/longitude pair lat,long find
     *                            geographically close numbers within distance
     *                            miles. (US/Canada only)
     * @param int $distance The search radius, in miles, for a near_ query.
     *                      (US/Canada only)
     * @param string $inPostalCode Limit results to a particular postal code.
     *                             (US/Canada only)
     * @param string $inRegion Limit results to a particular region. (US/Canada
     *                         only)
     * @param string $inRateCenter Limit results to a specific rate center, or
     *                             given a phone number search within the same rate
     *                             center as that number. (US/Canada only)
     * @param string $inLata Limit results to a specific local access and transport
     *                       area. (US/Canada only)
     * @param string $inLocality Limit results to a particular locality
     * @param bool $faxEnabled Whether the phone numbers can receive faxes
     * @return ReadNationalOptions Options builder
     */
    public static function read(
        $areaCode = Values::NONE,
        $contains = Values::NONE,
        $smsEnabled = Values::NONE,
        $mmsEnabled = Values::NONE,
        $voiceEnabled = Values::NONE,
        $excludeAllAddressRequired = Values::NONE,
        $excludeLocalAddressRequired = Values::NONE,
        $excludeForeignAddressRequired = Values::NONE,
        $beta = Values::NONE,
        $nearNumber = Values::NONE,
        $nearLatLong = Values::NONE,
        $distance = Values::NONE,
        $inPostalCode = Values::NONE,
        $inRegion = Values::NONE,
        $inRateCenter = Values::NONE,
        $inLata = Values::NONE,
        $inLocality = Values::NONE,
        $faxEnabled = Values::NONE
    ) {
        return new ReadNationalOptions($areaCode, $contains, $smsEnabled, $mmsEnabled, $voiceEnabled,
            $excludeAllAddressRequired, $excludeLocalAddressRequired, $excludeForeignAddressRequired, $beta,
            $nearNumber, $nearLatLong, $distance, $inPostalCode, $inRegion, $inRateCenter, $inLata, $inLocality,
            $faxEnabled);
    }
}

class ReadNationalOptions extends Options
{
    /**
     * @param int $areaCode The area code of the phone numbers to read
     * @param string $contains The pattern on which to match phone numbers
     * @param bool $smsEnabled Whether the phone numbers can receive text messages
     * @param bool $mmsEnabled Whether the phone numbers can receive MMS messages
     * @param bool $voiceEnabled Whether the phone numbers can receive calls.
     * @param bool $excludeAllAddressRequired Whether to exclude phone numbers that
     *                                        require an Address
     * @param bool $excludeLocalAddressRequired Whether to exclude phone numbers
     *                                          that require a local address
     * @param bool $excludeForeignAddressRequired Whether to exclude phone numbers
     *                                            that require a foreign address
     * @param bool $beta Whether to read phone numbers new to the Twilio platform
     * @param string $nearNumber Given a phone number, find a geographically close
     *                           number within distance miles. (US/Canada only)
     * @param string $nearLatLong Given a latitude/longitude pair lat,long find
     *                            geographically close numbers within distance
     *                            miles. (US/Canada only)
     * @param int $distance The search radius, in miles, for a near_ query.
     *                      (US/Canada only)
     * @param string $inPostalCode Limit results to a particular postal code.
     *                             (US/Canada only)
     * @param string $inRegion Limit results to a particular region. (US/Canada
     *                         only)
     * @param string $inRateCenter Limit results to a specific rate center, or
     *                             given a phone number search within the same rate
     *                             center as that number. (US/Canada only)
     * @param string $inLata Limit results to a specific local access and transport
     *                       area. (US/Canada only)
     * @param string $inLocality Limit results to a particular locality
     * @param bool $faxEnabled Whether the phone numbers can receive faxes
     */
    public function __construct(
        $areaCode = Values::NONE,
        $contains = Values::NONE,
        $smsEnabled = Values::NONE,
        $mmsEnabled = Values::NONE,
        $voiceEnabled = Values::NONE,
        $excludeAllAddressRequired = Values::NONE,
        $excludeLocalAddressRequired = Values::NONE,
        $excludeForeignAddressRequired = Values::NONE,
        $beta = Values::NONE,
        $nearNumber = Values::NONE,
        $nearLatLong = Values::NONE,
        $distance = Values::NONE,
        $inPostalCode = Values::NONE,
        $inRegion = Values::NONE,
        $inRateCenter = Values::NONE,
        $inLata = Values::NONE,
        $inLocality = Values::NONE,
        $faxEnabled = Values::NONE
    ) {
        $this->options['areaCode'] = $areaCode;
        $this->options['contains'] = $contains;
        $this->options['smsEnabled'] = $smsEnabled;
        $this->options['mmsEnabled'] = $mmsEnabled;
        $this->options['voiceEnabled'] = $voiceEnabled;
        $this->options['excludeAllAddressRequired'] = $excludeAllAddressRequired;
        $this->options['excludeLocalAddressRequired'] = $excludeLocalAddressRequired;
        $this->options['excludeForeignAddressRequired'] = $excludeForeignAddressRequired;
        $this->options['beta'] = $beta;
        $this->options['nearNumber'] = $nearNumber;
        $this->options['nearLatLong'] = $nearLatLong;
        $this->options['distance'] = $distance;
        $this->options['inPostalCode'] = $inPostalCode;
        $this->options['inRegion'] = $inRegion;
        $this->options['inRateCenter'] = $inRateCenter;
        $this->options['inLata'] = $inLata;
        $this->options['inLocality'] = $inLocality;
        $this->options['faxEnabled'] = $faxEnabled;
    }

    /**
     * The area code of the phone numbers to read. Applies to only phone numbers in the US and Canada.
     *
     * @param int $areaCode The area code of the phone numbers to read
     * @return $this Fluent Builder
     */
    public function setAreaCode($areaCode)
    {
        $this->options['areaCode'] = $areaCode;
        return $this;
    }

    /**
     * The pattern on which to match phone numbers. Valid characters are `*`, `0-9`, `a-z`, and `A-Z`. The `*` character matches any single digit. For examples, see [Example 2](https://www.twilio.com/docs/phone-numbers/api/availablephonenumber-resource#local-get-basic-example-2) and [Example 3](https://www.twilio.com/docs/phone-numbers/api/availablephonenumber-resource#local-get-basic-example-3). If specified, this value must have at least two characters.
     *
     * @param string $contains The pattern on which to match phone numbers
     * @return $this Fluent Builder
     */
    public function setContains($contains)
    {
        $this->options['contains'] = $contains;
        return $this;
    }

    /**
     * Whether the phone numbers can receive text messages. Can be: `true` or `false`.
     *
     * @param bool $smsEnabled Whether the phone numbers can receive text messages
     * @return $this Fluent Builder
     */
    public function setSmsEnabled($smsEnabled)
    {
        $this->options['smsEnabled'] = $smsEnabled;
        return $this;
    }

    /**
     * Whether the phone numbers can receive MMS messages. Can be: `true` or `false`.
     *
     * @param bool $mmsEnabled Whether the phone numbers can receive MMS messages
     * @return $this Fluent Builder
     */
    public function setMmsEnabled($mmsEnabled)
    {
        $this->options['mmsEnabled'] = $mmsEnabled;
        return $this;
    }

    /**
     * Whether the phone numbers can receive calls. Can be: `true` or `false`.
     *
     * @param bool $voiceEnabled Whether the phone numbers can receive calls.
     * @return $this Fluent Builder
     */
    public function setVoiceEnabled($voiceEnabled)
    {
        $this->options['voiceEnabled'] = $voiceEnabled;
        return $this;
    }

    /**
     * Whether to exclude phone numbers that require an [Address](https://www.twilio.com/docs/usage/api/address). Can be: `true` or `false` and the default is `false`.
     *
     * @param bool $excludeAllAddressRequired Whether to exclude phone numbers that
     *                                        require an Address
     * @return $this Fluent Builder
     */
    public function setExcludeAllAddressRequired($excludeAllAddressRequired)
    {
        $this->options['excludeAllAddressRequired'] = $excludeAllAddressRequired;
        return $this;
    }

    /**
     * Whether to exclude phone numbers that require a local [Address](https://www.twilio.com/docs/usage/api/address). Can be: `true` or `false` and the default is `false`.
     *
     * @param bool $excludeLocalAddressRequired Whether to exclude phone numbers
     *                                          that require a local address
     * @return $this Fluent Builder
     */
    public function setExcludeLocalAddressRequired($excludeLocalAddressRequired)
    {
        $this->options['excludeLocalAddressRequired'] = $excludeLocalAddressRequired;
        return $this;
    }

    /**
     * Whether to exclude phone numbers that require a foreign [Address](https://www.twilio.com/docs/usage/api/address). Can be: `true` or `false` and the default is `false`.
     *
     * @param bool $excludeForeignAddressRequired Whether to exclude phone numbers
     *                                            that require a foreign address
     * @return $this Fluent Builder
     */
    public function setExcludeForeignAddressRequired($excludeForeignAddressRequired)
    {
        $this->options['excludeForeignAddressRequired'] = $excludeForeignAddressRequired;
        return $this;
    }

    /**
     * Whether to read phone numbers that are new to the Twilio platform. Can be: `true` or `false` and the default is `true`.
     *
     * @param bool $beta Whether to read phone numbers new to the Twilio platform
     * @return $this Fluent Builder
     */
    public function setBeta($beta)
    {
        $this->options['beta'] = $beta;
        return $this;
    }

    /**
     * Given a phone number, find a geographically close number within `distance` miles. Distance defaults to 25 miles. Applies to only phone numbers in the US and Canada.
     *
     * @param string $nearNumber Given a phone number, find a geographically close
     *                           number within distance miles. (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setNearNumber($nearNumber)
    {
        $this->options['nearNumber'] = $nearNumber;
        return $this;
    }

    /**
     * Given a latitude/longitude pair `lat,long` find geographically close numbers within `distance` miles. Applies to only phone numbers in the US and Canada.
     *
     * @param string $nearLatLong Given a latitude/longitude pair lat,long find
     *                            geographically close numbers within distance
     *                            miles. (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setNearLatLong($nearLatLong)
    {
        $this->options['nearLatLong'] = $nearLatLong;
        return $this;
    }

    /**
     * The search radius, in miles, for a `near_` query.  Can be up to `500` and the default is `25`. Applies to only phone numbers in the US and Canada.
     *
     * @param int $distance The search radius, in miles, for a near_ query.
     *                      (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setDistance($distance)
    {
        $this->options['distance'] = $distance;
        return $this;
    }

    /**
     * Limit results to a particular postal code. Given a phone number, search within the same postal code as that number. Applies to only phone numbers in the US and Canada.
     *
     * @param string $inPostalCode Limit results to a particular postal code.
     *                             (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setInPostalCode($inPostalCode)
    {
        $this->options['inPostalCode'] = $inPostalCode;
        return $this;
    }

    /**
     * Limit results to a particular region, state, or province. Given a phone number, search within the same region as that number. Applies to only phone numbers in the US and Canada.
     *
     * @param string $inRegion Limit results to a particular region. (US/Canada
     *                         only)
     * @return $this Fluent Builder
     */
    public function setInRegion($inRegion)
    {
        $this->options['inRegion'] = $inRegion;
        return $this;
    }

    /**
     * Limit results to a specific rate center, or given a phone number search within the same rate center as that number. Requires `in_lata` to be set as well. Applies to only phone numbers in the US and Canada.
     *
     * @param string $inRateCenter Limit results to a specific rate center, or
     *                             given a phone number search within the same rate
     *                             center as that number. (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setInRateCenter($inRateCenter)
    {
        $this->options['inRateCenter'] = $inRateCenter;
        return $this;
    }

    /**
     * Limit results to a specific local access and transport area ([LATA](https://en.wikipedia.org/wiki/Local_access_and_transport_area)). Given a phone number, search within the same [LATA](https://en.wikipedia.org/wiki/Local_access_and_transport_area) as that number. Applies to only phone numbers in the US and Canada.
     *
     * @param string $inLata Limit results to a specific local access and transport
     *                       area. (US/Canada only)
     * @return $this Fluent Builder
     */
    public function setInLata($inLata)
    {
        $this->options['inLata'] = $inLata;
        return $this;
    }

    /**
     * Limit results to a particular locality or city. Given a phone number, search within the same Locality as that number.
     *
     * @param string $inLocality Limit results to a particular locality
     * @return $this Fluent Builder
     */
    public function setInLocality($inLocality)
    {
        $this->options['inLocality'] = $inLocality;
        return $this;
    }

    /**
     * Whether the phone numbers can receive faxes. Can be: `true` or `false`.
     *
     * @param bool $faxEnabled Whether the phone numbers can receive faxes
     * @return $this Fluent Builder
     */
    public function setFaxEnabled($faxEnabled)
    {
        $this->options['faxEnabled'] = $faxEnabled;
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
        return '[Twilio.Api.V2010.ReadNationalOptions ' . \implode(' ', $options) . ']';
    }
}