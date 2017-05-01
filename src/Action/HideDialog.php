<?php
/**
 * Hiding a JQuery UI Dialog (QDialog)
 *
 * @package Actions
 */
class QHideDialog extends QAction {
    /** @var null|string JS to be executed on the client side for closing the dialog */
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
