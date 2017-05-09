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
 * Class ToggleEffect
 *
 * Toggle visibility of a control, using additional visual effects
 *
 * @package QCubed\Jqui\Action
 * @was QJQToggleEffectAction
 */
class ToggleEffect extends ActionBase
{
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, $strMethod = "slow", $strOptions = "", $strSpeed = 1000)
    {
        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, $strMethod);
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").toggle("%s", {%s}, %s);', $this->strControlId, $this->strMethod, $this->strOptions, $this->strSpeed);
    }
}
