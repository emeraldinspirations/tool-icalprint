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

}