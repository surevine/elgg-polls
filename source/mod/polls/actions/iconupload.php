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


	global $CONFIG;

	gatekeeper();
	action_gatekeeper();

	set_context('polls');

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	$topbar = get_resized_image_from_uploaded_file('candidateicon', 16, 16, TRUE);
	$tiny = get_resized_image_from_uploaded_file('candidateicon', 25, 25, TRUE);
	$small = get_resized_image_from_uploaded_file('candidateicon', 40, 40, TRUE);
	$medium = get_resized_image_from_uploaded_file('candidateicon', 100, 100, TRUE);
	$large = get_resized_image_from_uploaded_file('candidateicon', 200, 200);
	$master = get_resized_image_from_uploaded_file('candidateicon', 550, 550);
	
	if ($small !== FALSE && $medium !== FALSE && $large !== FALSE && $tiny !== FALSE)
	{
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $item->getOwner();

		$filehandler->setFilename("polls/" . $item_guid . "large.jpg");
		$filehandler->open("write");
		$filehandler->write($large);
		$filehandler->close();

		$filehandler->setFilename("polls/" . $item_guid . "medium.jpg");
		$filehandler->open("write");
		$filehandler->write($medium);
		$filehandler->close();

		$filehandler->setFilename("polls/" . $item_guid . "small.jpg");
		$filehandler->open("write");
		$filehandler->write($small);
		$filehandler->close();

		$filehandler->setFilename("polls/" . $item_guid . "tiny.jpg");
		$filehandler->open("write");
		$filehandler->write($tiny);
		$filehandler->close();

		$filehandler->setFilename("polls/" . $item_guid . "topbar.jpg");
		$filehandler->open("write");
		$filehandler->write($topbar);
		$filehandler->close();

		$filehandler->setFilename("polls/" . $item_guid . "master.jpg");
		$filehandler->open("write");
		$filehandler->write($master);
		$filehandler->close();

		// timestamp last upload time - we use this to construct the URL
		// to reference the icon so that browser doesn't use the old
		// cached version

		$item->icontime = time();

		system_message(elgg_echo("polls:icon:uploaded"));
		
	}
	else
	{
		register_error(elgg_echo("polls:icon:notfound"));					
	}
				
			
	// forward the user back to the upload page to crop

	$url = "pg/polls/editicon/" . $item_guid;
			
	forward($url);

