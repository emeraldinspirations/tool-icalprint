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
     * Escape characters in supplied string as needed for Value
     *
     * Function will escape `\` as `\\` and cr/lf as `\n`
     *
     * @param string $Value The string to escape
     *
     * @return string
     */
    static function escapeString(string $Value) : string
    {

        $Filter = [
            '\\'   => '\\\\',
            "\r\n" => '\\n',
            "\n"   => '\\n',
            "\r"   => '\\n',
        ];

        return str_replace(
            array_keys($Filter),
            array_values($Filter),
            $Value
        );
    }

    /**
     * Unescape characters in supplied string as needed for Value
     *
     * Function will unescape `\\` as `\` and `n` as line feed
     *
     * There is an unusual cercumstance where the original string was "\n".
     * This would then be parsed to "\\n" but when unparsing using str_replace
     * the \n would be converted to either backslash-linefeed or simply
     * linefeed.  Threfore it is neccessary to extract all the double
     * backslashes (i.e. escaped backslashes) before looking for the escaped
     * linebreaks.
     *
     * @param string $Value The string to unescape
     *
     * @return string
     */
    static function unescapeString(string $Value) : string
    {


        // Remove escaped backslashes by using them as a delimiter for an array
        $Exploded = explode('\\\\', $Value);

        // Iterate through the array, unescape the newline values
        foreach ($Exploded as &$Segment) {
            $Segment = str_replace(
                '\\n',
                "\n",
                $Segment
            );
        }

        // Put the array back together using backslashes
        return implode('\\', $Exploded);
    }

    /**
     * Fold the string at 75 octets, add hanging indent of 1 char
     *
     * @param string $Value The string to fold
     *
     * @return string
     */
    static function foldString(string $Value) : string
    {
        $SPLIT_LENGTH = 74;

        // All lines after the first are indented by 1 char.  In order to
        //  simply the code, extract the first character.  Then re-prepend it
        //  to the result so the wrap can be the same length throughout.
        $FirstChar = substr($Value, 0, 1);
        $Array = str_split(substr($Value, 1), $SPLIT_LENGTH);

        return $FirstChar . implode("\n ", $Array);
    }

    /**
     * Build new ContentLine value object
     *
     * @param string $Field The content line field name
     * @param string $Value The content line value
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
