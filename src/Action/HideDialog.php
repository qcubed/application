<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Project\ControlDialog as QDialog;

/**
 * Class HideDialog
 *
 * Hiding a JQuery UI Dialog (QDialog)
 *
 * @was QHideDialog
 * @package QCubed\Action
 */
class HideDialog extends AbstractBase {
    /** @var null|string JS to be executed on the client side for closing the dialog */
    protected $strJavaScript = null;

    /**
     * Constructor
     *
     * @param QDialog $objControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct(\QCubed\Control\Dialog $objControl) {
        if (!($objControl instanceof QDialog)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QDialog');
        }

        $strControlId = $objControl->getJqControlId();
        $this->strJavaScript = sprintf('jQuery("#%s").dialog("close");', $strControlId);
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return null|string JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return $this->strJavaScript;
    }
}
