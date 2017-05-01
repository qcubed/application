<?php

/** When the down arrow key is pressed while the element is in focus */
class QDownArrowKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS */
    protected $strCondition = 'event.keyCode == 40';
}
