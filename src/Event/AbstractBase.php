<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Event;

use QCubed\Exception\Caller;
use QCubed\Type;

/**
 * Class AbstractBase
 * Events are used in conjunction with actions to respond to user actions, like clicking, typing, etc.,
 * or even programmable timer events.
 * @property-read string $EventName the javascript event name that will be fired
 * @property-read string $Condition a javascript condition that is tested before the event is sent
 * @property-read integer $Delay ms delay before action is fired
 * @property-read string $JsReturnParam the javascript used to create the strParameter that gets sent to the event handler registered with the event.
 * @property-read string $Selector a jquery selector, causes the event to apply to child items matching the selector, and then get sent up the chain to this object
 * @property-read boolean $Block indicates that other events after this event will be thrown away until the browser receives a response from this event.
 * @package QCubed\Event
 */
abstract class AbstractBase extends \QCubed\AbstractBase
{
    /** @var string|null The JS condition in which an event would fire */
    protected $strCondition = null;
    /** @var int|mixed The number of second after which the event has to be fired */
    protected $intDelay = 0;
    protected $strSelector = null;
    /** @var  boolean True to block all other events until a response is received. */
    protected $blnBlock;

    /**
     * Create an event.
     * @param integer $intDelay ms delay to wait before action is fired
     * @param string $strCondition javascript condition to check before firing the action
     * @param string $strSelector jquery selector to cause event to be attached to child items instead of this item
     * @param boolean $blnBlockOtherEvents True to "debounce" the event by throwing away all other events until the browser receives a response from this event.
     *                            Only use this on Server and Ajax events. Do not use on Javascript events, or the browser will stop responding to Ajax and Server events.
     * @throws Caller
     */
    public function __construct($intDelay = 0, $strCondition = null, $strSelector = null, $blnBlockOtherEvents = false)
    {
        try {
            if ($intDelay) {
                $this->intDelay = Type::cast($intDelay, Type::INTEGER);
            }
            if ($strCondition) {
                if ($this->strCondition) {
                    $this->strCondition = sprintf('(%s) && (%s)', $this->strCondition, $strCondition);
                } else {
                    $this->strCondition = Type::Cast($strCondition, Type::STRING);
                }
            }
            if ($strSelector) {
                $this->strSelector = $strSelector;
            }
            $this->blnBlock = $blnBlockOtherEvents;
        } catch (Caller $objExc) {
            $objExc->incrementOffset();
            throw $objExc;
        }
    }

    /**
     * The PHP Magic function for this class
     * @param string $strName Name of the property to fetch
     *
     * @return int|mixed|null|string
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'EventName':
                $strEvent = constant(get_class($this) . '::EventName');
                if ($this->strSelector) {
                    $strEvent .= '","' . addslashes($this->strSelector);
                }
                return $strEvent;
            case 'Condition':
                return $this->strCondition;
            case 'Delay':
                return $this->intDelay;
            case 'JsReturnParam':
                $strConst = get_class($this) . '::JsReturnParam';
                return defined($strConst) ? constant($strConst) : '';
            case 'Selector':
                return $this->strSelector;
            case 'Block':
                return $this->blnBlock;

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





