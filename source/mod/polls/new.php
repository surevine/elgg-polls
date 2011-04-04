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


	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();

	global $CONFIG;
	
	// Get the current poll's owner
	if ($container = (int) get_input('container_guid'))
	{
		set_page_owner($container);
	}

	$page_owner = page_owner_entity();

	if ($page_owner === FALSE || is_null($page_owner))
	{
		$page_owner = $_SESSION['user'];
		set_page_owner($page_owner->getGUID());
	}


	$title = elgg_echo("polls:new");
	$area2 .= elgg_view_title($title);
	$area2 .= elgg_view("forms/polls/edit");
	
	$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
	
	page_draw($title, $body);
