<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Control;

use QCubed\Exception\Caller;
use QCubed\Exception\CrossScripting;
use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Type;


/**
 * Class AbstractTextBox
 *
 * This class will render an HTML Textbox -- which can either be [input type="text"],
 * [input type="password"] or [textarea] depending on the TextMode (see below).
 *
 * @package Controls\Base
 * @property integer $Columns               is the "cols" html attribute (applicable for MultiLine textboxes)
 * @property string $Format
 * @property string $Text                  is the contents of the textbox, itself
 * @property string|null $Value            Returns the value of the text. If the text is empty, will return null.
 *                                            Subclasses can use this to return a specific type of data.
 * @property string $LabelForRequired
 * @property string $LabelForRequiredUnnamed
 * @property string $LabelForTooShort
 * @property string $LabelForTooShortUnnamed
 * @property string $LabelForTooLong
 * @property string $LabelForTooLongUnnamed
 * @property string $Placeholder           HTML5 Only. Placeholder text that gets erased once a user types.
 * @property string $CrossScripting        can be Allow, HtmlEntities, or Deny.  Deny is the default. Prevents cross scripting hacks.  HtmlEntities causes framework to automatically call php function htmlentities on the input data.  Allow allows everything to come through without altering at all.  USE "ALLOW" judiciously: using ALLOW on text entries, and then outputting that data WILL allow hackers to perform cross scripting hacks.
 * @property integer $MaxLength             is the "maxlength" html attribute (applicable for SingleLine textboxes)
 * @property integer $MinLength             is the minimum requred length to pass validation
 * @property integer $Rows                  is the "rows" html attribute (applicable for MultiLine textboxes)
 * @property string $TextMode              a QTextMode item. Determines if its a single or multi-line textbox, and the "type" property of the input.
 * @property boolean $AutoTrim              to automatically remove white space from beginning and end of data
 * @property integer $SanitizeFilter        PHP filter constant to apply to incoming data
 * @property mixed $SanitizeFilterOptions PHP filter constants or array to apply to SanitizeFilter option
 * @property integer $ValidateFilter        PHP filter constant to apply to validate with
 * @property mixed $ValidateFilterOptions PHP filter constants or array to apply to ValidateFilter option
 * @property mixed $LabelForInvalid       PHP filter constants or array to apply to ValidateFilter option
 *
 * @was QTextBoxBase
 * @package QCubed\Control
 */
abstract class AbstractTextBox extends QControl
{
    // APPEARANCE
    /** @var int */
    protected $intColumns = 0;
    /** @var string */
    protected $strText = null;
    /** @var string */
    protected $strLabelForRequired;
    /** @var string */
    protected $strLabelForRequiredUnnamed;
    /** @var string */
    protected $strLabelForTooShort;
    /** @var string */
    protected $strLabelForTooShortUnnamed;
    /** @var string */
    protected $strLabelForTooLong;
    /** @var string */
    protected $strLabelForTooLongUnnamed;
    /** @var string */
    protected $strPlaceholder = '';
    /** @var string */
    protected $strFormat = '%s';

    // BEHAVIOR
    /** @var int */
    protected $intMaxLength = 0;
    /** @var int */
    protected $intMinLength = 0;
    /** @var int */
    protected $intRows = 0;
    /** @var string Subclasses should not set this directly, but rather use the TextMode accessor */
    protected $strTextMode = QTextMode::SingleLine;
    /** @var string */
    protected $strCrossScripting;
    /** @var null */
    protected $objHTMLPurifierConfig = null;

    // Sanitization and validating
    /** @var bool */
    protected $blnAutoTrim = false;
    /** @var int */
    protected $intSanitizeFilter = null;
    /** @var mixed */
    protected $mixSanitizeFilterOptions = null;
    /** @var int */
    protected $intValidateFilter = null;
    /** @var mixed */
    protected $mixValidateFilterOptions = null;
    /** @var string */
    protected $strLabelForInvalid = null;


    //////////
    // Methods
    //////////
    /**
     * Constructor for the QTextBox[Base]
     *
     * @param QControl|QForm $objParentObject
     * @param null|string $strControlId
     */
    public function __construct($objParentObject, $strControlId = null)
    {
        parent::__construct($objParentObject, $strControlId);

        $this->strLabelForRequired = t('%s is required');
        $this->strLabelForRequiredUnnamed = t('Required');

        $this->strLabelForTooShort = t('%s must have at least %s characters');
        $this->strLabelForTooShortUnnamed = t('Must have at least %s characters');

        $this->strLabelForTooLong = t('%s must have at most %s characters');
        $this->strLabelForTooLongUnnamed = t('Must have at most %s characters');

        $this->strCrossScripting = QApplication::$DefaultCrossScriptingMode;

        if ($this->strCrossScripting == QCrossScripting::HTMLPurifier) {
            $this->initHtmlPurifier();
        }
    }

    /**
     * Initializee the HtmlPurifier library.
     */
    protected function initHtmlPurifier()
    {
        // If we are purifying using HTMLPurify, we will need the autoloader to be included.
        // We load lazy to make sure that the library is not loaded every time 'prepend.inc.php'
        // or 'qcubed.inc.php' is inlcuded. HTMLPurifier is a HUGE and SLOW library. Lazy loading
        // keeps it simpler.
        require_once(__DOCROOT__ . __VENDOR_ASSETS__ . '/ezyang/htmlpurifier/library/HTMLPurifier.auto.php');

        // We configure the default set of forbidden tags (elements) and attributes here
        // so that the rules are applicable the moment CrossScripting is set to Purify.
        // Use the QTextBox::SetPurifierConfig method to override these settings.
        $this->objHTMLPurifierConfig = HTMLPurifier_Config::createDefault();
        $this->objHTMLPurifierConfig->set('HTML.ForbiddenElements',
            'script,applet,embed,style,link,iframe,body,object');
        $this->objHTMLPurifierConfig->set('HTML.ForbiddenAttributes',
            '*@onfocus,*@onblur,*@onkeydown,*@onkeyup,*@onkeypress,*@onmousedown,*@onmouseup,*@onmouseover,*@onmouseout,*@onmousemove,*@onclick');

        if (defined('__PURIFIER_CACHE__')) {
            if (!is_dir(__PURIFIER_CACHE__)) {
                mkdir(__PURIFIER_CACHE__);
            }
            $this->objHTMLPurifierConfig->set('Cache.SerializerPath', __PURIFIER_CACHE__);
        } else {
            # Disable the cache entirely
            $this->objHTMLPurifierConfig->set('Cache.DefinitionImpl', null);
        }
    }

    /**
     * This function allows to set the Configuration for HTMLPurifier
     * similar to the HTMLPurifierConfig::set() method from the HTMLPurifier API.
     *
     * @param strParameter : The parameter to set for HTMLPurifier
     * @param mixValue : Value of the parameter.
     *                     NOTE: THERE IS NO SUPPORT FOR THE DEPRECATED API OF HTMLPURIFIER, HENCE NO THIRD ARGUMENT TO THE
     *                     FUNCTION CAN BE PASSED.
     *                     Visit http://htmlpurifier.org/live/configdoc/plain.html for the list of parameters and their effects.
     */
    public function setPurifierConfig($strParameter, $mixValue)
    {
        if ($this->objHTMLPurifierConfig != null) {
            $this->objHTMLPurifierConfig->set($strParameter, $mixValue);
        }
    }

    /**
     * Parse the data posted back via the control.
     * This function basically test for the Crossscripting rules applied to the QTextBox
     * @throws QCrossScriptingException
     */
    public function parsePostData()
    {
        // Check to see if this Control's Value was passed in via the POST data
        if (array_key_exists($this->strControlId, $_POST)) {
            // It was -- update this Control's value with the new value passed in via the POST arguments
            $strText = $_POST[$this->strControlId];
            $strText = str_replace("\r\n", "\n", $strText); // Convert posted newlines to PHP newlines
            $this->strText = $strText;

            $this->sanitize();

            switch ($this->strCrossScripting) {
                case QCrossScripting::Allow:
                    // Do Nothing, allow everything
                    break;
                case QCrossScripting::HtmlEntities:
                    // Go ahead and perform HtmlEntities on the text
                    $this->strText = QApplication::htmlEntities($this->strText);
                    break;
                case QCrossScripting::HTMLPurifier:
                    // let HTMLPurifier do the job! User should have set it up!
                    //	require_once(__VENDOR__ . '/ezyang/htmlpurifier/library/HTMLPurifier.auto.php');
                    $objPurifier = new HTMLPurifier($this->objHTMLPurifierConfig);

                    // HTML Purifier does an html_encode, which is not what we usually want.
                    $this->strText = html_entity_decode($objPurifier->purify($this->strText)); // don't save data as html entities! Encode at display time.
                    break;
                // The use of the modes below is not recommended; they're there only for legacy
                // purposes. If you need to check for cross-site scripting violations, use QCrossScripting::Purify
                case QCrossScripting::Legacy:
                case QCrossScripting::Deny:
                default:
                    // Deny the Use of CrossScripts
                    // Check for cross scripting patterns
                    $strText = mb_strtolower($this->strText, QApplication::$EncodingType);
                    if ((mb_strpos($strText, '<script', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<applet', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<embed', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<style', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<link', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<body', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<iframe', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, 'javascript:', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onfocus=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onblur=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onkeydown=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onkeyup=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onkeypress=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onmousedown=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onmouseup=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onmouseover=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onmouseout=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onmousemove=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, ' onclick=', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, '<object', 0, QApplication::$EncodingType) !== false) ||
                        (mb_strpos($strText, 'background:url', 0, QApplication::$EncodingType) !== false)
                    ) {
                        throw new CrossScripting($this->strControlId);
                    }
            }
        }
    }

    /**
     * Sanitizes the current value.
     */
    protected function sanitize()
    {
        if ($this->blnAutoTrim) {
            $this->strText = trim($this->strText);
        }

        if ($this->intSanitizeFilter) {
            $this->strText = filter_var($this->strText, $this->intSanitizeFilter, $this->mixSanitizeFilterOptions);
        }
    }

    /**
     * Returns the HTML formatted string for the control
     * @return string HTML string
     */
    protected function getControlHtml()
    {
        $attrOverride = array('name' => $this->strControlId);

        switch ($this->strTextMode) {
            case QTextMode::MultiLine:
                $strText = QApplication::htmlEntities($this->strText);

                return $this->renderTag('textarea',
                    $attrOverride,
                    null,
                    $strText);

            default:
                $attrOverride['value'] = $this->strText;
                return $this->renderTag('input',
                    $attrOverride,
                    null,
                    null,
                    true
                );

        }
    }


    /**
     * Render HTML attributes for the purpose of drawing the tag. Text objects have a number of parameters specific
     * to them, some of which we use for validation, and some of which are dual purpose.
     * We render those here, rather than setting the attributes when those are set.
     *
     * @param null $attributeOverrides
     * @param null $styleOverrides
     *
     * @return string
     */
    public function renderHtmlAttributes($attributeOverrides = null, $styleOverrides = null)
    {
        if ($this->intMaxLength) {
            $attributeOverrides['maxlength'] = $this->intMaxLength;
        }
        if ($this->strTextMode == QTextMode::MultiLine) {
            if ($this->intColumns) {
                $attributeOverrides['cols'] = $this->intColumns;
            }
            if ($this->intRows) {
                $attributeOverrides['rows'] = $this->intRows;
            }
            //if (!$this->blnWrap) {
            /**
             * $strToReturn .= 'wrap="off" '; Note that this is not standard HTML5 and not supported by all browsers
             * In fact, HTML5 has completely changed its meaning to mean whether the text itself has embedded
             * hard returns inserted when the textarea wraps. Deprecating. We will have to wait for another solution.
             */
            //}
        } else {
            if ($this->intColumns) {
                $attributeOverrides['size'] = $this->intColumns;
            }
            $typeStr = $this->strTextMode ? $this->strTextMode : 'text';
            $attributeOverrides['type'] = $typeStr;
        }

        if (strlen($this->strPlaceholder) > 0) {
            $attributeOverrides['placeholder'] = $this->strPlaceholder;
        }

        return parent::renderHtmlAttributes($attributeOverrides, $styleOverrides);
    }


    /**
     * Tests that the value given inside the textbox passes the rules set for the input
     * Tests it does:
     * (1) Checks if the textbox was empty while 'Required' property was set to true
     * (2) Checks for length contrainsts set by 'MaxLength' and 'MinLength' properties
     *
     * @return bool whether or not the control is valid
     */
    public function validate()
    {
        // Copy text
        $strText = $this->strText;
        // Check for Required
        if ($this->blnRequired) {
            if (mb_strlen($strText, QApplication::$EncodingType) == 0) {
                if ($this->strName) {
                    $this->ValidationError = sprintf($this->strLabelForRequired, $this->strName);
                } else {
                    $this->ValidationError = $this->strLabelForRequiredUnnamed;
                }
                return false;
            }
        }

        // Check against minimum length?
        if ($this->intMinLength > 0) {
            if (mb_strlen($strText, QApplication::$EncodingType) < $this->intMinLength) {
                if ($this->strName) {
                    $this->ValidationError = sprintf($this->strLabelForTooShort, $this->strName, $this->intMinLength);
                } else {
                    $this->ValidationError = sprintf($this->strLabelForTooShortUnnamed, $this->intMinLength);
                }
                return false;
            }
        }

        // Check against maximum length?
        if ($this->intMaxLength > 0) {
            if (mb_strlen($strText, QApplication::$EncodingType) > $this->intMaxLength) {
                if ($this->strName) {
                    $this->ValidationError = sprintf($this->strLabelForTooLong, $this->strName, $this->intMaxLength);
                } else {
                    $this->ValidationError = sprintf($this->strLabelForTooLongUnnamed, $this->intMaxLength);
                }
                return false;
            }
        }

        // Check against PHP validation
        if ($this->intValidateFilter && $this->strText) {
            if (!filter_var($this->strText, $this->intValidateFilter, $this->mixValidateFilterOptions)) {
                $this->ValidationError = $this->strLabelForInvalid;
                return false;
            }
        }

        // If we're here, then everything is a-ok.  Return true.
        return true;
    }

    /**
     * This will focus on and do a "select all" on the contents of the textbox
     */
    public function select()
    {
        QApplication::executeJavaScript(sprintf('qc.getW("%s").select();', $this->strControlId));
    }

    /**
     * Returns the current state of the control to be able to restore it later.
     * @return mixed
     */
    protected function getState()
    {
        return array('text' => $this->Text);
    }

    /**
     * Restore the state of the control.
     * @param mixed $state Previously saved state as returned by GetState above.
     */
    protected function putState($state)
    {
        if (isset($state['text'])) {
            $this->Text = $state['text'];
        }
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    /**
     * PHP __get magic method implementation
     * @param string $strName Name of the property
     *
     * @return array|bool|int|mixed|null|QControl|QForm|string
     * @throws Exception|Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            // APPEARANCE
            case "Columns":
                return $this->intColumns;
            case "Format":
                return $this->strFormat;
            case "Text":
                return $this->strText;
            case "LabelForRequired":
                return $this->strLabelForRequired;
            case "LabelForRequiredUnnamed":
                return $this->strLabelForRequiredUnnamed;
            case "LabelForTooShort":
                return $this->strLabelForTooShort;
            case "LabelForTooShortUnnamed":
                return $this->strLabelForTooShortUnnamed;
            case "LabelForTooLong":
                return $this->strLabelForTooLong;
            case "LabelForTooLongUnnamed":
                return $this->strLabelForTooLongUnnamed;
            case "Placeholder":
                return $this->strPlaceholder;
            case 'Value':
                return empty($this->strText) ? null : $this->strText;


            // BEHAVIOR
            case "CrossScripting":
                return $this->strCrossScripting;
            case "MaxLength":
                return $this->intMaxLength;
            case "MinLength":
                return $this->intMinLength;
            case "Rows":
                return $this->intRows;
            case "TextMode":
                return $this->strTextMode;

            // LAYOUT
            //case "Wrap": return $this->blnWrap;

            // FILTERING and VALIDATION
            case "AutoTrim":
                return $this->blnAutoTrim;
            case "SanitizeFilter":
                return $this->intSanitizeFilter;
            case "SanitizeFilterOptions":
                return $this->mixSanitizeFilterOptions;
            case "ValidateFilter":
                return $this->intValidateFilter;
            case "ValidateFilterOptions":
                return $this->mixValidateFilterOptions;
            case "LabelForInvalid":
                return $this->strLabelForInvalid;

            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    /**
     * PHP __set magic method implementation
     *
     * @param string $strName Name of the property
     * @param string $mixValue Value of the property
     *
     * @return void
     * @throws Exception|Caller|InvalidCast
     */
    public function __set($strName, $mixValue)
    {
        // Setters that do not cause a complete redraw
        switch ($strName) {
            case "Text":
            case "Value":
                try {
                    $val = Type::cast($mixValue, Type::STRING);
                    if ($val !== $this->strText) {
                        $this->strText = $val;
                        $this->addAttributeScript('val', $val);
                    }
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;

            // APPEARANCE
            case "Columns":
                try {
                    if ($this->intColumns !== ($mixValue = Type::cast($mixValue, Type::INTEGER))) {
                        $this->blnModified = true;
                        $this->intColumns = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Format":
                try {
                    if ($this->strFormat !== ($mixValue = Type::cast($mixValue, Type::STRING))) {
                        $this->blnModified = true;
                        $this->strFormat = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForRequired":
                try {
                    // no redraw needed
                    $this->strLabelForRequired = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForRequiredUnnamed":
                try {
                    $this->strLabelForRequiredUnnamed = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForTooShort":
                try {
                    $this->strLabelForTooShort = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForTooShortUnnamed":
                try {
                    $this->strLabelForTooShortUnnamed = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForTooLong":
                try {
                    $this->strLabelForTooLong = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "LabelForTooLongUnnamed":
                try {
                    $this->strLabelForTooLongUnnamed = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Placeholder":
                try {
                    if ($this->strPlaceholder !== ($mixValue = Type::cast($mixValue, Type::STRING))) {
                        $this->blnModified = true;
                        $this->strPlaceholder = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            // BEHAVIOR
            case "CrossScripting":
                try {
                    $this->strCrossScripting = Type::cast($mixValue, Type::STRING);
                    // Protect from XSS to the best we can do with HTMLPurifier.
                    if ($this->strCrossScripting == QCrossScripting::HTMLPurifier) {
                        $this->initHtmlPurifier();
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "MaxLength":
                try {
                    if ($this->intMaxLength !== ($mixValue = Type::cast($mixValue, Type::INTEGER))) {
                        $this->blnModified = true;
                        $this->intMaxLength = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "MinLength":
                try {
                    if ($this->intMinLength !== ($mixValue = Type::cast($mixValue, Type::INTEGER))) {
                        $this->blnModified = true;
                        $this->intMinLength = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Rows":
                try {
                    if ($this->intRows !== ($mixValue = Type::cast($mixValue, Type::INTEGER))) {
                        $this->blnModified = true;
                        $this->intRows = $mixValue;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "TextMode":
                try {
                    if ($this->strTextMode !== ($strMode = Type::cast($mixValue, Type::STRING))) {
                        $this->blnModified = true;
                        $this->strTextMode = $strMode;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

                // LAYOUT
                //case "Wrap":
                // Deprecated. HTML5 has changed the meaning of this, and wrap=off is not consistenly implemented
                // across browers.

            // FILTERING and VALIDATING, no redraw needed
            case "AutoTrim":
                try {
                    $this->blnAutoTrim = Type::cast($mixValue, Type::BOOLEAN);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "SanitizeFilter":
                try {
                    $this->intSanitizeFilter = Type::cast($mixValue, Type::INTEGER);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "SanitizeFilterOptions":
                try {
                    $this->mixSanitizeFilterOptions = $mixValue; // can be integer or array. See PHP doc.
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "ValidateFilter":
                try {
                    $this->intValidateFilter = Type::cast($mixValue, Type::INTEGER);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "ValidateFilterOptions":
                try {
                    $this->mixValidateFilterOptions = $mixValue; // can be integer or array. See PHP doc.
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "LabelForInvalid":
                try {
                    $this->strLabelForInvalid = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
        }
    }

    /**
     * Returns an description of the options available to modify by the designer for the code generator.
     *
     * @return QModelConnectorParam[]
     */
    public static function getModelConnectorParams()
    {
        return array_merge(parent::getModelConnectorParams(), array(
            new QModelConnectorParam (get_called_class(), 'Columns', 'Width of field', Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'Rows', 'Height of field for multirow field',
                Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'Format', 'printf format string to use',
                Type::STRING),
            new QModelConnectorParam (get_called_class(), 'Placeholder', 'HTML5 Placeholder attribute',
                Type::STRING),
            new QModelConnectorParam (get_called_class(), 'ReadOnly', 'Editable or not', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'TextMode', 'Field type', QModelConnectorParam::SelectionList,
                array(
                    null => '-',
                    'QTextMode::Search' => 'Search',
                    'QTextMode::MultiLine' => 'MultiLine',
                    'QTextMode::Password' => 'Password',
                    'QTextMode::SingleLine' => 'SingleLine'
                ))
        ));
    }
}

