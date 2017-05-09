<?php
define ('__IN_EXAMPLE__', true);
require_once('../qcubed.inc.php');


// The following code sets up a temporary watcher just for this example, since the examples are based on the default
// installation of the code, and the default installation does not create a watcher class. Normally, to be able to
// use watchers correctly, you must edit the \QCubed\Project\Watcher\Watcher.class.php to specify the kind of watcher you want to use.


class ExampleForm extends \QCubed\Project\Control\FormBase {

	// Declare the DataGrid
	protected $dtgPersons;
	protected $txtFirstName;
	protected $txtLastName;
	protected $btnNew;
	protected $timer;
	/** @var  \QCubed\Control\Proxy */
	protected $pxyDelete;

	protected function formCreate() {
		// Define the DataGrid
		$this->dtgPersons = new \QCubed\Project\Control\DataGrid($this);

		// Define Columns
		$this->dtgPersons->CreateNodeColumn('First Name', QQN::Person()->FirstName);
		$this->dtgPersons->CreateNodeColumn('Last Name', QQN::Person()->LastName);

		// Specify the local Method which will actually bind the data source to the datagrid.
		$this->dtgPersons->SetDataBinder('dtgPersons_Bind');

		// By default, the examples database uses the qc_watchers table to record when a something in the database has changed.
		// To configure this, including changing the table name, or even using a shared caching mechanism like
		// APC or Memcached, modify the \QCubed\Project\Watcher\Watcher class in project/includes/controls
		
		// Tell the datagrid to watch the Person table.
		$this->dtgPersons->Watch(QQN::Person());

		// Create a timer to periodically check whether another user has changed the database. Depending on your
		// application, you might not need to do this, as any activity the user does to a control will also check.
		//$this->timer = new \QCubed\Project\Control\JsTimer($this, 500, true);
		//$this->timer->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Ajax());

		$this->txtFirstName = new \QCubed\Project\Control\TextBox($this);
		$this->txtLastName = new \QCubed\Project\Control\TextBox($this);
		$this->btnNew = new \QCubed\Project\Jqui\Button($this);
		$this->btnNew->Text = 'Add';
		$this->btnNew->AddAction (new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnNew_Click'));

		// Create a proxy control to handle clicking for a delete
		$this->pxyDelete = new \QCubed\Control\Proxy($this);
		$this->pxyDelete->AddAction (new \QCubed\Event\Click(), new \QCubed\Action\Ajax ('delete_Click'));
	}

	protected function dtgPersons_Bind() {
		// We load the data source, and set it to the datagrid's DataSource parameter
		$this->dtgPersons->DataSource = Person::LoadAll();
	}
	protected function btnNew_Click($strFormId, $strControlId, $strParameter) {
		$objPerson = new Person();
		$objPerson->FirstName = $this->txtFirstName->Text;
		$objPerson->LastName = $this->txtLastName->Text;
		$objPerson->Save();
	}
}

ExampleForm::Run('ExampleForm');
?>