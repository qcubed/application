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
 * Class Highlight
 *
 * Highlight a control
 *
 * @package QCubed\Jqui\Action
 * @was QJQHighlightAction
 */
class Highlight extends ActionBase
{
    protected $strOptions = null;
    protected $strSpeed = null;

    public function __construct(QControl $objControl, $strOptions = "", $strSpeed = 1000)
    {
        $this->strOptions = Type::cast($strOptions, Type::STRING);
        $this->strSpeed = Type::cast($strSpeed, Type::STRING);

        parent::__construct($objControl, 'highlight');
    }

    public function renderScript(QControl $objControl)
    {
        return sprintf('$j("#%s").effect("highlight", {%s}, %s);', $this->strControlId, $this->strOptions, $this->strSpeed);
    }
}
