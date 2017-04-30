<?php

/** When the Tab key is pressed with element in focus */
class QTabKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS with keycode for tab key */
    protected $strCondition = 'event.keyCode == 9';
}
