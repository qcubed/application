<?php
/**
 *
 * Part of the QCubed PHP framework.
 *
 * @license MIT
 *
 */

namespace QCubed\ModelConnector;

use QCubed\Project\Control\Dialog as QDialog;
use QCubed\Project\Control\ControlBase as QControl;
use QCubed\Event;
use QCubed\Control\Panel;


/**
 * Class EditDlg
 *
 * A dialog that lets you specify code generation options for a control. These options control how a control
 * is generated, and includes additional parameters that can be specified for a control.
 *
 * This dialog pops up when designer mode is turned on and the user right clicks on a control.
 *
 * The code below will set up the dialog and display options that are generic to all QControls. Individual
 * controls can add parameters to this dialog by implementing the GetModelConnectorParams function.
 *
 * Everything gets saved in the configuration/codegen_options.json file.
 *
 * @was QModelConnectorEditDlg
 * @package QCubed\ModelConnector
 */
class EditDlg extends QDialog
{
    /** @var  QControl */
    protected $objCurrentControl;

    protected $tabs;

    protected $txtName;
    protected $txtControlId;
    protected $txtControlClass;
    protected $lstFormGen;

    protected $params;
    protected $objModelConnectorOptions;

    protected $generalOptions;
    protected $dtgGeneralOptions;

    protected $categories;
    protected $datagrids;

    public function __construct($objParentObject, $strControlId)
    {
        parent::__construct($objParentObject, $strControlId);

        $this->AutoRenderChildren = true;
        $this->Width = 750;

        $this->objModelConnectorOptions = new QModelConnectorOptions();

        $objPanel = new Panel($this);    // This is a kind of bug fix, to fix a unique problem when putting tabs in a dialog
        $objPanel->Height = 400;    // We need an intermediate object to force the height
        $objPanel->AutoRenderChildren = true;

        $this->tabs = new QTabs ($objPanel, 'qconnectoreditTabs');
        $this->tabs->HeightStyle = "content";

        $this->addButton('Save', 'save');
        $this->addButton('Save, Regenerate and Reload', 'saveRefresh');
        $this->addButton('Cancel', 'cancel');

        $this->addAction(new Event\DialogButton, new QAjaxControlAction($this, 'ButtonClick'));
    }

    /**
     * Recreate the tabs in the dialog
     */
    protected function setupTabs()
    {
        $strClassNames = $this->createClassNameArray();
        $this->tabs->removeChildControls(true);
        $this->categories = array();

        $this->dtgGeneralOptions = new QHtmlTable($this->tabs);
        $this->dtgGeneralOptions->ShowHeader = false;
        $this->dtgGeneralOptions->Name = "General";
        $this->dtgGeneralOptions->createPropertyColumn('Attribute', 'Name');
        $col = $this->dtgGeneralOptions->addColumn(new QHtmlTableCallableColumn('Attribute',
            array($this, 'dtg_ValueRender'), $this->dtgGeneralOptions));
        $col->HtmlEntities = false;
        $this->dtgGeneralOptions->setDataBinder('dtgGeneralOptions_Bind', $this);

        /**
         * The following default options are somewhat matched to the default list and edit templates. A more robust
         * implementation would get the options from the templates, or what the templates generate, so that the templates
         * decide what to put there. If someone wants to radically change the templates, but still have them use this dialog
         * to edit the options, then would be the time to change the code below.
         */
        if ($this->objCurrentControl->LinkedNode->_ParentNode) {
            // Specify general options for a database column
            $this->generalOptions = array(
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'ControlClass',
                    'Override of the PHP type for the control. If you change this, save the dialog and reopen to reload the tabs to show the control specific options.',
                    QModelConnectorParam::SelectionList, $strClassNames),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'FormGen',
                    'Whether or not to generate this object, just a label for the object, just the control, or both the control and label',
                    QModelConnectorParam::SelectionList,
                    array(
                        QFormGen::Both => 'Both',
                        QFormGen::None => 'None',
                        QFormGen::ControlOnly => 'Control',
                        QFormGen::LabelOnly => 'Label'
                    )),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'Name', 'Control\'s Name',
                    QType::String),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'NoColumn',
                    'True to prevent a column in the lister from being generated.', QType::Boolean)
            );
        } else {
            // Specify general options for a database table, meaning an object that is listing the content of a whole table.
            // These would be options at a higher level than the control itself, and would modify how the control is used in a form.
            $this->generalOptions = array(
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'ControlClass',
                    'Override of the PHP type for the control. If you change this, save the dialog and reopen to reload the tabs to show the control specific options.',
                    QModelConnectorParam::SelectionList, $strClassNames),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'Name',
                    'The Control\'s Name. Generally leave this blank, or use a plural name.', QType::String),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'ItemName',
                    'The public name of an item in the list. Its used by the title of the edit form, for example. Defaults to the name of the table in the database.',
                    QType::String),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'CreateFilter',
                    'Whether to generate a separate control to filter the data. If the data list control does its own filtering, set this to false. Default is true.',
                    QType::Boolean),
                new QModelConnectorParam (QModelConnectorParam::GeneralCategory, 'EditMode',
                    'How to edit an item. 1) Options are: to go to a separate form, 2) popup a dialog, or 3) popup a dialog only if not on a mobile device since mobile devices struggle with showing dialogs that are bigger than the screen.',
                    QModelConnectorParam::SelectionList,
                    array(
                        'form' => 'Edit with a QForm',
                        'dialog' => 'Edit with a QDialog',
                        'both' => 'Edit with a form on mobile devices, and a dialog on desktops.'
                    ))
            );
        }

        // load values from settings file
        foreach ($this->generalOptions as $objParam) {
            $objControl = $objParam->getControl($this->dtgGeneralOptions);    // get a control that will edit this option
            $strName = $objControl->Name;

            if (isset($this->params[$strName])) {
                $objControl->Value = $this->params[$strName];
                if ($strName == 'ControlClass') {
                    $strControlClass = $this->params[$strName];
                }
            } else {
                $objControl->Value = null;
            }
        }

        if (!isset ($strControlClass)) {
            $strControlClass = get_class($this->objCurrentControl);
        }
        $params = $strControlClass::getModelConnectorParams();

        // gather categories
        foreach ($params as $param) {
            $this->categories[$param->Category][] = $param;
        }

        // Add any additional general items to the general tab
        if (isset ($this->categories[QModelConnectorParam::GeneralCategory])) {
            // load values from settings file
            foreach ($this->categories[QModelConnectorParam::GeneralCategory] as $objParam) {
                $objControl = $objParam->getControl($this->dtgGeneralOptions);    // get a control that will edit this option
                $strName = $objControl->Name;

                if (isset($this->params[$strName])) {
                    $objControl->Value = $this->params[$strName];
                } else {
                    $objControl->Value = null;
                }
                $this->generalOptions[] = $objParam;
            }

            unset($this->categories[QModelConnectorParam::GeneralCategory]);
        }

        foreach ($this->categories as $tabName => $params) {
            //$panel = new QPanel ($this->tabs);
            //$panel->setCssStyle('overflow-y', 'scroll');
            //$panel->setCssStyle('max-height', '200');
            //$panel->AutoRenderChildren = true;
            //$panel->Name = $tabName;

            $dtg = new QHtmlTable($this->tabs);
            $dtg->ShowHeader = false;
            $dtg->Name = $tabName;
            $dtg->createPropertyColumn('Attribute', 'Name');
            $col = $dtg->addColumn(new QHtmlTableCallableColumn('Attribute', array($this, 'dtg_ValueRender'), $dtg));
            $col->HtmlEntities = false;
            $dtg->setDataBinder('dtgControlBind', $this);
            $dtg->Name = $tabName; // holder for category
            $this->datagrids[$tabName] = $dtg;

            // load values from settings file
            foreach ($params as $objParam) {
                $objControl = $objParam->getControl($this->datagrids[$tabName]);
                if ($objControl) {
                    $strName = $objControl->Name;

                    if (isset($this->params['Overrides'][$strName])) {
                        $objControl->Value = $this->params['Overrides'][$strName];
                    } else {
                        $objControl->Value = null;
                    }
                }
            }

        }
    }

    /**
     * Bind the general options
     */
    public function dtgGeneralOptions_Bind()
    {
        $this->dtgGeneralOptions->DataSource = $this->generalOptions;
    }

    /**
     * Binder for the control specific options
     */
    public function dtgControlBind($dtg)
    {
        $dtg->DataSource = $this->categories[$dtg->Name];
    }

    /**
     * Render the value column, which allows the user to specify the value of an option for the control.
     *
     * @param QModelConnectorParam $objControlParam
     * @param QControl $objParent
     * @return string
     */
    public function dtg_ValueRender(QModelConnectorParam $objControlParam, QControl $objParent)
    {
        $objControl = $objControlParam->getControl($objParent);
        return $objControl->render(false);
    }

    /**
     * Entry point for the dialog. Brings up the dialog and loads all the options so that it can be edited.
     *
     * @param QControl $objControl
     */
    public function editControl(QControl $objControl)
    {
        $this->objCurrentControl = $objControl;

        $this->Title = $objControl->Name . ' Edit';

        $blnEditable = $this->readParams();
        if ($blnEditable) {
            $this->setupTabs();
            $this->open();
            $this->tabs->refresh();
        }
    }

    /**
     * Dialog button has been clicked. Save the options, or Save, codegen, and then reload.
     *
     * @param $strFormId
     * @param $strControlId
     * @param $mixParam
     */
    public function buttonClick($strFormId, $strControlId, $mixParam)
    {
        if ($mixParam == 'save') {
            $this->updateControlInfo();
            $this->writeParams();
        } elseif ($mixParam == 'saveRefresh') {
            $this->updateControlInfo();
            $this->writeParams();
            QCodeGen::run(__CONFIGURATION__ . '/codegen_settings.xml');
            foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
                $objCodeGen->generateAll(); // silently codegen
            }
            QApplication::redirect($_SERVER['PHP_SELF']);
        }

        $this->close();
    }

    /**
     * Puts the values of the dialog into the params array to be saved off into the settings file.
     */
    protected function updateControlInfo()
    {
        $objParams = $this->generalOptions;
        foreach ($objParams as $objParam) {
            $objControl = $objParam->getControl($this->dtgGeneralOptions);
            $strName = $objControl->Name;
            $value = $objControl->Value;

            if (!is_null($value)) {
                $this->params[$strName] = $value;
            } else {
                unset ($this->params[$strName]);
            }
        }

        foreach ($this->categories as $objParams) {
            foreach ($objParams as $objParam) {
                $objControl = $objParam->getControl();
                if ($objControl) {
                    $strName = $objControl->Name;
                    $value = $objControl->Value;

                    if (!is_null($value)) {
                        $this->params['Overrides'][$strName] = $value;
                    } else {
                        unset ($this->params['Overrides'][$strName]);
                    }
                } else {
                    unset ($this->params['Overrides'][$strName]);
                }
            }
        }

        if (empty($this->params['Overrides'])) {
            unset ($this->params['Overrides']);
        }
    }

    /**
     * Write the current params into the settings file.
     */
    protected function writeParams()
    {
        $node = $this->objCurrentControl->LinkedNode;
        if ($node) {
            if ($node->_ParentNode) {
                $strClassName = $node->_ParentNode->_ClassName;
                $this->objModelConnectorOptions->setOptions($strClassName, $node->_PropertyName, $this->params);
                $this->objModelConnectorOptions->save();
            } else {
                // Table options
                $this->objModelConnectorOptions->setOptions($node->_ClassName,
                    QModelConnectorOptions::TableOptionsFieldName, $this->params);
                $this->objModelConnectorOptions->save();
            }
        }
    }

    /**
     * Read the params from the settings file.
     * Returns false if there were no params to be read, meaning this control is not attached to a database object.
     * @return bool
     */
    protected function readParams()
    {
        $node = $this->objCurrentControl->LinkedNode;
        if ($node) {
            if ($node->_ParentNode) {
                $strClassName = $node->_ParentNode->_ClassName;
                $this->params = $this->objModelConnectorOptions->getOptions($strClassName, $node->_PropertyName);
            } else {
                // Table options
                $this->params = $this->objModelConnectorOptions->getOptions($node->_ClassName,
                    QModelConnectorOptions::TableOptionsFieldName);
            }
            return true;
        }
        return false;
    }

    /**
     * Returns an array of class names that can be used to edit the current control's data type.
     *
     * @return array
     */
    protected function createClassNameArray()
    {
        // create the control array
        $controls = array();
        include(__QCUBED_CORE__ . '/control_registry.inc.php');

        if (file_exists(__APP_INCLUDES__ . '/control_registry.inc.php')) {
            include(__APP_INCLUDES__ . '/control_registry.inc.php');
        }

        if (defined('__PLUGINS__') &&
            is_dir(__PLUGINS__)
        ) {
            $plugins = scandir(__PLUGINS__);
            foreach ($plugins as $dirName) {
                if ($dirName != '.' && $dirName != '..') {
                    if (file_exists(__PLUGINS__ . '/' . $dirName . '/control_registry.inc.php')) {
                        include(__PLUGINS__ . '/' . $dirName . '/control_registry.inc.php');
                    }
                }
            }
        }

        // $controls is now an array indexed by QType, with each entry a QControl type name

        // Figure out what type of control we are looking for
        // For the most part, the control category types are the same as the database type
        $node = $this->objCurrentControl->LinkedNode;
        $type = $node->_Type;
        if (($node->_Type == QType::ReverseReference && $node->isUnique()) || $node->_Type == QType::ArrayType) {
            $type = QControlCategoryType::SingleSelect;
        } elseif (($node->_Type == QType::ReverseReference && !$node->isUnique()) || $node->_Type == QType::Association) {
            $type = QControlCategoryType::MultiSelect;
        } elseif ($node->_TableName) { // indicates a reference to a table
            if ($node->_ParentNode) {
                // A foreign key to another table
                $type = QControlCategoryType::SingleSelect;
            } else {
                // A top level table, so a grid or list view
                $type = QControlCategoryType::Table;
            }
        }

        if (isset ($controls[$type])) {
            foreach ($controls[$type] as $strClassName) {
                $a[$strClassName] = $strClassName;    // remove duplicates
            }

            return $a;
        } else {
            return null;
        }

    }

}

