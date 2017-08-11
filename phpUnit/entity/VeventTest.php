<?php

/**
 * Container for unit tests for Vevent entity
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
 * Unit tests for Vevent entity
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @version   GIT: $Id: f627306671268e7a24c3809def44b16abd93065a $ In Development.
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */
class VeventTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Verify constructable
     *
     * @return void
     */
    public function testConstruct()
    {

        $this->assertInstanceOf(
            Vevent::class,
            new Vevent(),
            'Fails if class doesn\'t exist'
        );

    }

    /**
     * Verify returns array of ContentLine value objects
     *
     * @return void
     */
    public function testGetUnrecognizedContentLines()
    {

        $UnrecognizedContentLines
            = (new Vevent())->getUnrecognizedContentLines();
        // Fails if function dosn't exist

        $this->assertTrue(
            is_array($UnrecognizedContentLines),
            'Fails if returns non-array'
        );

        foreach ($UnrecognizedContentLines as $Line) {
            $this->assertInstanceOf(
                ContentLine::class,
                $Line,
                'Fails if array contains non ConentLine value object'
            );
        }

    }

}
