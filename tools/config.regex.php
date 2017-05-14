<?php
// Help to convert the old config defines to new ones
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__CSS_ASSETS__'] = 'QCUBED_CSS_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__JS_ASSETS__'] = 'QCUBED_JS_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__IMAGE_ASSETS__'] = 'QCUBED_IMAGE_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__PHP_ASSETS__'] = 'QCUBED_PHP_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__EXAMPLES__'] = 'QCUBED_EXAMPLES_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__FORMS__'] = 'QCUBED_FORMS_URL';

$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__APP_JS_ASSETS__'] = 'QCUBED_PROJECT_JS_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__APP_CSS_ASSETS__'] = 'QCUBED_PROJECT_CSS_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__APP_IMAGE_ASSETS__'] = 'QCUBED_PROJECT_IMAGE_URL';
$a['regex']['__VIRTUAL_DIRECTORY__\\s*\\.\\s*__APP_PHP_ASSETS__'] = 'QCUBED_PROJECT_PHP_URL';


$a['regex']['__DOCROOT__\\s*\\.\\s*__EXAMPLES__'] = 'QCUBED_EXAMPLES_DIR';
$a['regex']['__DOCROOT__\\s*\\.\\s*__FORMS__'] = 'QCUBED_FORMS_DIR';

$a['regex']['__VENDOR_ASSETS__'] = 'QCUBED_VENDOR_URL';
$a['regex']['__FORM_LIST_ITEMS_PER_PAGE__'] = 'QCUBED_ITEMS_PER_PAGE';
$a['regex']['__JQUERY_BASE__'] = 'QCUBED_JQUERY';
$a['regex']['__JQUERY_EFFECTS__'] = 'QCUBED_JQUI';
$a['regex']['__QCUBED_JS_CORE__'] = 'QCUBED_JS_FILE';
$a['regex']['__JQUERY_CSS__'] = 'QCUBED_JQUI_CSS_FILE';

return $a;