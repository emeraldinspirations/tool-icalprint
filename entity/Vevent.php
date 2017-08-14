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
use emeraldinspirations\library\objectDesignPattern\immutable\ImmutableTrait;

/**
 * Vevent entity
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @version   GIT: $Id$ In Development.
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */
class Vevent
{
    use ImmutableTrait;

    protected $UnrecognizedContentLines = [];
    protected $Description;
    protected $Summary;
    protected $Location;

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
     * Return event description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return $this->Description ?? '';
    }

    /**
     * Return event summary
     *
     * @return string
     */
    public function getSummary() : string
    {
        return $this->Summary ?? '';
    }

    /**
     * Return event location
     *
     * @return string
     */
    public function getLocation() : string
    {
        return $this->Location ?? '';
    }

    /**
     * Return array containing ContentLine value object if value not null
     *
     * Per the DRY principle, the oporation of: creating a single-element array
     * if the specified field has a value, or a zero-element array if the field
     * is empty / zero-length string; has been refactored to it's own procedure.
     *
     * @param string $Field Filed of the ContentLine object
     * @param string $Value (Optional) Value of the ContentLine object
     *
     * @see self::toContentLineArray Calling function
     *
     * @return array
     */
    static function optionalContentLine(
        string $Field,
        string $Value = null
    ) : array {
        if (! isset($Value)) {
            return [];
        }

        if ($Value == '') {
            return [];
        }

        return [new ContentLine($Field, $Value)];
    }

    /**
     * Return array of content lines, including unrecognized lines
     *
     * WHEREAS some fields may have multiple values, AND
     * WHEREAS the easiest way to merge arrays of these values is to use the
     *         built-in array_merge, AND
     * WHEREAS it is easy to encapsulate individual values in single element
     *         arrays, AND
     * WHEREAS zero-length arrays are ignored by array_merge, AND
     * WHEREAS all fields are optional, AND
     * WHEREAS arrays in PHP can have commas at the end of the final values
     *         which allows easy re-ordering and single-line revisions (vs.
     *         multiple lines when the last element MUST omit it's comment), AND
     * WHEREAS the new PHP 7 scalar operator (`...`) allows converting an array
     *         to the parameters of a function (including array_merge);
     * THEREFORE for code simplicity, the array is built by merging arrays of
     *           each field's individual array of values,
     *
     * @return array Note: May contain non ContentLine value objects
     */
    public function toContentLineArray()
    {

        return array_merge(
            [],
            ...[
                self::optionalContentLine('DESCRIPTION', $this->Description),
                self::optionalContentLine('LOCATION', $this->Location),
                self::optionalContentLine('SUMMARY', $this->Summary),
                $this->UnrecognizedContentLines,
            ]
        );
    }

    /**
     * Build Vevent from array of ContentLine value objects
     *
     * @param array $ContentLineArray Array to be parsed
     *
     * @return self
     */
    static function fromContentLineArray(array $ContentLineArray) : self
    {

        $Return = new self();

        $Parser = [
            'DESCRIPTION' => function (string $Value) use ($Return) {
                $Return->Description = $Value;
            },
            'SUMMARY' => function (string $Value) use ($Return) {
                $Return->Summary = $Value;
            },
            'LOCATION' => function (string $Value) use ($Return) {
                $Return->Location = $Value;
            },
        ];

        foreach ($ContentLineArray as $ContentLine) {

            if (! $ContentLine instanceof ContentLine) {
                // Don't attempt to parse
            } elseif (isset($Parser[$ContentLine->getField()])) {
                $Parser[$ContentLine->getField()]($ContentLine->getValue());
                continue;
            }

            $Return->UnrecognizedContentLines[] = $ContentLine;

        }

        return $Return;
    }

    /**
     * Verify all specified properties are cloned as neccessary
     *
     * @return void
     */
    public function __clone()
    {

        // Create an array of references to properties to clone
        $PropertiesToByCloned = [
            &$this->UnrecognizedContentLines,
        ];

        ImmutableTrait::CloneArrayRecursively($PropertiesToByCloned);

    }

}
