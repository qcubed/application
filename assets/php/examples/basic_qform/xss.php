<?php
require_once('../qcubed.inc.php');

// Define the \QCubed\Project\Control\FormBase with all our Qcontrols
class ExamplesForm extends \QCubed\Project\Control\FormBase {

	/** @var \QCubed\Project\Control\TextBox */
	protected $txtTextbox1;

	/** @var \QCubed\Control\Label */
	protected $lblLabel1;

	/** @var \QCubed\Project\Jqui\Button */
	protected $btnButton1;

	/** @var \QCubed\Project\Control\TextBox */
	protected $txtTextbox2;

	/** @var \QCubed\Control\Label */
	protected $lblLabel2;

	/** @var \QCubed\Project\Jqui\Button */
	protected $btnButton2;

	/** @var \QCubed\Project\Control\TextBox */
	protected $txtTextbox3;

	/** @var \QCubed\Control\Label */
	protected $lblLabel3;

	/** @var \QCubed\Project\Jqui\Button */
	protected $btnButton3;

	/** @var \QCubed\Project\Control\TextBox */
	protected $txtTextbox4;

	/** @var \QCubed\Control\Label */
	protected $lblLabel4;

	/** @var \QCubed\Project\Jqui\Button */
	protected $btnButton4;

	/** @var \QCubed\Project\Control\TextBox */
	protected $txtTextbox5;

	/** @var \QCubed\Control\Label */
	protected $lblLabel5;

	/** @var \QCubed\Project\Jqui\Button */
	protected $btnButton5;

	// Initialize our Controls during the Form Creation process
	protected function formCreate() {
		// default legacy protection, will throw an exception
		$this->txtTextbox1 = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox1->Text = 'Hello!';
		$this->txtTextbox1->Width = 500;

		$this->lblLabel1 = new \QCubed\Control\Label($this);
		$this->lblLabel1->HtmlEntities = false;
		$this->lblLabel1->Text = "";

		$this->btnButton1 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton1->Text = "Parse and Display";
		$this->btnButton1->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton1_Click'));

		// htmlentities mode
		$this->txtTextbox2 = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox2->CrossScripting = QCrossScripting::HtmlEntities;
		$this->txtTextbox2->Text = 'Hello! <script>alert("I am an evil attacker.")</script>';
		$this->txtTextbox2->Width = 500;

		$this->lblLabel2 = new \QCubed\Control\Label($this);
		$this->lblLabel2->Text = "";

		$this->btnButton2 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton2->Text = "Parse and Display";
		$this->btnButton2->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton2_Click'));

		// full protection with the HTMLPurifier defaults
		$this->txtTextbox3 = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox3->CrossScripting = QCrossScripting::HTMLPurifier;
		$this->txtTextbox3->Text = 'Hello! <script>alert("I am an evil attacker.")</script>';
		$this->txtTextbox3->Width = 500;

		$this->lblLabel3 = new \QCubed\Control\Label($this);
		$this->lblLabel3->Text = "";

		$this->btnButton3 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton3->Text = "Parse and Display";
		$this->btnButton3->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton3_Click'));

		// full protection with an allowed list of tags
		$this->txtTextbox4 = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox4->CrossScripting = QCrossScripting::HTMLPurifier;
		$this->txtTextbox4->SetPurifierConfig("HTML.Allowed", "b,strong,i,em,img[src]");
		$this->txtTextbox4->Text = 'Hello! <script>alert("I am an evil attacker.")</script><b>Hello</b> <i>again</i>!';
		$this->txtTextbox4->Width = 500;

		$this->lblLabel4 = new \QCubed\Control\Label($this);
		$this->lblLabel4->HtmlEntities = false;
		$this->lblLabel4->Text = "";

		$this->btnButton4 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton4->Text = "Parse and Display";
		$this->btnButton4->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton4_Click'));

		// the textbox won't have the XSS protection!
		$this->txtTextbox5 = new \QCubed\Project\Control\TextBox($this);
		$this->txtTextbox5->CrossScripting = QCrossScripting::Allow;
		$this->txtTextbox5->Text = 'Hello! <script>alert("I am an evil attacker.")</script><b>Hello</b> again!';
		$this->txtTextbox5->Width = 500;

		$this->lblLabel5 = new \QCubed\Control\Label($this);
		$this->lblLabel5->HtmlEntities = false;
		$this->lblLabel5->Text = "";

		$this->btnButton5 = new \QCubed\Project\Jqui\Button($this);
		$this->btnButton5->Text = "Parse and Display";
		$this->btnButton5->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton5_Click'));
	}

	protected function btnButton1_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel1->Text = $this->txtTextbox1->Text;
	}

	protected function btnButton2_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel2->Text = $this->txtTextbox2->Text;
	}

	protected function btnButton3_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel3->Text = $this->txtTextbox3->Text;
	}

	protected function btnButton4_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel4->Text = $this->txtTextbox4->Text;
	}

	protected function btnButton5_Click($strFormId, $strControlId, $strParameter) {
		$this->lblLabel5->Text = $this->txtTextbox5->Text;
	}
}

// Run the Form we have defined
ExamplesForm::Run('ExamplesForm');
?>