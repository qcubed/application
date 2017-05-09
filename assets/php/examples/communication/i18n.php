<?php

require_once('../qcubed.inc.php');

// NOTE: IF YOU ARE RUNNING THIS EXAMPLE FROM YOUR OWN DEVELOPMENT ENVIRONMENT
// you **MUST** remember to copy the custom es.po file from this directory and
// place it into /project/includes/qcubed/i18n

class ExamplesForm extends \QCubed\Project\Control\FormBase {

    protected $btnEs;
    protected $btnEn;

    // Initialize our Controls during the Form Creation process
    protected function formCreate() {
        // Note how we do not define any TEXT properties here -- we define them
        // in the template, so that translation and langauge switches can occur
        // even after this form is created
        $this->btnEs = new \QCubed\Project\Jqui\Button($this);
        $this->btnEs->ActionParameter = 'es';
        $this->btnEs->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('button_Click'));

        $this->btnEn = new \QCubed\Project\Jqui\Button($this);
        $this->btnEn->ActionParameter = 'en';
        $this->btnEn->AddAction(new \QCubed\Event\Click(), new \QCubed\Action\Server('button_Click'));
    }

    // The "btnButton_Click" Event handler
    protected function button_Click($strFormId, $strControlId, $strParameter) {
        // NORMALLY -- these settings are setup in prepend.inc
        // But it is pulled out here to illustrate

        $_SESSION['language_code'] = $strParameter;

        // In order for I18n Translation to be enabled, you must have a language code
        // defined and the QI18n object must be initialized
        \QCubed\Project\Application::$LanguageCode = $strParameter;
        QI18n::Initialize();
    }

}

// Run the Form we have defined
// The \QCubed\Project\Control\FormBase engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>
