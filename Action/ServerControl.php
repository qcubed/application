<?php
/**
 * Server control action is identical to server action, except
 * the handler for it is defined NOT in the form, but in a control.
 *
 * @package Actions
 */
class QServerControlAction extends QServerAction {
    /**
     * @param QControl $objControl                  Control where the action handler is defined
     * @param string   $strMethodName               Name of the method which acts as the action handler
     * @param mixed    $mixCausesValidationOverride Override for CausesValidation (if needed)
     * @param string   $strJsReturnParam            Override for ActionParameter
     */
    public function __construct(QControl $objControl, $strMethodName, $mixCausesValidationOverride = null,
        $strJsReturnParam = "") {
        parent::__construct($objControl->ControlId . ':' . $strMethodName, $mixCausesValidationOverride, $strJsReturnParam);
    }
}

