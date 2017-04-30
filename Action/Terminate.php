<?php
/**
 * Prevents the default action on an event.
 *
 * E.g. If you have a click action added to a label whose text is a link, clicking it will take the action
 * but also take you to the link pointed by the label. You can add a QTerminateAction after all QClickEvent
 * handlers and that will make sure that action handlers are triggered but the browser does not navigate
 * the user to the link pointed by the label
 *
 * @package Actions
 */
class QTerminateAction extends QAction {
    /**
     * Returns the JS for the browser
     *
     * @param QControl $objControl
     *
     * @return string JS to prevent the default action
     */
    public function RenderScript(QControl $objControl) {
        return 'event.preventDefault();';
    }
}
