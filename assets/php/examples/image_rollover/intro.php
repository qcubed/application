<?php
	require_once('../qcubed.inc.php');

	class ExampleForm extends \QCubed\Project\Control\FormBase {
		protected $imgMyRolloverImage;

		protected function formCreate() {
			$this->imgMyRolloverImage = new QImageRollover($this);
			$this->imgMyRolloverImage->ImageStandard = "../images/emoticons/1.png";
			$this->imgMyRolloverImage->ImageHover = "../images/emoticons/2.png";
		}
	}

	ExampleForm::Run('ExampleForm');
?>
