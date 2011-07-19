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
	
	// Get the current page's owner
	$page_owner = page_owner_entity();

	if ($page_owner === FALSE || is_null($page_owner))
	{
		$page_owner = $_SESSION['user'];
		set_page_owner($_SESSION['guid']);
	}

	if ($page_owner && can_create_poll($page_owner))
	{
		elgg_register_menu_item('title', array(
			'name' => 'new',
			'href' => 'polls/new/',
			'text' => elgg_echo("polls:new"),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}

	$title = sprintf(elgg_echo("polls:all"), page_owner_entity()->name);

	// Get objects

    $options = array(
        'types' => 'object',
		'subtypes' => 'poll',
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

