<?php
/**
 * Be careful with change events for listboxes -
 * they don't fire when the user picks a value on many browsers!
 */
class QChangeEvent extends QEvent {
    /** Event Name */
    const EventName = 'change';
}
