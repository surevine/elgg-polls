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
	
	$matches = NULL;
	
	if (preg_match('/^vote(\\d+)_category(\\d+)$/', $item->action_type, $matches))
	{
		$vote = $matches[ 1 ];
		$category_number = $matches[ 2 ];
		
		$parent_guid = $candidate->parent_guid;
		$parent = get_entity($parent_guid);
	
		$category_title = $parent->getMetaData('category' . $category_number);
		
		// set the required url to the user who carried out the action
		$who = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";

		$category = "<a href=\"{$candidate->getURL()}\">{$category_title}</a>";
		
		$what = "<a href=\"{$candidate->getURL()}\">{$candidate->title}</a>";
	
		$where = "<a href=\"{$parent->getURL()}\">{$parent->title}</a>";
	
		// get the container
		$container = get_entity($parent->container_guid);
	
	
		if ($container instanceof ElggGroup)
		{
			$group = "<a href=\"" . $container->getURL() . "\">" . $container->name . "</a>";
	
			if ($vote == "1")
			{
				$string = sprintf(elgg_echo("polls:river:vote:category:stars:group:one"),
									$who, $category, $what, $where, $group);
			}
			else
			{
				$string = sprintf(elgg_echo("polls:river:vote:category:stars:group"),
									$who, $vote, $category, $what, $where, $group);
			}
		}
		else
		{
			if ($vote == "1")
			{
				$string = sprintf(elgg_echo("polls:river:vote:category:stars:one"),
									$who, $category, $what, $where);
			}
			else
			{
				$string = sprintf(elgg_echo("polls:river:vote:category:stars"),
									$who, $vote, $category, $what, $where);
			}
		}
		
		echo $string;
	}	
