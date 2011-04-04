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
	require_once(dirname(__FILE__) . "/lib.php");

	global $CONFIG;
	
	// Get the current page's owner
	$page_owner = page_owner_entity();

	if ($page_owner === FALSE || is_null($page_owner))
	{
		$page_owner = $_SESSION['user'];
		set_page_owner($_SESSION['guid']);
	}

	if ($page_owner && can_create_poll($page_owner))
	{
		add_submenu_item(elgg_echo('polls:new'), $CONFIG->url . "pg/polls/new/", 'pollsactions');
	}


	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	$title = sprintf(elgg_echo("polls:all"),page_owner_entity()->name);
	

	// Get objects
	$objects = list_entities("object", "poll", 0, $limit, FALSE);

	$body = elgg_view_title($title);
	$body .= $objects;
	$body = elgg_view_layout('two_column_left_sidebar','',$body);
	
	// Finally draw the page
	page_draw($title, $body);
