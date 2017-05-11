<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Jqui\Action;

use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Type;

/**
 * Class Toggle
 *
 * Toggle visibility of a control, using additional visual effects
 *
 * @package QCubed\Jqui\Action
 * @was QJQToggleAction
 */
class Toggle extends ActionBase
{
    public function __construct(QControl $objControl, $strMethod = "slow")
    {
        parent::__construct($objControl, $strMethod);
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").toggle("%s");', $this->strControlId, $this->strMethod);
    }
}
