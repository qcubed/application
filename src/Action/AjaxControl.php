<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

/**
 * Class AjaxControl
 *
 * Ajax control action is identical to Ajax action, except
 * the handler for it is defined NOT on the form host, but on a QControl.
 *
 * @was QAjaxControlAction
 * @package QCubed\Action
 */
class AjaxControl extends Ajax
{
    /**
     * @param QControl $objControl Control where the action handler is defined
     * @param string $strMethodName Name of the action handler method
     * @param string $objWaitIconControl The wait icon to be implemented
     * @param null $mixCausesValidationOverride Override for CausesValidation (if needed)
     * @param string $strJsReturnParam Override for ActionParameter
     * @param boolean $blnAsync True to have the events for this action fire asynchronously
     */
    public function __construct(
        QControl $objControl,
        $strMethodName,
        $objWaitIconControl = 'default',
        $mixCausesValidationOverride = null,
        $strJsReturnParam = "",
        $blnAsync = false
    ) {
        assert($objControl->ControlId != '');    // Are you adding an action before adding the control to the form?
        parent::__construct($objControl->ControlId . ':' . $strMethodName, $objWaitIconControl,
            $mixCausesValidationOverride, $strJsReturnParam, $blnAsync);
    }
}
