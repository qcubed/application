<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Exception\Caller;
use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Type;

/**
 * Class ToggleEnable
 *
 * Toggle the 'enabled' status of a control
 * NOTE: It does not change the Enabled property on the server side
 *
 * @was QToggleEnableAction
 * @package QCubed\Action
 */
class ToggleEnable extends ActionBase
{
    /** @var null|string Control ID of the control to be Enabled/Disabled */
    protected $strControlId = null;
    /** @var boolean|null Enforce the Enabling or Disabling action */
    protected $blnEnabled = null;

    /**
     * @param QControl|QControlBase $objControl
     * @param boolean $blnEnabled
     *
     * @throws Exception|Caller|\QCubed\Exception\InvalidCast
     */
    public function __construct($objControl, $blnEnabled = null)
    {
        if (!($objControl instanceof QControl)) {
            throw new Caller('First parameter of constructor is expecting an object of type QControl');
        }

        $this->strControlId = $objControl->ControlId;

        if (!is_null($blnEnabled)) {
            $this->blnEnabled = Type::cast($blnEnabled, Type::BOOLEAN);
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string Client side JS
     */
    public function renderScript(QControl $objControl)
    {
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
