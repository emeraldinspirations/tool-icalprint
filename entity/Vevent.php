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
    protected $Description = '';

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
        return $this->Description;
    }

    /**
     * Return array of content lines, including unrecognized lines
     *
     * @return array Note: May contain non ContentLine value objects
     */
    public function toContentLineArray()
    {
        return array_merge(
            [],
            ...[
                [ new ContentLine('DESCRIPTION', $this->Description)],
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
