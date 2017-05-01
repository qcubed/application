<?php
/** When enter key is pressed while the control is in focus */
class QEnterKeyEvent extends QKeyDownEvent {
    /** @var string Condition JS */
    protected $strCondition = 'event.keyCode == 13';
}

