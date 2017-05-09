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
 * Class Show
 *
 * Show a control (if it's hidden)
 *
 * @package QCubed\Jqui\Action
 * @was QJQShowAction
 */
class Show extends ActionBase
{
    public function __construct(QControl $objControl, $strMethod = "slow")
    {
        parent::__construct($objControl, $strMethod);
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").show("%s");', $this->strControlId, $this->strMethod);
    }
}
