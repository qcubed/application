<?php
require_once('../qcubed.inc.php');

class ExampleForm extends \QCubed\Project\Control\FormBase {

	/** @var \QCubed\Project\Control\Table */
	protected $tblProjects;
	protected $pnlClick;
	protected $pxyLink;

	protected function formCreate() {
		// define the proxy that we will use later
		$this->pxyLink = new \QCubed\Control\Proxy($this);
		$this->pxyLink->AddAction(new \QCubed\Event\MouseOver(), new \QCubed\Action\Ajax('mouseOver'));

		// Define the DataGrid
		$this->tblProjects = new \QCubed\Project\Control\Table($this);

		// This css class is used to style alternate rows and the header, all in css
		$this->tblProjects->CssClass = 'simple_table';

		// Define Columns

		// Create a link column that shows the name of the project, and when clicked, calls back to this page with an id
		// of the item clicked on
		$this->tblProjects->CreateLinkColumn('Project', '->Name', \QCubed\Project\Application::instance()->context()->scriptName(), ['intId'=>'->Id']);

		// Create a link column using a proxy
		$col = $this->tblProjects->CreateLinkColumn('Status', '->ProjectStatusType', $this->pxyLink, '->Id');

		$this->tblProjects->SetDataBinder('tblProjects_Bind');

		$this->pnlClick = new \QCubed\Control\Panel($this);

		if (($intId = \QCubed\Project\Application::instance()->context()->queryStringItem('intId')) && ($objProject = Project::Load($intId))) {
			$this->pnlClick->Text = 'You clicked on ' . $objProject->Name;
		}

	}

	/**
	 * Bind the Projects table to the html table.
	 *
	 * @throws \QCubed\Exception\Caller
	 */
	protected function tblProjects_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->tblProjects->DataSource = Project::LoadAll();
	}

	public function mouseOver($strFormId, $strControlId, $param) {
		if ($objProject = Project::Load($param)) {
			$this->pnlClick->Text = 'You hovered over ' . $objProject->Name;
		}
	}

}

ExampleForm::Run('ExampleForm');
?>