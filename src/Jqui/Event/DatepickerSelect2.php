<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Jqui\Event;

/**
 * Class DatepickerSelect2
 *
 * Use this class instead of the QDatepicker_SelectEvent. The QDatepicker_SelectEvent will cause the
 * datepicker to not function correctly. The problem is related to how the datepicker is implemented on the
 * JQueryUI end. They have been meaning to do a rewrite for quite some time, but have not gotten to that.
 *
 * @was QDatepicker_SelectEvent2
 */

class DatepickerSelect2 extends AbstractBase {
    /** Event name for the event */
    const EventName = 'QDatepicker_Select2';
}

