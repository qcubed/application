<?php
/**
 * Class QJqUiEvent: When an event is triggered by jQuery-UI (drag, drop, resize etc.)
 * @abstract Implementation in children class
 */
abstract class QJqUiEvent extends QEvent {
    // be sure to subclass your events from this class if they are JqUiEvents
}
