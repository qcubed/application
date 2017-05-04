<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Event;

class CheckboxColumnClick extends Click
{
    const JS_RETURN_PARAM = '{"row": $j(this).closest("tr")[0].rowIndex, "col": $j(this).closest("th,td")[0].cellIndex, "checked":this.checked, "id":this.id}'; // returns the array of cell info, and the new state of the checkbox

    public function __construct($intDelay = 0, $strCondition = null)
    {
        parent::__construct($intDelay, $strCondition, 'input[type="checkbox"]');
    }
}
