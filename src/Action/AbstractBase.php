<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Action;

use \QCubed\Exception\Caller;
use QCubed\Type;
use QCubed\Project\Control\ControlBase as QControl;


/**
 * Base class for all other Actions.
 *
 * @package Actions
 * @property QEvent $Event Any QEvent derivated class instance
 * @was QAction
 */
abstract class AbstractBase extends \QCubed\AbstractBase
{
    /**
     * Abstract method, implemented in derived classes. Returns the JS needed for the action to work
     *
     * @param QControl $objControl
     *
     * @return mixed
     */
    abstract public function RenderScript(QControl $objControl);

    /** @var \QCubed\Event\AbstractBase Event object which will fire this action */
    protected $objEvent;

    /**
     * @param \QControl $objControl QControl for which the actions have to be rendered
     * @param string $strEventName Name of the event for which the actions have to be rendered
     * @param AbstractBase[] $objActions Array of actions
     *
     * @return null|string
     * @throws \Exception
     */
    public static function RenderActions(QControl $objControl, $strEventName, $objActions)
    {
        $strToReturn = '';
        $strJqUiProperty = null;

        if ($objControl->ActionsMustTerminate) {
            $strToReturn .= ' event.preventDefault();' . _nl();
        }

        if ($objActions && count($objActions)) {
            foreach ($objActions as $objAction) {
                if ($objAction->objEvent->EventName != $strEventName) {
                    throw new Exception('Invalid Action Event in this entry in the ActionArray');
                }

                if ($objAction->objEvent instanceof QJqUiPropertyEvent) {
                    $strJqUiProperty = $objAction->objEvent->JqProperty;
                }

                if ($objAction->objEvent->Delay > 0) {
                    $strCode = sprintf(" qcubed.setTimeout('%s', \$j.proxy(function(){%s},this), %s);",
                        $objControl->ControlId,
                        _nl() . _indent(trim($objAction->RenderScript($objControl))) . _nl(),
                        $objAction->objEvent->Delay);
                } else {
                    $strCode = ' ' . $objAction->RenderScript($objControl);
                }

                // Add Condition (if applicable)
                if (strlen($objAction->objEvent->Condition)) {
                    $strCode = sprintf(' if (%s) {%s}', $objAction->objEvent->Condition,
                        _nl() . _indent(trim($strCode)) . _nl());
                }

                $strCode .= _nl();

                // Append it to the Return Value
                $strToReturn .= $strCode;
            }
        }

        if (strlen($strToReturn)) {
            if ($objAction->objEvent->Block) {
                $strToReturn .= 'qc.blockEvents = true;';
            }
            $strToReturn = _nl() . _indent($strToReturn);


            if ($strJqUiProperty) {
                $strOut = sprintf('$j("#%s").%s("option", {%s: function(event, ui){%s}});',
                    $objControl->getJqControlId(),
                    $objControl->getJqSetupFunction(),
                    $strJqUiProperty,
                    $strToReturn);
            } elseif ($objControl instanceof Proxy) {
                $strOut = sprintf('$j("#%s").on("%s", "[data-qpxy=\'%s\']", function(event, ui){%s});',
                    $objControl->Form->FormId, $strEventName, $objControl->ControlId, $strToReturn);
            } else {
                $strOut = sprintf('$j("#%s").on("%s", function(event, ui){%s});',
                    $objControl->getJqControlId(),
                    $strEventName, $strToReturn);

            }

            if (isset($strOut)) {
                if (!QApplication::$Minimize) {
                    // Render a comment
                    $strOut = _nl() . _nl() .
                        sprintf('/*** Event: %s  Control Type: %s, Control Name: %s, Control Id: %s  ***/',
                            $strEventName, get_class($objControl), $objControl->Name, $objControl->ControlId) .
                        _nl() .
                        _indent($strOut) .
                        _nl() . _nl();
                }
                return $strOut;
            }
        }

        return null;
    }

    /**
     * PHP Magic function to set the property values of an object of the class
     * In this case, we only have 'Event' property to be set
     *
     * @param string $strName Name of the property
     * @param string $mixValue Value of the property
     *
     * @throws Caller
     * @return void
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case 'Event':
                $this->objEvent = Type::cast($mixValue, '\QCubed\Event\AbstractBase');
                break;

            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * PHP Magic function to get the property values of an object of the class
     * In this case, we only have 'Event' property to be set
     *
     * @param string $strName Name of the property
     *
     * @return mixed
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'Event':
                return $this->objEvent;
            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }
}













