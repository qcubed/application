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
 * Hides a dialog box (QDialogBox)
 *
 * @package Actions
 */
class QHideDialogBox extends AbstractBase {
    /** @var null|string The JS for hiding the dialog box */
    protected $strJavaScript = null;

    /**
     * Constructor
     *
     * @param QDialogBox $objControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QDialogBox)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QDialogBox');
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
