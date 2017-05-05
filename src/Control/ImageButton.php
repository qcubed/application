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
 * Class ImageButton
 *
 * Needs some cleaning up.
 *
 * This class will render an HTML ImageButton <input type="image">.
 *
 * @property string $AlternateText is rendered as the HTML "alt" tag
 * @property string $ImageUrl is the url of the image to be used
 * @property boolean $PrimaryButton
 * @property-read integer $ClickX
 * @property-read integer $ClickY
 * @was QImageButton
 * @package QCubed\Control
 */
class ImageButton extends ActionControl
{
    protected $strAlternateText = null;
    protected $strImageUrl = null;
    protected $blnPrimaryButton = false;
    protected $intClickX;
    protected $intClickY;

    // SETTINGS
    protected $blnActionsMustTerminate = true;


    public function renderHtmlAttributes($attributeOverrides = null, $styleOverrides = null)
    {
        $strToReturn = parent::renderHtmlAttributes($attributeOverrides, $styleOverrides);

        if ($this->strAlternateText) {
            $strToReturn .= sprintf('alt="%s" ', $this->strAlternateText);
        }
        if ($this->strImageUrl) {
            $strToReturn .= sprintf('src="%s" ', $this->strImageUrl);
        }

        return $strToReturn;
    }

    public function parsePostData()
    {
        $strKeyX = sprintf('%s_x', $this->strControlId);
        $strKeyY = sprintf('%s_y', $this->strControlId);
        if (isset($strKeyX) && $_POST[$strKeyX] !== '') {
            $this->intClickX = $_POST[$strKeyX];
            $this->intClickY = $_POST[$strKeyY];
        }
        /*
        else {
            $this->intClickX = null;
            $this->intClickY = null;
        }*/
    }

    protected function getControlHtml()
    {
        $strStyle = '';
        /*
        $strStyle = $this->getStyleAttributes();
        if ($strStyle) {
            $strStyle = sprintf('style="%s"', $strStyle);
        }*/

        if ($this->blnPrimaryButton) {
            $strToReturn = sprintf('<input type="image" name="%s" %s%s />',
                $this->strControlId,
                $this->renderHtmlAttributes(),
                $strStyle);
        } else {
            $strToReturn = sprintf('<img  %s%s />',
                $this->renderHtmlAttributes(),
                $strStyle);
        }

        $strToReturn .= sprintf('<input type="hidden" name="%s_x" id="%s_x" value=""/><input type="hidden" name="%s_y" id="%s_y" value=""/>',
            $this->strControlId,
            $this->strControlId,
            $this->strControlId,
            $this->strControlId);

        return $strToReturn;
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName)
    {
        switch ($strName) {
            // APPEARANCE
            case "AlternateText":
                return $this->strAlternateText;
            case "ImageUrl":
                return $this->strImageUrl;

            // BEHAVIOR
            case "PrimaryButton":
                return $this->blnPrimaryButton;
            case "ClickX":
                return $this->intClickX;
            case "ClickY":
                return $this->intClickY;

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
    public function __set($strName, $mixValue)
    {
        $this->blnModified = true;

        switch ($strName) {
            // APPEARANCE
            case "AlternateText":
                try {
                    $this->strAlternateText = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "ImageUrl":
                try {
                    $this->strImageUrl = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            // BEHAVIOR
            case "PrimaryButton":
                try {
                    $this->blnPrimaryButton = Type::cast($mixValue, Type::BOOLEAN);
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
}
