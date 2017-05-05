<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Project\Control\ControlBase as QControl;

/**
 * Class SelectControl
 *
 * Selects contents inside a QTextBox on the client-side/browser
 *
 * @was QSelectControlAction
 * @package QCubed\Action
 */
class SelectControl extends ActionBase
{
    /** @var null|string Control ID of the QTextBox which is to be selected */
    protected $strControlId = null;

    /**
     * Constructor
     *
     * @param QTextBox $objControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct($objControl)
    {
        if (!($objControl instanceof QTextBox)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QTextBox');
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
        return sprintf("qc.getW('%s').select();", $this->strControlId);
    }
}
