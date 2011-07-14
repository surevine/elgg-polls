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

	elgg_push_breadcrumb($title);

	$content = elgg_view("forms/polls/edit");

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);

