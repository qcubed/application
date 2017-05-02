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
 * Shows a QDialog
 * This is the JQuery UI alternative to show dialog
 *
 * @package Actions
 */
class QShowDialog extends AbstractBase {
    /** @var null|string The JS to show the dialog */
    protected $strJavaScript = null;

    /**
     * Constructor
     *
     * @param QDialog $objControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QDialog)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QDialog');
        }

        $strControlId = $objControl->getJqControlId();
        $this->strJavaScript = sprintf('jQuery("#%s").dialog("open");', $strControlId);
    }

    /**
     * Returns the JavaScript to be executed on the client side for opening/showing the dialog
     *
     * @param QControl $objControl
     *
     * @return null|string JS that will be run on the client side
     */
    public function RenderScript(QControl $objControl) {
        return $this->strJavaScript;
    }
}

