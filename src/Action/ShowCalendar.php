<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Exception\Caller;
use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Control\Calendar;

/**
 * Class ShowCalendar
 *
 * Shows a Calendar Control. Probably should be deprecated in favor of calendar plugins from css and javascript frameworks.
 *
 * @was QShowCalendarAction
 * @package QCubed\Action
 */
class ShowCalendar extends ActionBase
{
    /** @var null|string Control ID of the calendar */
    protected $strControlId = null;

    /**
     * @param QCalendar $calControl
     *
     * @throws Caller
     */
    public function __construct($calControl)
    {
        if (!($calControl instanceof Calendar)) {
            throw new Caller('First parameter of constructor is expecting an object of type QCalendar');
        }
        $this->strControlId = $calControl->ControlId;
    }

    /**
     * Returns the JavaScript to be executed on the client side
     * @param QControl $objControl
     *
     * @return string JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl)
    {
        return sprintf("qc.getC('%s').showCalendar();", $this->strControlId);
    }
}
