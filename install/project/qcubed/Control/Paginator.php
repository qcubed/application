<?php
namespace QCubed\Project\Control;

/**
 * Contains the QPaginator Class - the paginator control for QDataGrid and QDataRepeater controls
 */

/**
 * Class QPaginator - The paginator control which can be attached to a QDataRepeater or QDataGrid
 * This class will take care of the number of pages, current page, next/previous links and so on
 * automatically.
 */
class Paginator extends \QCubed\Control\PaginatorBase
{
    // APPEARANCE
    protected $intIndexCount = 10;

    //////////
    // Methods
    //////////
    /**
     * Constructor
     * @param ControlBase|FormBase $objParentObject
     * @param null|string $strControlId
     */
    public function __construct($objParentObject, $strControlId = null)
    {
        parent::__construct($objParentObject, $strControlId);

        $this->CssClass = 'paginator';
        //$this->strLabelForPrevious = QApplication::translate('<<');
        //$this->strLabelForNext = QApplication::translate('>>');
    }
}
