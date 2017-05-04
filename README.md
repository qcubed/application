# Application Framework
This is the application part of the QCubed framework, and includes forms, controls,
actions, events and code to tie them all together.

##Upgrade Notes
This version now uses namespacing. See the tools directory for tools to help you
convert your current code base to the new names.


The application framework moving forward will focus on supporting html5 tags in its 
control library only. There may be some other items in there to provide a way to support
common data relationships (like radio and checkbox lists), but for the most part, we
would like anything that isn't directly drawing a tag to be in a plugin.

As such, the following controls are no longer supported in the core, and are currently dead
code. You will find them in the "dead" directory. 
However, if these old controls are important to you, feel free to resurect them
as a plugin.

* FileAssetDialog.php
* QDialogBox.class.php (We currently use the JQuery UI dialog, but this may change)
* QFileAssetBase.class.php
* QImageBase.class.php
* QImageBrowser.class.php
* QImageControlBase.class.php
* QImageLabelBase.class.php
* QImageRollover.class.php
* QTreeNav.class.php
* QTreeNavItem.class.php
* QWriteBox.class.php

Also, the JQuery UI framework has been put in its own directory to prepare
for moving it to a plugin in a later version.


