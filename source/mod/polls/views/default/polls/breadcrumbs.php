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

	// This is used to add an extra trail to the end of the breadcrumbs (after the poll/candidate)
	$extra = $vars['extra'];
	
	if (is_array($extra) && !empty($extra))
	{
		$has_extra = TRUE;
	}
	else
	{
		$has_extra = FALSE;
	}
	
	$breadcrumbs = '';


	if ($item->getSubtype() == "poll")
	{
		$poll = $item;
	}
	else if ($item->getSubtype() == "poll_candidate")
	{
		$candidate = $item;
		$poll = get_entity($candidate->parent_guid);

		if ($poll)
		{
			$breadcrumbs = "<li><a href=\"{$poll->getURL()}\">$poll->title</a></li>" . $breadcrumbs;
		}
	}


	if ($poll)
	{
		// get container of the top of the hierarchy


		$container_guid = $poll->container_guid;
		$container = get_entity($container_guid);

		if ($container)
		{
			$container_url = $CONFIG->wwwroot . "pg/polls/owned/" . $container->username;

			if (isloggedin() && $container->getGUID() == $_SESSION['user']->getGUID())
			{
				$label = elgg_echo('polls:mine');
			}
			else
			{
				$label = sprintf(elgg_echo('polls:owned'), $container->name);
			}
		}
		else
		{
			// unusual case where poll is visible to the user but the container
			// isn't, e.g. public poll in private group
			$container_url = "";
		}

		// output it all

		echo '<div><ul class="elgg-menu elgg-breadcrumbs">';

		if ($container_url)
		{
			echo "<li><b><a href=\"{$container_url}\">" . $label . "</a></b></li>";
		}

		echo $breadcrumbs;

		// output the item itself
		// if it is adding an item, or we have tag filter,
		// make the last item a link, otherwise, don't

		if ($has_extra)
		{
			echo "<li><a href=\"{$item->getURL()}\">$item->title</a></li>";
			
			$i = 0;
			$count = count($extra);
			
			foreach ($extra as $extra_item)
			{
				if (++$i < $count)
				{
					echo "<li><a href=\"{$extra_item['url']}\">{$extra_item['title']}</a></li>";
				}
				else
				{
					echo "<li>{$extra_item['title']}</li>";			
				}
			}
		}
		else
		{
			echo "<li>$item->title</li>";
		}

		echo "</ul></div>";
	}

