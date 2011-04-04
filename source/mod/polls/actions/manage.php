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

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	action_gatekeeper();

	set_context('polls');


	$guids = get_input('manage_guid');
	$moveto = get_input('moveto');


	if (empty($guids))
	{
		register_error(elgg_echo('polls:manage:notselected'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}

	if (empty($moveto))
	{
		register_error(elgg_echo('polls:manage:notmoved'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}

	$count = 0;

	foreach ($guids as $guid)
	{
		$candidate_guid = $guid[0];
		$candidate = get_entity($candidate_guid);

		if ($candidate)
		{
			$candidate->state = $moveto;

			if ($candidate->save())
			{
				$count++;
			}
			else
			{
				register_error(elgg_echo('polls:manage:notmoved'));
			}
		}
		else
		{
			register_error(elgg_echo('polls:manage:notmoved'));
		}
	}

	if ($count == 1)
	{
		system_message(elgg_echo("polls:manage:moved:one"));
	}
	else
	{
		system_message(sprintf(elgg_echo("polls:manage:moved"), $count));
	}


	forward($_SERVER['HTTP_REFERER']);
