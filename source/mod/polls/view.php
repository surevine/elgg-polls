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
	set_context('polls');
	
	$item = get_entity($item_guid);

	if (!$item)
	{
		forward();
	}

	$container_guid = $item->container_guid;

	if (!$container_guid)
	{
		$container_guid = $item->owner_guid;
	}

	set_page_owner($container_guid);


	// for now let anyone who can view a poll add a candidate -
	// eventually check to see if poll attributes allow it

	if (isloggedin() && $item->getSubtype() == 'poll')
	{
		// work out container for any new candidates
		// group polls store candidates in the group
		// user polls store candidates in the user who adds the candidate
		// (not the user who owns the poll)

		$container = get_entity($container_guid);

		if ($container && $container instanceof ElggGroup)
		{
			$candidate_container_guid = $container_guid;
		}
		else
		{
			$candidate_container_guid = $_SESSION['user']->getGUID();
		}

		// make sure the logged in user can write to that container -
		// this should only fail if it's a group poll and they're not a
		// member of the group

		if (can_write_to_container(0, $candidate_container_guid))
		{
			if ($item->moderated == "no")
			{
				$label = elgg_echo('polls:candidate:new');
			}
			else
			{
				$label = elgg_echo('polls:candidate:new:propose');
			}

			elgg_register_menu_item('title', array(
				'name' => 'newcandidate',
				'href' => "polls/newcandidate/?parent_guid={$item_guid}&container_guid={$candidate_container_guid}",
				'text' => $label,
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}

	global $CONFIG;

	$extra_trail = array();
	
	$tag_filter = get_input('tag');
	
	if ($tag_filter != '')
	{
		// The regex here just adds a space in after any commas
		$extra_trail[] = array('title' => elgg_echo('tags') . ': ' .
			preg_replace('/,(?=\S)/', ', ', $tag_filter), 'url' => '');
	}

	$title = $item->title;

	if ($item->getSubtype() == 'poll_candidate')
	{
		$poll = get_entity($item->parent_guid);

		if (!is_null($poll))
		{
			elgg_push_breadcrumb($poll->title, $poll->getURL());
		}
	}

	elgg_push_breadcrumb($title);

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
