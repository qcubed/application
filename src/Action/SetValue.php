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
 * Class SetValue
 *
 * Sets the javascript value of a control in the form. The value has to be known ahead of time. Useful for
 * automatically clearing a text field when it receives focus, for example.
 *
 * @was QSetValueAction
 * @package QCubed\Action
 */
class SetValue extends AbstractBase {
    protected $strControlId = null;
    protected $strValue = "";

    public function __construct($objControl, $strValue = "") {
        $this->strControlId = $objControl->ControlId;
        $this->strValue = $strValue;
    }

    /**
     * @param QControl $objControl
     * @return mixed|string
     */
    public function renderScript(QControl $objControl) {
        return sprintf("jQuery('#%s').val('%s');", $this->strControlId, $this->strValue);
    }
}

