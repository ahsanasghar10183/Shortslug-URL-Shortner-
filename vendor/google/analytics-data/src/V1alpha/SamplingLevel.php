<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/analytics/data/v1alpha/data.proto

namespace Google\Analytics\Data\V1alpha;

use UnexpectedValueException;

/**
 * Categories of sampling levels for the requests.
 *
 * Protobuf type <code>google.analytics.data.v1alpha.SamplingLevel</code>
 */
class SamplingLevel
{
    /**
     * Unspecified type.
     *
     * Generated from protobuf enum <code>SAMPLING_LEVEL_UNSPECIFIED = 0;</code>
     */
    const SAMPLING_LEVEL_UNSPECIFIED = 0;
    /**
     * Applies a sampling level of 10 million to standard properties and
     * 100 million to Google Analytics 360 properties.
     *
     * Generated from protobuf enum <code>LOW = 1;</code>
     */
    const LOW = 1;
    /**
     * Exclusive to Google Analytics 360 properties with a sampling level of 1
     * billion.
     *
     * Generated from protobuf enum <code>MEDIUM = 2;</code>
     */
    const MEDIUM = 2;
    /**
     * Exclusive to Google Analytics 360 properties. Unsampled explorations are
     * more accurate and can reveal insights that aren't visible in standard
     * explorations. To learn more, see
     * https://support.google.com/analytics/answer/10896953.
     *
     * Generated from protobuf enum <code>UNSAMPLED = 3;</code>
     */
    const UNSAMPLED = 3;

    private static $valueToName = [
        self::SAMPLING_LEVEL_UNSPECIFIED => 'SAMPLING_LEVEL_UNSPECIFIED',
        self::LOW => 'LOW',
        self::MEDIUM => 'MEDIUM',
        self::UNSAMPLED => 'UNSAMPLED',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}
