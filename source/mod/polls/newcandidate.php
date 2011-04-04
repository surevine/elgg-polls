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

		// Breadcrumbs
		$area2 .= elgg_view('polls/breadcrumbs', array('item' => $poll, 'extra' => array(
			array('title' => $title, 'url' => ''),
		)));

		$area2 .= elgg_view_title($title);
		$area2 .= elgg_view("forms/polls/editcandidate");
		
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
		page_draw($title, $body);
	}
