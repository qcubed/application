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
 * Class ToggleDisplay
 *
 * Toggle the Disaply of a control
 *
 * @was QToggleDisplayAction
 * @package QCubed\Action
 */
class ToggleDisplay extends ActionBase
{
    /** @var string Control ID of the control */
    protected $strControlId = null;
    /** @var boolean|null Enforce 'show' or 'hide' action */
    protected $blnDisplay = null;

    /**
     * @param QControl|QControlBase $objControl
     * @param bool $blnDisplay
     *
     * @throws Exception|Caller|\QCubed\Exception\InvalidCast
     */
    public function __construct($objControl, $blnDisplay = null)
    {
        if (!($objControl instanceof QControl)) {
            throw new Caller('First parameter of constructor is expecting an object of type QControl');
        }

        $this->strControlId = $objControl->ControlId;

        if (!is_null($blnDisplay)) {
            $this->blnDisplay = Type::cast($blnDisplay, Type::BOOLEAN);
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string Returns the JavaScript to be executed on the client side
     */
    public function renderScript(QControl $objControl)
    {
        if ($this->blnDisplay === true) {
            $strShowOrHide = 'show';
        } else {
            if ($this->blnDisplay === false) {
                $strShowOrHide = 'hide';
            } else {
                $strShowOrHide = '';
            }
        }

        return sprintf("qc.getW('%s').toggleDisplay('%s');",
            $this->strControlId, $strShowOrHide);
    }
}
