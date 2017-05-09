<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $dtxDateTimeTextBox;
		protected $btnDateTimeTextBox;

		protected $calQJQCalendar;
		protected $btnQJQCalendar;
		
		protected $dtpDatePicker;
		protected $btnDatePicker;

		protected $dtpDateTimePicker;
		protected $btnDateTimePicker;

		protected $lblResult;

		protected function formCreate() {
			
			$this->calQJQCalendar = new \QCubed\Control\Calendar($this);
			
			$this->dtxDateTimeTextBox = new \QCubed\Control\DateTimeTextBox($this);

			// \QCubed\Control\DateTimePicker can have different "Types"
			$this->dtpDatePicker = new \QCubed\Control\DateTimePicker($this);
			$this->dtpDatePicker->DateTimePickerType = \QCubed\Control\DateTimePicker::SHOW_DATE;

			$this->dtpDateTimePicker = new \QCubed\Control\DateTimePicker($this);
			$this->dtpDateTimePicker->DateTimePickerType = \QCubed\Control\DateTimePicker::SHOW_DATE_TIME;

			// To View the "Results"
			$this->lblResult = new \QCubed\Control\Label($this);
			$this->lblResult->Text = 'Results...';

			// Various Buttons
			$this->btnQJQCalendar = new \QCubed\Project\Control\Button($this);
			$this->btnQJQCalendar->Text = 'Update';
			$this->btnQJQCalendar->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnUpdate_Click'));
			$this->btnQJQCalendar->ActionParameter = $this->calQJQCalendar->ControlId;
			
			$this->btnDateTimeTextBox = new \QCubed\Project\Control\Button($this);
			$this->btnDateTimeTextBox->Text = 'Update';
			$this->btnDateTimeTextBox->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnUpdate_Click'));
			$this->btnDateTimeTextBox->ActionParameter = $this->dtxDateTimeTextBox->ControlId;

			$this->btnDatePicker = new \QCubed\Project\Control\Button($this);
			$this->btnDatePicker->Text = 'Update';
			$this->btnDatePicker->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnUpdate_Click'));
			$this->btnDatePicker->ActionParameter = $this->dtpDatePicker->ControlId;

			$this->btnDateTimePicker = new \QCubed\Project\Control\Button($this);
			$this->btnDateTimePicker->Text = 'Update';
			$this->btnDateTimePicker->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnUpdate_Click'));
			$this->btnDateTimePicker->ActionParameter = $this->dtpDateTimePicker->ControlId;
		}

		protected function btnUpdate_Click($strFormId, $strControlId, $strParameter) {
			$objControlToLookup = $this->GetControl($strParameter);
			$dttDateTime = $objControlToLookup->DateTime;

			// If a DateTime value is NOT selected or is INVALID, then this will be NULL
			if ($dttDateTime) {
				$this->lblResult->Text = '\QCubed\QDateTime object:<br/>';
				if (!$dttDateTime->IsDateNull())
					$this->lblResult->Text .= 'Date: <strong>' . $dttDateTime->qFormat('DDD MMM D YYYY') . '</strong><br/>';
				else
					$this->lblResult->Text .= 'Date: <strong>Null</strong><br/>';
				if (!$dttDateTime->IsTimeNull())
					$this->lblResult->Text .= 'Time: <strong>' . $dttDateTime->qFormat('h:mm:ss z') . '</strong>';
				else
					$this->lblResult->Text .= 'Time: <strong>Null</strong>';
			} else {
				$this->lblResult->Text = '\QCubed\QDateTime object: <strong>Null</strong>';
			}
		}
	}

	// And now run our defined form
	ExampleForm::Run('ExampleForm');
?>