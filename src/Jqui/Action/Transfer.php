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
 * Class Transfer
 *
 * Transfer the border of a control to another control
 *
 * @package QCubed\Jqui\Action
 * @was QJQTransferAction
 */
class Transfer extends ActionBase
{
    protected $strTargetControlId = null;
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, QControl $objTargetControl, $strOptions = "", $strSpeed = 1000)
    {
        $this->strTargetControlId = $objTargetControl->ControlId;

        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, 'transfer');
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").effect("transfer", {to: "#%s_ctl" %s}, %s);', $this->strControlId, $this->strTargetControlId, $this->strOptions, $this->strSpeed);
    }
}
