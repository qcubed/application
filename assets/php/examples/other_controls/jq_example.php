<?php
    require_once('../qcubed.inc.php');	
    
	class ExampleForm extends \QCubed\Project\Control\FormBase {
		/** @var \QCubed\Project\Jqui\Draggable */
		protected $Draggable;
		/** @var \QCubed\Project\Jqui\Droppable */
		protected $Droppable;
		/** @var \QCubed\Project\Jqui\Resizable */
		protected $Resizable;
		/** @var \QCubed\Project\Jqui\Selectable */
		protected $Selectable;
		/** @var \QCubed\Project\Jqui\Sortable */
		protected $Sortable;
	
		/** @var \QCubed\Project\Jqui\Accordion */
		protected $Accordion;
		/** @var \QCubed\Project\Jqui\Autocomplete */
		protected $Autocomplete;
		/** @var \QCubed\Project\Jqui\Autocomplete */
		protected $AjaxAutocomplete;
		/** @var \QCubed\Project\Jqui\Button */
		protected $Button;
		/** @var \QCubed\Project\Jqui\Checkbox */
		protected $CheckBox;
		/** @var \QCubed\Project\Jqui\RadioButton */
		protected $RadioButton;
		/** @var \QCubed\Project\Jqui\Button */
		protected $IconButton;
		/** @var \QCubed\Control\CheckboxList */
		protected $CheckList1;
		/** @var \QCubed\Control\CheckboxList */
		protected $CheckList2;
		/** @var \QCubed\Control\RadioButtonList */
		protected $RadioList1;
		/** @var \QCubed\Control\RadioButtonList */
		protected $RadioList2;
		/** @var \QCubed\Project\Jqui\SelectMenu */
		protected $SelectMenu;

		/** @var \QCubed\Project\Jqui\Datepicker */
		protected $Datepicker;
		/** @var \QCubed\Project\Jqui\DatepickerBox */
		protected $DatepickerBox;
		/** @var \QCubed\Project\Jqui\Dialog */
		protected $Dialog;
		/** @var \QCubed\Project\Jqui\Progressbar */
		protected $Progressbar;
		/** @var \QCubed\Project\Jqui\Slider */
		protected $Slider;
		protected $Slider2;
		/** @var \QCubed\Project\Jqui\Tabs */
		protected $Tabs;
        /** @var  \QCubed\Project\Jqui\Button */
        protected $btnShowDialog;
        /** @var  \QCubed\Project\Control\TextBox */
        protected $txtDlgTitle;
        /** @var  \QCubed\Project\Control\TextBox */
        protected $txtDlgText;

		// Array we'll use to demonstrate the autocomplete functionality
		static private $LANGUAGES = array("c++", "java", "php",
			"coldfusion", "javascript", "asp", "ruby");

		protected function formCreate() {
			$this->Draggable = new \QCubed\Control\Panel($this);
			$this->Draggable->Text = 'Drag me';
			$this->Draggable->CssClass = 'draggable';
			$this->Draggable->Moveable = true;
			//$this->Draggable->AddAction(new \QCubed\Jqui\Event\DraggableStop(), new \QCubed\Action\JavaScript("alert('Dragged to ' + ui.position.top + ',' + ui.position.left)"));
			$this->Draggable->AddAction(new \QCubed\Jqui\Event\DraggableStop(), new \QCubed\Action\Ajax("drag_stop"));
						
			// Dropable
			$this->Droppable = new \QCubed\Control\Panel($this);
			$this->Droppable->Text = "Drop here";
			//$this->Droppable->AddAction(new \QCubed\Jqui\Event\DroppableDrop(), new \QCubed\Action\JavaScript("alert('Dropped ' + ui.draggable.attr('id'))"));
			$this->Droppable->AddAction(new \QCubed\Jqui\Event\DroppableDrop(), new \QCubed\Action\Ajax("droppable_drop"));
			$this->Droppable->CssClass = 'droppable';
			$this->Droppable->Droppable = true;
	
			// Resizable
			$this->Resizable = new \QCubed\Control\Panel($this);
			$this->Resizable->CssClass = 'resizable';
			$this->Resizable->Resizable = true;
			$this->Resizable->AddAction (new \QCubed\Jqui\Event\ResizableStop(), new \QCubed\Action\Ajax ('resizable_stop'));

			
			// Selectable
			$this->Selectable = new \QCubed\Project\Jqui\Selectable($this);
			$this->Selectable->AutoRenderChildren = true;
			$this->Selectable->CssClass = 'selectable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new \QCubed\Control\Panel($this->Selectable);
				$pnl->Text = 'Item '.$i;
				$pnl->CssClass = 'selitem';
			}
			$this->Selectable->Filter = 'div.selitem';
			$this->Selectable->SelectedItems = array ($pnl->ControlId);	// pre-select last item
			$this->Selectable->AddAction(new \QCubed\Jqui\Event\SelectableStop(), new \QCubed\Action\Ajax ('selectable_stop'));

			// Sortable
			$this->Sortable = new \QCubed\Project\Jqui\Sortable($this);
			$this->Sortable->AutoRenderChildren = true;
			$this->Sortable->CssClass = 'sortable';
			for ($i = 1; $i <= 5; ++$i) {
				$pnl = new \QCubed\Control\Panel($this->Sortable);
				$pnl->Text = 'Item '.$i;
				$pnl->CssClass = 'sortitem';
			}
			$this->Sortable->Items = 'div.sortitem';
			$this->Sortable->AddAction(new \QCubed\Jqui\Event\SortableStop(), new \QCubed\Action\Ajax ('sortable_stop'));
			
			// Accordion
			$this->Accordion = new \QCubed\Project\Jqui\Accordion($this, 'accordionCtl');
			$lbl = new \QCubed\Control\LinkButton($this->Accordion);
			$lbl->Text = 'Header 1';
			$pnl = new \QCubed\Control\Panel($this->Accordion);
			$pnl->Text = 'Section 1';
			$lbl = new \QCubed\Control\LinkButton($this->Accordion);
			$lbl->Text = 'Header 2';
			$pnl = new \QCubed\Control\Panel($this->Accordion);
			$pnl->Text = 'Section 2';
			$lbl = new \QCubed\Control\LinkButton($this->Accordion);
			$lbl->Text = 'Header 3';
			$pnl = new \QCubed\Control\Panel($this->Accordion);
			$pnl->Text = 'Section 3';
			
			$this->Accordion->AddAction (new \QCubed\Event\Change(), new \QCubed\Action\Ajax ('accordion_change'));

			// Autocomplete

			// Both autocomplete controls below will use the mode
			// "match only on the beginning of the word"
			\QCubed\Project\Jqui\Autocomplete::UseFilter(\QCubed\Project\Jqui\Autocomplete::FILTER_STARTS_WITH);

			// Client-side only autocomplete
			$this->Autocomplete = new \QCubed\Project\Jqui\Autocomplete($this);
			$this->Autocomplete->Source = self::$LANGUAGES;
			$this->Autocomplete->Name = "Standard Autocomplete";

			// Ajax Autocomplete
			// Note: To show the little spinner while the ajax search is happening, you
			// need to define the .ui-autocomplete-loading class in a style sheet. See
			// header.inc.php for an example.
			$this->AjaxAutocomplete = new \QCubed\Project\Jqui\Autocomplete($this);
			$this->AjaxAutocomplete->SetDataBinder("update_autocompleteList");
			$this->AjaxAutocomplete->AddAction (new \QCubed\Jqui\Event\AutocompleteChange(), new \QCubed\Action\Ajax ('ajaxautocomplete_change'));
			$this->AjaxAutocomplete->AutoFocus = true;
			$this->AjaxAutocomplete->Name = 'With AutoFocus';

			// Button
			$this->Button = new \QCubed\Project\Jqui\Button($this);
			$this->Button->Label = "Click me";	// Label overrides Text
			$this->Button->AddAction(new \QCubed\Event\Click, new \QCubed\Action\Server("button_click"));

			$this->CheckBox = new \QCubed\Project\Jqui\Checkbox($this);
			$this->CheckBox->Text = "CheckBox";
			
			$this->RadioButton = new \QCubed\Project\Jqui\RadioButton($this);
			$this->RadioButton->Text = "RadioButton";

			$this->IconButton = new \QCubed\Project\Jqui\Button($this);
			$this->IconButton->Text = "Sample";
			$this->IconButton->ShowText = false;
			$this->IconButton->Icon = \QCubed\Jqui\Icon::Lightbulb;
			
			// Lists
			$this->CheckList1 = new \QCubed\Control\CheckboxList($this);
			$this->CheckList1->Name = "CheckBoxList with buttonset";
			foreach (self::$LANGUAGES as $strLang) {
				$this->CheckList1->AddItem ($strLang);
			}
			$this->CheckList1->ButtonMode = \QCubed\Control\CheckboxList::BUTTON_MODE_SET;

			$this->CheckList2 = new \QCubed\Control\CheckboxList($this);
			$this->CheckList2->Name = "CheckBoxList with button style";
			foreach (self::$LANGUAGES as $strLang) {
				$this->CheckList2->AddItem ($strLang);
			}
			$this->CheckList2->ButtonMode = \QCubed\Control\CheckboxList::BUTTON_MODE_JQ;
			$this->CheckList2->RepeatColumns = 4;
			
			$this->RadioList1 = new \QCubed\Control\RadioButtonList($this);
			$this->RadioList1->Name = "RadioButtonList with buttonset";
			foreach (self::$LANGUAGES as $strLang) {
				$this->RadioList1->AddItem ($strLang);
			}
			$this->RadioList1->ButtonMode = \QCubed\Control\CheckboxList::BUTTON_MODE_SET;

			$this->RadioList2 = new \QCubed\Control\RadioButtonList($this);
			$this->RadioList2->Name = "RadioButtonList with button style";
			foreach (self::$LANGUAGES as $strLang) {
				$this->RadioList2->AddItem ($strLang);
			}
			$this->RadioList2->ButtonMode = \QCubed\Control\CheckboxList::BUTTON_MODE_JQ;
			$this->RadioList2->RepeatColumns = 4;

			$this->SelectMenu = new \QCubed\Project\Jqui\SelectMenu($this);
			$this->SelectMenu->Name = "SelectMenu";
			$this->SelectMenu->Width = 200;
			foreach (self::$LANGUAGES as $strLang) {
				$this->SelectMenu->AddItem ($strLang);
			}


			// Datepicker
			$this->Datepicker = new \QCubed\Project\Jqui\Datepicker($this);
			$this->Datepicker->AddAction (new \QCubed\Jqui\Event\DatepickerSelect2(), new \QCubed\Action\Ajax('setDate'));
			$this->Datepicker->ActionParameter = 'Datepicker';
	
			// DatepickerBox
			$this->DatepickerBox = new \QCubed\Project\Jqui\DatepickerBox($this);
			$this->DatepickerBox->AddAction(new \QCubed\Event\Change(), new \QCubed\Action\Ajax('setDate'));
			$this->DatepickerBox->ActionParameter = 'DatepickerBox';


			// Dialog
			$this->Dialog = new \QCubed\Project\Jqui\Dialog($this);
			$this->Dialog->Text = 'a non modal dialog';
			$this->Dialog->AddButton ('Cancel', 'cancel');
			$this->Dialog->AddButton ('OK', 'ok');
			$this->Dialog->AddAction (new \QCubed\Event\DialogButton(), new \QCubed\Action\Ajax ('dialog_press'));
			$this->Dialog->AutoOpen = false;

            $this->btnShowDialog = new \QCubed\Project\Jqui\Button($this);
            $this->btnShowDialog->Text = 'Show Dialog';
            $this->btnShowDialog->AddAction (new \QCubed\Event\Click(), new \QCubed\Action\ShowDialog ($this->Dialog));

            $this->txtDlgTitle = new \QCubed\Project\Control\TextBox($this);
            $this->txtDlgTitle->Name = "Set Title To:";
            $this->txtDlgTitle->AddAction (new \QCubed\Event\KeyPress(10), new \QCubed\Action\Ajax('dlgTitle_Change'));
            $this->txtDlgTitle->AddAction (new \QCubed\Event\BackspaceKey(10), new \QCubed\Action\Ajax('dlgTitle_Change'));

            $this->txtDlgText = new \QCubed\Project\Control\TextBox($this);
            $this->txtDlgText->Name = "Set Text To:";
            $this->txtDlgText->AddAction (new \QCubed\Event\KeyPress(10), new \QCubed\Action\Ajax('dlgText_Change'));
            $this->txtDlgText->AddAction (new \QCubed\Event\BackspaceKey(10), new \QCubed\Action\Ajax('dlgText_Change'));

            // Progressbar
			$this->Progressbar = new \QCubed\Project\Jqui\Progressbar($this);
			$this->Progressbar->Value = 37;
	
			// Slider
			$this->Slider = new \QCubed\Project\Jqui\Slider($this);
			$this->Slider->AddAction (new \QCubed\Jqui\Event\SliderSlide(), new \QCubed\Action\JavaScript (
				'jQuery("#' . $this->Progressbar->ControlId . '").progressbar ("value", ui.value)'
			));
			$this->Slider->AddAction (new \QCubed\Jqui\Event\SliderChange(), new \QCubed\Action\Ajax ('slider_change'));

			$this->Slider2 = new \QCubed\Project\Jqui\Slider($this);
			$this->Slider2->Range = true;
			$this->Slider2->Values = array(10, 50);
			$this->Slider2->AddAction (new \QCubed\Jqui\Event\SliderChange(), new \QCubed\Action\Ajax ('slider2_change'));
						
			// Tabs
			$this->Tabs = new \QCubed\Project\Jqui\Tabs($this);
			$tab1 = new \QCubed\Control\Panel($this->Tabs);
			$tab1->Text = 'First tab is active by default';
			$tab2 = new \QCubed\Control\Panel($this->Tabs);
			$tab2->Text = 'Tab 2';
			$tab3 = new \QCubed\Control\Panel($this->Tabs);
			$tab3->Text = 'Tab 3';
			$this->Tabs->Headers = array('One', 'Two', 'Three');
			$this->Tabs->AddAction (new \QCubed\Jqui\Event\TabsActivate(), new \QCubed\Action\Ajax('tabs_change'));
		}

		protected function update_autocompleteList($strFormId, $strControlId, $strParameter) {
			$strLookup = $strParameter;
			$objControl = $this->GetControl ($strControlId);
			
			$cond = \QCubed\Query\QQ::OrCondition (
						\QCubed\Query\QQ::Like (QQN::Person()->FirstName, '%' . $strLookup . '%'),
						\QCubed\Query\QQ::Like (QQN::Person()->LastName, '%' . $strLookup . '%')
					);
					
			$clauses[] = \QCubed\Query\QQ::OrderBy (QQN::Person()->LastName, QQN::Person()->FirstName);
					
			$lst = Person::QueryArray ($cond, $clauses);
			
			/*
			 * If you implement Person::__toString in the model->Person.class.php file, you
			 * could just pass the $lst to the DataSource. If you want to add a 'label' item
			 * to the display, you can override toJsObject in the People.class.php file.
			 * 
			 * For puposes of this example, we will build a custom list using list items below.
			 * 
			 */  
			
			//$this->AjaxAutocomplete->DataSource = $lst; 
			$a = array();
			foreach ($lst as $objPerson) {
				$item = new \QCubed\Control\ListItem ($objPerson->FirstName . ' ' . $objPerson->LastName, $objPerson->Id);
				$a[] = $item;
			}
			$objControl->DataSource = $a;
		}
		
		protected function ajaxautocomplete_change() {
			\QCubed\Project\Application::DisplayAlert ('Selected item ID: ' . $this->AjaxAutocomplete->SelectedId);
		}
		
		protected function button_click() {
			$dtt = $this->DatepickerBox->DateTime;
			if ($dtt) {
				\QCubed\Project\Application::DisplayAlert ($dtt->qFormat('MM/DD/YY'));
			}
		}
		
		protected function slider_change() {
			\QCubed\Project\Application::DisplayAlert ($this->Progressbar->Value . ', ' . $this->Slider->Value);
		}
		
		protected function slider2_change() {
			$a = $this->Slider2->Values;
			\QCubed\Project\Application::DisplayAlert ($a[0] . ', ' . $a[1]);
		}
		
		public function dialog_press($strFormId, $strControlId, $strParameter) {
			$id = $this->Dialog->ClickedButton;
			\QCubed\Project\Application::DisplayAlert ($id . ' was pressed');
		}
		
		public function droppable_drop($strFormId, $strControlId, $strParameter) {
			$id = $this->Droppable->DropObj->DroppedId;
			\QCubed\Project\Application::DisplayAlert ($id . ' was dropped.');
		}
		
		public function resizable_stop($strFormId, $strControlId, $strParameter) {
			\QCubed\Project\Application::DisplayAlert ( 'Width change = ' . $this->Resizable->ResizeObj->DeltaX . ', height change = ' . $this->Resizable->ResizeObj->DeltaY);
		}

		public function drag_stop($strFormId, $strControlId, $strParameter) {
			$x = $this->Draggable->DragObj->DeltaX;
			$y = $this->Draggable->DragObj->DeltaY;
			\QCubed\Project\Application::DisplayAlert ( 'Left change = ' . $x . ', top change = ' . $y);
		}
		
		public function selectable_stop($strFormId, $strControlId, $strParameter) {
			$a = $this->Selectable->SelectedItems;
			$strItems = join (",", $a);
			\QCubed\Project\Application::DisplayAlert ($strItems);
		}
		
		public function sortable_stop($strFormId, $strControlId, $strParameter) {
			$a = $this->Sortable->ItemArray;
			$strItems = join (",", $a);
			\QCubed\Project\Application::DisplayAlert ($strItems);
		}

		protected function accordion_change() {
			\QCubed\Project\Application::DisplayAlert ($this->Accordion->Active . ' selected.');
		}

        protected function dlgTitle_Change($strFormId, $strControlId, $strParameter) {
            $strNewTitle = $this->txtDlgTitle->Text;
            $this->Dialog->Title = $strNewTitle;
        }

        protected function dlgText_Change($strFormId, $strControlId, $strParameter) {
            $strNewText = $this->txtDlgText->Text;
            $this->Dialog->Text = $strNewText;
        }

		protected function setDate($strFormId, $strControlId, $strParameter) {
			if ($strParameter == 'Datepicker') {
				$this->DatepickerBox->DateTime = $this->Datepicker->DateTime;
			} else {
				$this->Datepicker->DateTime = $this->DatepickerBox->DateTime;
			}
		}

		protected function tabs_change($strFormId, $strControlId, $strParameter) {
			$index = $this->Tabs->Active;
			$id = $this->Tabs->SelectedId;
			$strItems = $index . ', ' . $id;
			\QCubed\Project\Application::DisplayAlert ($strItems);
		}

	}
    ExampleForm::Run('ExampleForm');
?>