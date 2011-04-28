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


	// Breadcrumbs
	$header = elgg_view('polls/breadcrumbs', array('item' => $item, 'extra' => array(
		array('title' => elgg_echo('polls:manage'),
				'url' => $CONFIG->url . 'pg/polls/' . $item->guid . '/manage')
	)));

	$title = $item->title;

	$header .= elgg_view('page/layouts/content/header', array('title' => $title, 'buttons' => ''));
	
	$content = elgg_view_entity($item, array('full_view' => TRUE));
	
	//add comments
	$content .= elgg_view_comments($item);
	
	$body = elgg_view_layout('content', array(
		'content' => $content,
		'header' => $header,
		'filter' => '',
	));
	
	// Finally draw the page
	echo elgg_view_page($title, $body);	