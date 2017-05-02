<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Control;

/**
 * Class Calendar
 *
 * This class will render a pop-up, modeless calendar control
 * that can be used to let the user pick a date.
 *
 * @package Controls
 * @property \QCubed\QDateTime MinDate
 * @property \QCubed\QDateTime MaxDate
 * @property \QCubed\QDateTime DefaultDate
 * @property int FirstDay
 * @property int|int[] NumberOfMonths
 * @property boolean AutoSize
 * @property boolean GotoCurrent
 * @property boolean IsRTL
 * @property string DateFormat
 * @property-write string DateTimeFormat
 * @property string JqDateFormat
 * @property boolean ShowButtonPanel
 * @was QCalendar
 * @package QCubed\Control
 */
class Calendar extends DateTimeTextBox
{
    protected $strJavaScripts = __JQUERY_EFFECTS__;
    protected $strStyleSheets = __JQUERY_CSS__;
    protected $datMinDate = null;
    protected $datMaxDate = null;
    protected $datDefaultDate = null;
    protected $intFirstDay = null;
    protected $mixNumberOfMonths = null;
    protected $blnAutoSize = false;
    protected $blnGotoCurrent = false;
    protected $blnIsRTL = false;
    protected $blnModified = false;
    protected $strJqDateFormat = 'M d yy';
    protected $blnShowButtonPanel = true;

    // map the JQuery datepicker format specs to QCubed \QCubed\QDateTime format specs.
    //QCubed	JQuery		PHP	Description
    //-------------------------------------------------
    //MMMM	    MM			F	Month as full name (e.g., March)
    //MMM	    M			M	Month as three-letters (e.g., Mar)
    //MM	    mm			m	Month as an integer with leading zero (e.g., 03)
    //M	        m			n	Month as an integer (e.g., 3)
    //DDDD	    DD			l	Day of week as full name (e.g., Wednesday)
    //DDD	    D			D	Day of week as three-letters (e.g., Wed)
    //DD	    dd			d	Day as an integer with leading zero (e.g., 02)
    //D	        d			j	Day as an integer (e.g., 2)
    //YYYY	    yy			Y	Year as a four-digit integer (e.g., 1977)
    //YY	    y			y	Year as a two-digit integer (e.g., 77)
    /** @var array QCubed to JQuery Map of date formates */
    private static $mapQC2JQ = array(
        'MMMM' => 'MM',
        'MMM' => 'M',
        'MM' => 'mm',
        'M' => 'm',
        'DDDD' => 'DD',
        'DDD' => 'D',
        'DD' => 'dd',
        'D' => 'd',
        'YYYY' => 'yy',
        'YY' => 'y',
    );
    private static $mapJQ2QC = null;

    public static function qcFrmt($jqFrmt)
    {
        if (!static::$mapJQ2QC) {
            static::$mapJQ2QC = array_flip(static::$mapQC2JQ);
        }

        return strtr($jqFrmt, static::$mapJQ2QC);
    }

    public static function jqFrmt($qcFrmt)
    {
        return strtr($qcFrmt, static::$mapQC2JQ);
    }

    /**
     * @deprecated Use \QCubed\Js\Helper::toJsObject
     */
    public static function jsDate(\QCubed\QDateTime $dt)
    {
        return \QCubed\Js\Helper::toJsObject($dt);
    }

    /**
     * Validates the control (default: returns true)
     *
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    protected function makeJsProperty($strProp, $strKey)
    {
        $objValue = $this->$strProp;
        if (null === $objValue) {
            return '';
        }

        return $strKey . ': ' . \QCubed\Js\Helper::toJsObject($objValue) . ', ';
    }

    /**
     * Returns the HTML for the control
     *
     * @return string The HTML which can be sent to browser
     */
    public function getControlHtml()
    {
        $strToReturn = parent::getControlHtml();

        $strJqOptions = '';
        $strJqOptions .= $this->makeJsProperty('ShowButtonPanel', 'showButtonPanel');
        $strJqOptions .= $this->makeJsProperty('JqDateFormat', 'dateFormat');
        $strJqOptions .= $this->makeJsProperty('AutoSize', 'autoSize');
        $strJqOptions .= $this->makeJsProperty('MaxDate', 'maxDate');
        $strJqOptions .= $this->makeJsProperty('MinDate', 'minDate');
        $strJqOptions .= $this->makeJsProperty('DefaultDate', 'defaultDate');
        $strJqOptions .= $this->makeJsProperty('FirstDay', 'firstDay');
        $strJqOptions .= $this->makeJsProperty('GotoCurrent', 'gotoCurrent');
        $strJqOptions .= $this->makeJsProperty('IsRTL', 'isRTL');
        $strJqOptions .= $this->makeJsProperty('NumberOfMonths', 'numberOfMonths');
        if ($strJqOptions) {
            $strJqOptions = substr($strJqOptions, 0, -2);
        }

        QApplication::executeJavaScript(
            sprintf('jQuery("#%s").datepicker({%s})', $this->strControlId, $strJqOptions));

        return $strToReturn;
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    /**
     * PHP magic method
     *
     * @param string $strName
     *
     * @return mixed
     * @throws Exception|\QCubed\Exception\Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case "MinDate":
                return $this->datMinDate;
            case "MaxDate":
                return $this->datMaxDate;
            case "DefaultDate":
                return $this->datDefaultDate;
            case "FirstDay":
                return $this->intFirstDay;
            case "GotoCurrent":
                return $this->blnGotoCurrent;
            case "IsRTL":
                return $this->blnIsRTL;
            case "NumberOfMonths":
                return $this->mixNumberOfMonths;
            case "AutoSize":
                return $this->blnAutoSize;
            case "DateFormat":
                return $this->strDateTimeFormat;
            case "JqDateFormat":
                return $this->strJqDateFormat;
            case "ShowButtonPanel":
                return $this->blnShowButtonPanel;
            default:
                try {
                    return parent::__get($strName);
                } catch (\QCubed\Exception\Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    /**
     * PHP magic method
     *
     * @param string $strName
     * @param string $mixValue
     *
     * @return mixed
     * @throws Exception|\QCubed\Exception\Caller|\QCubed\Exception\InvalidCast|Exception
     */
    public function __set($strName, $mixValue)
    {
        $this->blnModified = true;
        switch ($strName) {
            case "MinDate":
                try {
                    $this->datMinDate = \QCubed\Type::cast($mixValue, \QCubed\Type::DATE_TIME);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "MaxDate":
                $blnMaxDate = true;
                try {
                    $this->datMaxDate = \QCubed\Type::cast($mixValue, \QCubed\Type::DATE_TIME);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "DefaultDate":
                $blnDefaultDate = true;
                try {
                    $this->datDefaultDate = \QCubed\Type::cast($mixValue, \QCubed\Type::DATE_TIME);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "FirstDay":
                $blnFirstDay = true;
                try {
                    $this->intFirstDay = \QCubed\Type::cast($mixValue, \QCubed\Type::INTEGER);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "GotoCurrent":
                try {
                    $this->blnGotoCurrent = \QCubed\Type::cast($mixValue, \QCubed\Type::BOOLEAN);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "IsRTL":
                try {
                    $this->blnIsRTL = \QCubed\Type::cast($mixValue, \QCubed\Type::BOOLEAN);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "NumberOfMonths":
                $blnNumberOfMonths = true;
                if (!is_array($mixValue) && !is_numeric($mixValue)) {
                    throw new exception('NumberOfMonths must be an integer or an array');
                }
                $this->mixNumberOfMonths = $mixValue;
                break;
            case "AutoSize":
                try {
                    $this->blnAutoSize = \QCubed\Type::cast($mixValue, \QCubed\Type::BOOLEAN);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "JqDateFormat":
                try {
                    $this->strJqDateFormat = \QCubed\Type::cast($mixValue, \QCubed\Type::STRING);
                    parent::__set('DateTimeFormat', static::qcFrmt($this->strJqDateFormat));
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "DateTimeFormat":
            case "DateFormat":
                parent::__set('DateTimeFormat', $mixValue);
                $this->strJqDateFormat = static::jqFrmt($this->strDateTimeFormat);
                break;
            case "ShowButtonPanel":
                try {
                    $this->blnShowButtonPanel = \QCubed\Type::cast($mixValue, \QCubed\Type::BOOLEAN);
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (\QCubed\Exception\Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
                break;
        }
    }

    /**
     * Adds an event to the calendar
     * It overrides the base method to make sure click events are not accepted
     *
     * @param QEvent $objEvent
     * @param QAction $objAction
     *
     * @throws \QCubed\Exception\Caller
     */
    public function addAction($objEvent, $objAction)
    {
        if ($objEvent instanceof QClickEvent) {
            throw new \QCubed\Exception\Caller('QCalendar does not support click events');
        }
        parent::addAction($objEvent, $objAction);
    }
}
