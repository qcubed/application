<?php
/** When the Backspace key is pressed with element in focus */
class QBackspaceKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS with keycode for escape key */
    protected $strCondition = 'event.keyCode == 8';
}

