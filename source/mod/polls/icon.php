<?php

/**
	* Polls - a polling module for Elgg
	*
	* @package polls
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	* @author Surevine Limited <elgg@surevine.com>
	* @copyright Surevine Limited 2010
	* @link http://www.surevine.com/
*/


	// Get the size
	$size = strtolower(get_input('size'));

	if (!in_array($size, array('large','medium','small','tiny','master','topbar')))
	{
		$size = "medium";
	}
	
	$success = FALSE;

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	if ($item)
	{
		$owner_guid = $item->getOwner();

		// Try and get the icon
	
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $owner_guid;
		$filehandler->setFilename("polls/" . $item_guid . $size . ".jpg");
	
		if ($filehandler->open("read"))
		{
			if ($contents = $filehandler->read($filehandler->size()))
			{
				$success = TRUE;
			}
		}
		
		$content_type = "image/jpeg";
	}


	if (!$success)
	{
		// Hopefully we'll never get here, as the URL which points to
		// this PHP file only gets called if there is a custom icon.
		// But we'll put the default icon here in case anything slips
		// through the net to this URL.  NB. This will return the default
		// plugin icon, not any default icon overridden in an installed
		// theme.  But it's better than nothing.

		global $CONFIG;
		//$contents = @file_get_contents($CONFIG->pluginspath . "polls/images/candidate_{$size}.gif");
		$contents = file_get_contents($CONFIG->pluginspath . "polls/images/candidate_{$size}.gif");
		$content_type = "image/gif";
	}

	header("Content-type: " . $content_type);
	header('Expires: ' . date('r',time() + 864000));
	header("Pragma: public");
	header("Cache-Control: public");
	header("Content-Length: " . strlen($contents));
	echo $contents;

