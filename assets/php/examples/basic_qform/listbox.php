<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $lblMessage;
	// A listbox of Persons
	protected $lstPersons;

	protected $chkPersons;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define our Label
		$this->lblMessage = new \QCubed\Control\Label($this);
		$this->lblMessage->Text = '<None>';

		// Define the ListBox, and create the first listitem as 'Select One'
		$this->lstPersons = new \QCubed\Project\Control\ListBox($this);
		$this->lstPersons->AddItem('- Select One -', null);

		// Add the items for the listbox, pulling in from the Person table
		$objPersons = Person::LoadAll(\QCubed\Query\QQ::Clause(\QCubed\Query\QQ::OrderBy(QQN::Person()->LastName, QQN::Person()->FirstName)));
		if ($objPersons){
			foreach ($objPersons as $objPerson) {
				// We want to display the listitem as Last Name, First Name
				// and the VALUE of the listitem should be the person object itself
				$this->lstPersons->AddItem($objPerson->LastName . ', ' . $objPerson->FirstName, $objPerson);
			}
		}
		// Declare a \QCubed\Event\Change to call a server action: the lstPersons_Change PHP method
		$this->lstPersons->AddAction(new \QCubed\Event\Change(), new \QCubed\Action\Server('lstPersons_Change'));

		// Do the same but with a multiple selection \QCubed\Control\CheckboxList
		$this->chkPersons = new \QCubed\Control\CheckboxList($this);
		if ($objPersons){
			foreach ($objPersons as $objPerson) {
				// We want to display the listitem as Last Name, First Name
				// and the VALUE of the listitem will be the database id
				$this->chkPersons->AddItem($objPerson->FirstName . ' ' . $objPerson->LastName, $objPerson->Id);
			}
		}
		$this->chkPersons->RepeatColumns = 2;
		$this->chkPersons->AddAction(new \QCubed\Event\Change(), new \QCubed\Action\Server('chkPersons_Change'));

	}

	// Handle the changing of the listbox
	protected function lstPersons_Change($strFormId, $strControlId, $strParameter) {
		// See if there is something selected
		// Note that in the HTML that gets rendered, the <option> values are arbitrary
		// index numbers.  However, we put in the whole Person object as the \QCubed\Control\ListItem
		// value.  So the SelectedValue property of the \QCubed\Control\ListControl will
		// do a proper lookup of the \QCubed\Control\ListItem that was selected, and will return
		// to us the Person OBJECT (or NULL if they selected "- Select One -").
		$objPerson = $this->lstPersons->SelectedValue;

		if ($objPerson) {
			$this->lblMessage->Text = sprintf('%s %s, Person ID of %s', $objPerson->FirstName, $objPerson->LastName, $objPerson->Id);
		} else {
			// No one was selected
			$this->lblMessage->Text = '<None>';
		}
	}

	// Handle the changing of the checkbox list
	protected function chkPersons_Change($strFormId, $strControlId, $strParameter) {
		// In this example, since our values are database ids, we use the ids to lookup the names and display them.

		$names = $this->chkPersons->SelectedNames;

		if ($names) {
			$this->lblMessage->Text = implode (", ", $names);
		} else {
			// No one was selected
			$this->lblMessage->Text = '<None>';
		}
	}

}

// Run the Form we have defined
// The \QCubed\Project\Control\FormBase engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>