<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Control\Calendar;
use QCubed\Exception\Caller;
use QCubed\Project\Control\ControlBase as QControl;

/**
 * Class HideCalendar
 *
 * Hides a calendar control
 *
 * @was QHideCalendarAction
 * @package QCubed\Action
 */
class HideCalendar extends AbstractBase
{
    /** @var null|string Control ID of the calendar control */
    protected $strControlId = null;

    /**
     * Constructor
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
        return sprintf("qc.getC('%s').hideCalendar();", $this->strControlId);
    }
}
