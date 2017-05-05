<?php
namespace QCubed\Project\Control;

/**
 * QDataGrid can help generate tables automatically with pagination. It can also be used to
 * render data directly from database by using a 'DataSource'. The code-generated search pages you get for
 * every table in your database are all QDataGrids
 *
 * @was QDataGrid
 * @package QCubed\Project\Control
 */
class DataGrid extends \QCubed\Control\DataGridBase
{
    // Feel free to specify global display preferences/defaults for all QDataGrid controls

    /**
     * QDataGrid::__construct()
     *
     * @param mixed $objParentObject The Datagrid's parent
     * @param string $strControlId Control ID
     *
     * @throws QCallerException
     * @return \QDataGrid
     */
    public function __construct($objParentObject, $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (QCallerException  $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->CssClass = 'datagrid';
    }
}
