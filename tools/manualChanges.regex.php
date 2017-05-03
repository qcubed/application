<?php
/**
 * These are arrays of changes that were manually edited to help with automatically converting version 3 code to version 4.
 */

$a['const']['QFontFamily::Arial'] = '\\QCubed\\Html::FONT_FAMILY_ARIAL';
$a['const']['QFontFamily::Helvetica'] = '\\QCubed\\Html::FONT_FAMILY_HELVETICA';
$a['const']['QFontFamily::Tahoma'] = '\\QCubed\\Html::FONT_FAMILY_TAHOMA';
$a['const']['QFontFamily::TrebuchetMs'] = '\\QCubed\\Html::FONT_FAMILY_TREBUCHET_MS';
$a['const']['QFontFamily::Verdana'] = '\\QCubed\\Html::FONT_FAMILY_VERDANA';
$a['const']['QFontFamily::TimesNewRoman'] = '\\QCubed\\Html::FONT_FAMILY_TIMES_NEW_ROMAN';
$a['const']['QFontFamily::Georgia'] = '\\QCubed\\Html::FONT_FAMILY_GEORGIA';
$a['const']['QFontFamily::LucidaConsole'] = '\\QCubed\\Html::FONT_FAMILY_LUCIDA_CONSOLE';
$a['const']['QFontFamily::CourierNew'] = '\\QCubed\\Html::FONT_FAMILY_LUCIDA_COURIER_NEW';
$a['const']['QFontFamily::Courier'] = '\\QCubed\\Html::FONT_FAMILY_LUCIDA_COURIER';

$a['const']['QCausesValidation::None'] = '\\QCubed\\Control\\AbstractBase::CAUSES_VALIDATION_NONE';
$a['const']['QCausesValidation::AllControls'] = '\\QCubed\\Control\\AbstractBase::CAUSES_VALIDATION_ALL';
$a['const']['QCausesValidation::SiblingsAndChildren'] = '\\QCubed\\Control\\AbstractBase::CAUSES_VALIDATION_SIBLINGS_AND_CHILDREN';
$a['const']['QCausesValidation::SiblingsOnly'] = '\\QCubed\\Control\\AbstractBase::CAUSES_VALIDATION_SIBLINGS_ONLY';

$a['const']['QTextAlign::Left'] = '\\QCubed\\Html::TEXT_ALIGN_LEFT';
$a['const']['QTextAlign::Right'] = '\\QCubed\\Html::TEXT_ALIGN_RIGHT';

$a['const']['QSelectionMode::Single'] = '\\QCubed\\AbstractListBoxBase::SELECTION_MODE_SINGLE';
$a['const']['QSelectionMode::Multiple'] = '\\QCubed\\AbstractListBoxBase::SELECTION_MODE_MULTIPLE';
$a['const']['QSelectionMode::None'] = '\\QCubed\\AbstractListBoxBase::SELECTION_MODE_NONE';

$a['func']['QApplication::Translate'] = 't';
$a['func']['QApplication::PathInfo'] = '\\QCubed\\Project\\Application::instance()->context()->pathItem';
$a['func']['QApplication::QueryString'] = '\\QCubed\\Project\\Application::instance()->context()->queryStringItem';
$a['func']['QApplication::IsBrowser'] = '\\QCubed\\Project\\Application::instance()->context()->isBrowser';
$a['func']['QApplication::HtmlEntities'] = '\\QCubed\\QString::htmlEntities';

$a['regex']['QApplication::\\$EncodingType'] = '\\QCubed\\Project\\Application::instance()->encodingType()';
$a['regex']['QApplication::\\$CliMode'] = '\\QCubed\\Project\\Application::instance()->context()->cliMode()';
$a['regex']['QApplication::\\$ServerAddress'] = '\\QCubed\\Project\\Application::instance()->context()->serverAddress()';
$a['regex']['QApplication::\\$ScriptFilename'] = '\\QCubed\\Project\\Application::instance()->context()->scriptFileName()';
$a['regex']['QApplication::\\$ScriptName'] = '\\QCubed\\Project\\Application::instance()->context()->scriptName()';
$a['regex']['QApplication::\\$PathInfo'] = '\\QCubed\\Project\\Application::instance()->context()->pathInfo()';
$a['regex']['QApplication::\\$QueryString'] = '\\QCubed\\Project\\Application::instance()->context()->queryString()';
$a['regex']['QApplication::\\$RequestUri'] = '\\QCubed\\Project\\Application::instance()->context()->requestUri()';
$a['regex']['QApplication::\\$BrowserType'] = '\\QCubed\\Project\\Application::instance()->context()->browserType()';
$a['regex']['QApplication::\\$BrowserVersion'] = '\\QCubed\\Project\\Application::instance()->context()->browserVersion()';
$a['regex']['QApplication::\\$DocumentRoot'] = '\\QCubed\\Project\\Application::instance()->context()->docRoot()';
$a['regex']['QApplication::\\$Minimize'] = '\\QCubed\\Project\\Application::instance()->context()->minimize()';
$a['regex']['QApplication::\\$RequestMode'] = '\\QCubed\\Project\\Application::instance()->context()->requestMode()';

return $a;