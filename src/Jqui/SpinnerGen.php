<?php
namespace QCubed\Jqui;

use QCubed;
use QCubed\Type;
use QCubed\Project\Application;
use QCubed\Exception\InvalidCast;
use QCubed\Exception\Caller;
use QCubed\ModelConnector\Param as QModelConnectorParam;

/**
 * Class SpinnerGen
 *
 * This is the SpinnerGen class which is automatically generated
 * by scraping the JQuery UI documentation website. As such, it includes all the options
 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
 * the SpinnerBase class for any glue code to make this class more
 * usable in QCubed.
 *
 * @see SpinnerBase
 * @package QCubed\Jqui
 * @property mixed $Classes
 * Specify additional classes to add to the widgets elements. Any of
 * classes specified in the Theming section can be used as keys to
 * override their value. To learn more about this option, check out the
 * learn article about the classes option.

 *
 * @property string $Culture
 * Sets the culture to use for parsing and formatting the value. If null,
 * the currently set culture in Globalize is used, see Globalize docs for
 * available cultures. Only relevant if the numberFormat option is set.
 * Requires Globalize to be included.
 *
 * @property boolean $Disabled
 * Disables the spinner if set to true.
 *
 * @property mixed $Icons
 * Icons to use for buttons, matching an icon provided by the jQuery UI
 * CSS Framework. 
 * 
 * 	* up (string, default: "ui-icon-triangle-1-n")
 * 	* down (string, default: "ui-icon-triangle-1-s")
 * 

 *
 * @property mixed $Incremental
 * Controls the number of steps taken when holding down a spin
 * button.Multiple types supported:
 * 
 * 	* Boolean: When set to true, the stepping delta will increase when
 * spun incessantly. When set to false, all steps are equal (as defined
 * by the step option).
 * 	* Function: Receives one parameter: the number of spins that have
 * occurred. Must return the number of steps that should occur for the
 * current spin.
 * 

 *
 * @property mixed $Max
 * The maximum allowed value. The elements max attribute is used if it
 * exists and the option is not explicitly set. If null, there is no
 * maximum enforced.Multiple types supported:
 * 
 * 	* Number: The maximum value.
 * 	* String: If Globalize is included, the max option can be passed as
 * a string which will be parsed based on the numberFormat and culture
 * options; otherwise it will fall back to the native parseFloat()
 * method.
 * 

 *
 * @property mixed $Min
 * The minimum allowed value. The elements min attribute is used if it
 * exists and the option is not explicitly set. If null, there is no
 * minimum enforced.Multiple types supported:
 * 
 * 	* Number: The minimum value.
 * 	* String: If Globalize is included, the min option can be passed as
 * a string which will be parsed based on the numberFormat and culture
 * options; otherwise it will fall back to the native parseFloat()
 * method.
 * 

 *
 * @property string $NumberFormat
 * Format of numbers passed to Globalize, if available. Most common are
 * "n" for a decimal number and "C" for a currency value. Also see the
 * culture option.
 *
 * @property integer $Page
 * The number of steps to take when paging via the pageUp/pageDown
 * methods.
 *
 * @property mixed $Step
 * The size of the step to take when spinning via buttons or via the
 * stepUp()/stepDown() methods. The elements step attribute is used if it
 * exists and the option is not explicitly set.Multiple types supported:
 * 
 * 	* Number: The size of the step.
 * 	* String: If Globalize is included, the step option can be passed as
 * a string which will be parsed based on the numberFormat and culture
 * options, otherwise it will fall back to the native parseFloat.
 * 

 *
 * @was QSpinnerGen

 */

class SpinnerGen extends QCubed\Project\Control\TextBox
{
    protected $strJavaScripts = __JQUERY_EFFECTS__;
    protected $strStyleSheets = __JQUERY_CSS__;
    /** @var mixed */
    protected $mixClasses = null;
    /** @var string */
    protected $strCulture = null;
    /** @var boolean */
    protected $blnDisabled = null;
    /** @var mixed */
    protected $mixIcons = null;
    /** @var mixed */
    protected $mixIncremental = null;
    /** @var mixed */
    protected $mixMax = null;
    /** @var mixed */
    protected $mixMin = null;
    /** @var string */
    protected $strNumberFormat = null;
    /** @var integer */
    protected $intPage = null;
    /** @var mixed */
    protected $mixStep = null;

    /**
     * Builds the option array to be sent to the widget constructor.
     *
     * @return array key=>value array of options
     */
    protected function makeJqOptions() {
        $jqOptions = null;
        if (!is_null($val = $this->Classes)) {$jqOptions['classes'] = $val;}
        if (!is_null($val = $this->Culture)) {$jqOptions['culture'] = $val;}
        if (!is_null($val = $this->Disabled)) {$jqOptions['disabled'] = $val;}
        if (!is_null($val = $this->Icons)) {$jqOptions['icons'] = $val;}
        if (!is_null($val = $this->Incremental)) {$jqOptions['incremental'] = $val;}
        if (!is_null($val = $this->Max)) {$jqOptions['max'] = $val;}
        if (!is_null($val = $this->Min)) {$jqOptions['min'] = $val;}
        if (!is_null($val = $this->NumberFormat)) {$jqOptions['numberFormat'] = $val;}
        if (!is_null($val = $this->Page)) {$jqOptions['page'] = $val;}
        if (!is_null($val = $this->Step)) {$jqOptions['step'] = $val;}
        return $jqOptions;
    }

    /**
     * Return the JavaScript function to call to associate the widget with the control.
     *
     * @return string
     */
    public function getJqSetupFunction()
    {
        return 'spinner';
    }

    /**
     * Returns the script that attaches the JQueryUI widget to the html object.
     *
     * @return string
     */
    public function getEndScript()
    {
        $strId = $this->getJqControlId();
        $jqOptions = $this->makeJqOptions();
        $strFunc = $this->getJqSetupFunction();

        if ($strId !== $this->ControlId && Application::isAjax()) {
            // If events are not attached to the actual object being drawn, then the old events will not get
            // deleted during redraw. We delete the old events here. This must happen before any other event processing code.
            Application::instance()->executeControlCommand($strId, 'off', QJsPriority::High);
        }

        // Attach the javascript widget to the html object
        if (empty($jqOptions)) {
            Application::instance()->executeControlCommand($strId, $strFunc, Application::PRIORITY_HIGH);
        } else {
            Application::instance()->executeControlCommand($strId, $strFunc, $jqOptions, Application::PRIORITY_HIGH);
        }

        return parent::getEndScript();
    }

    /**
     * Removes the spinner functionality completely. This will return the
     * element back to its pre-init state.
     * 
     * 	* This method does not accept any arguments.
     */
    public function destroy()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", Application::PRIORITY_LOW);
    }
    /**
     * Disables the spinner.
     * 
     * 	* This method does not accept any arguments.
     */
    public function disable()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "disable", Application::PRIORITY_LOW);
    }
    /**
     * Enables the spinner.
     * 
     * 	* This method does not accept any arguments.
     */
    public function enable()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "enable", Application::PRIORITY_LOW);
    }
    /**
     * Retrieves the spinners instance object. If the element does not have
     * an associated instance, undefined is returned.
     * 
     * Unlike other widget methods, instance() is safe to call on any element
     * after the spinner plugin has loaded.
     * 
     * 	* This method does not accept any arguments.
     */
    public function instance()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", Application::PRIORITY_LOW);
    }
    /**
     * Returns whether the Spinners value is valid given its min, max, and
     * step.
     * 
     * 	* This method does not accept any arguments.
     */
    public function isValid()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "isValid", Application::PRIORITY_LOW);
    }
    /**
     * Gets the value currently associated with the specified optionName.
     * 
     * Note: For options that have objects as their value, you can get the
     * value of a specific key by using dot notation. For example, "foo.bar"
     * would get the value of the bar property on the foo option.
     * 
     * 	* optionName Type: String The name of the option to get.
     * @param $optionName
     */
    public function option($optionName)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, Application::PRIORITY_LOW);
    }
    /**
     * Gets an object containing key/value pairs representing the current
     * spinner options hash.
     * 
     * 	* This signature does not accept any arguments.
     */
    public function option1()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", Application::PRIORITY_LOW);
    }
    /**
     * Sets the value of the spinner option associated with the specified
     * optionName.
     * 
     * Note: For options that have objects as their value, you can set the
     * value of just one property by using dot notation for optionName. For
     * example, "foo.bar" would update only the bar property of the foo
     * option.
     * 
     * 	* optionName Type: String The name of the option to set.
     * 	* value Type: Object A value to set for the option.
     * @param $optionName
     * @param $value
     */
    public function option2($optionName, $value)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, $value, Application::PRIORITY_LOW);
    }
    /**
     * Sets one or more options for the spinner.
     * 
     * 	* options Type: Object A map of option-value pairs to set.
     * @param $options
     */
    public function option3($options)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, Application::PRIORITY_LOW);
    }
    /**
     * Decrements the value by the specified number of pages, as defined by
     * the page option. Without the parameter, a single page is decremented.
     * 
     * If the resulting value is above the max, below the min, or results in
     * a step mismatch, the value will be adjusted to the closest valid
     * value.
     * 
     * Invoking pageDown() will cause start, spin, and stop events to be
     * triggered.
     * 
     * 	* pages Type: Number Number of pages to decrement, defaults to 1.
     * @param $pages
     */
    public function pageDown($pages = null)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "pageDown", $pages, Application::PRIORITY_LOW);
    }
    /**
     * Increments the value by the specified number of pages, as defined by
     * the page option. Without the parameter, a single page is incremented.
     * 
     * If the resulting value is above the max, below the min, or results in
     * a step mismatch, the value will be adjusted to the closest valid
     * value.
     * 
     * Invoking pageUp() will cause start, spin, and stop events to be
     * triggered.
     * 
     * 	* pages Type: Number Number of pages to increment, defaults to 1.
     * @param $pages
     */
    public function pageUp($pages = null)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "pageUp", $pages, Application::PRIORITY_LOW);
    }
    /**
     * Decrements the value by the specified number of steps. Without the
     * parameter, a single step is decremented.
     * 
     * If the resulting value is above the max, below the min, or results in
     * a step mismatch, the value will be adjusted to the closest valid
     * value.
     * 
     * Invoking stepDown() will cause start, spin, and stop events to be
     * triggered.
     * 
     * 	* steps Type: Number Number of steps to decrement, defaults to 1.
     * @param $steps
     */
    public function stepDown($steps = null)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "stepDown", $steps, Application::PRIORITY_LOW);
    }
    /**
     * Increments the value by the specified number of steps. Without the
     * parameter, a single step is incremented.
     * 
     * If the resulting value is above the max, below the min, or results in
     * a step mismatch, the value will be adjusted to the closest valid
     * value.
     * 
     * Invoking stepUp() will cause start, spin, and stop events to be
     * triggered.
     * 
     * 	* steps Type: Number Number of steps to increment, defaults to 1.
     * @param $steps
     */
    public function stepUp($steps = null)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "stepUp", $steps, Application::PRIORITY_LOW);
    }
    /**
     * Gets the current value as a number. The value is parsed based on the
     * numberFormat and culture options.
     * 
     * 	* This signature does not accept any arguments.
     */
    public function value()
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "value", Application::PRIORITY_LOW);
    }
    /**
     * * value Type: Number or String The value to set. If passed as a
     * string, the value is parsed based on the numberFormat and culture
     * options.
     * @param $value
     */
    public function value1($value)
    {
        Application::instance()->executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "value", $value, Application::PRIORITY_LOW);
    }


    public function __get($strName)
    {
        switch ($strName) {
            case 'Classes': return $this->mixClasses;
            case 'Culture': return $this->strCulture;
            case 'Disabled': return $this->blnDisabled;
            case 'Icons': return $this->mixIcons;
            case 'Incremental': return $this->mixIncremental;
            case 'Max': return $this->mixMax;
            case 'Min': return $this->mixMin;
            case 'NumberFormat': return $this->strNumberFormat;
            case 'Page': return $this->intPage;
            case 'Step': return $this->mixStep;
            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case 'Classes':
                $this->mixClasses = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'classes', $mixValue);
                break;

            case 'Culture':
                try {
                    $this->strCulture = Type::Cast($mixValue, QType::String);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'culture', $this->strCulture);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Disabled':
                try {
                    $this->blnDisabled = Type::Cast($mixValue, QType::Boolean);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'disabled', $this->blnDisabled);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Icons':
                $this->mixIcons = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'icons', $mixValue);
                break;

            case 'Incremental':
                $this->mixIncremental = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'incremental', $mixValue);
                break;

            case 'Max':
                $this->mixMax = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'max', $mixValue);
                break;

            case 'Min':
                $this->mixMin = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'min', $mixValue);
                break;

            case 'NumberFormat':
                try {
                    $this->strNumberFormat = Type::Cast($mixValue, QType::String);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'numberFormat', $this->strNumberFormat);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Page':
                try {
                    $this->intPage = Type::Cast($mixValue, QType::Integer);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'page', $this->intPage);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Step':
                $this->mixStep = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'step', $mixValue);
                break;


            case 'Enabled':
                $this->Disabled = !$mixValue;	// Tie in standard QCubed functionality
                parent::__set($strName, $mixValue);
                break;

            default:
                try {
                    parent::__set($strName, $mixValue);
                    break;
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
    * If this control is attachable to a codegenerated control in a ModelConnector, this function will be
    * used by the ModelConnector designer dialog to display a list of options for the control.
    * @return QModelConnectorParam[]
    **/
    public static function getModelConnectorParams()
    {
        return array_merge(parent::GetModelConnectorParams(), array(
            new QModelConnectorParam (get_called_class(), 'Culture', 'Sets the culture to use for parsing and formatting the value. If null,the currently set culture in Globalize is used, see Globalize docs foravailable cultures. Only relevant if the numberFormat option is set.Requires Globalize to be included.', QType::String),
            new QModelConnectorParam (get_called_class(), 'Disabled', 'Disables the spinner if set to true.', QType::Boolean),
            new QModelConnectorParam (get_called_class(), 'NumberFormat', 'Format of numbers passed to Globalize, if available. Most common are\"n\" for a decimal number and \"C\" for a currency value. Also see theculture option.', QType::String),
            new QModelConnectorParam (get_called_class(), 'Page', 'The number of steps to take when paging via the pageUp/pageDownmethods.', QType::Integer),
        ));
    }
}
