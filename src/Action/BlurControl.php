<?php
/**
 * Blurs (JS blur, not visual blur) a control on server side (i.e. removes focus from that control)
 *
 * @package Actions
 */
class QBlurControlAction extends QAction {
    /** @var null|string Control ID of the control from which focus has to be removed */
    protected $strControlId = null;

    /**
     * Constructor
     *
     * @param QControl $objControl
     *
     * @throws QCallerException
     */
    public function __construct($objControl) {
        if (!($objControl instanceof QControl)) {
            throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
        }

        $this->strControlId = $objControl->ControlId;
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return sprintf("qc.getW('%s').blur();", $this->strControlId);
    }
}
