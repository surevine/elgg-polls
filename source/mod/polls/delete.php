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

	if (!$item)
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

	if ($item->getSubtype() == 'poll_candidate')
	{
		$title = elgg_echo("polls:candidate:confirmdelete:title");
		$crumb_title = elgg_echo("polls:candidate:delete");
	}
	else
	{
		$title = elgg_echo("polls:confirmdelete:title");
		$crumb_title = elgg_echo("polls:delete");
	}
		
	$body = elgg_view('polls/breadcrumbs', array('item' => $item, 'extra' => array(
		array('title' => $crumb_title, url => ''),
	)));
	
	$body .= elgg_view_title($title);

	$body .= '<div class="contentWrapper">';

	if ($item && $item->canEdit())
	{
		if ($item->getSubtype() == 'poll_candidate')
		{
			// only allowed to delete the candidate if you
			// have write access to the poll

			$poll_guid = $item->parent_guid;
			$poll = get_entity($poll_guid);

			if ($poll && $poll->canEdit())
			{
				$body .= elgg_view("forms/polls/delete", array('entity' => $item));
			}
			else
			{
				$body .= elgg_echo("polls:noaccess");
			}
		}
		else
		{
			$body .= elgg_view("forms/polls/delete", array('entity' => $item));
		}
	}
	else
	{
		$body .= elgg_echo("polls:noaccess");
	}

	$body .= '</div>';
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $body);
	
	page_draw($title, $body);
