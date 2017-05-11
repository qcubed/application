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
 * Class Size
 *
 * Resize a control
 *
 * @package QCubed\Jqui\Action
 * @was QJQSizeAction
 */
class Size extends ActionBase
{
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, $strOptions, $strSpeed = 1000)
    {
        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, 'size');
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").effect("size", {%s}, %s);', $this->strControlId, $this->strOptions, $this->strSpeed);
    }
}
