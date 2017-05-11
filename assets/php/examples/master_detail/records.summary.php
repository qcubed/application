<?php
/*
Here is the Child \QCubed\Project\Control\DataGrid...
*/

// Load the QCubed Development Framework
	require_once('../../qcubed.inc.php');

	class RecordsSummary extends \QCubed\Control\Panel {
		public $dtgRecordsSummary;
		
		protected $objParentObject;
		
		// Protected Objects
		protected $objProject;

		// in the contructor pass the item bounded too just for other process
		public function __construct($objParentObject, Project $objProject, $strControlId = null) {			
			try {
				parent::__construct($objParentObject, $strControlId);
				
				// Watch out for template later gonna talk about it,
				// need a trick to look good 
				// (insert the child content as row in table already present for Master
				//   close colums -insert row - insert child - close row - open column 
				//  </td> <tr><td> render content of this child </td> </tr> <td> )
				$this->Template = 'records.summary.tpl.php';
				
				// Setting local the Msster \QCubed\Project\Control\DataGrid to refresh on
				// Saves on the Child DataGrid..
				$this->objParentObject = $objParentObject;
				$this->objProject = $objProject;
				
				// Create the child DataGrid as a normal \QCubed\Project\Control\DataGrid 
				$this->dtgRecordsSummary = new \QCubed\Project\Control\DataGrid($this);
				// pagination
				$this->dtgRecordsSummary->Paginator = new \QCubed\Project\Control\Paginator($this->dtgRecordsSummary);

				$this->dtgRecordsSummary->ItemsPerPage = 5;

				$this->dtgRecordsSummary->SetDataBinder('dtgRecordsSummary_Bind', $this);
				

				// Add some data to show...
				$this->dtgRecordsSummary->CreateCallableColumn ('Person', [$this, 'render_PersonColumn']);
				$col = $this->dtgRecordsSummary->CreateNodeColumn('Id', QQN::Person()->Id);
				$col->CellStyler->Width = 120;

			} catch (\QCubed\Exception\Caller $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function render_PersonColumn(Person $objPerson) {
			return $objPerson->FirstName . ' ' . $objPerson->LastName;
		}

		public function dtgRecordsSummary_Bind() {
			//$objConditions = $this->dtgRecordsSummary->Conditions;

			// setup $objClauses array 
			$objClauses = array();

			// add OrderByClause to the $objClauses array 
			// if ($objClause = $this->dtgRecordsSummary->OrderByClause){
			if ($objClause = $this->dtgRecordsSummary->OrderByClause) {
				array_push($objClauses, $objClause);             
			}

			// add LimitByClause to the $objClauses array 
			//if ($objClause = $this->dtgRecordsSummary->LimitClause)
			if ($objClause = $this->dtgRecordsSummary->LimitClause)	
				array_push($objClauses, $objClause);


			$team_array = $this->objProject->GetPersonAsTeamMemberArray
												(\QCubed\Query\QQ::Clause($this->dtgRecordsSummary->OrderByClause));
			$this->dtgRecordsSummary->TotalItemCount = count($team_array);

			$this->dtgRecordsSummary->DataSource = $this->objProject->GetPersonAsTeamMemberArray
												(\QCubed\Query\QQ::Clause($this->dtgRecordsSummary->OrderByClause), $objClauses);

		}
          
	}
?>
