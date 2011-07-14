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

	elgg_load_library('elgg:polls');

	$owner = page_owner_entity();
	$viewer_is_owner = (isloggedin() && $owner->getGUID() == $_SESSION['user']->getGUID());

	// Add menus

	if ($owner && can_create_poll($owner))
	{
		elgg_register_menu_item('title', array(
			'name' => 'new',
			'href' => 'polls/new/?container_guid=' . page_owner(),
			'text' => elgg_echo("polls:new"),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}

	if ($viewer_is_owner)
	{
		$title = elgg_echo('polls:mine');
	}
	else
	{
		$title = sprintf(elgg_echo("polls:owned"), $owner->name);
	}

	elgg_push_breadcrumb($title);

	// Get objects

    $options = array(
        'types' => 'object',
		'subtypes' => 'poll',
		'container_guids' => page_owner(),
		'full_view' => FALSE,
		'list_type_toggle' => FALSE,
		'pagination' => TRUE,
	);

	$objects = elgg_list_entities($options);

	$params = array(
		'content' => $objects,
		'title' => $title,
		'filter' => '',
	);

	$body = elgg_view_layout('content', $params);

	// Finally draw the page
	echo elgg_view_page($title, $body);	
