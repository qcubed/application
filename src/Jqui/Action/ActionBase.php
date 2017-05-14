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
 * Class ActionBase
 *
 * Base class for all jQuery-based effects.
 *
 * @package QCubed\Jqui\Action
 * @was QJQAction
 */
abstract class ActionBase extends \QCubed\Action\ActionBase
{
    /** @var null|string  */
    protected $strControlId = null;
    /** @var string|null  */
    protected $strMethod = null;

    protected function __construct(QControl $objControl, $strMethod)
    {
        $this->strControlId = $objControl->ControlId;
        $this->strMethod = Type::cast($strMethod, Type::STRING);
        $this->setJavaScripts($objControl);
    }

    private function setJavaScripts(QControl $objControl)
    {
        $objControl->addJavascriptFile(QCUBED_JQUI);
    }
}
