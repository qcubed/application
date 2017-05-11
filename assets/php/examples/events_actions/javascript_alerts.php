<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $lblMessage;
		protected $btnJavaScript;
		protected $btnAlert;
		protected $btnConfirm;

		protected function formCreate() {
			// Define the Controls
			$this->lblMessage = new \QCubed\Control\Label($this);
			$this->lblMessage->Text = 'Click on the "\QCubed\Action\Confirm Example" button to change.';

			// Define different buttons to show off the various JavaScript-based Actions
			$this->btnJavaScript = new \QCubed\Project\Jqui\Button($this);
			$this->btnJavaScript->Text = '\QCubed\Action\JavaScript Example';
			$this->btnJavaScript->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\JavaScript('SomeArbitraryJavaScript();'));

			// Define different buttons to show off the various Alert-based Actions
			$this->btnAlert = new \QCubed\Project\Jqui\Button($this);
			$this->btnAlert->Text = '\QCubed\Action\Alert Example';
			$this->btnAlert->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Alert("This is a test of the \"\QCubed\Action\Alert\" example.\r\nIsn't this fun? =)"));

			// Define different buttons to show off the various Confirm-based Actions
			$this->btnConfirm = new \QCubed\Project\Jqui\Button($this);
			$this->btnConfirm->Text = '\QCubed\Action\Confirm Example';
			$this->btnConfirm->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Confirm('Are you SURE you want to update the lblMessage?'));
			// Notice: this next action ONLY RUNS if the user hit "Ok"
			$this->btnConfirm->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnConfirm_Click'));
		}

		protected function btnConfirm_Click() {
			// Update the Label
			if ($this->lblMessage->Text == 'Hello, world!')
				$this->lblMessage->Text = 'Buh Bye!';
			else
				$this->lblMessage->Text = 'Hello, world!';
		}
	}

	ExampleForm::Run('ExampleForm');
?>
