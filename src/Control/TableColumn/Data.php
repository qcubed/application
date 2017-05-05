<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\Control\TableColumn;

use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\QDateTime;
use QCubed\Type;
use QCubed\Project\Control\FormBase as QForm;


/**
 * Class Data
 *
 * An abstract column designed to work with DataGrid and other tables that require more than basic columns.
 * Supports post processing of cell contents for further formatting, and OrderBy clauses.
 *
 * @property mixed $OrderByClause        order by info for sorting the column in ascending order. Used by subclasses.
 *    Most often this is a \QCubed\Query\QQ::Clause, but can be any data needed.
 * @property mixed $ReverseOrderByClause order by info for sorting the column in descending order.
 * @property string $Format               the default format to use for FetchCellValueFormatted(). Used by QDataTables plugin.
 *    For date columns it should be a format accepted by \QCubed\QDateTime::qFormat()
 * @property-write string $PostMethod           after the cell object is retrieved, call this method on the obtained object
 * @property-write callback $PostCallback         after the cell object is retrieved, call this callback on the obtained object.
 *    If $PostMethod is also set, this will be called after that method call.
 * @was QAbstractHtmlTableDataColumn
 * @package QCubed\Control\TableColumn
 */
abstract class Data extends TableColumnBase
{
    /** @var mixed Order By information. Can be a \QCubed\Query\QQ::Clause, or any kind of object depending on your need */
    protected $objOrderByClause = null;
    /** @var mixed */
    protected $objReverseOrderByClause = null;
    /** @var string */
    protected $strFormat = null;
    /** @var string */
    protected $strPostMethod = null;
    /** @var callback */
    protected $objPostCallback = null;

    /**
     * Return the raw string that represents the cell value.
     * This version uses a combination of post processing strategies so that you can set
     * column options to format the raw data. If no
     * options are set, then $item will just pass through, or __toString() will be called
     * if its an object. If none of these work for you, just override FetchCellObject and
     * return your formatted string from there.
     *
     * @param mixed $item
     *
     * @return mixed|string
     */
    public function fetchCellValue($item)
    {
        $cellValue = $this->fetchCellObject($item);

        if ($cellValue !== null && $this->strPostMethod) {
            $strPostMethod = $this->strPostMethod;
            assert('is_callable([$cellValue, $strPostMethod])');    // Malformed post method, or the item is not an object
            $cellValue = $cellValue->$strPostMethod();
        }
        if ($this->objPostCallback) {
            $cellValue = call_user_func($this->objPostCallback, $cellValue);
        }
        if ($cellValue === null) {
            return '';
        }

        if ($cellValue instanceof QDateTime) {
            return $cellValue->qFormat($this->strFormat);
        }
        if (is_object($cellValue)) {
            $cellValue = (string)$cellValue;
        }
        if ($this->strFormat) {
            return sprintf($this->strFormat, $cellValue);
        }

        return $cellValue;
    }

    /**
     * Return the value of the cell. FetchCellValue will process this more if needed.
     * Default returns an entire data row and relies on FetchCellValue to extract the needed data.
     *
     * @param mixed $item
     */
    abstract public function fetchCellObject($item);

    /**
     * Fix up possible embedded reference to the form.
     */
    public function sleep()
    {
        $this->objPostCallback = QControl::sleepHelper($this->objPostCallback);
        parent::sleep();
    }

    /**
     * The object has been unserialized, so fix up pointers to embedded objects.
     * @param QForm $objForm
     */
    public function wakeup(QForm $objForm)
    {
        parent::wakeup($objForm);
        $this->objPostCallback = QControl::wakeupHelper($objForm, $this->objPostCallback);
    }

    /**
     * PHP magic method
     *
     * @param string $strName
     *
     * @return bool|int|mixed|QHtmlTableBase|string
     * @throws Exception
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case "OrderByClause":
                return $this->objOrderByClause;
            case "ReverseOrderByClause":
                return $this->objReverseOrderByClause;
            case "Format":
                return $this->strFormat;

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
     * PHP magic method
     *
     * @param string $strName
     * @param string $mixValue
     *
     * @return mixed|void
     * @throws Exception
     * @throws Caller
     * @throws InvalidCast
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "OrderByClause":
                try {
                    $this->objOrderByClause = $mixValue;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "ReverseOrderByClause":
                try {
                    $this->objReverseOrderByClause = $mixValue;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "Format":
                try {
                    $this->strFormat = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "PostMethod":
                try {
                    $this->strPostMethod = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case "PostCallback":
                $this->objPostCallback = $mixValue;
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
}
