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
 * Class DateTimeTextBox
 *
 * @property \QCubed\QDateTime $Maximum
 * @property \QCubed\QDateTime $Minimum
 * @property string $DateTimeFormat
 * @property \QCubed\QDateTime $DateTime
 * @property string $LabelForInvalid
 * @was QDateTimeTextBox
 * @package QCubed\Control
 */
class DateTimeTextBox extends \QCubed\Project\Control\TextBox
{
    ///////////////////////////
    // Private Member Variables
    ///////////////////////////

    // MISC
    protected $dttMinimum = null;
    protected $dttMaximum = null;
    protected $strDateTimeFormat = "MMM D, YYYY";
    protected $dttDateTime = null;

    protected $strLabelForInvalid = 'For example, "Mar 20, 4:30pm" or "Mar 20"';
    protected $calLinkedControl;

    //////////
    // Methods
    //////////

    public function ParsePostData()
    {
        // Check to see if this Control's Value was passed in via the POST data
        if (array_key_exists($this->strControlId, $_POST)) {
            parent::ParsePostData();
            $this->dttDateTime = QDateTimeTextBox::ParseForDateTimeValue($this->strText);
        }
    }

    public static function ParseForDateTimeValue($strText)
    {
        // Trim and Clean
        $strText = strtolower(trim($strText));
        while (strpos($strText, '  ') !== false) {
            $strText = str_replace('  ', ' ', $strText);
        }
        $strText = str_replace('.', '', $strText);
        $strText = str_replace('@', ' ', $strText);

        // Are we ATTEMPTING to parse a Time value?
        if ((strpos($strText, ':') === false) &&
            (strpos($strText, 'am') === false) &&
            (strpos($strText, 'pm') === false)
        ) {
            // There is NO TIME VALUE
            $dttToReturn = new \QCubed\QDateTime($strText);
            if ($dttToReturn->IsDateNull()) {
                return null;
            } else {
                return $dttToReturn;
            }
        }

        // Add ':00' if it doesn't exist AND if 'am' or 'pm' exists
        if ((strpos($strText, 'pm') !== false) &&
            (strpos($strText, ':') === false)
        ) {
            $strText = str_replace(' pm', ':00 pm', $strText, $intCount);
            if (!$intCount) {
                $strText = str_replace('pm', ':00 pm', $strText, $intCount);
            }
        } else {
            if ((strpos($strText, 'am') !== false) &&
                (strpos($strText, ':') === false)
            ) {
                $strText = str_replace(' am', ':00 am', $strText, $intCount);
                if (!$intCount) {
                    $strText = str_replace('am', ':00 am', $strText, $intCount);
                }
            }
        }

        $dttToReturn = new \QCubed\QDateTime($strText);
        if ($dttToReturn->IsDateNull()) {
            return null;
        } else {
            return $dttToReturn;
        }
    }

    public function Validate()
    {
        if (parent::Validate()) {
            if ($this->strText != "") {
                $dttTest = QDateTimeTextBox::ParseForDateTimeValue($this->strText);

                if (!$dttTest) {
                    $this->ValidationError = $this->strLabelForInvalid;
                    return false;
                }

                if (!is_null($this->dttMinimum)) {
                    if ($this->dttMinimum == \QCubed\QDateTime::NOW) {
                        $dttToCompare = new \QCubed\QDateTime(QDateTime::Now);
                        $strError = t('in the past');
                    } else {
                        $dttToCompare = $this->dttMinimum;
                        $strError = t('before ') . $dttToCompare->__toString();
                    }

                    if ($dttTest->IsEarlierThan($dttToCompare)) {
                        $this->ValidationError = t('Date cannot be ') . $strError;
                        return false;
                    }
                }

                if (!is_null($this->dttMaximum)) {
                    if ($this->dttMaximum == \QCubed\QDateTime::NOW) {
                        $dttToCompare = new \QCubed\QDateTime(QDateTime::Now);
                        $strError = t('in the future');
                    } else {
                        $dttToCompare = $this->dttMaximum;
                        $strError = t('after ') . $dttToCompare->__toString();
                    }

                    if ($dttTest->IsLaterThan($dttToCompare)) {
                        $this->ValidationError = t('Date cannot be ') . $strError;
                        return false;
                    }
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    public function __get($strName)
    {
        switch ($strName) {
            // MISC
            case "Maximum":
                return $this->dttMaximum;
            case "Minimum":
                return $this->dttMinimum;
            case 'DateTimeFormat':
                return $this->strDateTimeFormat;
            case 'DateTime':
                return $this->dttDateTime;
            case 'LabelForInvalid':
                return $this->strLabelForInvalid;

            default:
                try {
                    return parent::__get($strName);
                } catch (\QCubed\Exception\Caller $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    public function __set($strName, $mixValue)
    {
        $this->blnModified = true;

        switch ($strName) {
            // MISC
            case 'Maximum':
                try {
                    if ($mixValue == \QCubed\QDateTime::NOW) {
                        $this->dttMaximum = \QCubed\QDateTime::NOW;
                    } else {
                        $this->dttMaximum = \QCubed\Type::Cast($mixValue, \QCubed\Type::DATE_TIME);
                    }
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Minimum':
                try {
                    if ($mixValue == \QCubed\QDateTime::NOW) {
                        $this->dttMinimum = \QCubed\QDateTime::NOW;
                    } else {
                        $this->dttMinimum = \QCubed\Type::Cast($mixValue, \QCubed\Type::DATE_TIME);
                    }
                    break;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'DateTimeFormat':
                try {
                    $this->strDateTimeFormat = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING);
                    // trigger an update to reformat the text with the new format
                    $this->DateTime = $this->dttDateTime;
                    return $this->strDateTimeFormat;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'DateTime':
                try {
                    $this->dttDateTime = \QCubed\Type::Cast($mixValue, \QCubed\Type::DATE_TIME);
                    if (!$this->dttDateTime || !$this->strDateTimeFormat) {
                        parent::__set('Text', '');
                    } else {
                        parent::__set('Text', $this->dttDateTime->qFormat($this->strDateTimeFormat));
                    }
                    return $this->dttDateTime;
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'Text':
                $this->dttDateTime = QDateTimeTextBox::ParseForDateTimeValue($this->strText);
                return parent::__set('Text', $mixValue);

            case 'LabelForInvalid':
                try {
                    return ($this->strLabelForInvalid = \QCubed\Type::Cast($mixValue, \QCubed\Type::STRING));
                } catch (\QCubed\Exception\InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strName, $mixValue);
                } catch (\QCubed\Exception\Caller $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
                break;
        }
    }

    /**** Codegen Helpers, used during the Codegen process only. ****/

    public static function Codegen_VarName($strPropName)
    {
        return 'cal' . $strPropName;
    }
}
