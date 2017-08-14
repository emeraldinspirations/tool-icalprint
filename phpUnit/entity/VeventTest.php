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
 * @version   GIT: $Id$ In Development.
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
     * Verify constructor builds new object
     *
     * @return void
     */
    public function testFromContentLineArray()
    {

        $this->assertInstanceOf(
            Vevent::class,
            Vevent::fromContentLineArray([]),
            'Fails if function doesn\'t exist or returns non Vevent'
        );

    }


    /**
     * Verify returns array
     *
     * @return void
     */
    public function testToContentLineArray()
    {

        $Array  = [1,2,3];
        $Object = Vevent::fromContentLineArray($Array);
        $Actual = $Object->toContentLineArray();

        $this->assertTrue(
            is_array($Actual = $Object->toContentLineArray()),
            'Fails if function doesn\'t exist or returns non array'
        );

        /* Due to the [Robustness_principle][1]
         *
         * > Be conservative in what you send, be liberal in what you accept
         *
         * it is possible that the returned array may be in a different order,
         * or may contain additional values not supplied.
         *
         * [1][https://en.wikipedia.org/wiki/Robustness_principle]
         */

        $TestActual = $Actual;
        shuffle($TestActual);

        foreach ($Array as $Expected) {
            foreach ($TestActual as $Key => $Value) {
                if (gettype($Value) != gettype($Expected)) {
                    // Types don't match
                } elseif ($Value == $Expected) {
                    unset($TestActual[$Key]);
                    continue 2;
                }
            }
            $this->fails('Fails if test value not retained');
        }

        // Asserts that all neccessary values are found
        $this->assertTrue(true);

    }

    /**
     * Verify sets / returns array of ContentLine value objects
     *
     * @return void
     */
    public function testUnrecognizedContentLines()
    {

        $NewContent = [
            $this->generateRandomContentLine(),
            $this->generateRandomContentLine(),
            $this->generateRandomContentLine(),
        ];

        $Object = Vevent::fromContentLineArray($NewContent);

        $Actual = $Object->getUnrecognizedContentLines();

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

        $Cloned = clone $Object;
        $ClonedActual = $Cloned->getUnrecognizedContentLines();

        $this->assertNotSame(
            $Object,
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

    /**
     * Verify function returns array containing 1 or 0 elements
     *
     * @return void
     */
    public function testOptionalContentLine()
    {
        $RandomContentLine = $this->generateRandomContentLine();

        $this->assertEquals(
            [],
            Vevent::optionalContentLine(
                $RandomContentLine->getField(),
                null
            ),
            'Fails if function doesn\'t exist, or returns non empty array'
        );

        /* Due to the [Robustness_principle][1]
         *
         * > Be conservative in what you send, be liberal in what you accept
         *
         * it is possible that the supplied value may be an empty string.  This
         * is ommitted in the output
         *
         * [1][https://en.wikipedia.org/wiki/Robustness_principle]
         */

        $this->assertEquals(
            [],
            Vevent::optionalContentLine(
                $RandomContentLine->getField(),
                ''
            ),
            'Fails if function returns Content Line with empty string for a'
            . ' value'
        );

        $this->assertEquals(
            [$RandomContentLine],
            Vevent::optionalContentLine(
                $RandomContentLine->getField(),
                $RandomContentLine->getValue()
            ),
            'Fails if function returns empty array instead of Content Line'
        );

    }

    /**
     * Verify Description parsed, returned, and re-encoded
     *
     * @return void
     */
    public function testGetDescription()
    {

        $this->genericParserTest(
            [
                'Field'           => 'DESCRIPTION',
                'ValueSerialized' => microtime(),
                'GetFunction'     => 'getDescription',
                'IsType'          => 'is_string',
            ]
        );

    }

    /**
     * Verify Location parsed, returned, and re-encoded
     *
     * @return void
     */
    public function testGetLocation()
    {

        $this->genericParserTest(
            [
                'Field'           => 'LOCATION',
                'ValueSerialized' => microtime(),
                'GetFunction'     => 'getLocation',
                'IsType'          => 'is_string',
            ]
        );

    }

    /**
     * Verify Summary parsed, returned, and re-encoded
     *
     * @return void
     */
    public function testGetSummary()
    {

        $this->genericParserTest(
            [
                'Field'           => 'SUMMARY',
                'ValueSerialized' => microtime(),
                'GetFunction'     => 'getSummary',
                'IsType'          => 'is_string',
            ]
        );

    }

    /**
     * Test that ContentLine parses, returned, and re-encoded
     *
     * @param array $Params The paramerters required
     *        string   $Field
     *        string   $ValueSerialized
     *        string   $GetFunction
     *        callable $IsType
     *
     * @return void
     */
    protected function genericParserTest(array $Params)
    {

        extract($Params);

        $LineInital  = new ContentLine($Field, $ValueSerialized);

        $EmptyEntity = Vevent::fromContentLineArray([]);

        $this->assertTrue(
            $IsType($EmptyEntity->$GetFunction()),
            'Fails if GET function for ' . $Field
            . ' doesn\'t exist, or returns wrong type on empty'
        );

        $Entity     = Vevent::fromContentLineArray([$LineInital]);

        $this->assertTrue(
            $IsType($Entity->$GetFunction()),
            'Fails if GET function for ' . $Field
            . ' returns wrong type on non-empty'
        );

        // TODO: Replace with $IsEqual($ValueObject, $Object->$GetFunction())
        $this->assertEquals(
            $ValueSerialized,
            $Entity->$GetFunction(),
            'Fails if ' . $Field . ' not parsed correctly, or not returned '
            . 'from GET function'
        );

        while (true) {

            foreach ($Entity->toContentLineArray() as $LinePost) {

                if ($LinePost->getField() == $Field
                    && $LinePost->getValue() == $ValueSerialized
                ) {
                    // Test passes
                    $this->assertTrue(true);
                    break 2;
                }

            }

            $this->fails(
                'Fails if unable to locate content line for ' . $Field
                . ' in toContentLineArray array'
            );
            break;

        }
    }

}
