<?php
    /** @var QSqlTable $objTable */
use QCubed\Project\Codegen\CodegenBase;

/** @var \QCubed\Codegen\DatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;

    $strPropertyName = CodegenBase::dataListPropertyName($objTable);

    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => false,
        'DirectorySuffix' => '',
        'TargetDirectory' => __PANEL__,
        'TargetFileName' => $strPropertyName . 'ListPanel.php'
    );

    $listCodegenerator = $objCodeGen->getDataListCodeGenerator($objTable);

?>
<?php print("<?php\n"); ?>
require(__PANEL_GEN__ . '/<?= $strPropertyName ?>ListPanelGen.php');
require(__MODEL_CONNECTOR__ . '/<?= $strPropertyName ?>List.php');

/**
 * This is the customizable subclass for the list panel functionality
 * of the <?= $strPropertyName ?> class.
 *
 * This file is intended to be modified. Subsequent code regenerations will NOT modify
 * or overwrite this file.
 */
class <?= $strPropertyName ?>ListPanel extends <?= $strPropertyName ?>ListPanelGen
{
	public function __construct($objParent, $strControlId = null) {
		parent::__construct($objParent, $strControlId);

		/**
		 * Default is just to render everything generic. Comment out the AutoRenderChildren line, and uncomment the
		 * template line to use a template for greater customization of how the panel draws its contents.
		 **/
		$this->AutoRenderChildren = true;
		//$this->Template =  __PANEL_GEN__ . '/<?= $strPropertyName ?>ListPanel.tpl.php';
	}

<?= $listCodegenerator->dataListSubclassOverrides($objCodeGen, $objTable); ?>



}
