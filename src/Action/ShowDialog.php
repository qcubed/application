<?php
/**
 * Shows a QDialog
 * This is the JQuery UI alternative to show dialog
 *
 * @package Actions
 */
class QShowDialog extends QAction {
    /** @var null|string The JS to show the dialog */
    protected $strJavaScript = null;

    /**
     * Constructor
     *
     * @param QDialog $objControl
     *
     * @throws QCallerException
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QDialog)) {
            throw new QCallerException('First parameter of constructor is expecting an object of type QDialog');
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

