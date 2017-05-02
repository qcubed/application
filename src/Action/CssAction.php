<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Project\Control\ControlBase as QControl;

/**
 * Class CssAction
 *
 * Sets the CSS class of a control on the client side (does not update the server side)
 *
 * @was QCssAction
 * @package QCubed\Action
 */
class CssAction extends AbstractBase
{
    /** @var string CSS property to be set */
    protected $strCssProperty = null;
    /** @var string Value to which the CSS property should be set */
    protected $strCssValue = null;
    /**
     * @var null|string The control ID for which the action should be done.
     *                  By default, it is applied to the QControl to which the action is added.
     */
    protected $strControlId = null;

    /**
     * Constructor
     *
     * @param string $strCssProperty
     * @param string $strCssValue
     * @param null|QControl $objControl
     */
    public function __construct($strCssProperty, $strCssValue, $objControl = null)
    {
        $this->strCssProperty = $strCssProperty;
        $this->strCssValue = $strCssValue;
        if ($objControl) {
            $this->strControlId = $objControl->ControlId;
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string JavaScript to be executed on the client side for setting the CSS
     */
    public function RenderScript(QControl $objControl)
    {
        if ($this->strControlId == null) {
            $this->strControlId = $objControl->ControlId;
        }

        // Specified a Temporary Css Class to use?
        return sprintf('$j("#%s").css("%s", "%s"); ', $this->strControlId, $this->strCssProperty, $this->strCssValue);
    }
}
