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
 * Class Hide
 *
 * Hide a control (if it's showing)
 *
 * @package QCubed\Jqui\Action
 * @was QJQHideAction
 */
class Hide extends ActionBase
{
    public function __construct(QControl $objControl, $strMethod = "slow")
    {
        parent::__construct($objControl, $strMethod);
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").hide("%s");', $this->strControlId, $this->strMethod);
    }
}
