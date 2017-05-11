<?php

require_once('../qcubed.inc.php');

class ExamplesForm extends \QCubed\Project\Control\FormBase {

    protected $btnEs;
    protected $btnEn;

    // Initialize our Controls during the Form Creation process
    protected function formCreate() {
        // let's change translation class
        require_once ('sample_translator.class.php');
        QI18n::$DefaultTranslationClass = 'QSampleTranslation';

        // Set default language to French
        \QCubed\Project\Application::$LanguageCode = 'fr';
        \QCubed\Project\Application::$CountryCode = null;
        QI18n::Initialize();
    }

}

// Run the Form we have defined
// The \QCubed\Project\Control\FormBase engine will look to intro.tpl.php to use as its HTML template include file
ExamplesForm::Run('ExamplesForm');
?>
