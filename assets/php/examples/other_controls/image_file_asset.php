<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $ifaSample;

		protected $lblMessage;
		protected $btnButton;

		protected function formCreate() {
			// Define the sample QFileAssset control -- make it required to show off validation
			$this->ifaSample = new QImageFileAsset($this);
			$this->ifaSample->Required = true;

			// Let's make the File Icon "clickable" -- allowing users to download / view the currently uploaded file
			// We need to do two things -- first, set a temporaryuploadpath that is within the docroot
			// and then we need to set ClickToView to true
			$this->ifaSample->TemporaryUploadPath = __QCUBED_UPLOAD__;
			$this->ifaSample->ClickToView = true;

			// Feel free to uncomment this yourself, but note that you can pre-define the File property.
			// Notice how the path is an absolute path to a file.
			// Also notice that the file doesn't even need to be in the docroot.
//			$this->ifaSample->File = __DOCROOT__ . __IMAGE_ASSETS__ . '/calendar.png';

			// Add Styling
			$this->ifaSample->CssClass = 'file_asset';
			$this->ifaSample->imgFileIcon->CssClass = 'file_asset_icon';

			$this->lblMessage = new \QCubed\Control\Label($this);
			$this->lblMessage->Text = 'Click on the button to change this message.';
			
			//Could you define optional limits if the field is required
			$this->ifaSample->MinWidth = 100;
			$this->ifaSample->MinHeight = 100;

			// The "Form Submit" Button -- notice how the form is being submitted via AJAX, even though we are handling
			// File Uploads on the form.
			$this->btnButton = new \QCubed\Project\Jqui\Button($this);
			$this->btnButton->Text = 'Click Me';
			$this->btnButton->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnButton_Click'));
			$this->btnButton->CausesValidation = true;
		}

		protected function btnButton_Click($strFormId, $strControlId, $strParameter) {
			$this->lblMessage->Text = 'Thanks for uploading the file: ' . $this->ifaSample->FileName;
		}
	}

	// And now run our defined form
	ExampleForm::Run('ExampleForm', 'image_file_asset.tpl.php');
?>
