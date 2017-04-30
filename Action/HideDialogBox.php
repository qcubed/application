<?php
/**
 * Hides a dialog box (QDialogBox)
 *
 * @package Actions
 */
class QHideDialogBox extends QAction {
    /** @var null|string The JS for hiding the dialog box */
    protected $strJavaScript = null;

    /**
     * Constructor
     *
     * @param QDialogBox $objControl
     *
     * @throws QCallerException
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QDialogBox)) {
            throw new QCallerException('First parameter of constructor is expecting an object of type QDialogBox');
        }

        $this->strJavaScript = $objControl->GetHideDialogJavaScript();
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return null|string JS to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return $this->strJavaScript;
    }
}
