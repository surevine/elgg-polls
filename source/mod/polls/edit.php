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
		set_page_owner($container);
	}

	$item_owner = page_owner_entity();

	if ($item_owner === FALSE || is_null($item_owner))
	{
		$item_owner = $_SESSION['user'];
		set_page_owner($item_owner->getGUID());
	}

	if ($item->getSubtype() == 'poll_candidate')
	{
		$title = elgg_echo("polls:candidate:edit");
	}
	else
	{
		$title = elgg_echo("polls:edit");
	}

	if ($item->getSubtype() == 'poll_candidate')
	{
		$poll = get_entity($item->parent_guid);

		if (!is_null($poll))
		{
			elgg_push_breadcrumb($poll->title, $poll->getURL());
		}
	}

	elgg_push_breadcrumb($item->title, $item->getURL());
	elgg_push_breadcrumb($title);
	
	if (($item) && ($item->canEdit()))
	{
		if ($item->getSubtype() == 'poll_candidate')
		{
			$content = elgg_view("forms/polls/editcandidate", array('entity' => $item));
		}
		else
		{
			$content = elgg_view("forms/polls/edit", array('entity' => $item));
		}
	}
	else
	{
		$content .= elgg_echo("polls:noaccess");
	}
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
