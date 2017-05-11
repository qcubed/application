<?php
namespace QCubed\Jqui;

use QCubed;
use QCubed\Type;
use QCubed\Project\Application;
use QCubed\Exception\InvalidCast;
use QCubed\Exception\Caller;
use QCubed\ModelConnector\Param as QModelConnectorParam;

/**
 * Class DialogGen
 *
 * This is the DialogGen class which is automatically generated
 * by scraping the JQuery UI documentation website. As such, it includes all the options
 * as listed by the JQuery UI website, which may or may not be appropriate for QCubed. See
 * the DialogBase class for any glue code to make this class more
 * usable in QCubed.
 *
 * @see DialogBase
 * @package QCubed\Jqui
 * @property mixed $AppendTo
 * Which element the dialog (and overlay, if modal) should be appended
 * to.
 * Note: The appendTo option should not be changed while the dialog is
 * open. (version added: 1.10.0)
 *
 * @property boolean $AutoOpen
 * If set to true, the dialog will automatically open upon
 * initialization. If false, the dialog will stay hidden until the open()
 * method is called.
 *
 * @property mixed $Buttons
 * Specifies which buttons should be displayed on the dialog. The context
 * of the callback is the dialog element; if you need access to the
 * button, it is available as the target of the event object.Multiple
 * types supported:
 * 
 * 	* Object: The keys are the button labels and the values are the
 * callbacks for when the associated button is clicked.
 * 	* Array: Each element of the array must be an object defining the
 * attributes, properties, and event handlers to set on the button. In
 * addition, a key of icons can be used to control buttons icons option,
 * and a key of showText can be used to control buttons text option.
 * 

 *
 * @property mixed $Classes
 * Specify additional classes to add to the widgets elements. Any of
 * classes specified in the Theming section can be used as keys to
 * override their value. To learn more about this option, check out the
 * learn article about the classes option.

 *
 * @property boolean $CloseOnEscape
 * Specifies whether the dialog should close when it has focus and the
 * user presses the escape (ESC) key.
 *
 * @property string $CloseText
 * Specifies the text for the close button. Note that the close text is
 * visibly hidden when using a standard theme.
 *
 * @property string $DialogClass
 * The specified class name(s) will be added to the dialog, for
 * additional theming.
 * 
 * The dialogClass option has been deprecated in favor of the classes
 * option, using the ui-dialog property.
 * (version deprecated: 1.12)
 *
 * @property boolean $Draggable
 * If set to true, the dialog will be draggable by the title bar.
 * Requires the jQuery UI Draggable widget to be included.
 *
 * @property mixed $Height
 * The height of the dialog.Multiple types supported:
 * 
 * 	* Number: The height in pixels.
 * 	* String: The only supported string value is "auto" which will allow
 * the dialog height to adjust based on its content.
 * 

 *
 * @property mixed $Hide
 * If and how to animate the hiding of the dialog.Multiple types
 * supported:
 * 
 * 	* Boolean: When set to false, no animation will be used and the
 * dialog will be hidden immediately. When set to true, the dialog will
 * fade out with the default duration and the default easing.
 * 	* Number: The dialog will fade out with the specified duration and
 * the default easing.
 * 	* String: The dialog will be hidden using the specified effect. The
 * value can either be the name of a built-in jQuery animation method,
 * such as "slideUp", or the name of a jQuery UI effect, such as "fold".
 * In either case the effect will be used with the default duration and
 * the default easing.
 * 	* Object: If the value is an object, then effect, delay, duration,
 * and easing properties may be provided. If the effect property contains
 * the name of a jQuery method, then that method will be used; otherwise
 * it is assumed to be the name of a jQuery UI effect. When using a
 * jQuery UI effect that supports additional settings, you may include
 * those settings in the object and they will be passed to the effect. If
 * duration or easing is omitted, then the default values will be used.
 * If effect is omitted, then "fadeOut" will be used. If delay is
 * omitted, then no delay is used.
 * 

 *
 * @property integer $MaxHeight
 * The maximum height to which the dialog can be resized, in pixels.
 *
 * @property integer $MaxWidth
 * The maximum width to which the dialog can be resized, in pixels.
 *
 * @property integer $MinHeight
 * The minimum height to which the dialog can be resized, in pixels.
 *
 * @property integer $MinWidth
 * The minimum width to which the dialog can be resized, in pixels.
 *
 * @property boolean $Modal
 * If set to true, the dialog will have modal behavior; other items on
 * the page will be disabled, i.e., cannot be interacted with. Modal
 * dialogs create an overlay below the dialog but above other page
 * elements.
 *
 * @property mixed $Position
 * Specifies where the dialog should be displayed when opened. The dialog
 * will handle collisions such that as much of the dialog is visible as
 * possible.
 * 
 * The of property defaults to the window, but you can specify another
 * element to position against. You can refer to the jQuery UI Position
 * utility for more details about the available properties.

 *
 * @property boolean $Resizable
 * If set to true, the dialog will be resizable. Requires the jQuery UI
 * Resizable widget to be included.
 *
 * @property mixed $Show
 * If and how to animate the showing of the dialog.Multiple types
 * supported:
 * 
 * 	* Boolean: When set to false, no animation will be used and the
 * dialog will be shown immediately. When set to true, the dialog will
 * fade in with the default duration and the default easing.
 * 	* Number: The dialog will fade in with the specified duration and
 * the default easing.
 * 	* String: The dialog will be shown using the specified effect. The
 * value can either be the name of a built-in jQuery animation method,
 * such as "slideDown", or the name of a jQuery UI effect, such as
 * "fold". In either case the effect will be used with the default
 * duration and the default easing.
 * 	* Object: If the value is an object, then effect, delay, duration,
 * and easing properties may be provided. If the effect property contains
 * the name of a jQuery method, then that method will be used; otherwise
 * it is assumed to be the name of a jQuery UI effect. When using a
 * jQuery UI effect that supports additional settings, you may include
 * those settings in the object and they will be passed to the effect. If
 * duration or easing is omitted, then the default values will be used.
 * If effect is omitted, then "fadeIn" will be used. If delay is omitted,
 * then no delay is used.
 * 

 *
 * @property string $Title
 * Specifies the title of the dialog. If the value is null, the title
 * attribute on the dialog source element will be used.
 *
 * @property integer $Width
 * The width of the dialog, in pixels.
 *
 * @was QDialogGen

 */

class DialogGen extends QCubed\Control\Panel
{
    protected $strJavaScripts = __JQUERY_EFFECTS__;
    protected $strStyleSheets = __JQUERY_CSS__;
    /** @var mixed */
    protected $mixAppendTo = null;
    /** @var boolean */
    protected $blnAutoOpen = null;
    /** @var mixed */
    protected $mixButtons = null;
    /** @var mixed */
    protected $mixClasses = null;
    /** @var boolean */
    protected $blnCloseOnEscape = null;
    /** @var string */
    protected $strCloseText = null;
    /** @var string */
    protected $strDialogClass = null;
    /** @var boolean */
    protected $blnDraggable = null;
    /** @var mixed */
    protected $mixHeight = null;
    /** @var mixed */
    protected $mixHide = null;
    /** @var integer */
    protected $intMaxHeight = null;
    /** @var integer */
    protected $intMaxWidth = null;
    /** @var integer */
    protected $intMinHeight = null;
    /** @var integer */
    protected $intMinWidth = null;
    /** @var boolean */
    protected $blnModal = null;
    /** @var mixed */
    protected $mixPosition = null;
    /** @var boolean */
    protected $blnResizable = null;
    /** @var mixed */
    protected $mixShow = null;
    /** @var string */
    protected $strTitle = null;
    /** @var integer */
    protected $intWidth = null;

    /**
     * Builds the option array to be sent to the widget constructor.
     *
     * @return array key=>value array of options
     */
    protected function makeJqOptions() {
        $jqOptions = null;
        if (!is_null($val = $this->AppendTo)) {$jqOptions['appendTo'] = $val;}
        if (!is_null($val = $this->AutoOpen)) {$jqOptions['autoOpen'] = $val;}
        if (!is_null($val = $this->Buttons)) {$jqOptions['buttons'] = $val;}
        if (!is_null($val = $this->Classes)) {$jqOptions['classes'] = $val;}
        if (!is_null($val = $this->CloseOnEscape)) {$jqOptions['closeOnEscape'] = $val;}
        if (!is_null($val = $this->CloseText)) {$jqOptions['closeText'] = $val;}
        if (!is_null($val = $this->DialogClass)) {$jqOptions['dialogClass'] = $val;}
        if (!is_null($val = $this->Draggable)) {$jqOptions['draggable'] = $val;}
        if (!is_null($val = $this->Height)) {$jqOptions['height'] = $val;}
        if (!is_null($val = $this->Hide)) {$jqOptions['hide'] = $val;}
        if (!is_null($val = $this->MaxHeight)) {$jqOptions['maxHeight'] = $val;}
        if (!is_null($val = $this->MaxWidth)) {$jqOptions['maxWidth'] = $val;}
        if (!is_null($val = $this->MinHeight)) {$jqOptions['minHeight'] = $val;}
        if (!is_null($val = $this->MinWidth)) {$jqOptions['minWidth'] = $val;}
        if (!is_null($val = $this->Modal)) {$jqOptions['modal'] = $val;}
        if (!is_null($val = $this->Position)) {$jqOptions['position'] = $val;}
        if (!is_null($val = $this->Resizable)) {$jqOptions['resizable'] = $val;}
        if (!is_null($val = $this->Show)) {$jqOptions['show'] = $val;}
        if (!is_null($val = $this->Title)) {$jqOptions['title'] = $val;}
        if (!is_null($val = $this->Width)) {$jqOptions['width'] = $val;}
        return $jqOptions;
    }

    /**
     * Return the JavaScript function to call to associate the widget with the control.
     *
     * @return string
     */
    public function getJqSetupFunction()
    {
        return 'dialog';
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
            Application::executeControlCommand($strId, 'off', Application::PRIORITY_HIGH);
        }

        // Attach the javascript widget to the html object
        if (empty($jqOptions)) {
            Application::executeControlCommand($strId, $strFunc, Application::PRIORITY_HIGH);
        } else {
            Application::executeControlCommand($strId, $strFunc, $jqOptions, Application::PRIORITY_HIGH);
        }

        return parent::getEndScript();
    }

    /**
     * Closes the dialog.
     * 
     * 	* This method does not accept any arguments.
     */
    public function close()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "close", Application::PRIORITY_LOW);
    }
    /**
     * Removes the dialog functionality completely. This will return the
     * element back to its pre-init state.
     * 
     * 	* This method does not accept any arguments.
     */
    public function destroy()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "destroy", Application::PRIORITY_LOW);
    }
    /**
     * Retrieves the dialogs instance object. If the element does not have an
     * associated instance, undefined is returned.
     * 
     * Unlike other widget methods, instance() is safe to call on any element
     * after the dialog plugin has loaded.
     * 
     * 	* This method does not accept any arguments.
     */
    public function instance()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "instance", Application::PRIORITY_LOW);
    }
    /**
     * Whether the dialog is currently open.
     * 
     * 	* This method does not accept any arguments.
     */
    public function isOpen()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "isOpen", Application::PRIORITY_LOW);
    }
    /**
     * Moves the dialog to the top of the dialog stack.
     * 
     * 	* This method does not accept any arguments.
     */
    public function moveToTop()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "moveToTop", Application::PRIORITY_LOW);
    }
    /**
     * Opens the dialog.
     * 
     * 	* This method does not accept any arguments.
     */
    public function open()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "open", Application::PRIORITY_LOW);
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
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, Application::PRIORITY_LOW);
    }
    /**
     * Gets an object containing key/value pairs representing the current
     * dialog options hash.
     * 
     * 	* This signature does not accept any arguments.
     */
    public function option1()
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", Application::PRIORITY_LOW);
    }
    /**
     * Sets the value of the dialog option associated with the specified
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
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $optionName, $value, Application::PRIORITY_LOW);
    }
    /**
     * Sets one or more options for the dialog.
     * 
     * 	* options Type: Object A map of option-value pairs to set.
     * @param $options
     */
    public function option3($options)
    {
        Application::executeControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), "option", $options, Application::PRIORITY_LOW);
    }


    public function __get($strName)
    {
        switch ($strName) {
            case 'AppendTo': return $this->mixAppendTo;
            case 'AutoOpen': return $this->blnAutoOpen;
            case 'Buttons': return $this->mixButtons;
            case 'Classes': return $this->mixClasses;
            case 'CloseOnEscape': return $this->blnCloseOnEscape;
            case 'CloseText': return $this->strCloseText;
            case 'DialogClass': return $this->strDialogClass;
            case 'Draggable': return $this->blnDraggable;
            case 'Height': return $this->mixHeight;
            case 'Hide': return $this->mixHide;
            case 'MaxHeight': return $this->intMaxHeight;
            case 'MaxWidth': return $this->intMaxWidth;
            case 'MinHeight': return $this->intMinHeight;
            case 'MinWidth': return $this->intMinWidth;
            case 'Modal': return $this->blnModal;
            case 'Position': return $this->mixPosition;
            case 'Resizable': return $this->blnResizable;
            case 'Show': return $this->mixShow;
            case 'Title': return $this->strTitle;
            case 'Width': return $this->intWidth;
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
            case 'AppendTo':
                $this->mixAppendTo = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'appendTo', $mixValue);
                break;

            case 'AutoOpen':
                try {
                    $this->blnAutoOpen = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoOpen', $this->blnAutoOpen);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Buttons':
                $this->mixButtons = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'buttons', $mixValue);
                break;

            case 'Classes':
                $this->mixClasses = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'classes', $mixValue);
                break;

            case 'CloseOnEscape':
                try {
                    $this->blnCloseOnEscape = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'closeOnEscape', $this->blnCloseOnEscape);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'CloseText':
                try {
                    $this->strCloseText = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'closeText', $this->strCloseText);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'DialogClass':
                try {
                    $this->strDialogClass = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'dialogClass', $this->strDialogClass);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Draggable':
                try {
                    $this->blnDraggable = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'draggable', $this->blnDraggable);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Height':
                $this->mixHeight = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'height', $mixValue);
                break;

            case 'Hide':
                $this->mixHide = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'hide', $mixValue);
                break;

            case 'MaxHeight':
                try {
                    $this->intMaxHeight = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'maxHeight', $this->intMaxHeight);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'MaxWidth':
                try {
                    $this->intMaxWidth = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'maxWidth', $this->intMaxWidth);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'MinHeight':
                try {
                    $this->intMinHeight = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'minHeight', $this->intMinHeight);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'MinWidth':
                try {
                    $this->intMinWidth = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'minWidth', $this->intMinWidth);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Modal':
                try {
                    $this->blnModal = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'modal', $this->blnModal);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Position':
                $this->mixPosition = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'position', $mixValue);
                break;

            case 'Resizable':
                try {
                    $this->blnResizable = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'resizable', $this->blnResizable);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Show':
                $this->mixShow = $mixValue;
                $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'show', $mixValue);
                break;

            case 'Title':
                try {
                    $this->strTitle = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'title', $this->strTitle);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Width':
                try {
                    $this->intWidth = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'width', $this->intWidth);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }


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
            new QModelConnectorParam (get_called_class(), 'AutoOpen', 'If set to true, the dialog will automatically open uponinitialization. If false, the dialog will stay hidden until the open()method is called.', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'CloseOnEscape', 'Specifies whether the dialog should close when it has focus and theuser presses the escape (ESC) key.', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'CloseText', 'Specifies the text for the close button. Note that the close text isvisibly hidden when using a standard theme.', Type::STRING),
            new QModelConnectorParam (get_called_class(), 'DialogClass', 'The specified class name(s) will be added to the dialog, foradditional theming.The dialogClass option has been deprecated in favor of the classesoption, using the ui-dialog property.(version deprecated: 1.12)', Type::STRING),
            new QModelConnectorParam (get_called_class(), 'Draggable', 'If set to true, the dialog will be draggable by the title bar.Requires the jQuery UI Draggable widget to be included.', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'MaxHeight', 'The maximum height to which the dialog can be resized, in pixels.', Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'MaxWidth', 'The maximum width to which the dialog can be resized, in pixels.', Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'MinHeight', 'The minimum height to which the dialog can be resized, in pixels.', Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'MinWidth', 'The minimum width to which the dialog can be resized, in pixels.', Type::INTEGER),
            new QModelConnectorParam (get_called_class(), 'Modal', 'If set to true, the dialog will have modal behavior; other items onthe page will be disabled, i.e., cannot be interacted with. Modaldialogs create an overlay below the dialog but above other pageelements.', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'Resizable', 'If set to true, the dialog will be resizable. Requires the jQuery UIResizable widget to be included.', Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'Title', 'Specifies the title of the dialog. If the value is null, the titleattribute on the dialog source element will be used.', Type::STRING),
            new QModelConnectorParam (get_called_class(), 'Width', 'The width of the dialog, in pixels.', Type::INTEGER),
        ));
    }
}
