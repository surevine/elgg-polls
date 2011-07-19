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


	$item_guid = get_input('item_guid');
	set_context('polls_manage');
	
	$item = get_entity($item_guid);

	if (!$item)
	{
		forward();
	}

	$container_guid = $item->container_guid;

	if ($container_guid)
	{
		set_page_owner($container_guid);
	}
	else
	{
		set_page_owner($item->owner_guid);
	}

	global $CONFIG;


	$title = $item->title;

	elgg_push_breadcrumb($title, $item->getURL());
	elgg_push_breadcrumb(elgg_echo('polls:manage'));
	
	$content = elgg_view_entity($item, array('full_view' => TRUE));
	
	//add comments
	$content .= elgg_view_comments($item);
	
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);