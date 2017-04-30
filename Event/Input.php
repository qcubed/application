<?php

/**
 * Detects changes to textboxes and other input elements. Responds to cut/paste, search cancel, etc.
 * Ignores arrow keys, etc.
 * Not in IE8 or below. Buggy in IE9. Full support in IE10 and above.
 * No support in Safari 5 and below for textarea elements.
 */
class QInputEvent extends QEvent {
    /** Event Name */
    const EventName = 'input';
}

