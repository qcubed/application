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
use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Project\Control\FormBase as QForm;
use QCubed as Q;
use QCubed\Type;

/**
 * Class JsTimerBase
 *
 * Timer Control:
 * This control uses a javascript timer to execute Actions after a defined time
 * Periodic or one shot timers are possible.
 * You can add only one type of Event to to this control: QTimerExpiredEvent
 * but multiple actions can be registered for this event
 * @property int $DeltaTime Time till the timer fires and executes the Actions added.
 * @property boolean $Periodic  <ul>
 *                      <li><strong>true</strong>: timer is restarted after firing</li>
 *                      <li><strong>false</strong>: you have to restart the timer by calling Start()</li>
 *                              </ul>
 *
 * @property boolean $Started <strong>true</strong>: timer is running / <strong>false</strong>: stopped
 * @property boolean $RestartOnServerAction After a 'Server Action' (QServerAction) the executed java script
 *                                                        (including the timer) is stopped!
 *                                                        Set this parameter to true to restart the timer automatically.
 * @notes <ul><li>You do not need to render this control!</li>
 *            <li>QTimerExpiredEvent - condition and delay parameters of the constructor are ignored (for now) </li>
 * @was QJsTimerBase
 * @package QCubed\Event
 */
class JsTimerBase extends QControl
{
    // Values determining the state of the timer
    /** Constant used to indicate that the timer has stopped */
    const STOPPED = 0;
    /** Constant used to indicate that the timer has started */
    const STARTED = 1;
    /** Constant used to indicate that the timer has autostart enabled (starts with the page load) */
    const AUTO_START = 2;

    /** @var bool does the timer run periodically once started? */
    protected $blnPeriodic = true;
    /** @var int The duration after which the timer will fire (in milliseconds) */
    protected $intDeltaTime = 0;
    /** @var int default state in which timer will be (stopped) */
    protected $intState = self::STOPPED;
    /** @var bool should the timer start after a QServerAction occurrs. */
    protected $blnRestartOnServerAction = false;


    /**
     * @param QForm|QControl $objParentObject the form or parent control
     * @param int $intTime timer interval in ms
     * @param boolean $blnPeriodic if true the timer is "restarted" automatically after it has fired
     * @param boolean $blnStartNow starts the timer automatically after adding the first action
     * @param string $strTimerId
     *
     * @throws Caller
     */
    public function __construct(
        $objParentObject,
        $intTime = 0,
        $blnPeriodic = true,
        $blnStartNow = true,
        $strTimerId = null
    ) {
        try {
            parent::__construct($objParentObject, $strTimerId);
        } catch (Caller $objExc) {
            $objExc->incrementOffset();
            throw $objExc;
        }

        $this->intDeltaTime = $intTime;
        $this->blnPeriodic = $blnPeriodic;
        if ($intTime != self::STOPPED && $blnStartNow) {
            $this->intState = self::AUTO_START;
        } //prepare to start the timer after the first action gets added
    }

    /**
     * Returns the callback string
     * @return string
     */
    private function callbackString()
    {
        return "qcubed._objTimers['" . $this->strControlId . "_cb']";
    }

    /**
     * Returns a timer ID (string) as an element of the 'qcubed._objTimers' javascript array.
     * This array is used to start and stop timers (and keep track)
     * @return string
     */
    private function tidString()
    {
        return "qcubed._objTimers['" . $this->strControlId . "_tId']";
    }

    /**
     * @param int $intTime (optional)
     *              sets the interval/delay, after that the timer executes the registered actions
     *              if no parameter is given the time stored in $intDeltaTime is used
     * @throws Caller
     * @return void
     */
    public function start($intTime = null)
    {
        $this->stop();
        if ($intTime != null && is_int($intTime)) {
            $this->intDeltaTime = $intTime;
        }
        $event = $this->getEvent();
        if (!$event) {
            throw new Caller("Can't start the timer: add an Event/Action first!");
        }

        // Is the timer periodic or runs only once?
        if ($this->blnPeriodic) {
            // timer is periodic. We will set the interval
            $strJS = $this->tidString() . ' = window.setInterval("' . $this->callbackString() . '()", ' . $this->intDeltaTime . ');';
        } else {
            // timer is not periodic. We will set the timeout
            $strJS = $this->tidString() . ' = window.setTimeout("' . $this->callbackString() . '()", ' . $this->intDeltaTime . ');';
        }
        QApplication::executeJavaScript($strJS);
        $this->intState = self::STARTED;
    }

    /**
     * stops the timer
     */
    public function stop()
    {
        $event = $this->getEvent();
        if (!$event) {
            throw new Caller('Can\'t stop the timer: no Event/Action present!');
        }
        // Is timer periodic or one-time?
        if ($this->blnPeriodic) {
            // Periodic timer. We should clear the interval we had set beforehand
            $strJS = 'window.clearInterval(' . $this->tidString() . ');';
        } else {
            // One-time timer. We should clear the timeout we had set beforehand
            $strJS = 'window.clearTimeout(' . $this->tidString() . ');';
        }
        QApplication::executeJavaScript($strJS);
        $this->intState = self::STOPPED;
    }

    /**
     * Adds an action to the control
     *
     * @param QEvent $objEvent has to be an instance of QTimerExpiredEvent
     * @param QAction $objAction Only a QTimerExpiredEvent can be added,
     *                                         but multiple Actions using the same event are possible!
     *
     * @throws Caller
     * @return void
     */
    public function addAction($objEvent, $objAction)
    {
        if (!($objEvent instanceof Q\Event\TimerExpired)) {
            throw new Caller('First parameter of JsTimer::AddAction is expecting an object of type Event\\TimerExpired');
        }
        if (!($objAction instanceof Q\Action\Base)) {
            throw new Caller('Second parameter of AddAction is expecting an object of type Action');
        }

        $strEventName = $objEvent->EventName;
        if (!count($this->objActionArray)) {
            //no event registerd yet
            $this->objActionArray[$strEventName] = array();
        }

        // Store the Event object in the Action object
        $objAction->Event = $objEvent;

        array_push($this->objActionArray[$strEventName], $objAction);

        if ($this->intState === self::AUTO_START && $this->intDeltaTime != 0) {
            $this->start();
        } //autostart the timer

        $this->blnModified = true;
    }

    /**
     * Returns all actions connected/attached to the timer
     * @param string $strEventType
     * @param null $strActionType
     *
     * @return array
     */
    public function getAllActions($strEventType, $strActionType = null)
    {
        if (($strEventType == 'QTimerExpiredEvent' && $this->blnPeriodic == false) &&
            (($strActionType == 'QAjaxAction' && $this->objForm->CallType == QCallType::Ajax) ||
                ($strActionType == 'QServerAction' && $this->objForm->CallType == QCallType::Server))
        ) {
            //if we are in an ajax or server post and our timer is not periodic
            //and this method gets called then the timer has finished(stopped) --> set the State flag to "stopped"
            $this->intState = self::STOPPED;
        }
        return parent::getAllActions($strEventType, $strActionType);
    }

    /**
     * Remove all actions attached to the timer
     * @param null $strEventName
     */
    public function removeAllActions($strEventName = null)
    {
        $this->stop(); //no actions are registered for this timer stop it
        parent::removeAllActions($strEventName);
    }

    /**
     * @return null|
     */
    public function getEvent()
    {
        if (!count($this->objActionArray)) {
            return null;
        }
        // point to the first action in the list
        $arrActions = reset($this->objActionArray);
        return reset($arrActions)->Event;
    }

    /**
     * Returns all action attributes
     * @return string
     */
    public function renderActionScripts()
    {
        $strToReturn = $this->callbackString() . " = ";
        if (!count($this->objActionArray)) {
            return $strToReturn . 'null;';
        }

        $strToReturn .= 'function() {';

        foreach (reset($this->objActionArray) as $objAction) {
            /** @var Q\Action\Base $objAction */
            $strToReturn .= ' ' . $objAction->renderScript($this);
        }
        if ($this->ActionsMustTerminate) {
            $strToReturn .= ' return false;';
        }
        $strToReturn .= ' }; ';
        return $strToReturn;
    }

    /**
     * Returns all Javscript that needs to be executed after rendering of this control
     * (It overrides the GetEndScript of the parent to handle specific case of JsTimers)
     * @return string
     */
    public function getEndScript()
    {
        if ($this->objForm->CallType == QCallType::Server) {
            //this point is not reached on initial rendering
            if ($this->blnRestartOnServerAction && $this->intState === self::STARTED) {
                $this->start();
            } //restart after a server action
            else {
                $this->intState = self::STOPPED;
            }
        }
        return parent::getEndScript();
    }

    /**
     * PHP magic function to get value of properties of an object of this class
     * @param string $strName Name of the properties
     *
     * @return array|bool|int|mixed|null|QControl|QForm|string
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'DeltaTime':
                return $this->intDeltaTime;
            case 'Periodic':
                return $this->blnPeriodic;
            case 'Started':
                return ($this->intState === self::STARTED);
            case 'RestartOnServerAction':
                return $this->blnRestartOnServerAction;
            case 'Rendered':
                return true;
            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * PHP Magic function to set property values for an object of this class
     * @param string $strName Name of the property
     * @param string $mixValue Value of the property
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "DeltaTime":
                try {
                    $this->intDeltaTime = Type::cast($mixValue, Type::INTEGER);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
            case 'Periodic':
                try {
                    $newMode = Type::cast($mixValue, Type::BOOLEAN);
                    if ($this->blnPeriodic != $newMode) {
                        if ($this->intState === self::STARTED) {
                            $this->stop();
                            $this->blnPeriodic = $newMode;
                            $this->start();
                        } else {
                            $this->blnPeriodic = $newMode;
                        }
                    }
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
            case 'RestartOnServerAction':
                try {
                    $this->blnRestartOnServerAction = Type::cast($mixValue, Type::BOOLEAN);
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
            case "Rendered": //ensure that the control is marked as Rendered to get js updates
                $this->blnRendered = true;
                break;
            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
        }
    }

    /**
     * Render function for the Control (must not be called becasue JsTimer is not for being rendered)
     * @param bool $blnDisplayOutput useless in this case
     *
     * @return string|void
     * @throws Caller
     */
    public function render($blnDisplayOutput = true)
    {
        throw new Caller('Do not render JsTimer!');
    }

    /**
     * Add a child control to the current control (useless because JsTimer cannot have children)
     * @param Q\Control\Base $objControl
     *
     * @throws Caller
     */
    public function addChildControl(Q\Control\Base $objControl)
    {
        throw new Caller('Do not add child-controls to an instance of JsTimer!');
    }

    /**
     * Remove the child controls (useless)
     * Since JsTimer cannot have children, removing child controls does not yeild anything
     * @param string $strControlId
     * @param bool $blnRemoveFromForm
     */
    public function removeChildControl($strControlId, $blnRemoveFromForm)
    {
    }

    /**
     * Get the HTML for the control (blank in this case becuase JsTimer cannot be rendered)
     * @return string
     */
    protected function getControlHtml()
    {
        // no control html
        return "";
    }

    /**
     * This function would typically parse the data posted back by the control.
     */
    public function parsePostData()
    {
    }

    /**
     * Validation logic for control. Since we never render, we must return true to continue using the control.
     * @return bool
     */
    public function validate()
    {
        return true;
    }
}
