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
 * Class Pulsate
 *
 * Pulsate the contents of a control
 *
 * @package QCubed\Jqui\Action
 * @was QJQPulsateAction
 */
class Pulsate extends ActionBase
{
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000)
    {
        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, 'pulsate');
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").effect("pulsate", {%s}, %s);', $this->strControlId, $this->strOptions, $this->strSpeed);
    }
}
