<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;


/**
 * Prevents the event from bubbling up the DOM tree, preventing any parent
 * handlers from being notified of the event.
 *
 * @package Actions
 */
class QStopPropagationAction extends AbstractBase {
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

