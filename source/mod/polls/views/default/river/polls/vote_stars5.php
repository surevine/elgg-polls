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


	$item = $vars['item'];
	$performed_by = get_entity($item->subject_guid);
	$candidate = get_entity($item->object_guid);

	// action name is "vote" followed by number of stars
	$vote = substr($item->action_type , -1);

	$parent_guid = $candidate->parent_guid;
	$parent = get_entity($parent_guid);


	// set the required url to the user who carried out the action
	$who = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";

	$what = "<a href=\"{$candidate->getURL()}\">{$candidate->title}</a>";

	$where = "<a href=\"{$parent->getURL()}\">{$parent->title}</a>";

	// get the container
	$container = get_entity($parent->container_guid);


	if ($container instanceof ElggGroup)
	{
		$group = "<a href=\"" . $container->getURL() . "\">" . $container->name . "</a>";

		if ($vote == "1")
		{
			$string = sprintf(elgg_echo("polls:river:vote:stars:group:one"), $who, $what, $where, $group);
		}
		else
		{
			$string = sprintf(elgg_echo("polls:river:vote:stars:group"),
								$who, $vote, $what, $where, $group);
		}
	}
	else
	{
		if ($vote == "1")
		{
			$string = sprintf(elgg_echo("polls:river:vote:stars:one"), $who, $what, $where);
		}
		else
		{
			$string = sprintf(elgg_echo("polls:river:vote:stars"), $who, $vote, $what, $where);
		}
	}

	echo $string;
