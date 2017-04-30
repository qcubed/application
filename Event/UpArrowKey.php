<?php

/** When the up arrow key is pressed while the element is in focus */
class QUpArrowKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS */
    protected $strCondition = 'event.keyCode == 38';
}
