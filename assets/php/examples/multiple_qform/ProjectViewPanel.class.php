<?php
	class ProjectViewPanel extends \QCubed\Control\Panel {
		// Child Controls must be Publically Accessible so that they can be rendered in the template
		// Typically, you would want to do this by having public __getters for each control
		// But for simplicity of this demo, we'll simply make the child controls public, themselves.
		public $pnlTitle;
		public $dtgMembers;
		public $btnEditProject;
		public $txtBlah;

		// The Local Project object which this panel represents
		protected $objProject;

		// The Reference to the Main Form's "Right Panel" so that this panel
		// can make changes to the right panel on the page
		protected $strPanelRightControlId;

		// Specify the Template File for this custom \QCubed\Control\Panel
		protected $strTemplate = 'ProjectViewPanel.tpl.php';

		// Customize the Look/Feel
		protected $strPadding = '10px';
		protected $strBackColor = '#fefece';

		// We Create a new __constructor that takes in the Project we are "viewing"
		// The functionality of __construct in a custom \QCubed\Control\Panel is similar to the \QCubed\Project\Control\FormBase's formCreate() functionality
		public function __construct($objParentObject, $objProject, $strPanelRightControlId, $strControlId = null) {
			// First, let's call the Parent's __constructor
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (\QCubed\Exception\Caller $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			// Next, we set the local project object
			$this->objProject = $objProject;
			
			/* Let's record the reference to the form's RightPanel
			 * Note: this ProjectViewPanel needs the reference to the main form's RightPanel so that it can
			 * "update" the RightPanel's contents during the ProjectViewPanel's event handlers (e.g., when the user
			 * click's "Edit" on a Person, this ProjectViewPanel's btnEdit_Click handler will update RightPanel
			 * to display the PersonEditPanel panel.
			 *
			 * HOWEVER, realize that this interaction can be done many different ways.
			 * A very suitable alternative would be for this __construct to take in a public method name from the Form instead
			 * of $strPanelRightControlId.  And btnEdit_Click, instead of updating the right panel directly, could simply
			 * make a call to the Form's method, and the interaction could be defined on the Form itself.
			 *
			 * This design decision depends on how tightly coupled the custom panels are together, or if each panel
			 * is to be more independent and you want the Form to define the interaction only.  So it would depend on how
			 * the developer would want to do it.
			 *
			 * We show an example of accessing the RightPanel direclty in ProjectViewPanel, and we show examples
			 * of MethodCallbacks on the Form in ProjectEditPanel and PersonEditPanel.
			 */
			$this->strPanelRightControlId = $strPanelRightControlId;

			// Let's set up some other local child control
			// Notice that we define the child controls' parents to be "this", which is this ProjectViewPanel object.
			$this->pnlTitle = new \QCubed\Control\Panel($this);
			$this->pnlTitle->Text = $objProject->Name;
			$this->pnlTitle->CssClass = 'projectTitle';

			$this->btnEditProject = new \QCubed\Project\Jqui\Button($this);
			$this->btnEditProject->Text = 'Edit Project Name';
			$this->btnEditProject->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\AjaxControl($this, 'btnEditProject_Click'));

			// Now, let's set up this custom panel's child controls
			$this->dtgMembers = new \QCubed\Project\Control\DataGrid($this);
			$col = $this->dtgMembers->CreateNodeColumn('ID', QQN::Person()->Id);
			$col->CellStyler->Width = 30;
			$col = $this->dtgMembers->CreateNodeColumn('First Name', QQN::Person()->FirstName);
			$col->CellStyler->Width = 120;
			$col = $this->dtgMembers->CreateNodeColumn('Last Name', QQN::Person()->LastName);
			$col->CellStyler->Width = 120;
			$col = $this->dtgMembers->CreateCallableColumn('Edit', [$this, 'EditColumn_Render']);
			$col->HtmlEntities = false;


			// Let's make sorting Ajax-ified
			$this->dtgMembers->UseAjax = true;

			// Finally, we take advantage of the DataGrid's SetDataBinder to specify the method we use to actually bind
			// a datasource to the DataGrid
			$this->dtgMembers->SetDataBinder('dtgMembers_Bind', $this);
		}
		
		// This is the method that will perform the actual databinding on the dtgMembers datagrid
		// Note that because it is called by the \QCubed\Project\Control\FormBase, this needs to be public
		public function dtgMembers_Bind() {
			$this->dtgMembers->DataSource = $this->objProject->GetPersonAsTeamMemberArray(\QCubed\Query\QQ::Clause($this->dtgMembers->OrderByClause));
		}

		// DataGrid Render Handlers Below
		public function EditColumn_Render(Person $objPerson) {
			// Let's specify a specific Control ID for our button, using the datagrid's CurrentRowIndex
			$strControlId = 'btnEditPerson' . $this->dtgMembers->CurrentRowIndex;

			$btnEdit = $this->objForm->GetControl($strControlId);
			if (!$btnEdit) {
				// Only create/instantiate a new Edit button for this Row if it doesn't yet exist
				$btnEdit = new \QCubed\Project\Jqui\Button($this->dtgMembers, $strControlId);
				$btnEdit->Text = 'Edit';

				// Define an Event Handler on the Button
				// Because the event handler, itself, is defined in the control, we use \QCubed\Action\AjaxControl instead of \QCubed\Action\Ajax
				$btnEdit->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\AjaxControl($this, 'btnEditPerson_Click'));
			}

			// Finally, update the Actionparameter for our button to store the $objPerson's ID.
			$btnEdit->ActionParameter = $objPerson->Id;

			// Return the Rendered Button Control
			return $btnEdit->Render(false);
		}

		// Event Handlers Here
		public function btnEditPerson_Click($strFormId, $strControlId, $strParameter) {
			// Get pnlRight from the Parent Form
			$pnlRight = $this->objForm->GetControl($this->strPanelRightControlId);

			// First, remove all children panels from pnlRight
			$pnlRight->RemoveChildControls(true);

			// Now create a new PersonEditPanel, setting pnlRight as its parent
			// and specifying parent form's "CloseRightPanel" as the method callback
			// See the note in _constructor, above, for more information
			$objPersonToEdit = Person::Load($strParameter);
			new PersonEditPanel($pnlRight, $objPersonToEdit, 'CloseRightPanel');
		}

		public function btnEditProject_Click($strFormId, $strControlId, $strParameter) {
			// Get pnlRight from the Parent Form
			$pnlRight = $this->objForm->GetControl($this->strPanelRightControlId);

			// First, remove all children panels from pnlRight
			$pnlRight->RemoveChildControls(true);

			// Now create a new PersonEditPanel, setting pnlRight as its parent
			// and specifying parent form's "CloseRightPanel" as the method callback
			// See the note in _constructor, above, for more information
			new ProjectEditPanel($pnlRight, $this->objProject, 'CloseRightPanel');
		}
	}