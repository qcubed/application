<?php
	/** @noinspection PhpIncludeInspection */
	require_once('../qcubed.inc.php');

	// adding the javascript return parameter to the event is one 
	// possibility to retrieve values/objects/arrays via an Ajax or Server Action
	class MyQSlider_ChangeEvent extends \QCubed\Event\EventBase {
		const EVENT_NAME = 'slidechange';
		const JS_RETURN_PARAM = 'arguments[1].value';
	}

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		/** @var \QCubed\Project\Jqui\Resizable */
		protected $Resizable;
		/** @var \QCubed\Project\Jqui\Selectable */
		protected $Selectable;
		/** @var \QCubed\Project\Jqui\Sortable */
		protected $Sortable;
		/** @var \QCubed\Project\Jqui\Slider */
		protected $Slider;
		/** @var \QCubed\Project\Jqui\Button */
		protected $btnSubmit;
		/** @var \QCubed\Project\Jqui\Sortable */
		protected $Sortable2;

		/** @var \QCubed\Control\Panel */
		protected $SortableResult;
		/** @var \QCubed\Control\Panel */
		protected $Sortable2Result;
		/** @var \QCubed\Control\Panel */
		protected $ResizableResult;
		/** @var \QCubed\Control\Panel */
		protected $SelectableResult;
		/** @var \QCubed\Control\Panel */
		protected $SubmitResult;
		/** @var \QCubed\Control\Panel */
		protected $SliderResult;

		protected function formCreate() {
			$strServerActionJsParam = "";

			$this->btnSubmit = new \QCubed\Project\Jqui\Button($this);
			$this->btnSubmit->Text = "ServerAction Submit";
			$this->SubmitResult = new \QCubed\Control\Panel($this);

			// Slider
			$this->Slider = new \QCubed\Project\Jqui\Slider($this);
			$this->Slider->Max = 1250;
			$this->Slider->AddAction(new MyQSlider_ChangeEvent(), new \QCubed\Action\Ajax('onSlide'));
			$this->SliderResult = new \QCubed\Control\Panel($this);

			// Resizable
			$this->Resizable = new \QCubed\Control\Panel($this);
			$this->Resizable->CssClass = 'resizable';
			$this->Resizable->Resizable = true;
			$this->ResizableResult = new \QCubed\Control\Panel($this);
			$strJsParam = '{ 
				"width": $j("#' . $this->Resizable->ControlId . '").width(), 
				"height": $j("#' . $this->Resizable->ControlId . '").height() 
			}';
			$this->Resizable->AddAction(new \QCubed\Jqui\Event\ResizableStop(), new \QCubed\Action\Ajax("onResize", "default", null, $strJsParam));
			$this->ResizableResult = new \QCubed\Control\Panel($this);

			$strServerActionJsParam = '{"resizable": ' . $strJsParam . ', ';

			// Selectable
			$this->Selectable = new \QCubed\Project\Jqui\Selectable($this);
			$this->Selectable->AutoRenderChildren = true;
			$this->Selectable->CssClass = 'selectable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new \QCubed\Control\Panel($this->Selectable);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'selitem';
			}
			$this->Selectable->Filter = 'div.selitem';

			/*
			* if your objects to return get more complex you can define a javascript function that returns your
			* object. the essential thing is the ".call()", this executes the function that you have just defined
			* and returns your object.
			* In this example a function is uesd to temporary store jquery's search result for selected items,
			* because it is needed twice. then the ids are stored to objRet.ids as a comma-separated string and
			* the contents of the selected items are stored to objRet.content as an array.
			*
			*/
			$this->SelectableResult = new \QCubed\Control\Panel($this);
			$strJsParam = 'function() { 
				objRet = new Object(); 
				selection = $j("#' . $this->Selectable->ControlId . '")
					.find(".ui-selected");
				objRet.ids = selection.map(function(){
						return this.id;
					}).get()
					.join(",");
				objRet.content = selection.map(function() { 
					return $j(this).html();
				}).get(); 
				return objRet;
			}.call()';
			$this->Selectable->AddAction(new \QCubed\Jqui\Event\SelectableStop(), new \QCubed\Action\Ajax("onSelect", "default", null, $strJsParam));

			$strServerActionJsParam .= '"selectable": ' . $strJsParam . ', ';


			// Sortable
			$this->Sortable = new \QCubed\Project\Jqui\Sortable($this);
			$this->Sortable->AutoRenderChildren = true;
			$this->Sortable->CssClass = 'sortable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new \QCubed\Control\Panel($this->Sortable);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable->Items = 'div.sortitem';

			$this->SortableResult = new \QCubed\Control\Panel($this);
			$strJsParam = '$j("#' . $this->Sortable->ControlId . '").
				find("div.sortitem").
				map(function() { 
					return $j(this).html()
				}).get()';
			$this->Sortable->AddAction(new \QCubed\Jqui\Event\SortableUpdate(), new \QCubed\Action\Ajax("onSort", "default", null, $strJsParam));

			$strServerActionJsParam .= '"sortable": ' . $strJsParam . '}';


			//a second Sortable that can receive items from the first Sortable
			//when an item is dragged over from the first sortable an receive event is triggered
			$this->Sortable2 = new \QCubed\Project\Jqui\Sortable($this);
			$this->Sortable2->AutoRenderChildren = true;
			$this->Sortable2->CssClass = 'sortable';
			for ($i = 6; $i <= 10; ++$i) {
				$pnl = new \QCubed\Control\Panel($this->Sortable2);
				$pnl->Text = 'Item ' . $i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable2->Items = 'div.sortitem';

			//allow dragging from Sortable to Sortable2
			$this->Sortable->ConnectWith = '#' . $this->Sortable2->ControlId;
			//enable the following line to allow dragging Sortable2 child items to the Sortable list
			// $this->Sortable2->ConnectWith = '#' . $this->Sortable->ControlId;

			//using a \QCubed\Js\Closure as the ActionParameter for Sortable2 to return a Js object
			//the ActionParameter is used for every ajax / server action defined on this control
			$this->Sortable2->ActionParameter = 
				new \QCubed\Js\Closure('return $j("#' . $this->Sortable2->ControlId . '")
					.find("div.sortitem")
					.map(function() { 
						return $j(this).html()
					}).get();');

			//(the list of names from the containing items) is returned for the following two Ajax Actions
			$this->Sortable2->AddAction(new \QCubed\Jqui\Event\SortableUpdate(), new \QCubed\Action\Ajax("onSort2"));
			//$this->Sortable2->AddAction(new \QCubed\Jqui\Event\SortableReceive() ,new \QCubed\Action\Ajax("onSort2"));

			$this->Sortable2Result = new \QCubed\Control\Panel($this);

			$this->btnSubmit->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server("onSubmit", null, $strServerActionJsParam));
		}

		public function onSort($formId, $objId, $objParam) {
			$this->SortableResult->Text = print_r($objParam, true);
		}

		public function onSort2($formId, $objId, $objParam) {
			$this->Sortable2Result->Text = print_r($objParam, true);
		}

		public function onResize($formId, $objId, $objParam) {
			$this->ResizableResult->Text = print_r($objParam, true);
		}

		public function onSelect($formId, $objId, $objParam) {
			$this->SelectableResult->Text = print_r($objParam, true);
		}

		public function onSubmit($formId, $objId, $objParam) {
			$this->SubmitResult->Text = print_r($objParam, true);
		}

		public function onSlide($formId, $objId, $objParam) {
			$this->SliderResult->Text = print_r($objParam, true);
		}
	}

	ExampleForm::Run('ExampleForm');
