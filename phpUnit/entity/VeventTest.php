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
     * Verify sets / returns array of ContentLine value objects
     *
     * @return void
     */
    public function testUnrecognizedContentLines()
    {

        $Object = new Vevent();

        $NewContent = [
            $this->generateRandomContentLine(),
            $this->generateRandomContentLine(),
            $this->generateRandomContentLine(),
        ];

        $NewInstance = $Object
            ->withUnrecognizedContentLines($NewContent);

        $this->assertNotSame(
            $Object,
            $NewInstance,
            'Fails if object immutability not maintained'
        );

        $Actual = $NewInstance->getUnrecognizedContentLines();

        $this->assertTrue(
            is_array($Actual),
            'Fails if returns non-array'
        );

        $this->assertEquals(
            $NewContent,
            $Actual,
            'Fails if stored data not matching passed value'
        );

        foreach ($Actual as $Key => $Value) {
            $this->assertSame(
                $NewContent[$Key],
                $Value,
                'Fails if stored data not matching passed value'
            );
        }

        $Cloned = clone $NewInstance;
        $ClonedActual = $Cloned->getUnrecognizedContentLines();

        $this->assertNotSame(
            $NewInstance,
            $Cloned,
            'Fails if stored data not cloned'
        );

        foreach ($ClonedActual as $Key => $Value) {
            $this->assertNotSame(
                $NewContent[$Key],
                $Value,
                'Fails if stored data not cloned'
            );
        }

    }

    /**
     * Return valid random ContentLine
     *
     * @return string
     */
    protected function generateRandomContentLine() : ContentLine
    {
        return new ContentLine(
            str_replace([' ','.'], ['-','a'], microtime()),
            microtime()
        );
    }

}
