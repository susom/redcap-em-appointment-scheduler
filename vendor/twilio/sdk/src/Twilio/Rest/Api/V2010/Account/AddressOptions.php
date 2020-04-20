<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Api\V2010\Account;

use Twilio\Options;
use Twilio\Values;

abstract class AddressOptions
{
    /**
     * @param string $friendlyName A string to describe the new resource
     * @param bool $emergencyEnabled Whether to enable emergency calling on the new
     *                               address
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     * @return CreateAddressOptions Options builder
     */
    public static function create(
        $friendlyName = Values::NONE,
        $emergencyEnabled = Values::NONE,
        $autoCorrectAddress = Values::NONE
    ) {
        return new CreateAddressOptions($friendlyName, $emergencyEnabled, $autoCorrectAddress);
    }

    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $customerName The name to associate with the address
     * @param string $street The number and street address of the address
     * @param string $city The city of the address
     * @param string $region The state or region of the address
     * @param string $postalCode The postal code of the address
     * @param bool $emergencyEnabled Whether to enable emergency calling on the
     *                               address
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     * @return UpdateAddressOptions Options builder
     */
    public static function update(
        $friendlyName = Values::NONE,
        $customerName = Values::NONE,
        $street = Values::NONE,
        $city = Values::NONE,
        $region = Values::NONE,
        $postalCode = Values::NONE,
        $emergencyEnabled = Values::NONE,
        $autoCorrectAddress = Values::NONE
    ) {
        return new UpdateAddressOptions($friendlyName, $customerName, $street, $city, $region, $postalCode,
            $emergencyEnabled, $autoCorrectAddress);
    }

    /**
     * @param string $customerName The `customer_name` of the Address resources to
     *                             read
     * @param string $friendlyName The string that identifies the Address resources
     *                             to read
     * @param string $isoCountry The ISO country code of the Address resources to
     *                           read
     * @return ReadAddressOptions Options builder
     */
    public static function read($customerName = Values::NONE, $friendlyName = Values::NONE, $isoCountry = Values::NONE)
    {
        return new ReadAddressOptions($customerName, $friendlyName, $isoCountry);
    }
}

class CreateAddressOptions extends Options
{
    /**
     * @param string $friendlyName A string to describe the new resource
     * @param bool $emergencyEnabled Whether to enable emergency calling on the new
     *                               address
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     */
    public function __construct(
        $friendlyName = Values::NONE,
        $emergencyEnabled = Values::NONE,
        $autoCorrectAddress = Values::NONE
    ) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        $this->options['autoCorrectAddress'] = $autoCorrectAddress;
    }

    /**
     * A descriptive string that you create to describe the new address. It can be up to 64 characters long.
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
     * Whether to enable emergency calling on the new address. Can be: `true` or `false`.
     *
     * @param bool $emergencyEnabled Whether to enable emergency calling on the new
     *                               address
     * @return $this Fluent Builder
     */
    public function setEmergencyEnabled($emergencyEnabled)
    {
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        return $this;
    }

    /**
     * Whether we should automatically correct the address. Can be: `true` or `false` and the default is `true`. If empty or `true`, we will correct the address you provide if necessary. If `false`, we won't alter the address you provide.
     *
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     * @return $this Fluent Builder
     */
    public function setAutoCorrectAddress($autoCorrectAddress)
    {
        $this->options['autoCorrectAddress'] = $autoCorrectAddress;
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
        return '[Twilio.Api.V2010.CreateAddressOptions ' . \implode(' ', $options) . ']';
    }
}

class UpdateAddressOptions extends Options
{
    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $customerName The name to associate with the address
     * @param string $street The number and street address of the address
     * @param string $city The city of the address
     * @param string $region The state or region of the address
     * @param string $postalCode The postal code of the address
     * @param bool $emergencyEnabled Whether to enable emergency calling on the
     *                               address
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     */
    public function __construct(
        $friendlyName = Values::NONE,
        $customerName = Values::NONE,
        $street = Values::NONE,
        $city = Values::NONE,
        $region = Values::NONE,
        $postalCode = Values::NONE,
        $emergencyEnabled = Values::NONE,
        $autoCorrectAddress = Values::NONE
    ) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['customerName'] = $customerName;
        $this->options['street'] = $street;
        $this->options['city'] = $city;
        $this->options['region'] = $region;
        $this->options['postalCode'] = $postalCode;
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        $this->options['autoCorrectAddress'] = $autoCorrectAddress;
    }

    /**
     * A descriptive string that you create to describe the address. It can be up to 64 characters long.
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
     * The name to associate with the address.
     *
     * @param string $customerName The name to associate with the address
     * @return $this Fluent Builder
     */
    public function setCustomerName($customerName)
    {
        $this->options['customerName'] = $customerName;
        return $this;
    }

    /**
     * The number and street address of the address.
     *
     * @param string $street The number and street address of the address
     * @return $this Fluent Builder
     */
    public function setStreet($street)
    {
        $this->options['street'] = $street;
        return $this;
    }

    /**
     * The city of the address.
     *
     * @param string $city The city of the address
     * @return $this Fluent Builder
     */
    public function setCity($city)
    {
        $this->options['city'] = $city;
        return $this;
    }

    /**
     * The state or region of the address.
     *
     * @param string $region The state or region of the address
     * @return $this Fluent Builder
     */
    public function setRegion($region)
    {
        $this->options['region'] = $region;
        return $this;
    }

    /**
     * The postal code of the address.
     *
     * @param string $postalCode The postal code of the address
     * @return $this Fluent Builder
     */
    public function setPostalCode($postalCode)
    {
        $this->options['postalCode'] = $postalCode;
        return $this;
    }

    /**
     * Whether to enable emergency calling on the address. Can be: `true` or `false`.
     *
     * @param bool $emergencyEnabled Whether to enable emergency calling on the
     *                               address
     * @return $this Fluent Builder
     */
    public function setEmergencyEnabled($emergencyEnabled)
    {
        $this->options['emergencyEnabled'] = $emergencyEnabled;
        return $this;
    }

    /**
     * Whether we should automatically correct the address. Can be: `true` or `false` and the default is `true`. If empty or `true`, we will correct the address you provide if necessary. If `false`, we won't alter the address you provide.
     *
     * @param bool $autoCorrectAddress Whether we should automatically correct the
     *                                 address
     * @return $this Fluent Builder
     */
    public function setAutoCorrectAddress($autoCorrectAddress)
    {
        $this->options['autoCorrectAddress'] = $autoCorrectAddress;
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
        return '[Twilio.Api.V2010.UpdateAddressOptions ' . \implode(' ', $options) . ']';
    }
}

class ReadAddressOptions extends Options
{
    /**
     * @param string $customerName The `customer_name` of the Address resources to
     *                             read
     * @param string $friendlyName The string that identifies the Address resources
     *                             to read
     * @param string $isoCountry The ISO country code of the Address resources to
     *                           read
     */
    public function __construct($customerName = Values::NONE, $friendlyName = Values::NONE, $isoCountry = Values::NONE)
    {
        $this->options['customerName'] = $customerName;
        $this->options['friendlyName'] = $friendlyName;
        $this->options['isoCountry'] = $isoCountry;
    }

    /**
     * The `customer_name` of the Address resources to read.
     *
     * @param string $customerName The `customer_name` of the Address resources to
     *                             read
     * @return $this Fluent Builder
     */
    public function setCustomerName($customerName)
    {
        $this->options['customerName'] = $customerName;
        return $this;
    }

    /**
     * The string that identifies the Address resources to read.
     *
     * @param string $friendlyName The string that identifies the Address resources
     *                             to read
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName)
    {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The ISO country code of the Address resources to read.
     *
     * @param string $isoCountry The ISO country code of the Address resources to
     *                           read
     * @return $this Fluent Builder
     */
    public function setIsoCountry($isoCountry)
    {
        $this->options['isoCountry'] = $isoCountry;
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
        return '[Twilio.Api.V2010.ReadAddressOptions ' . \implode(' ', $options) . ']';
    }
}