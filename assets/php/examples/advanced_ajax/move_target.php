<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblHandle;
	protected $txtTextbox;
	protected $pnlParent;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		$this->pnlParent = new \QCubed\Control\Panel($this);
		$this->pnlParent->AutoRenderChildren = true;

		$this->lblHandle = new \QCubed\Control\Panel($this->pnlParent);
		$this->lblHandle->Text = 'Please Enter your Name';
		$this->lblHandle->Cursor = 'move';
		$this->lblHandle->BackColor = '#333333';
		$this->lblHandle->ForeColor = '#FFFFFF';
		$this->lblHandle->Width = '250px';
		$this->lblHandle->Padding = '4';

		$this->txtTextbox = new \QCubed\Project\Control\TextBox($this->pnlParent);
		$this->txtTextbox->Width = '250px';

		// Let's assign the panel as a moveable control, handled
		// by the label.
		$this->pnlParent->Moveable = true;
		$this->pnlParent->DragObj->Handle = $this->lblHandle;
	}
}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>