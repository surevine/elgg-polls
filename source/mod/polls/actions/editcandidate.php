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

	// Get group fields
	$input = array();
	foreach ($CONFIG->candidate_form as $shortname => $details)
	{
		$input[$shortname] = get_input($shortname);

		if ($details['type'] == 'tags')
		{
			$input[$shortname] = string_to_tag_array($input[$shortname]);
		}
	}
	
	// Get parent
	$parent_guid = (int)get_input('parent_guid', 0);
	$poll = get_entity($parent_guid);

	if (!$poll)
	{
		register_error(elgg_echo('polls:candidate:notsaved'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// New or old?
	$candidate = NULL;
	$candidate_guid = (int)get_input('candidate_guid');

	if ($candidate_guid)
	{
		$editing = TRUE;
		$candidate = get_entity($candidate_guid);

		if (!$candidate->canEdit())
		{
			$candidate = NULL; // if we can't edit it, go no further.
		}
	}
	else
	{
		$editing = FALSE;
		$candidate = new ElggObject();

		$candidate->subtype = 'poll_candidate';

		// New instance, so set container_guid
		$candidate->container_guid = get_input('container_guid', $_SESSION['user']->getGUID());

		if ($poll->moderated == "no")
		{
			$candidate->state = 'active';
		}
		else
		{
			$candidate->state = 'proposed';
		}
	}
	
	// Have we got it? Can we edit it?
	if ($candidate instanceof ElggObject)
	{
		// Yes we have, and yes we can.
		
		// Save fields - note we always save latest description as both description and annotation
		if (sizeof($input) > 0)
		{
			foreach ($input as $shortname => $value)
			{
				if (!$candidate_guid ||
					($candidate_guid && ($CONFIG->candidate_form[$shortname]['editable'] != FALSE)))
				{
					$candidate->$shortname = $value;
				}
			}
		}
		
	
		// Validate create
		if (!$candidate->title)
		{
			register_error(elgg_echo("polls:notitle"));
			
			forward($_SERVER['HTTP_REFERER']);
			exit;
		}
		
		// Access ids

		// candidiates inherit read access from poll
		$candidate->access_id = $poll->access_id;
		
		// Set parent
		$candidate->parent_guid = $parent_guid;
		
		// Ensure ultimate owner
		$candidate->owner_guid = ($candidate->owner_guid ?
										$candidate->owner_guid : $_SESSION['user']->guid); 


		// finally save
		if ($candidate->save())
		{
			// Now save description as an annotation
			$candidate->annotate('poll_candidate', $candidate->description, $candidate->access_id);

			// add to the river
			if (!$editing)
			{
				add_to_river('river/object/poll_candidate/create', 'create',
								$_SESSION['user']->guid, $candidate->guid);
			}

			system_message(elgg_echo("polls:candidate:saved"));
		
			// Forward to the parent poll
			forward($candidate->getUrl());
			exit;
		}
		else
		{
			register_error(elgg_echo('polls:candidate:notsaved'));
		}

	}
	else
	{
		register_error(elgg_echo("polls:noaccess"));
	}
	

	forward($_SERVER['HTTP_REFERER']);
	exit;
