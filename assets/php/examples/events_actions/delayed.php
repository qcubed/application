<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $txtItem;
		protected $lblSelected;

		protected function formCreate() {
			// Define the Controls
			$this->txtItem = new \QCubed\Project\Control\TextBox($this);
			$this->txtItem->Name = 'Random Data';

			$this->lblSelected = new \QCubed\Control\Label($this);
			$this->lblSelected->Name = 'What You Entered';
			$this->lblSelected->Text = '<none>';

			// We want to update the label whenever the user types in data
			// into the textbox.  However, in order to prevent too many simultaneous
			// submits, we'll add a half-second delay on the KeyPress event.
			$this->txtItem->AddAction(new \QCubed\Event\KeyPress(500), new \QCubed\Action\Ajax('txtItem_KeyPress'));

			// Because this is just an example, we'll go ahead and terminate on Enter/ESC to prevent
			// any inadvertant form posts -- this can obviously be changed to a \QCubed\Action\Ajax to a separate
			// method/function, etc.
			$this->txtItem->AddAction(new \QCubed\Event\EnterKey(), new \QCubed\Action\Terminate());
			$this->txtItem->AddAction(new \QCubed\Event\EscapeKey(), new \QCubed\Action\Terminate());
		}

		protected function txtItem_KeyPress() {
			// Update the Label
			$this->lblSelected->Text = trim($this->txtItem->Text);
		}
	}

	ExampleForm::Run('ExampleForm');
?>
