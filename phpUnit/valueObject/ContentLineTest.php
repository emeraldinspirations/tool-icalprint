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

    /**
     * Verify function escapes the required values inside supplied string
     *
     * @return void
     */
    public function testEscapeString()
    {
        $Tests = [
            [
                'Supplied' => 'This is \\ a test',
                'Expected' => 'This is \\\\ a test',
                'Failure'  => 'Backslash not escaped',
            ],
            [
                'Supplied' => "This is \\\n a test",
                'Expected' => 'This is \\\\\\n a test',
                'Failure'  => 'Backslash-Linefeed not escaped',
            ],
            [
                'Supplied' => 'This is \\n a test',
                'Expected' => 'This is \\\\n a test',
                'Failure'  => 'Backslash-N not escaped',
            ],
            [
                'Supplied' => "This is \n a test",
                'Expected' => 'This is \\n a test',
                'Failure'  => 'Line Feed not escaped',
            ],
            [
                'Supplied' => "This is \r a test",
                'Expected' => 'This is \\n a test',
                'Failure'  => 'Carrage Return not escaped',
            ],
            [
                'Supplied' => "This is \r\n a test",
                'Expected' => 'This is \\n a test',
                'Failure'  => 'CRLF not escaped',
            ],
        ];

        $this->assertTrue(
            is_string(ContentLine::escapeString('')),
            'Fails if function doesn\'t exist, returns non-string'
        );

        foreach ($Tests as $Test) {
            $this->assertEquals(
                $Test['Expected'],
                ContentLine::escapeString($Test['Supplied']),
                'Fails if ' . $Test['Failure']
            );
        }

    }

    /**
     * Verify function un-escapes the required values inside supplied string
     *
     * @return void
     */
    public function testUnescapeString()
    {
        $Tests = [
            [
                'Supplied' => 'This is \\\\ a test',
                'Expected' => 'This is \\ a test',
                'Failure'  => 'Backslash not unescaped',
            ],
            [
                'Supplied' => 'This is \\\\n a test',
                'Expected' => 'This is \\n a test',
                'Failure'  => 'Backslash-N not unescaped',
            ],
            [
                'Supplied' => 'This is \\n a test',
                'Expected' => "This is \n a test",
                'Failure'  => 'Line Feed not unescaped',
            ],
        ];

        $this->assertTrue(
            is_string(ContentLine::unescapeString('')),
            'Fails if function doesn\'t exist, returns non-string'
        );

        foreach ($Tests as $Test) {
            $this->assertEquals(
                $Test['Expected'],
                ContentLine::unescapeString($Test['Supplied']),
                'Fails if ' . $Test['Failure']
            );
        }

    }

    /**
     * Verifty unwrapps string, removing hanging indent
     *
     * @return void
     */
    public function testUnfoldString()
    {
        $this->assertTrue(
            is_string(ContentLine::unfoldString('')),
            'Function doesn\'t exist, or returns wrong type'
        );

        $this->assertEquals(
            'This is an unfolded string',
            ContentLine::unfoldString(
                ''
                . 'This ' . "\n"        // Verify when space at end of line
                . 'is' . "\n"           // Verify when hanging indent omitted
                . '  an unf' . "\n"     // Verify when space at begining of line
                . 'olded st' . "\n"     // Verify when mid-word split
                . "\t" . 'ring'         // Verify when tab used for indent
            ),
            'Fails if string unfolded incorrectly'
        );
    }

    /**
     * Verify function wrapps string every 75 octets with 1 char hanging indent
     *
     * @return void
     */
    public function testFoldString()
    {
        $Supplied = 'Lines of text SHOULD NOT be longer than 75 octets,'
            . ' excluding the line break. Long content lines SHOULD be split'
            . ' into a multiple line representations using a line "folding"'
            . ' technique. That is, a long line can be split between any two'
            . ' characters by inserting a CRLF immediately followed by a single'
            . ' linear white space character ...';

        $Expected = ''
            . 'Lines of text SHOULD NOT be longer than 75 octets, excluding the'
            . ' line break' . "\n"
            . ' . Long content lines SHOULD be split into a multiple line'
            . ' representations ' . "\n"
            . ' using a line "folding" technique. That is, a long line can be'
            . ' split betwee' . "\n"
            . ' n any two characters by inserting a CRLF immediately followed'
            . ' by a single ' . "\n"
            . ' linear white space character ...';

        $this->assertEquals(
            $Expected,
            ContentLine::foldString($Supplied),
            'Fails if function dosn\'t exist, or folds string incorrectly'
        );

        $this->assertEquals(
            '',
            ContentLine::foldString(''),
            'Fails if function folds 0 char string incorrectly'
        );

        $this->assertEquals(
            '1',
            ContentLine::foldString('1'),
            'Fails if function folds 1 char string incorrectly'
        );

        $this->assertEquals(
            str_repeat('1', 75),
            ContentLine::foldString(str_repeat('1', 75)),
            'Fails if folds string ending in multi-char incorrectly'
        );

        $this->assertEquals(
            str_repeat('1', 74)."\n 🍱",
            ContentLine::foldString(str_repeat('1', 74).'🍱'),
            'Fails if folds string ending in multi-char incorrectly'
        );

        // The first charictar of a ContentLine will never be a multi-byte char
        // as the title always comes first

    }

    /**
     * Verify function splits string without splitting multibyte characters
     *
     * @return void
     */
    public function testSplitMultibyteString()
    {
        $Tests = [
            [
                'String' => 'Test🍱',
                'Length' => 8,
                'Expected' => ['Test🍱'],
                'Failure'  => 'string split unnecessarily',
            ],
            [
                'String' => 'Test🍱',
                'Length' => 7,
                'Expected' => ['Test', '🍱'],
                'Failure'  => 'string split incorrectly',
            ],
            [
                'String' => '🍱',
                'Length' => 4,
                'Expected' => ['🍱'],
                'Failure'  => 'string split unnecessarily',
            ],
            [
                'String' => '🍱a🍱bb🍱ccc🍱dddd🍱',
                'Length' => 3,
                'Expected' => [
                    '🍱', 'a', '🍱', 'bb', '🍱', 'ccc', '🍱', 'ddd', 'd', '🍱'
                ],
                'Failure'  => 'string split incorrectly',
            ],
            [
                'String' => '',
                'Length' => 3,
                'Expected' => [],
                'Failure'  => 'string split incorrectly',
            ],
        ];

        foreach ($Tests as $Test) {
            extract($Test);
            $this->assertEquals(
                $Expected,
                ContentLine::splitMultibyteString($String, $Length),
                'Fails if '.$Failure
            );
        }

    }

    /**
     * Verify function throws exception when length < 1 is passed
     *
     * @expectedException     \InvalidArgumentException
     * @expectedExceptionCode 1502275279
     *
     * @return void
     */
    public function testSplitMultibyteStringLengthTooSmall()
    {
        ContentLine::splitMultibyteString('', 0);
    }
}
