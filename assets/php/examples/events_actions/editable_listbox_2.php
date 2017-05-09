<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $lstListbox;
		protected $txtItem;
		protected $btnAdd;

		protected $lblSelected;

		protected function formCreate() {
			// Define the Controls
			$this->lstListbox = new \QCubed\Project\Control\ListBox($this);
			$this->lstListbox->Name = 'Items to Choose From';
			$this->lstListbox->Rows = 6;

			// When the the user changes the selection on the listbox, we'll call lstListbox_Change
			$this->lstListbox->AddAction(new \QCubed\Event\Change(), new \QCubed\Action\Ajax('lstListbox_Change'));
			$this->lstListbox->AddItem('Sample Item', 'Sample Item');

			// Note: we need to explicitly define the textbox's ControlId so that we can write
			// javascript code to access it.  This is done by passing in the specific ControlId we want
			// as the optional second parameter into the QControl's constructor.
			$this->txtItem = new \QCubed\Project\Control\TextBox($this, 'txtItem');
			$this->txtItem->Name = 'Item to Add';

			$this->btnAdd = new \QCubed\Project\Jqui\Button($this);
			$this->btnAdd->Text = 'Add Item';

			$this->lblSelected = new \QCubed\Control\Label($this);
			$this->lblSelected->Name = 'Item Currently Selected';
			$this->lblSelected->Text = '<none>';

			// When we submit, we want to do the following actions:
			// * Immediately disable the button, textbox and listbox
			// * Perform the AddListItem action via AJAX
			$objSubmitListItemActions = array(
				new \QCubed\Action\ToggleEnable($this->btnAdd, false),
				new \QCubed\Action\ToggleEnable($this->txtItem, false),
				new \QCubed\Action\ToggleEnable($this->lstListbox, false),
				new \QCubed\Action\Ajax('AddListItem')
			);

			// Let's add this set of actions to the Add Button
			// Note: we are adding a conditional, specifying that txtItem must have
			// text in it, before performing the actions.  We can call out 'txtItem' in our
			// javascript call because we explicitly set $this->txtItem's ControlId to 'txtItem'
			// when we constructed it (see line 23)
			$this->btnAdd->AddActionArray(new \QCubed\Event\Click(0, "qcubed.getControl('txtItem').value != ''"), $objSubmitListItemActions);

			// Let's add this set of actions to the Textbox, as a EnterKeyEvent
			// Note: we are adding the same conditional
			$this->txtItem->AddActionArray(new \QCubed\Event\EnterKey(0, "qcubed.getControl('txtItem').value != ''"), $objSubmitListItemActions);

			// Because the enter key will also call form.submit() on some browsers, which we
			// absolutely DON'T want to have happen, let's be sure to terminate any additional
			// actions on EnterKey
			$this->txtItem->AddAction(new \QCubed\Event\EnterKey(), new \QCubed\Action\Terminate());
		}

		protected function lstListbox_Change() {
			// Whenever the user changes the selected listbox item, let's
			// update the label to reflect the selected item
			$this->lblSelected->Text = $this->lstListbox->SelectedValue;
		}

		protected function AddListItem() {
			// First off, let's make sure that data was typed in
			// Note that even though we are doing javascript-based validation, we still want to do it on
			// the server as well, just in case
			if (!strlen(trim($this->txtItem->Text))) {
				$this->txtItem->Warning = 'Nothing was entered';
			} else {			
				// Add the new item
				$this->lstListbox->AddItem(trim($this->txtItem->Text), trim($this->txtItem->Text));
			}

			// Clear the textbox
			$this->txtItem->Text = '';

			// Let's re-enable all the controls;
			$this->txtItem->Enabled = true;
			$this->lstListbox->Enabled = true;
			$this->btnAdd->Enabled = true;
		}

	}

	ExampleForm::Run('ExampleForm');
?>
