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
use QCubed\Query\QQ;
use QCubed\Type;

/**
 * Class VirtualAttribute
 *
 * A column to display a virtual attribute from a database record.
 *
 * @property string $Attribute
 * @was QVirtualAttributeColumn
 * @package QCubed\Control\TableColumn
 */
class VirtualAttribute extends Data
{
    protected $strAttribute;

    public function __construct($strName, $strAttribute = null)
    {
        parent::__construct($strName);
        if ($strAttribute) {
            $this->strAttribute = $strAttribute;
        }

        $this->OrderByClause = QQ::orderBy(QQ::virtual($strAttribute));
        $this->ReverseOrderByClause = QQ::orderBy(QQ::virtual($strAttribute), false);
    }

    public function fetchCellObject($item)
    {
        return $item->getVirtualAttribute($this->strAttribute);
    }

    /**
     * PHP magic method
     *
     * @param string $strName
     *
     * @return mixed
     * @throws Exception
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'Attribute':
                return $this->strAttribute;
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
     * @throws \QCubed\Exception\InvalidCast
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "Attribute":
                $this->strAttribute = Type::cast($mixValue, Type::STRING);
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
