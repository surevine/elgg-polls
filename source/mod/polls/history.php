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

	if ($item->container_guid)
	{
		elgg_set_page_owner_guid($item->container_guid);
	}
	else
	{
		elgg_set_page_owner_guid($item->owner_guid);
	}

	$limit = (int)get_input('limit', 20);
	$offset = (int)get_input('offset');
	
	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	if ($item->getSubtype() == "poll_candidate")
	{
		$crumb_title = elgg_echo("polls:candidate:history");
	}
	else
	{
		$crumb_title = elgg_echo("polls:history");
	}
	
	$title = $item->title . ": " . $crumb_title;

	if ($item->getSubtype() == 'poll_candidate')
	{
		$poll = get_entity($item->parent_guid);

		if (!is_null($poll))
		{
			elgg_push_breadcrumb($poll->title, $poll->getURL());
		}
	}

	elgg_push_breadcrumb($item->title, $item->getURL());
	elgg_push_breadcrumb($crumb_title);

	$options = array(
		'guid' => $item_guid,
		'limit' => $limit,
		'annotation_name' => $item->getSubtype(),
		'order_by' => "n_table.time_created DESC"
	);

	$content = elgg_list_annotations($options);

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
