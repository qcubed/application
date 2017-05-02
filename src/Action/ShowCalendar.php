<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;


/**
 * Shows a Calendar Control
 *
 * @package Actions
 */
class QShowCalendarAction extends AbstractBase {
    /** @var null|string Control ID of the calendar */
    protected $strControlId = null;

    /**
     * @param QCalendar $calControl
     *
     * @throws \QCubed\Exception\Caller
     */
    public function __construct($calControl) {
        if (!($calControl instanceof QCalendar)) {
            throw new \QCubed\Exception\Caller('First parameter of constructor is expecting an object of type QCalendar');
        }
        $this->strControlId = $calControl->ControlId;
    }

    /**
     * Returns the JavaScript to be executed on the client side
     * @param QControl $objControl
     *
     * @return string JavaScript to be executed on the client side
     */
    public function RenderScript(QControl $objControl) {
        return sprintf("qc.getC('%s').showCalendar();", $this->strControlId);
    }
}
