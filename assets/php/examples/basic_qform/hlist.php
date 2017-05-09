<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	protected $lstProjects;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define the ListBox, and create the first listitem as 'Select One'
		$this->lstProjects = new \QCubed\Control\HList($this);
		$this->lstProjects->SetDataBinder(array ($this, 'lstProjects_Bind'));
		$this->lstProjects->UnorderedListStyle = \QCubed\Css\UnorderedListStyleType::Square;

	}

	/**
	 * Add the items to the project list.
	 */
	public function lstProjects_Bind() {
		$clauses[] = \QCubed\Query\QQ::ExpandAsArray (QQN::Project()->PersonAsTeamMember);
		$objProjects = Project::QueryArray(\QCubed\Query\QQ::All(), $clauses);

		foreach ($objProjects as $objProject) {
			$item = new \QCubed\Control\HListItem ($objProject->Name);
			$item->Tag = 'ol';
			$item->GetSubTagStyler()->OrderedListType = \QCubed\Css\OrderedListType::LowercaseRoman;
			foreach ($objProject->_PersonAsTeamMemberArray as $objPerson) {
				/****
				 * Here we add a sub-item to each item before adding the item to the main list.
				 */
				$item->AddItem ($objPerson->FirstName . ' ' . $objPerson->LastName);
			}
			$this->lstProjects->AddItem ($item);
		}
	}

}

// Run the Form we have defined
// The \QCubed\Project\Control\FormBase engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>