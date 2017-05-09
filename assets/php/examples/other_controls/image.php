<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $imgSample;
		protected $txtWidth;
		protected $txtHeight;
		protected $chkScaleCanvasDown;
		protected $btnUpdate;

		protected function formCreate() {
			// Get a Sample Image
			$this->imgSample = new QImageControl($this);
			$this->imgSample->ImagePath = 'earthlights.jpg';
			$this->imgSample->Width = 400;
			$this->imgSample->Height = 250;
			$this->imgSample->CssClass = 'image_canvas';
			
			// And finally, let's specify a CacheFolder so that the images are cached
			// Notice that this CacheFolder path is a complete web-accessible relative-to-docroot path
			$this->imgSample->CacheFolder = __IMAGE_CACHE_ASSETS__;

			$this->txtWidth = new \QCubed\Control\IntegerTextBox($this);
			$this->txtWidth->Minimum = 0;
			$this->txtWidth->Maximum = 1000;
			$this->txtWidth->Name = 'Width';
			$this->txtWidth->Text = 400;
			
			$this->txtHeight = new \QCubed\Control\IntegerTextBox($this);
			$this->txtHeight->Minimum = 0;
			$this->txtHeight->Maximum = 700;
			$this->txtHeight->Name = 'Height';
			$this->txtHeight->Text = 250;
			
			$this->chkScaleCanvasDown = new \QCubed\Project\Control\Checkbox($this);
			$this->chkScaleCanvasDown->Checked = false;
			$this->chkScaleCanvasDown->Text = 'Scale Canvas Down';

			$this->btnUpdate = new \QCubed\Project\Jqui\Button($this);
			$this->btnUpdate->Text = 'Update Image';
			$this->btnUpdate->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnUpdate_Click'));
			$this->btnUpdate->CausesValidation = true;
		}

		// Let's ensure that a width or a height value is specified -- just so that we don't get people rendering really large versions of the image
		protected function formValidate() {
			if (!trim($this->txtWidth->Text) && !trim($this->txtHeight->Text)) {
				$this->txtWidth->Warning = 'For this example, you must specifiy at least a width OR a height value';
				return false;
			}
			return true;
		}

		protected function btnUpdate_Click($strFormId, $strControlId, $strParameter) {
			$this->imgSample->Width = $this->txtWidth->Text;
			$this->imgSample->Height = $this->txtHeight->Text;
			$this->imgSample->ScaleCanvasDown = $this->chkScaleCanvasDown->Checked;
		}
	}

	// And now run our defined form
	ExampleForm::Run('ExampleForm');
?>
