<?php

/**
 * Container unit tests for Content Line value object
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

namespace emeraldinspirations\tool\iCalPrint\valueObject;

/**
 * Unit tests for Content Line value object
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @version   GIT: $Id: f627306671268e7a24c3809def44b16abd93065a $ In Development.
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */
class ContentLineTest extends \PHPUnit_Framework_TestCase
{

    protected $Field;
    protected $Value;
    protected $Object;

    /**
     * Run before each test
     *
     * @return void
     */
    public function setUp()
    {

        $this->Object = new ContentLine(
            $this->Field = microtime(),
            $this->Value = microtime()
        );

    }

    /**
     * Verify object is constructable
     *
     * @return void
     */
    public function testConstruct()
    {

        $this->assertInstanceOf(
            ContentLine::class,
            $this->Object,
            'Fails if object doesn\'t exist, or fails durring construction'
        );

    }

    /**
     * Verify function exists & returns correct value
     *
     * @return void
     */
    public function testGetField()
    {

        $this->assertTrue(
            is_string(
                $this->Object->getField()
            ),
            'Fails if function doesn\'t exist, returns non-string'
        );

        $this->assertEquals(
            $this->Field,
            $this->Object->getField(),
            'Fails if value not retained'
        );

    }

    /**
     * Verify function exists & returns correct value
     *
     * @return void
     */
    public function testGetValue()
    {

        $this->assertTrue(
            is_string($this->Object->getValue()),
            'Fails if function doesn\'t exist, returns non-string'
        );

        $this->assertEquals(
            $this->Value,
            $this->Object->getValue(),
            'Fails if value not retained'
        );

    }

}
