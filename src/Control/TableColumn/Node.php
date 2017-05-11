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
use QCubed\Query\Node as QQNode;
use QCubed\Query\QQ;


/**
 * Class Node
 *
 * A table column that displays the content of a database column represented by a NodeBase object.
 * The $objNodes can be a single node, or an array of nodes. If an array of nodes, the first
 * node will be the display node, and the rest of the nodes will be used for sorting.
 *
 * @was QHtmlTableNodeColumn
 * @package QCubed\Control\TableColumn
 */
class Node extends Property
{
    public function __construct($strName, $objNodes)
    {
        if ($objNodes instanceof QQNode\NodeBase) {
            $objNodes = [$objNodes];
        } elseif (empty($objNodes) || !is_array($objNodes) || !$objNodes[0] instanceof QQNode\NodeBase) {
            throw new Caller('Pass either a QQNode\\NodeBase node or an array of Nodes only');
        }

        $objNode = $objNodes[0]; // First node is the data node, the rest are for sorting.

        if (!$objNode->_ParentNode) {
            throw new Caller('First QQNode\\NodeBase cannot be a Top Level Node');
        }
        if (($objNode instanceof QQNode\ReverseReference) && !$objNode->isUnique()) {
            throw new Caller('Content QQNode\\NodeBase cannot go through any "To Many" association nodes.');
        }

        $properties = array($objNode->_PropertyName);
        while ($objNode = $objNode->_ParentNode) {
            if (!($objNode instanceof QQNode\NodeBase)) {
                throw new Caller('QQNode\\NodeBase cannot go through any "To Many" association nodes.');
            }
            if (($objNode instanceof QQNode\ReverseReference) && !$objNode->isUnique()) {
                throw new Caller('QQNode\\NodeBase cannot go through any "To Many" association nodes.');
            }
            if ($strPropName = $objNode->_PropertyName) {
                $properties[] = $strPropName;
            }
        }
        $properties = array_reverse($properties);
        $strProp = implode('->', $properties);
        parent::__construct($strName, $strProp, null);

        // build sort nodes
        $objSortNodes = [];
        $objReverseNodes = [];
        foreach ($objNodes as $objNode) {
            if ($objNode instanceof QQNode\ReverseReference) {
                $objNode = $objNode->_PrimaryKeyNode;
            }
            $objSortNodes[] = $objNode;
            $objReverseNodes[] = $objNode;
            $objReverseNodes[] = false;
        }

        $this->OrderByClause = QQ::orderBy($objSortNodes);
        $this->ReverseOrderByClause = QQ::orderBy($objReverseNodes);
    }
}
