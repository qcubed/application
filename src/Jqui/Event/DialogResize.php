<?php
/**
*
* Part of the QCubed PHP framework.
*
* @license MIT
*
*/

namespace QCubed\Jqui\Event;

/**
 * Class DialogResize
 *
 * The abstract DialogResize class defined here is
 * code-generated. The code to generate this file is
 * in the /tools/jquery_ui_gen/jq_control_gen.php file
 * and you can regenerate the files if you need to.
 *
 * The comments in this file are taken from the api reference site, so they do
 * not always make sense with regard to QCubed. They are simply provided
 * as reference.
 * Triggered while the dialog is being resized.
 * 
 * 	* event Type: Event 
 * 
 * 	* ui Type: Object 
 * 
 * 	* originalPosition Type: Object The CSS position of the dialog prior
 * to being resized.
 * 	* position Type: Object The current CSS position of the dialog.
 * 	* originalSize Type: Object The size of the dialog prior to being
 * resized.
 * 	* size Type: Object The current size of the dialog.
 * 
 *
 * @was QDialog_Resize */
class DialogResize extends EventBase
{
    const EVENT_NAME = 'dialogresize';
}
