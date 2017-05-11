<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	protected $btnButton;
	protected $btnButton2;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define the Label
		$this->lblMessage = new \QCubed\Control\Label($this);
		$this->lblMessage->Text = 'Click the button to change my message.';

		// Define two Buttons
		$this->btnButton = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton->Text = 'Click Me!';
		$this->btnButton2 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton2->Text = '(No Spinner)';

		// Define the Wait Icon -- we need to remember to "RENDER" this wait icon, too!
		$this->objDefaultWaitIcon = new \QCubed\Control\WaitIcon($this);

		// Add a Click event handler to the button -- the action to run is an AjaxAction.
		$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton_Click'));

		// Add a second click event handler which will use NO spinner
		$this->btnButton2->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton_Click', null));
	}

	// The "btnButton_Click" Event handler
	protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
		$strMessage = 'Hello, world!';
		// Let's add artificial latency/wait to show the spinner
		sleep(1);
		if ($this->lblMessage->Text == $strMessage){
			$this->lblMessage->Text = 'Click the button to change my message.';
		} else {
			$this->lblMessage->Text = $strMessage;
		}
	}
}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>