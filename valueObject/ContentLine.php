<?php

/**
 * Container for ContentLine value object
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
 * Content Line value object
 *
 * > The iCalendar object is organized into individual lines of text,
 * > called content lines. Content lines are delimited by a line break,
 * > which is a CRLF sequence (US-ASCII decimal 13, followed by US-ASCII
 * > decimal 10).
 * >
 * > Lines of text SHOULD NOT be longer than 75 octets, excluding the line
 * > break. Long content lines SHOULD be split into a multiple line
 * > representations using a line "folding" technique. That is, a long
 * > line can be split between any two characters by inserting a CRLF
 * > immediately followed by a single linear white space character ...
 * >
 * > -- <cite>[rfc2445][1] (Nov 1998) sec. 4.1</cite>
 *
 * [1]:https://www.ietf.org/rfc/rfc2445.txt
 *
 * @category  Tool
 * @package   ICalPrint
 * @author    Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @copyright 2017 Matthew "Juniper" Barlett <emeraldinspirations@gmail.com>
 * @license   MIT ../LICENSE.md
 * @version   GIT: $Id: f627306671268e7a24c3809def44b16abd93065a $ In Development.
 * @link      https://github.com/emeraldinspirations/tool-icalprint
 */
class ContentLine
{

    protected $Field;
    protected $Value;

    /**
     * Return the field name of the content line
     *
     * @return string
     */
    public function getField() : string
    {
        return $this->Field;
    }

    /**
     * Return the value of the content line
     *
     * @return string
     */
    public function getValue() : string
    {
        return $this->Value;
    }

    /**
     * Build new ContentLine value object
     *
     * @param string $Field The content line field name
     *
     * @return void
     */
    public function __construct(
        string $Field,
        string $Value
    ) {
        $this->Field = $Field;
        $this->Value = $Value;
    }

}
