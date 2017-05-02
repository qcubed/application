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
 * Registers the click position on a control
 *
 * @package Actions
 */
class QRegisterClickPositionAction extends AbstractBase {
    /** @var null|string Control ID of the control on which the click position has to be registered */
    protected $strControlId = null;

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string
     */
    public function RenderScript(QControl $objControl) {
        return sprintf("qc.getW('%s').registerClickPosition(event);", $objControl->ControlId);
    }
}

