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
 * Toggle the Disaply of a control
 *
 * @package Actions
 */
class QToggleDisplayAction extends AbstractBase {
    /** @var string Control ID of the control */
    protected $strControlId = null;
    /** @var boolean|null Enforce 'show' or 'hide' action */
    protected $blnDisplay = null;

    /**
     * @param QControl|QControlBase $objControl
     * @param bool                  $blnDisplay
     *
     * @throws Exception|\QCubed\Exception\Caller|\QCubed\Exception\InvalidCast
     */
    public function __construct($objControl, $blnDisplay = null) {
        if (!($objControl instanceof QControl)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QControl');
        }

        $this->strControlId = $objControl->ControlId;

        if (!is_null($blnDisplay)) {
            $this->blnDisplay = \QCubed\Type::Cast($blnDisplay, \QCubed\Type::BOOLEAN);
        }
    }

    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string Returns the JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
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

