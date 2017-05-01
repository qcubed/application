<?php
/**
 * Toggle the 'enabled' status of a control
 * NOTE: It does not change the Enabled property on the server side
 *
 * @package Actions
 */
class QToggleEnableAction extends QAction {
    /** @var null|string Control ID of the control to be Enabled/Disabled */
    protected $strControlId = null;
    /** @var boolean|null Enforce the Enabling or Disabling action */
    protected $blnEnabled = null;

    /**
     * @param QControl|QControlBase $objControl
     * @param boolean               $blnEnabled
     *
     * @throws Exception|QCallerException|QInvalidCastException
     */
    public function __construct($objControl, $blnEnabled = null) {
        if (!($objControl instanceof QControl)) {
            throw new QCallerException('First parameter of constructor is expecting an object of type QControl');
        }

        $this->strControlId = $objControl->ControlId;

        if (!is_null($blnEnabled)) {
            $this->blnEnabled = QType::Cast($blnEnabled, QType::Boolean);
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string Client side JS
     */
    public function RenderScript(QControl $objControl) {
        if ($this->blnEnabled === true) {
            $strEnableOrDisable = 'enable';
        } else {
            if ($this->blnEnabled === false) {
                $strEnableOrDisable = 'disable';
            } else {
                $strEnableOrDisable = '';
            }
        }

        return sprintf("qc.getW('%s').toggleEnabled('%s');", $this->strControlId, $strEnableOrDisable);
    }
}

