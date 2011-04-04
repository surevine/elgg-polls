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


	require_once(dirname(__FILE__) . "/../../../engine/start.php");
	require_once(dirname(__FILE__) . "/../lib.php");
	
	$item_guid = get_input('candidate_guid');
	set_context('polls');
	
	$item = get_entity($item_guid);
	
	if (!$item)
	{
		exit;
	}

	$category_number = (int) get_input('category_number', 0);
	
	// Sanitise the category id
	$category_number = max(min($category_number, 4), 0);
		
	$container_guid = $item->container_guid;

	if ($container_guid)
	{
		set_page_owner($container_guid);
	}
	else
	{
		set_page_owner($item->owner_guid);
	}

	global $CONFIG;

	echo view_voting_box($item, $category_number);
