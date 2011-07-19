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

	global $CONFIG;
	
	$candidate_owner = $_SESSION['user'];
	set_page_owner($candidate_owner->getGUID());

	// provide a link back to parent poll

	$poll_guid = (int) get_input('parent_guid');
	$poll = get_entity($poll_guid);

	if ($poll)
	{
		if ($poll->moderated == "no")
		{
			$title = elgg_echo("polls:candidate:new");
		}
		else
		{
			$title = elgg_echo('polls:candidate:new:propose');
		}

		elgg_push_breadcrumb($poll->title, $poll->getURL());
		elgg_push_breadcrumb($title);

		$content = elgg_view("forms/polls/editcandidate");
		
		$params = array(
			'content' => $content,
			'title' => $title,
			'filter' => '',
		);
		$body = elgg_view_layout('content', $params);

		echo elgg_view_page($title, $body);
	}
