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
 * Class RegisterClickPosition
 *
 * Registers the click position on a control
 *
 * @was QRegisterClickPositionAction
 * @package QCubed\Action
 */
class RegisterClickPosition extends AbstractBase
{
    /** @var null|string Control ID of the control on which the click position has to be registered */
    protected $strControlId = null;

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string
     */
    public function renderScript(QControl $objControl)
    {
        return sprintf("qc.getW('%s').registerClickPosition(event);", $objControl->ControlId);
    }
}

