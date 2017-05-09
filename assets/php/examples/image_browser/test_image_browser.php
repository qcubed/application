<?php
// Include prepend.inc to load Qcubed
	require_once('../qcubed.inc.php');

	class TestImageBrowser extends \QCubed\Project\Control\FormBase {
		/**
		 * @var QImageBrowser
		 */
		protected $imbBrowser;
		
		protected function formCreate() {
			$this->imbBrowser = new QImageBrowser($this);
			$this->imbBrowser->Template = 'image_browser.tpl.php';
			// $this->imbBrowser->AutoRenderChildren = true;
			// force main image size
			$this->imbBrowser->MainImage->Width = 150;
			$this->imbBrowser->MainImage->Height = 150;
			$this->imbBrowser->MainImage->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\AjaxControl($this->imbBrowser, 'btnNext_Click'));
			$this->imbBrowser->LoadImagesFromDirectory("../images/emoticons", '/png/i');
		}
	}

	TestImageBrowser::Run('TestImageBrowser', 'test_image_browser.tpl.php');
?>
