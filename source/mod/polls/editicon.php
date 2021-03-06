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

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	// Get the current poll's owner
	if ($container = $item->container_guid)
	{
		elgg_set_page_owner_guid($container);
	}

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner === FALSE || is_null($page_owner))
	{
		$page_owner = $_SESSION['user'];
		elgg_set_page_owner_guid($page_owner->getGUID());
	}

	$title = elgg_echo("polls:editicon");

	$poll = get_entity($item->parent_guid);

	if (!is_null($poll))
	{
		elgg_push_breadcrumb($poll->title, $poll->getURL());
	}

	elgg_push_breadcrumb($item->title, $item->getURL());
	elgg_push_breadcrumb($title);


	if (($item) && $item->canEdit() && $item->getSubtype() == 'poll_candidate')
	{
		$content = elgg_view("polls/candidateicon/upload", array('entity' => $item));
		
		// If an icon has been uploaded then show the cropping tool
		$icontime = $item->icontime;

		if ($icontime && $icontime != "default")
		{
			$content .= elgg_view("polls/candidateicon/crop", array('entity' => $item));
		}
	}
	else
	{
		$content = elgg_echo("polls:noaccess");
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
