<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	// Local declarations of our Qcontrols
	protected $txtBasic;
	protected $txtInt;
	protected $txtFlt;
	protected $txtList;
	protected $txtEmail;
	protected $txtUrl;
	protected $txtCustom;
	protected $btnValidate;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// Define our Label
		$this->txtBasic = new \QCubed\Project\Control\TextBox($this);
		$this->txtBasic->Name = t("Basic");

		$this->txtBasic = new \QCubed\Project\Control\TextBox($this);
		$this->txtBasic->MaxLength = 5;

		$this->txtInt = new \QCubed\Control\IntegerTextBox($this);
		$this->txtInt->Maximum = 10;

		$this->txtFlt = new \QCubed\Control\FloatTextBox($this);

		$this->txtList = new \QCubed\Control\CsvTextBox($this);
		$this->txtList->MinItemCount = 2;
		$this->txtList->MaxItemCount = 5;

		$this->txtEmail = new \QCubed\Control\EmailTextBox($this);
		$this->txtUrl = new \QCubed\Control\UrlTextBox($this);
		$this->txtCustom = new \QCubed\Project\Control\TextBox($this);

		// These parameters are fed into filter_var. See PHP doc on filter_var() for more info.
		$this->txtCustom->ValidateFilter = FILTER_VALIDATE_REGEXP;
		$this->txtCustom->ValidateFilterOptions = array('options'=>array ('regexp'=>'/^(0x)?[0-9A-F]*$/i')); // must be a hex decimal, optional leading 0x

		$this->txtCustom->LabelForInvalid = 'Hex value required.';

		$this->btnValidate = new \QCubed\Project\Jqui\Button ($this);
		$this->btnValidate->Text = "Filter and Validate";
		$this->btnValidate->AddAction (new \QCubed\Event\Click(), new \QCubed\Action\Server()); // just validates
		$this->btnValidate->CausesValidation = true;
	}

}

// Run the Form we have defined
// The \QCubed\Project\Control\FormBase engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>