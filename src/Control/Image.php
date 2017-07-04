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
 * Class Image
 *
 * A basic img tag.
 *
 * @property string $AlternateText is rendered as the HTML "alt" tag
 * @property string $ImageUrl is the url of the image to be used
 * @property string $Height Height in pixels
 * @property string $Width Width in pixels
 * @package QCubed\Control
 */
class Image extends \QCubed\Project\Control\ControlBase
{
    /** @var  string */
    protected $strAlternateText;
    /** @var  string */
    protected $strImageUrl;
    /** @var  integer */
    protected $intHeight;
    /** @var  integer */
    protected $intWidth;

    public function renderHtmlAttributes($attributeOverrides = null, $styleOverrides = null)
    {
        if (!$attributeOverrides) {
            $attributeOverrides = [];
        }
        if ($this->strAlternateText) {
            $attributeOverrides['alt'] = $this->strAlternateText;
        }
        if ($this->strImageUrl) {
            $attributeOverrides['src'] = $this->strImageUrl;
        }
        if ($this->intHeight !== null) {
            $attributeOverrides['height'] = (string)$this->intHeight;
        }
        if ($this->intWidth !== null) {
            $attributeOverrides['width'] = (string)$this->intWidth;
        }

        return parent::renderHtmlAttributes($attributeOverrides, $styleOverrides);
    }

    protected function getControlHtml()
    {
        $strToReturn = $this->renderTag('img', null, null, null, true);
        return $strToReturn;
    }

    public function validate()
    {
        return true;
    }

    public function parsePostData()
    {
    }

    /**
     * @param string $strName
     * @return mixed|null
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            // APPEARANCE
            case "AlternateText":
                return $this->strAlternateText;
            case "ImageUrl":
                return $this->strImageUrl;
            case "Height":
                return $this->intHeight;
            case "Width":
                return $this->intWidth;


            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param $strName
     * @param $mixValue
     * @throws Caller
     * @throws InvalidCast
     * @return void
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            // APPEARANCE
            case "AlternateText":
                try {
                    $this->blnModified = true;
                    $this->strAlternateText = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "ImageUrl":
                try {
                    $this->blnModified = true;
                    $this->strImageUrl = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Height":
                try {
                    $this->blnModified = true;
                    $this->intHeight = Type::cast($mixValue, Type::INTEGER);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Width":
                try {
                    $this->blnModified = true;
                    $this->intWidth = Type::cast($mixValue, Type::INTEGER);
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
