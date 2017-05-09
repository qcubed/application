<?php
	require_once('../qcubed.inc.php');

	class Order {
		public $Id;
		public $Items;
	}
	
	class ExampleForm extends \QCubed\Project\Control\FormBase {
		// Declare the DataGrid
		protected $dtgOrders;
        public $objOrdersArray = array();

		protected $intOrderCnt = 0;

		protected $btnServerAction;
		protected $btnRestartOnServerAction;

		protected $ctlTimer;

		protected $btnStart;
		protected $btnStop;

		protected $objRandomProductsArray = array();

		protected function formCreate() {

			$this->objRandomProductsArray[0] = '1x Sandwich, 2x Coke, 1x Big Pekahuna Burger';
			$this->objRandomProductsArray[1] = '2x French fries, 3x Burritos, 1x Hot Dog';
			$this->objRandomProductsArray[2] = '1x Steak - Lone Star, 5x Wiener Schnitzel';
			$this->objRandomProductsArray[3] = '3x Socks, 3x Shorts';

			// Define the DataGrid
			$this->dtgOrders = new \QCubed\Project\Control\DataGrid($this);
			$this->dtgOrders->UseAjax = true;

			//button to simulate a server action
			$this->btnServerAction = new \QCubed\Project\Jqui\Button($this);
			$this->btnServerAction->SetCustomStyle('float','right');

			//button for switching the 'RestartOnServer' capability of \QCubed\Project\Control\JsTimer on/off
			$this->btnRestartOnServerAction = new \QCubed\Project\Jqui\Button($this);
			$this->btnStop = new \QCubed\Project\Jqui\Button($this);
			$this->btnStart = new \QCubed\Project\Jqui\Button($this);

			//create the timer: parent = $this, $time = 3000ms, periodic = true, autostart=true
			$this->ctlTimer = new \QCubed\Project\Control\JsTimer($this,3000,true,true);


			$this->dtgOrders->CreatePropertyColumn('Order-Id', 'Id');
			$this->dtgOrders->CreatePropertyColumn('Products', 'Items');
			$col = $this->dtgOrders->CreateCallableColumn('Remove', [$this, 'renderRemoveButton']);
			$col->HtmlEntities = false;
			$this->dtgOrders->SetDataBinder('dtgOrders_Bind');

			$this->btnServerAction->AddAction(new \QCubed\Event\Click(),new \QCubed\Action\Server('OnServerAction'));
			$this->btnServerAction->Text = "Server Action";

			$this->btnRestartOnServerAction->AddAction(new \QCubed\Event\Click(),new \QCubed\Action\Ajax('OnToggleRestartOnServerAction'));
			$this->btnRestartOnServerAction->Text = "Restart On Server Action [off]";


			$this->ctlTimer->AddAction(new \QCubed\Event\TimerExpired(), new \QCubed\Action\Ajax('OnUpdateDtg'));

			$this->btnStart->AddAction(new \QCubed\Event\Click(),new \QCubed\Action\AjaxControl($this->ctlTimer,'Start'));
			$this->btnStop->AddAction(new \QCubed\Event\Click(),new \QCubed\Action\AjaxControl($this->ctlTimer,'Stop'));
			$this->btnStart->Text = 'Start';
			$this->btnStop->Text = 'Stop';

		}

		//the timer callback function for updating the orders
		public function OnUpdateDtg() {
			//fetch new orders
			$randProdNum = rand(0,3);
			$this->intOrderCnt++;
			// Limit the amount of items in a table to 10
			// There is no paging for this datagrid, 
			// so many items here can consume CPU greatly
			if ($this->intOrderCnt > 10) {
				$this->intOrderCnt = 1;
				$this->objOrdersArray = array();
			}
			$order = new Order();
			$order->Id = $this->intOrderCnt;
			$order->Items = $this->objRandomProductsArray[$randProdNum];
			$this->objOrdersArray[$this->intOrderCnt] = $order;
			$this->dtgOrders->MarkAsModified();
		}

		public function OnToggleRestartOnServerAction() {
			$blnRestart = $this->ctlTimer->RestartOnServerAction;
			if($blnRestart)
				$this->btnRestartOnServerAction->Text = "Restart On Server Action [off]";
			else
				$this->btnRestartOnServerAction->Text = "Restart On Server Action [on]";
			
			$this->ctlTimer->RestartOnServerAction = !$blnRestart;
		}

		public function OnServerAction() {
			//ServerAction test
		}
			
		public function renderRemoveButton($item) {
			$objControlId = "removeButton" . $item->Id;
            $objControl = $this->GetControl($objControlId);
			if (!$objControl) {
				$objControl = new \QCubed\Project\Jqui\Button($this->dtgOrders, $objControlId);
				$objControl->Text = true;
				$objControl->ActionParameter = $item->Id;
				$objControl->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax("removeButton_Click"));
			}
                        
			$objControl->Label = "Remove";

			// We pass the parameter of "false" to make sure the control doesn't render
			// itself RIGHT HERE - that it instead returns its string rendering result.
			return $objControl->Render(false);
		}
		
		
		public function removeButton_Click($strFormId, $strControlId, $strParameter) {
			unset($this->objOrdersArray[$strParameter]);
            $this->dtgOrders->MarkAsModified();
		}

		protected function dtgOrders_Bind() {
			// We load the data source, and set it to the datagrid's DataSource parameter
			$this->dtgOrders->DataSource = $this->objOrdersArray;
		}
	}

	ExampleForm::Run('ExampleForm');
?>
