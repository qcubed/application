<?php

/**
 * Prevents the event from bubbling up the DOM tree, preventing any parent
 * handlers from being notified of the event.
 *
 * @package Actions
 */
class QStopPropagationAction extends QAction {
    /**
     * Returns the JavaScript to be executed on the client side
     *
     * @param QControl $objControl
     *
     * @return string Client side JS
     */
    public function RenderScript(QControl $objControl) {
        return 'event.stopPropagation();';
    }
}

