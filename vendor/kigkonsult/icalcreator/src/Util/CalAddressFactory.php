<?php
/**
 * iCalcreator, the PHP class package managing iCal (rfc2445/rfc5445) calendar information.
 *
 * This file is a part of iCalcreator.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2007-2024 Kjell-Inge Gustafsson, kigkonsult AB, All rights reserved
 * @link      https://kigkonsult.se
 * @license   Subject matter of licence is the software iCalcreator.
 *            The above copyright, link, package and version notices,
 *            this licence notice and the invariant [rfc5545] PRODID result use
 *            as implemented and invoked in iCalcreator shall be included in
 *            all copies or substantial portions of the iCalcreator.
 *
 *            iCalcreator is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *            iCalcreator is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public License
 *            along with iCalcreator. If not, see <https://www.gnu.org/licenses/>.
 */
declare( strict_types = 1 );
namespace Kigkonsult\Icalcreator\Util;

use InvalidArgumentException;
use Kigkonsult\Icalcreator\CalendarComponent;
use Kigkonsult\Icalcreator\IcalInterface;
use Kigkonsult\Icalcreator\Pc;
use Kigkonsult\Icalcreator\Vcalendar;
use Kigkonsult\Icalcreator\Vevent;
use function array_merge;
use function in_array;
use function method_exists;
use function sprintf;
use function strcasecmp;
use function strstr;
use function substr;
use function trim;

/**
 * iCalcreator attendee support class
 *
 * @since 2.41.85 2024-01-18
 */
class CalAddressFactory
{
    /**
     * Protocols
     *
     * @var string[]
     */
    public static array $PROTO3 = [ 'cid:', 'sms:', 'tel:', 'urn:' ];
    public static array $PROTO4 = [ 'crid:', 'news:', 'pres:', 'http:', ':http:' ];
    public static array $PROTO5 = [ 'https:' ];
    public static array $PROTO6 = [ 'mailto:', 'telnet:' ];
    public static array $PROTO7 = [ 'message:' ];

    /**
     * @var string[]
     */
    private static array $CALADDRESSPROPERTIES = [
        IcalInterface::ATTENDEE,
        IcalInterface::CONTACT,
        IcalInterface::ORGANIZER,
        IcalInterface::PARTICIPANT
    ];

    /**
     * @var string Prefix (protocol) for Ical cal-address etc
     * @since 2.41.52  2022-08-06
     */
    public  static string $MAILTOCOLON = 'mailto:';
    private static string $AT          = '@';

    /**
     * Assert cal-address (i.e. MAILTO-prefixed)
     *
     * @param string $calAddress
     * @return void
     * @throws InvalidArgumentException
     * @since  2.41.68 - 2022-10-21
     */
    public static function assertCalAddress( string $calAddress ) : void
    {
        static $DOT    = '.';
        static $XDOT   = 'x.';
        static $ERRMSG = 'Invalid email %s';
        if( ! str_contains( $calAddress, self::$AT )) {
            throw new InvalidArgumentException( sprintf( $ERRMSG, $calAddress ));
        }
        $domain = StringFactory::after( self::$AT, $calAddress );
        if( ! str_contains( strstr( $calAddress, self::$AT, true ), $DOT )) {
            $namePart    = self::extractNamepartFromEmail( $calAddress );
            $testAddress = $XDOT . $namePart . self::$AT . $domain;
        }
        else {
            $testAddress = self::removeMailtoPrefix( $calAddress );
        }
        if( ! filter_var( $testAddress, FILTER_VALIDATE_EMAIL ) &&
            ! filter_var( $domain, FILTER_VALIDATE_DOMAIN )) {
            throw new InvalidArgumentException( sprintf( $ERRMSG, $calAddress ));
        }
    }

    /**
     * Return conformed cal-address (i.e. MAILTO-prefixed)
     *
     * @param string $calAddress
     * @param bool $forceMailto   force if missing
     * @return string
     * @since  2.41.52 - 2022-08-06
     */
    public static function conformCalAddress( string $calAddress, ? bool $forceMailto = false ) : string
    {
        switch( true ) {
            case empty( $calAddress ) :
                break;
            case ( 0 === strcasecmp( self::$MAILTOCOLON, substr( $calAddress, 0, 7 ))) :
                // exists, force lowercase
                $calAddress = self::$MAILTOCOLON . substr( $calAddress, 7 );
                break;
            case $forceMailto :
                // missing and force
                $calAddress = self::$MAILTOCOLON . $calAddress;
                break;
        } // end switch
        return $calAddress;
    }

    /**
     * Return bool true if string starts with protocol
     *
     * @param string $value
     * @return bool
     * @since  2.41.80 - 2023-06-27
     */
    public static function hasProtocolPrefix( string $value ) : bool
    {
        static $PROTOCOLS = null;
        if( null === $PROTOCOLS ) {
            $PROTOCOLS = array_merge(
                self::$PROTO3,
                self::$PROTO4,
                self::$PROTO5,
                self::$PROTO6,
                self::$PROTO7,
            );
        }
        $cmpValue = strtolower( $value );
        foreach( $PROTOCOLS as $prtcl ) {
            if( str_starts_with( $cmpValue, $prtcl )) {
                return true;
            }
        } // end foreach
        return false;
    }

    /**
     * Return bool true if email has leading mailto:
     *
     * @param string $email
     * @return bool
     * @since  2.41.80 - 2023-06-27
     */
    public static function hasMailtoPrefix( string $email ) : bool
    {
        return ( 0 === strcasecmp( self::$MAILTOCOLON, substr( $email, 0, 7 )));
    }

    /**
     * Return email without prefix (anycase) 'mailto;
     *
     * @param string $email
     * @return string
     * @since  2.41.52 - 2022-08-06
     */
    public static function removeMailtoPrefix( string $email ) : string
    {
        return self::hasMailtoPrefix( $email ) ? substr( $email, 7 ) : $email;
    }

    /**
     * Return bool true if parameter EMAIL equals ATTENDEE/ORGANIZER value
     *
     * @param Pc $pc
     * @return void
     * @since 2.41.88 - 2024-10-18
     */
    public static function sameValueAndEMAILparam( Pc $pc ) : void
    {
        if( ! $pc->hasParamKey( IcalInterface::EMAIL )) {
            return;
        }
        $vEmail = self::removeMailtoPrefix( $pc->getValue());
        $pEmail = self::removeMailtoPrefix( $pc->getParams( IcalInterface::EMAIL ));
        if( 0 === strcasecmp( $vEmail, $pEmail )) {
            $pc->removeParam( IcalInterface::EMAIL );
        } // end if
    }

    /**
     * Extract namePart from email
     *
     * @param string $email
     * @return string
     * @since  2.41.68 - 2022-10-21
     */
    public static function extractNamepartFromEmail( string $email ) : string
    {
        if( self::hasMailtoPrefix( $email )) {
            return strstr( substr( $email, 7 ), self::$AT, true );
        }
        return strstr( $email, self::$AT, true );
    }

    /**
     * Return cal-addresses and (hopefully) name parts
     *
     * From ATTENDEEs and ORGANIZERs, name part from CONTACTs
     * NO parameters cal-addresses
     *
     * @param Vcalendar     $calendar    iCalcreator Vcalendar instance
     * @param null|string[] $properties
     * @return string[]
     * @since  2.39 - 2021-06-14
     */
    public static function getCalAddresses(
        Vcalendar $calendar,
        ? array $properties = []
    ) : array
    {
        $searchProperties = [];
        if( empty( $properties )) {
            $searchProperties = self::$CALADDRESSPROPERTIES;
        }
        else {
            foreach( $properties as $property ) {
                if( in_array( $property, self::$CALADDRESSPROPERTIES, true )) {
                    $searchProperties[] = $property;
                }
            } // end foreach
        }
        $output = [];
        foreach( $searchProperties as $propName ) {
            foreach( self::getCalAdressValuesFromProperty( $calendar, $propName ) as $calAddress ) {
                $output[] = $calAddress;
            }
        } // end foreach
        sort( $output );
        return array_unique( $output );
    }

    /**
     * Return value cal-addresses from Vcalendar property value
     *
     * From one of ATTENDEEs and ORGANIZERs, name part from CONTACTs
     *
     * @param Vcalendar $calendar  iCalcreator Vcalendar instance
     * @param string    $propName
     * @return string[]
     * @since  2.27.68 - 2022-10-21
     */
    public static function getCalAdressValuesFromProperty(
        Vcalendar $calendar,
        string $propName
    ) : array
    {
        $propValues = $calendar->getProperty( $propName );
        if( empty( $propValues )) {
            return [];
        }
        $output = [];
        foreach((array) $propValues as $propValue => $counts ) {
            $propValue = self::removeMailtoPrefix( $propValue );
            if( str_contains( $propValue, StringFactory::$COMMA )) {
                $propValue = strstr( $propValue, StringFactory::$COMMA, true );
            }
            try {
                self::assertCalAddress( $propValue );
            }
            catch( InvalidArgumentException ) {
                continue;
            }
            if( ! in_array( $propValue, $output, true )) {
                $output[] = $propValue;
            }
        } // end foreach
        sort( $output );
        return array_unique( $output );
    }

    /**
     * Return value and parameters cal-addresses from Vcalendar properties
     *
     * From one of ATTENDEEs and ORGANIZERs, name part from CONTACTs
     *
     * @param Vcalendar    $calendar  iCalcreator Vcalendar instance
     * @param null|string|string[]  $propName
     * @return string[]
     * @since  2.39 - 2021-06-19
     */
    public static function getCalAdressesAllFromProperty(
        Vcalendar $calendar,
        null | string | array $propName = null
    ) : array
    {
        if( empty( $propName )) {
            $propName = self::$CALADDRESSPROPERTIES;
        }
        $calendar->resetCompCounter();
        $output = [];
        while( $comp = $calendar->getComponent()) {
            foreach((array) $propName as $pName ) {
                $method = StringFactory::getGetMethodName( $pName );
                if( ! method_exists( $comp, $method )) {
                    continue;
                }
                switch( $pName ) {
                    case IcalInterface::ATTENDEE :
                        foreach( self::getCalAdressesAllFromAttendee( $comp ) as $email ) {
                            if( ! in_array( $email, $output, true )) {
                                $output[] = $email;
                            }
                        }
                        break;
                    case IcalInterface::ORGANIZER :
                        foreach( self::getCalAdressesAllFromOrganizer( $comp ) as $email ) {
                            if( ! in_array( $email, $output, true )) {
                                $output[] = $email;
                            }
                        }
                        break;
                    default :
                        foreach( self::getCalAdressesAllFromContact( $comp ) as $email ) {
                            if( ! in_array( $email, $output, true )) {
                                $output[] = $email;
                            }
                        }
                        break;
                } // end switch
            } // end foreach
        } // end while
        sort( $output );
        return array_unique( $output );
    }

    /**
     * Return value and parameters cal-addresses from Vcalendar ATTENDEE property
     *
     * @param CalendarComponent|Vevent $component  iCalcreator Vcalendar component instance
     * @return string[]
     * @since 2.41.85 2024-01-18
     */
    public static function getCalAdressesAllFromAttendee( CalendarComponent|Vevent $component ) : array
    {
        $output = [];
        foreach( $component->getAllAttendee( true ) as $propValue ) {
            if( empty( $propValue )) {
               continue;
            }
            $value = self::removeMailtoPrefix( $propValue->getValue());
            if( !in_array( $value, $output, true )) {
                $output[] = $value;
            }
            foreach( $propValue->params as $pLabel => $pValue ) {
                switch( $pLabel ) {
                    case IcalInterface::MEMBER:       // fall through
                    case IcalInterface::DELEGATED_TO: // fall through
                    case IcalInterface::DELEGATED_FROM:
                        foreach( $pValue as $pValue2 ) {
                            $pValue2 = self::removeMailtoPrefix( trim( $pValue2, StringFactory::$QQ ));
                            if( ! in_array( $pValue2, $output, true )) {
                                $output[] = $pValue2;
                            }
                        } // end foreach
                        break;
                    case IcalInterface::EMAIL :       // fall through
                    case IcalInterface::SENT_BY :
                        $pValue2 = self::removeMailtoPrefix(
                            trim( $pValue, StringFactory::$QQ )
                        );
                        if( ! in_array( $pValue2, $output, true )) {
                            $output[] = $pValue2;
                        }
                        break;
                } // end switch
            } // end foreach
        } // end foreach
        return $output;
    }

    /**
     * Return value and parameters cal-addresses from Vcalendar ORGANIZER property
     *
     * @param CalendarComponent|Vevent $component  iCalcreator Vcalendar component instance
     * @return string[]
     * @since  2.41.36 - 2022-04-03
     */
    public static function getCalAdressesAllFromOrganizer( CalendarComponent|Vevent $component ) : array
    {
        if(( false === ( $propValue = $component->getOrganizer( true ))) ||
            empty( $propValue )) {
            return [];
        }
        $output = [];
        $value  = self::removeMailtoPrefix( $propValue->getValue());
        if( ! in_array( $value, $output, true )) {
            $output[] = $value;
        }
        foreach( [ IcalInterface::EMAIL, IcalInterface::SENT_BY ] as $key ) {
            if( $propValue->hasParamkey( $key )) {
                $value = self::removeMailtoPrefix( $propValue->getParams( $key ));
                if( ! in_array( $value, $output, true )) {
                    $output[] = $value;
                }
            }
        } // end foreach
        return $output;
    }

    /**
     * Return value and parameters cal-addresses from Vcalendar CONTACT property
     *
     * @param CalendarComponent|Vevent $component  iCalcreator Vcalendar component instance
     * @return string[]
     * @since 2.41.85 2024-01-18
     */
    public static function getCalAdressesAllFromContact( CalendarComponent|Vevent $component ) : array
    {
        $output = [];
        foreach( $component->getAllContact( true ) as $propValue ) {
            if( empty( $propValue )) {
                continue;
            }
            $pcValue = $propValue->getValue();
            $value =
                str_contains( $pcValue, StringFactory::$COMMA )
                    ? strstr( $pcValue, StringFactory::$COMMA, true )
                    : $pcValue;
            try {
                self::assertCalAddress( $value );
            }
            catch( InvalidArgumentException ) {
                continue;
            }
            if( ! in_array( $value, $output, true )) {
                $output[] = $value;
            }
        } // end while
        return $output;
    }

    /**
     * Return formatted output for calendar component property attendee
     *
     * @param Pc $pc
     * @param string $compType
     * @param bool|string $lang  bool false if not config lang found
     * @return void
     * @throws InvalidArgumentException
     * @since  2.41.85 2024-01-19
     */
    public static function inputPrepAttendeeParams(
        Pc $pc,
        string $compType,
        bool | string $lang
    ) : void
    {
        static $NoParamComps = [ IcalInterface::VFREEBUSY, IcalInterface::VALARM ];
        static $DEFAULTSTOREMOVE     = [
            IcalInterface::CUTYPE   => IcalInterface::INDIVIDUAL,
            IcalInterface::PARTSTAT => IcalInterface::NEEDS_ACTION,
            IcalInterface::ROLE     => IcalInterface::REQ_PARTICIPANT,
            IcalInterface::RSVP     => IcalInterface::FALSE
        ];
        foreach( $pc->getParams() as $pLabel => $pValue ) {
            if( ! StringFactory::isXprefixed( $pLabel ) &&
                in_array( $compType, $NoParamComps, true )) { // skip
                $pc->removeParam( $pLabel );
                continue;
            }
            match( $pLabel ) {
                IcalInterface::EMAIL   => $pc->addParam( $pLabel, self::prepEmail( $pValue )),
                IcalInterface::MEMBER, IcalInterface::DELEGATED_TO, IcalInterface::DELEGATED_FROM
                                       => $pc->addParam( $pLabel, self::prepInputMDtDf((array) $pValue )),
                IcalInterface::ORDER   => $pc->addParam( $pLabel, $pValue ),
                IcalInterface::SENT_BY => $pc->addParam( $pLabel, self::prepSentBy( $pValue )),
                default                => $pc->addParam( $pLabel, trim( $pValue, StringFactory::$QQ )),
            }; // end match( $pLabel.. .
        } // end foreach
        // remove defaults
        foreach( $DEFAULTSTOREMOVE as $pLabel => $pValue ) {
            if( $pc->hasParamKey( $pLabel ) &&
                ( 0 === strcasecmp( $pValue, $pc->getParams( $pLabel )))) {
                $pc->removeParam( $pLabel );
            }
        }
        // check language setting
        if( $pc->hasParamKey( IcalInterface::CN ) &&
            ! isset( $params2[IcalInterface::LANGUAGE] ) &&
            ! empty( $lang )) {
            $pc->addParam( IcalInterface::LANGUAGE, $lang );
        }
    }

    /**
     * Prepare input Member, DELEGATED_TO, DELEGATED_FROM parameters
     *
     * @param string[] $calAddress
     * @return string[]
     * @throws InvalidArgumentException
     * @since  2.39 - 2021-06-17
     */
    private static function prepInputMDtDf( array $calAddress ) : array
    {
        $output = [];
        foreach( $calAddress as $pValue2 ) {
            if( empty( $pValue2 )) {
                continue;
            }
            $pValue2  = trim( $pValue2, StringFactory::$QQ );
            $pValue2  = self::conformCalAddress( $pValue2, true );
            self::assertCalAddress( $pValue2 );
            $output[] = $pValue2;
        } // end foreach
        return $output;
    }

    /**
     * Prepare input EMAIL parameter (without opt leading MAILTO)
     *
     * @param string $calAddress
     * @return string
     * @throws InvalidArgumentException
     * @since  2.39 - 2021-06-17
     */
    public static function prepEmail( string $calAddress ) : string
    {
        if( 0 === strcasecmp( self::$MAILTOCOLON, substr( $calAddress, 0, 7 ))) {
            $calAddress = substr( $calAddress, 7 );
        }
        self::assertCalAddress( $calAddress );
        return $calAddress;
    }

    /**
     * Prepare input SENT_BY parameter, force leading MAILTO
     *
     * @param string $calAddress
     * @return string
     * @throws InvalidArgumentException
     * @since  2.39 - 2021-06-17
     */
    public static function prepSentBy( string $calAddress ) : string
    {
        $calAddress = self::conformCalAddress(
            trim( $calAddress, StringFactory::$QQ ),
            true
        );
        self::assertCalAddress( $calAddress );
        return $calAddress;
    }
}
