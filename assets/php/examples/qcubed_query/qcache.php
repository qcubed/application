<?php
	require_once('../qcubed.inc.php');

	class ExamplesForm extends \QCubed\Project\Control\FormBase {
		protected $lblMessage;
		protected $btnReloadPage;
		protected $btnClearCache;

		protected function formCreate() {
			$this->lblMessage = new \QCubed\Control\Label($this);

			$this->btnReloadPage = new \QCubed\Project\Jqui\Button($this);
			$this->btnReloadPage->Text = 'Reload this page';
			$this->btnReloadPage->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\JavaScript('document.location.reload();'));
			
			$this->btnClearCache = new \QCubed\Project\Jqui\Button($this);
			$this->btnClearCache->Text = 'Clear the cache';
			$this->btnClearCache->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Ajax('btnClearCache_Click'));
		}
		
		protected function btnClearCache_Click($strFormId, $strControlId, $strParameter) {
			$blnSuccess = QCache::ClearNamespace('qquery/person');
			
			if ($blnSuccess) {
				$strStatus = "successful";
			} else {
				$strStatus = "NOT successful - check your cache / namespace paths";
			}

			$this->lblMessage->Text = 'Clearing the query cache for the Person table was ' . $strStatus . '. Reload the page to see the effect - query will not be executed against the database. ';
		}
	}

	// Run the Form we have defined
	ExamplesForm::Run('ExamplesForm');
?>
