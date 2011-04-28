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

	$owner = page_owner_entity();
	$viewer_is_owner = (isloggedin() && $owner->getGUID() == $_SESSION['user']->getGUID());

	// Add menus

	if ($owner && can_create_poll($owner))
	{
		add_submenu_item(elgg_echo('polls:new'), $CONFIG->url .
							"pg/polls/new/?container_guid=" . page_owner(), 'pollsactions');
	}

	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	if ($viewer_is_owner)
	{
		$title = elgg_echo('polls:mine');
	}
	else
	{
		$title = sprintf(elgg_echo("polls:owned"), $owner->name);
	}

	
	// Get objects

    $options = array(
        'types' => 'object',
		'subtypes' => 'poll',
		'container_guids' => page_owner(),
		'limit' => $limit,
		'full_view' => FALSE
	);

	$objects = elgg_list_entities($options);

	$content = elgg_view("polls/welcome");
	$content .= $objects;
	
	$body = elgg_view_layout('content', array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
		'buttons' => '',
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);	
