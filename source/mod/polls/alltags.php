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


	elgg_load_library('elgg:polls');
	
	$item_guid = get_input('item_guid');
	set_context('polls');
	
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
	
	elgg_push_breadcrumb($item->title, $item->getURL());
	elgg_push_breadcrumb(elgg_echo('polls:allcandidatetags'));

	$title = $item->title . ': ' . elgg_echo('polls:allcandidatetags');

	$tags = polls_get_all_candidate_tags_for_a_poll($item_guid);
	
	$content = elgg_view('polls/alltags', array(
		'baseurl' => 'polls/view/' . $item_guid,
		'tags' => $tags
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
