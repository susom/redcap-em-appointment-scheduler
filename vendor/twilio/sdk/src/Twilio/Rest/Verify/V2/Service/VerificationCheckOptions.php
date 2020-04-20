<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Verify\V2\Service;

use Twilio\Options;
use Twilio\Values;

abstract class VerificationCheckOptions
{
    /**
     * @param string $to The phone number or email to verify
     * @param string $verificationSid A SID that uniquely identifies the
     *                                Verification Check
     * @param string $amount The amount of the associated PSD2 compliant
     *                       transaction.
     * @param string $payee The payee of the associated PSD2 compliant transaction
     * @return CreateVerificationCheckOptions Options builder
     */
    public static function create(
        $to = Values::NONE,
        $verificationSid = Values::NONE,
        $amount = Values::NONE,
        $payee = Values::NONE
    ) {
        return new CreateVerificationCheckOptions($to, $verificationSid, $amount, $payee);
    }
}

class CreateVerificationCheckOptions extends Options
{
    /**
     * @param string $to The phone number or email to verify
     * @param string $verificationSid A SID that uniquely identifies the
     *                                Verification Check
     * @param string $amount The amount of the associated PSD2 compliant
     *                       transaction.
     * @param string $payee The payee of the associated PSD2 compliant transaction
     */
    public function __construct(
        $to = Values::NONE,
        $verificationSid = Values::NONE,
        $amount = Values::NONE,
        $payee = Values::NONE
    ) {
        $this->options['to'] = $to;
        $this->options['verificationSid'] = $verificationSid;
        $this->options['amount'] = $amount;
        $this->options['payee'] = $payee;
    }

    /**
     * The phone number or [email](https://www.twilio.com/docs/verify/email) to verify. Either this parameter or the `verification_sid` must be specified. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     *
     * @param string $to The phone number or email to verify
     * @return $this Fluent Builder
     */
    public function setTo($to)
    {
        $this->options['to'] = $to;
        return $this;
    }

    /**
     * A SID that uniquely identifies the Verification Check. Either this parameter or the `to` phone number/[email](https://www.twilio.com/docs/verify/email) must be specified.
     *
     * @param string $verificationSid A SID that uniquely identifies the
     *                                Verification Check
     * @return $this Fluent Builder
     */
    public function setVerificationSid($verificationSid)
    {
        $this->options['verificationSid'] = $verificationSid;
        return $this;
    }

    /**
     * The amount of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     *
     * @param string $amount The amount of the associated PSD2 compliant
     *                       transaction.
     * @return $this Fluent Builder
     */
    public function setAmount($amount)
    {
        $this->options['amount'] = $amount;
        return $this;
    }

    /**
     * The payee of the associated PSD2 compliant transaction. Requires the PSD2 Service flag enabled.
     *
     * @param string $payee The payee of the associated PSD2 compliant transaction
     * @return $this Fluent Builder
     */
    public function setPayee($payee)
    {
        $this->options['payee'] = $payee;
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
        return '[Twilio.Verify.V2.CreateVerificationCheckOptions ' . \implode(' ', $options) . ']';
    }
}