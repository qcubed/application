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
 * @package Actions
 */
class QResetTimerAction extends AbstractBase {
    /**
     * Returns the JavaScript to be executed on the client side (to clear the timeout on the control)
     *
     * @param QControl $objControl Control on which the timeout has to be cleared
     *
     * @return string JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return sprintf("qcubed.clearTimeout('%s');", $objControl->ControlId);
    }
}

