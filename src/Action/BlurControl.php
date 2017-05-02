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


/**
 * Class BlurControl
 *
 * Blurs (JS blur, not visual blur) a control on server side (i.e. removes focus from that control)
 *
 * @was QBlurControlAction
 * @package QCubed\Action
 */
class BlurControl extends AbstractBase
{
    /** @var null|string Control ID of the control from which focus has to be removed */
    protected $strControlId = null;

    /**
     * Constructor
     *
     * @param QControl $objControl
     *
     * @throws Caller
     */
    public function __construct($objControl)
    {
        if (!($objControl instanceof QControl)) {
            throw new Caller('First parameter of constructor is expecting an object of type QControl');
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
    public function renderScript(QControl $objControl)
    {
        return sprintf("qc.getW('%s').blur();", $this->strControlId);
    }
}
