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
		
	$item_guid = get_input('item_guid');
		
	$item = get_entity($item_guid);

	if ($item->container_guid)
	{
		set_page_owner($item->container_guid);
	}
	else
	{
		set_page_owner($item->owner_guid);
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
	
	$area2 = elgg_view('polls/breadcrumbs', array('item' => $item, 'extra' => array(
		array('title' => $crumb_title, url => ''),
	)));
	
	$area2 .= elgg_view_title($title);
	
	$area2 .= list_annotations($item_guid, $item->getSubtype(), $limit, FALSE);
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2, "");
	
	page_draw($title, $body);
