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
use QCubed\Exception\InvalidCast;
use QCubed\Type;

/**
 * Class AbstractButtonBase
 *
 * Base class for HTML Button.
 *
 * @package Controls
 *
 * @property string $Text is used to display the button's text
 * @property boolean $PrimaryButton is a boolean to specify whether or not the button is 'primary' (e.g. makes this button a "Submit" form element rather than a "Button" form element)
 * @property boolean $HtmlEntities
 * @was QButtonBase
 * @package QCubed\Control
 */
abstract class AbstractButtonBase extends AbstractActionControl
{
    ///////////////////////////
    // Private Member Variables
    ///////////////////////////

    // APPEARANCE
    /** @var string Text on the button */
    protected $strText = null;
    /** @var bool Whether or not to use Htmlentities for the control */
    protected $blnHtmlEntities = true;

    // BEHAVIOR
    /** @var bool Is the button a primary button (causes form submission)? */
    protected $blnPrimaryButton = false;

    // SETTINGS
    /**
     * @var bool Prevent any more actions from happening once action has been taken on this control
     *  causes "event.preventDefault()" to be called on the client side
     */
    protected $blnActionsMustTerminate = true;

    //////////
    // Methods
    //////////
    /**
     * Return the HTML string for the control
     * @return string The HTML string of the control
     */
    protected function getControlHtml()
    {
        if ($this->blnPrimaryButton) {
            $attrOverride['type'] = "submit";
        } else {
            $attrOverride['type'] = "button";
        }
        $attrOverride['name'] = $this->strControlId;
        $strInnerHtml = $this->getInnerHtml();

        return $this->renderTag('button', $attrOverride, null, $strInnerHtml);
    }

    /**
     * Returns the html to appear between the button tags.
     * @return string
     */
    protected function getInnerHtml()
    {
        return ($this->blnHtmlEntities) ? QApplication::htmlEntities($this->strText) : $this->strText;
    }



    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    /**
     * PHP Magic __get method implementation
     * @param string $strName Name of the property to be fetched
     *
     * @return mixed
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            // APPEARANCE
            case "Text":
                return $this->strText;
            case "HtmlEntities":
                return $this->blnHtmlEntities;

            // BEHAVIOR
            case "PrimaryButton":
                return $this->blnPrimaryButton;

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
     * PHP Magic method __set implementation for this class (QButtonBase)
     * @param string $strName Name of the property
     * @param string $mixValue Value of the property
     *
     * @return void
     * @throws InvalidCast|Caller
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            // APPEARANCE
            case "Text":
                try {
                    $val = Type::Cast($mixValue, Type::STRING);
                    if ($val !== $this->strText) {
                        $this->strText = $val;
                        $this->blnModified = true;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "HtmlEntities":
                try {
                    $val = Type::Cast($mixValue, Type::BOOLEAN);
                    if ($val !== $this->blnHtmlEntities) {
                        $this->blnHtmlEntities = $val;
                        $this->blnModified = true;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            // BEHAVIOR
            case "PrimaryButton":
                try {
                    $val = Type::Cast($mixValue, Type::BOOLEAN);
                    if ($val !== $this->blnPrimaryButton) {
                        $this->blnPrimaryButton = $val;
                        $this->blnModified = true;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strName, $mixValue);
                    break;
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }
}
