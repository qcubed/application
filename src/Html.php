<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed;

use QCubed\Exception\Caller;
use QCubed\Project\Application;

/**
 * An abstract utility class to handle Html tag rendering, as well as utilities to render
 * pieces of HTML and CSS code.  All methods are static.
 * @was QHtml
 */
abstract class Html {

    const IS_VOID = true;

    // Common URL Protocols
    const HTTP = 'http://';
    const HTTPS = 'https://';
    const FTP = 'ftp://';
    const SFTP = 'sftp://';
    const SMB = 'smb://';

    // Font Families
    const FONT_FAMILY_ARIAL = 'Arial, Helvetica, sans-serif';
    const FONT_FAMILY_HELVETICA = 'Helvetica, Arial, sans-serif';
    const FONT_FAMILY_TAHOMA = 'Tahoma, Arial, Helvetica, sans-serif';
    const FONT_FAMILY_TREBUCHET_MS = "'Trebuchet MS', Arial, Helvetica, sans-serif";
    const FONT_FAMILY_VERDANA = 'Verdana, Arial, Helvetica, sans-serif';
    const FONT_FAMILY_TIMES_NEW_ROMAN = "'Times New Roman', Times, serif";
    const FONT_FAMILY_GEORGIA = "Georgia, 'Times New Roman', Times, serif";
    const FONT_FAMILY_LUCIDA_CONSOLE = "'Lucida Console', 'Courier New', Courier, monospaced";
    const FONT_FAMILY_COURIER_NEW = "'Courier New', Courier, monospaced";
    const FONT_FAMILY_COURIER = 'Courier, monospaced';

    const TEXT_ALIGN_LEFT = "left";
    const TEXT_ALIGN_RIGHT = "right";

    // type property for ordered lists
    const OL_NUMBERS = '1';
    const OL_UPPERCASE_LETTERS = 'A';
    const OL_LOWERCASE_LETTERS = 'a';
    const OL_UPPERCASE_ROMAN = 'I';
    const OL_LOWERCASE_ROMAN = 'i';

    // list-style-type property for unordered list
    const UL_DISC = 'disc';
    const UL_CIRCLE = 'circle';
    const UL_SQUARE = 'square';
    const UL_NONE = 'none';

    /**
     * Contains/Defines Overflow CSS Styles to be used on QControls
     */
    const OVERFLOW_NOT_SET = 'NotSet';
    const OVERFLOW_AUTO = 'auto';
    const OVERFLOW_HIDDEN = 'hidden';
    const OVERFLOW_SCROLL = 'scroll';
    const OVERFLOW_VISIBLE = 'visible';

    /**
     * This faux constructor method throws a caller exception.
     * The Css object should never be instantiated, and this constructor
     * override simply guarantees it.
     *
     * @throws Caller
     */
    public final function __construct() {
        throw new Caller('\\QCubed\\Html should never be instantiated.  All methods and variables are publicly statically accessible.');
    }

    /**
     * Renders an html tag with the given attributes and inner html.
     *
     * If the innerHtml is detected as being wrapped in an html tag of some sort, it will attempt to format the code so that
     * it has a structured view in a browser, with the inner html indented and on a new line in between the tags. You
     * can turn this off by setting __MINIMIZE__, or by passing in true to $blnNoSpace.
     *
     * There area a few special cases to consider:
     * - Void elements will not be formatted to avoid adding unnecessary white space since these are generally
     *   inline elements
     * - Non-void elements always use internal newlines, even in __MINIMIZE__ mode. This is to prevent different behavior
     *   from appearing in __MINIMIZE__ mode on inline elements, because inline elements with internal space will render with space to separate
     *   from surrounding elements. Usually, this is not an issue, but in the special situations where you really need inline
     *   elements to be right up against its siblings, set $blnNoSpace to true.
     *
     *
     * @param string 		$strTag				The tag name
     * @param null|mixed 	$mixAttributes 		String of attribute values or array of attribute values.
     * @param null|string 	$strInnerHtml 		The html to print between the opening and closing tags. This will NOT be escaped.
     * @param boolean		$blnIsVoidElement 	True to print as a tag with no closing tag.
     * @param boolean		$blnNoSpace		 	Renders with no white-space. Useful in special inline situations.
     * @return string						The rendered html tag
     */
    public static function renderTag($strTag, $mixAttributes, $strInnerHtml = null, $blnIsVoidElement = false, $blnNoSpace = false) {
        assert ('!empty($strTag)');
        $strToReturn = '<' . $strTag;
        if ($mixAttributes) {
            if (is_string($mixAttributes)) {
                $strToReturn .=  ' ' . trim($mixAttributes);
            } else {
                // assume array
                $strToReturn .=  self::renderHtmlAttributes($mixAttributes);
            }
        };
        if ($blnIsVoidElement) {
            $strToReturn .= ' />'; // conforms to both XHTML and HTML5 for both normal and foreign elements
        }
        elseif ($blnNoSpace || substr (trim($strInnerHtml), 0, 1) !== '<') {
            $strToReturn .= '>' . $strInnerHtml . '</' . $strTag . '>';
        }
        else {
            // the hardcoded newlines below are important to prevent different drawing behavior in MINIMIZE mode
            $strToReturn .= '>' . "\n" . _indent(trim($strInnerHtml)) .  "\n" . '</' . $strTag . '>' . _nl();
        }
        return $strToReturn;
    }

    /**
     * Renders an input element with a label tag. Uses separate styling for the label and the input object.
     * In particular, this gives you the option of wrapping the input with a label (which is what Bootstrap
     * expects on checkboxes) or putting the label next to the object (which is what jQueryUI expects).
     *
     * Note that if you are not setting $blnWrapped, it is up to you to insert the "for" attribute into
     * the label attributes.
     *
     * @param $strLabel
     * @param $blnTextLeft
     * @param $strAttributes
     * @param $strLabelAttributes
     * @param $blnWrapped
     * @return string
     */
    public static function renderLabeledInput($strLabel, $blnTextLeft, $strAttributes, $strLabelAttributes, $blnWrapped) {
        $strHtml = trim(self::renderTag('input', $strAttributes, null, true));

        if ($blnWrapped) {
            if ($blnTextLeft) {
                $strCombined = $strLabel .  $strHtml;
            } else {
                $strCombined = $strHtml . $strLabel;
            }

            $strHtml = self::renderTag('label', $strLabelAttributes, $strCombined);
        }
        else {
            $strLabel = trim(self::renderTag('label', $strLabelAttributes, $strLabel));
            if ($blnTextLeft) {
                $strHtml = $strLabel .  $strHtml;
            } else {
                $strHtml = $strHtml . $strLabel;
            }
        }
        return $strHtml;
    }

    /**
     * Returns the formatted value of type <length>.
     * See http://www.w3.org/TR/CSS1/#units for more info.
     * @param 	string 	$strValue 	The number or string to be formatted to the <length> compatible value.
     * @return 	string 	the formatted value of type <length>.
     */
    public final static function formatLength($strValue) {
        if (is_numeric($strValue)) {
            if (0 == $strValue) {
                if (!is_int($strValue)) {
                    $fltValue = floatval($strValue);
                    return sprintf('%s', $fltValue);
                } else {
                    return sprintf('%s', $strValue);
                }
            } else {
                if (!is_int($strValue)) {
                    $fltValue = floatval($strValue);
                    return sprintf('%spx', $fltValue);
                } else {
                    return sprintf('%spx', $strValue);
                }
            }
        } else {
            return sprintf('%s', $strValue);
        }
    }

    /**
     * Sets the given length string to the new length value.
     * If the new length is preceded by a math operator (+-/*), then arithmetic is performed on the previous
     * value. Returns true if the length changed.
     * @param 	string 	$strOldLength
     * @param 	string 	$newLength
     * @return 	bool	true if the length was changed
     */
    public static function setLength(&$strOldLength, $newLength) {
        if ($newLength && preg_match('#^(\+|\-|/|\*)(.+)$#',$newLength, $matches)) { // do math operation
            $strOperator = $matches[1];
            $newValue = $matches[2];
            assert (is_numeric($newValue));
            if (!$strOldLength) {
                $oldValue  = 0;
                $oldUnits = 'px';
            } else {
                $oldValue = filter_var ($strOldLength, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if (preg_match('/([A-Z]+|[a-z]+|%)$/', $strOldLength, $matches)) {
                    $oldUnits = $matches[1];
                } else {
                    $oldUnits = 'px';
                }
            }

            switch ($strOperator) {
                case '+':
                    $newValue = $oldValue + $newValue;
                    break;

                case '-':
                    $newValue = $oldValue - $newValue;
                    break;

                case '/':
                    $newValue = $oldValue / $newValue;
                    break;

                case '*':
                    $newValue = $oldValue * $newValue;
                    break;
            }
            if ($newValue != $oldValue) {
                $strOldLength = $newValue . $oldUnits; // update returned value
                return true;
            } else {
                return false; // nothing changed
            }
        } else { // no math operation
            $newLength = self::formatLength($newLength);

            if ($strOldLength !== $newLength) {
                $strOldLength = $newLength;
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Helper to add a class or classes to a pre-existing space-separated list of classes. Checks to make sure the
     * class isn't already in the list. Returns true to indicate a change in the list.
     *
     * @param string 	$strClassList	Current list of classes separated by a space
     * @param string 	$strNewClasses 	New class to add. Could be a list separated by spaces.
     * @return bool 	true if the class list was changed.
     */
    public static function addClass(&$strClassList, $strNewClasses) {
        $strNewClasses = trim($strNewClasses);
        if (empty($strNewClasses)) return false;

        if (empty ($strClassList)) {
            $strCurrentClasses = array();
        }
        else {
            $strCurrentClasses = explode(' ', $strClassList);
        }

        $blnChanged = false;
        foreach (explode (' ', $strNewClasses) as $strClass) {
            if ($strClass && !in_array ($strClass, $strCurrentClasses)) {
                $blnChanged = true;
                if (!empty ($strClassList)) {
                    $strClassList .= ' ';
                }
                $strClassList .= $strClass;
            }
        }

        return $blnChanged;
    }

    /**
     * Helper to remove a class or classes from a list of space-separated classes.
     *
     * @param string $strClassList        class list string to search
     * @param string $strCssNamesToRemove space separated list of names to remove
     *
     * @return bool    true if the class list was changed
     */
    public static function removeClass(&$strClassList, $strCssNamesToRemove) {
        $strNewCssClass = '';
        $blnRemoved = false;
        $strCssNamesToRemove = trim($strCssNamesToRemove);
        if (empty($strCssNamesToRemove)) return false;

        if (empty ($strClassList)) {
            $strCurrentClasses = array();
        }
        else {
            $strCurrentClasses = explode(' ', $strClassList);
        }
        $strRemoveArray = explode (' ', $strCssNamesToRemove);

        foreach ($strCurrentClasses as $strCssClass) {
            if ($strCssClass = trim($strCssClass)) {
                if (in_array($strCssClass, $strRemoveArray)) {
                    $blnRemoved = true;
                }
                else {
                    $strNewCssClass .= $strCssClass . ' ';
                }
            }
        }
        if ($blnRemoved) {
            $strClassList = trim($strNewCssClass);
        }
        return $blnRemoved;
    }

    /**
     * Many CSS frameworks use families of classes, which are built up from a base family name. For example,
     * Bootstrap uses 'col-lg-6' to represent a column that is 6 units wide on large screens and Foundation
     * uses 'large-6' to do the same thing. This utility removes classes that start with a particular prefix
     * to remove whatever sizing class was specified.
     *
     * @param  $strClassList
     * @param  $strPrefix
     * @return bool true if the class list changed
     */
    public static function removeClassesByPrefix(&$strClassList, $strPrefix) {
        $aRet = array();
        $blnChanged = false;
        if ($strClassList) foreach (explode (' ', $strClassList) as $strClass) {
            if (strpos($strClass, $strPrefix) !== 0) {
                $aRet[] = $strClass;
            }
            else {
                $blnChanged = true;
            }
        }
        $strClassList = implode (' ', $aRet);
        return $blnChanged;
    }

    /**
     * Render the given attribute array for html output. Escapes html entities enclosed in values. Uses
     * double-quotes to surround the value. Precedes the resulting text with a space character.
     *
     * @param array|null $attributes
     * @return string
     */
    public static function renderHtmlAttributes($attributes) {
        $strToReturn = '';
        if ($attributes) {
            foreach ($attributes as $strName=>$strValue) {
                if ($strValue === false) {
                    $strToReturn .= (' ' . $strName);
                } elseif (!is_null($strValue)) {
                    $strToReturn .= (' ' . $strName . '="' . htmlspecialchars($strValue, ENT_COMPAT | ENT_HTML5, __APPLICATION_ENCODING_TYPE__) . '"');
                }
            }
        }
        return $strToReturn;
    }


    /**
     * Render the given array as a css style string. It will NOT be escaped.
     *
     * @param array 	$styles		key/value array representing the styles.
     * @return string	a string suitable for including in a css 'style' property
     */
    public static function renderStyles($styles) {
        if (!$styles) return '';
        return implode('; ', array_map(
            function ($v, $k) { return $k . ':' . $v; },
            $styles,
            array_keys($styles))
        );
    }

    /**
     * Returns the given string formatted as an html comment that will go on its own line.
     * @param string 	$strText
     * @param bool 		$blnRemoveOnMinimize
     * @return string
     */
    public static function comment($strText, $blnRemoveOnMinimize = true) {
        if ($blnRemoveOnMinimize && Application::instance()->minimize()) {
            return '';
        }
        return  _nl() . '<!-- ' . $strText . ' -->' . _nl();

    }

    /**
     * Generate a URL from components. This URL can be used in the Application::tedirect function, or applied to
     * an anchor tag by setting the href attribute.
     *
     * You can also use this to modify a URL by passing a complete URL in the location. The URL will be modified by the parameters given.
     *
     * @param string $strLocation			absolute or relative path to resource, depending on your protocol. If not needed, enter an empty string. Can be a complete URL.
     * @param array|null $queryParams		key->value array of query parameters to add to the location.
     * @param string|null $strAnchor		anchor to add to the url
     * @param string|null $strScheme		protocol if specifying a resource outside of the current server (i.e. http)
     * @param string|null $strHost			server that the resource is on. Required if specifying a scheme.
     * @param string|null $strUser			user name if needed. Some protocols like mailto and ftp need this
     * @param string|null $strPassword		password if needed. Note that password is sent in the clear.
     * @param string|null $intPort			port if different from default
     * @return string
     */
    public static function makeUrl($strLocation, $queryParams = null, $strAnchor = null, $strScheme = null, $strHost = null, $strUser = null, $strPassword = null, $intPort = null) {
        // Decompose
        if ($strLocation) {
            $params = parse_url($strLocation);
        }

        if (!empty($strLocation) && isset($params['path'])) {
            $strUrl = $params['path'];
        } else {
            $strUrl = '';
        }

        if (isset($params['query'])) {
            parse_str($params['query'], $queryParams2);
            if ($queryParams) {
                $queryParams = array_merge($queryParams2, $queryParams);
            } else {
                $queryParams = $queryParams2;
            }
        }

        if (empty($strAnchor) && isset($params['fragment'])) {
            $strAnchor = $params['fragment'];
        }

        if (empty($strScheme) && isset($params['scheme'])) {
            $strScheme = $params['scheme'];
        }

        if (empty($strHost) && isset($params['host'])) {
            $strHost = $params['host'];
        }

        if (empty($strUser) && isset($params['user'])) {
            $strUser = $params['user'];
        }
        if (empty($strPassword) && isset($params['pass'])) {
            $strPassword = $params['pass'];
        }
        if (empty($intPort) && isset($params['port'])) {
            $intPort = $params['port'];
        }

        if ($queryParams)  {
            $strUrl .= '?' . http_build_query($queryParams);
        }
        if ($strAnchor) {
            $strUrl .= '#' . urlencode($strAnchor);
        }

        // More complex URLs. Once you specify protocol, you will need to specify the server too.
        if ($strScheme) {
            assert(!empty($strHost));

            // We do not do any checking at this point since URLs can be complex. It is up to you to build a correct URL.
            // If you use a protocol that expects an absolute path, you must start with a slash (http), or a relative path (mailto), leave the slash off.

            // Build server portion.
            if ($intPort) {
                $strHost .= ':' . $intPort;
            }
            if ($strUser) {
                $strUser = rawurlencode($strUser);
                if ($strPassword) {
                    $strUser = $strUser . ':' . rawurlencode($strPassword);
                }
                $strHost = $strUser . '@' . $strHost;
            }
            $strUrl = $strScheme . $strHost . $strUrl;
        }
        return $strUrl;
    }

    /**
     * Returns a MailTo url.
     *
     * @param string $strUser
     * @param string| null $strServer optional server. If missing, will assume server and "@" are already in strUser
     * @param array|null $queryParams
     * @param string|null $strName Optional name to associate with the email address. Some email clients will show this instead of the address.
     * @return string	The mailto url.
     */
    public static function mailToUrl($strUser, $strServer = null, $queryParams = null, $strName = null) {
        if ($strServer) {
            $strUrl = $strUser . '@' . $strServer;
        } else {
            $strUrl = $strUser;
        }
        if ($strName) {
            $strUrl = '"' . $strName . '"' . '<' . $strUrl . '>';
        }
        $strUrl = rawurlencode($strUrl);
        if ($queryParams) {
            $strUrl .= '?' . http_build_query($queryParams, null, null, PHP_QUERY_RFC3986);
        }
        return $strUrl;
    }

    /**
     * Utility function to create a link, i.e. an "a" tag.
     *
     * @param string $strUrl URL to link to. Use MakeUrl or MailToUrl to create the URL.
     * @param string $strText The inner text. This WILL be escaped.
     * @param array $attributes Other html attributes to include in the tag
     * @param boolean $blnHtmlEntities False to prevent encoding
     * @return string
     */
    public static function renderLink($strUrl, $strText, $attributes = null, $blnHtmlEntities = true) {
        $attributes["href"] = $strUrl;
        if ($blnHtmlEntities) {
            $strText = QString::htmlEntities($strText);
        }
        return self::renderTag("a", $attributes, $strText);
    }

    /**
     * Renders a PHP string as HTML text. Makes sure special characters are encoded, and <br /> tags are substituted
     * for newlines.
     * @param string $strText
     * @return string
     */
    public static function renderString($strText) {
        return nl2br(htmlspecialchars($strText, ENT_COMPAT | ENT_HTML5, __APPLICATION_ENCODING_TYPE__));
    }

    /**
     * A quick way to render an HTML table from an array of data. For more control, or to automatically render
     * data that may change, see QHtmlTable and its subclasses.
     *
     * Example:
     * $data = [
     * 				['name'=>'apple', 'type'=>'fruit'],
     * 				['name'=>'carrot', 'type'=>'vegetable']
     * 	];
     *
     * 	print(Html::renderTable($data, ['name','type'], ['class'=>'mytable'], ['Name', 'Type']);
     *
     *
     * @param []mixed			$data				An array of objects, or an array of arrays
     * @param []string|null 	$strFields			An array of fields to display from the data. If the data contains objects,
     * 												the fields will be accessed using $obj->$strFieldName. If an array of arrays,
     * 												it will be accessed using $obj[$strFieldName]. If no fields specified, it will
     * 												treat the data as an array of arrays and just create cells for whatever it finds.
     * @param array|null 		$attributes			Optional array of attributes to be inserted into the table tag (like a class or id).
     * @param []string|null 	$strHeaderTitles	Optional array of titles to be added as a header row.
     * @param int 				$intHeaderColumnCount	Optional count of the number of columns on the left that will be
     * 													rendered using a 'th' tag instead of a 'td' tag.
     * @param bool 				$blnHtmlEntities	True (default) to run all titles and text through the HTMLEntities renderer. Set this to
     * 												false if you are trying to display raw html.
     * @return string
     */
    public static function renderTable(array $data, $strFields = null, $attributes = null, $strHeaderTitles = null, $intHeaderColumnCount = 0, $blnHtmlEntities = true) {
        if (!$data) {
            return '';
        }

        $strHeader = '';
        if ($strHeaderTitles) {
            foreach ($strHeaderTitles as $strHeaderTitle) {
                if ($blnHtmlEntities) {
                    $strHeaderTitle = QString::htmlEntities($strHeaderTitle);
                }
                $strHeader .= '<th>' . $strHeaderTitle . '</th>';
            }
            $strHeader = '<thead><tr>' . $strHeader . '</tr></thead>';
        }
        $strBody = '';
        foreach ($data as $row) {
            $intFieldNum = 0;
            $strRow = '';
            if ($strFields) {
                foreach ($strFields as $strField) {
                    $intFieldNum ++;
                    $strItem = '';
                    if (is_object($row)) {
                        $strItem = $row->$strField;
                    } elseif (isset($row[$strField])) {
                        $strItem = $row[$strField];
                    }
                    if ($blnHtmlEntities) {
                        $strItem = QString::htmlEntities($strItem);
                    }
                    if ($intFieldNum <= $intHeaderColumnCount) {
                        $strRow .= '<th>' . $strItem . '</th>';
                    } else {
                        $strRow .= '<td>' . $strItem . '</td>';
                    }
                }
            } else {
                foreach ($row as $strItem) {
                    $intFieldNum ++;
                    if ($blnHtmlEntities) {
                        $strItem = QString::htmlEntities($strItem);
                    }
                    if ($intFieldNum <= $intHeaderColumnCount) {
                        $strRow .= '<th>' . $strItem . '</th>';
                    } else {
                        $strRow .= '<td>' . $strItem . '</td>';
                    }
                }
            }
            $strRow = '<tr>' . $strRow . '</tr>';
            $strBody .= $strRow;
        }
        $strBody = '<tbody>' . $strBody . '</tbody>';
        $strTable = self::renderTag('table', $attributes , $strHeader . $strBody);
        return $strTable;
    }

}
