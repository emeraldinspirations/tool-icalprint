<?php

/**
 * Container for Vevent entity
 *
 * PHP Version 7
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */

namespace emeraldinspirations\tool\iCalPrint\entity;

use emeraldinspirations\tool\iCalPrint\valueObject\ContentLine;

/**
 * Vevent entity
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @version   GIT: $Id: f627306671268e7a24c3809def44b16abd93065a $ In Development.
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */
class Vevent
{

    protected $UnrecognizedContentLines = [];

    /**
     * Return content lines that are not recognized
     *
     * Ical allows content lines that are proprietary.  Rather than ignoring
     * these lines, or throwing an exception; these lines are stored in an
     * array.  This function returns these lines.
     *
     * Note: Not all UnrecognizedContentLines are necessarily ContentLine value
     * objects.
     *
     * @return array
     */
    public function getUnrecognizedContentLines()
    {
        return $this->UnrecognizedContentLines;
    }

    /**
     * Create new instance with new UnrecognizedContentLine array
     *
     * @param array $UnrecognizedContentLines New value
     *
     * @return self
     */
    public function withUnrecognizedContentLines(
        array $UnrecognizedContentLines
    ) : self {
        $Return = clone $this;
        $Return->UnrecognizedContentLines = $UnrecognizedContentLines;
        return $Return;
    }

    /**
     * Verify all specified properties are cloned as neccessary
     *
     * @return void
     */
    public function __clone()
    {

        // Create recersive function to clone properties
        $CloneArrayRecursively = function (
            array &$Array
        ) use (
            &$CloneArrayRecursively
        ) {
            foreach ($Array as &$Value) {
                if (is_array($Value)) {
                    $CloneArrayRecursively($Value);
                } elseif (is_object($Value)) {
                    $Value = clone $Value;
                }
                // If non-object and non-array then no cloning is neccessary
            }
        };

        // Create an array of references to properties to clone
        $PropertiesToByCloned = [
            &$this->UnrecognizedContentLines,
        ];

        // Clone properties referenced in array
        $CloneArrayRecursively($PropertiesToByCloned);

    }

}
