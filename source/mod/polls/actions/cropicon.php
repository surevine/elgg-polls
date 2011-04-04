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


	gatekeeper();
	action_gatekeeper();

	set_context('polls');

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);


	$x1 = (int) get_input('x_1',0);
	$y1 = (int) get_input('y_1',0);
	$x2 = (int) get_input('x_2',0);
	$y2 = (int) get_input('y_2',0);
	
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $item->getOwner();
	$filehandler->setFilename("polls/" . $item_guid . "master" . ".jpg");
	$filename = $filehandler->getFilenameOnFilestore();
	
	$topbar = get_resized_image_from_existing_file($filename, 16, 16, TRUE, $x1, $y1, $x2, $y2);
	$tiny = get_resized_image_from_existing_file($filename, 25, 25, TRUE, $x1, $y1, $x2, $y2);
	$small = get_resized_image_from_existing_file($filename, 40, 40, TRUE, $x1, $y1, $x2, $y2);
	$medium = get_resized_image_from_existing_file($filename, 100, 100, TRUE, $x1, $y1, $x2, $y2);
	
	if ($small !== FALSE && $medium !== FALSE && $tiny !== FALSE)
	{
		$filehandler = new ElggFile();
		$filehandler->owner_guid = $item->getOwner();

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
		
		$user->x1 = $x1;
		$user->x2 = $x2;
		$user->y1 = $y1;
		$user->y2 = $y2;
		
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


	// forward the user back to candidate view page

	$url = "pg/polls/view/" . $item_guid;

	forward($url);

