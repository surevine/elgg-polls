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

	admin_gatekeeper();
		
	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	if (!$item || $item->getSubtype() != 'poll')
	{
		forward();
	}
	
	// Get the current poll's owner
	if ($container = $item->container_guid)
	{
		set_page_owner($container);
	}

	$item_owner = page_owner_entity();

	if ($item_owner === FALSE || is_null($item_owner))
	{
		$item_owner = $_SESSION['user'];
		set_page_owner($item_owner->getGUID());
	}


	$title = sprintf(elgg_echo("polls:bulkupload:title"), $item->title);

	elgg_push_breadcrumb($item->title, $item->getURL());
	elgg_push_breadcrumb(elgg_echo("polls:bulkupload"));

	$content = elgg_view("forms/polls/bulkupload", array('entity' => $item));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
