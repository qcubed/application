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
 * Class HideEffect
 *
 * Hide a control, using additional visual effects.
 *
 * @package QCubed\Jqui\Action
 * @was QJQHideEffectAction
 */
class HideEffect extends ActionBase
{
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, $strMethod = "blind", $strOptions = "", $strSpeed = 1000)
    {
        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, $strMethod);
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").hide("%s", {%s}, %s);', $this->strControlId, $this->strMethod, $this->strOptions, $this->strSpeed);
    }
}
