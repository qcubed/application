<?php
/** When the escape key is pressed while the control is in focus */
class QEscapeKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS */
    protected $strCondition = 'event.keyCode == 27';
}
