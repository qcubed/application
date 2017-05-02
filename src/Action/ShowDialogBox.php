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
 * Shows a dialog box (QDialogBox)
 *
 * @package Actions
 */
class QShowDialogBox extends AbstractBase {
    /** @var null|string Control ID of the dialog box (QDialogBox) */
    protected $strControlId = null;
    /**
     * @var null|string The Javascript that executes on the client side
     *                  For this action, this string contains the JS to show the dialog box
     */
    protected $strJavaScript = null;

    /**
     * Constructor method
     *
     * @param QDialogBox $objControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QDialogBox)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QDialogBox');
        }

        $this->strControlId = $objControl->ControlId;
        $this->strJavaScript = $objControl->GetShowDialogJavaScript();
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string JS to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return (sprintf('%s; qcubed.recordControlModification("%s", "Display", "1");', $this->strJavaScript, $this->strControlId));
    }
}

