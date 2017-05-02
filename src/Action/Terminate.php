<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use QCubed\Project\Control\ControlBase as QControl;

/**
 * Class Terminate
 *
 * Prevents the default action on an event.
 *
 * E.g. If you have a click action added to a label whose text is a link, clicking it will take the action
 * but also take you to the link pointed by the label. You can add a QTerminateAction after all QClickEvent
 * handlers and that will make sure that action handlers are triggered but the browser does not navigate
 * the user to the link pointed by the label
 *
 * @was QTerminateAction
 * @package QCubed\Action
 */
class Terminate extends AbstractBase
{
    /**
     * Returns the JS for the browser
     *
     * @param QControl $objControl
     *
     * @return string JS to prevent the default action
     */
    public function renderScript(QControl $objControl)
    {
        return 'event.preventDefault();';
    }
}
