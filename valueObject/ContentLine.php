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
        $Array = self::splitMultibyteString(substr($Value, 1), $SPLIT_LENGTH);

        return $FirstChar . implode("\n ", $Array);
    }

    /**
     * Unfold a folded string, remove hanging indent
     *
     * @param string $Value The string to be unfolded
     *
     * @see self::foldString
     * @see http://www.rexegg.com/regex-quickstart.html
     *
     * @return string
     */
    static function unfoldString(string $Value) : string
    {

        /* Regular Expression Key
         * / ... /  = The contents of the expression
         * [\r|\n]+ = At least 1 newline charictar (CR and/or LF)
         * [\h]?    = 1 or less whitespace characters (ex: space / tab)
         */
        return preg_replace('/[\r|\n]+[\h]?/', '', $Value);
    }

    /**
     * Split string including multibyte chars per octet preserving chars
     *
     * When splitting a string using `str_split` that may include multibyte
     * characters there is the possibility that these multibyte charictars
     * might be split in the middle of the character.  This procedure splits as
     * close to the length specified as possible while preserving the multibyte
     * characters.
     *
     * This function will prefer to shorten the length as neccessary to
     * preserve each multibyte charictar.  If a single multibyte character
     * exceeds the requested length, it is returned by itself.
     *
     * @param string $MultibyteString The string to split
     * @param int    $Octets          (Optional) The goal number of octets
     *                                (minimum of 1 octet)
     *
     * @throws \InvalidArgumentException If value of Octets passed is < 1
     *
     * @see http://php.net/manual/en/function.str-split.php#113274
     * @see https://gist.github.com/hugowetterberg/81747
     *
     * @return array
     */
    static function splitMultibyteString(
        string $MultibyteString,
        int $Octets = 1
    ) : array {

        // Throw exception if length less than 1 is passed
        if ($Octets < 1) {
            throw new \InvalidArgumentException(
                'Length must be 1 or greater',
                1502275279
            );
        }

        // Split into array with each unicode char as it's own element
        $MultibyteArray = preg_split(
            '~~u',
            $MultibyteString,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $Return = [];

        while (count($MultibyteArray)) {

            // Remove the number of multibyte charictars specified
            $Splice = array_splice($MultibyteArray, 0, $Octets);

            while (
                // Verify the length in octets
                strlen(
                    $ImplodedString = implode('', $Splice)
                ) > $Octets
            ) {

                // If there is only one multibyte character, keep it
                //  (otherwise infinite loop possible)
                if (count($Splice) == 1) {
                    break;
                }

                // If too long, put one multibyte character back
                array_unshift($MultibyteArray, array_pop($Splice));
            }

            // If correct length, append to the return array
            $Return[] = $ImplodedString;
        }

        // Return the array
        return $Return;
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

    /**
     * Return escaped and folded Field / Value string
     *
     * @see self::__toString
     *
     * @return string
     */
    public function toString() : string
    {
        return self::foldString(
            self::escapeString(
                $this->Field . ':' . $this->Value
            )
        );
    }

    /**
     * Return escaped and folded Field / Value string
     *
     * @see self::toString Alias for
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->toString();
    }

}
