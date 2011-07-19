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

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	action_gatekeeper();

	set_context('polls');


	// Get group fields
	$input = array();
	foreach ($CONFIG->polls_form as $shortname => $details)
	{
		$input[$shortname] = get_input($shortname);

		if ($details['type'] == 'tags')
		{
			$input[$shortname] = string_to_tag_array($input[$shortname]);
		}
	}
	
	// New or old?
	$poll = NULL;
	$polls_guid = (int)get_input('polls_guid');

	if ($polls_guid)
	{
		$editing = TRUE;

		$poll = get_entity($polls_guid);

		if (!$poll->canEdit())
		{
			$poll = NULL; // if we can't edit it, go no further.
		}
	}
	else
	{
		$editing = FALSE;

		// New instance, so set container_guid
		$container_guid = get_input('container_guid', $_SESSION['user']->getGUID());
		$container = get_entity($container_guid);

		if ($container && can_create_poll($container))
		{
			$poll = new ElggObject();

			$poll->subtype = 'poll';

			$poll->container_guid = $container_guid;
		}
	}
	
	// Have we got it? Can we edit it?
	if ($poll && $poll instanceof ElggObject)
	{
		if ($editing)
		{
			$old_access_id = (int)$poll->access_id;
		}


		// Validate create
		if (!$poll->title && !$input['title'])
		{
			register_error(elgg_echo("polls:notitle"));

			$_SESSION['forwarded_form_data'] = $_REQUEST;
			
			forward($_SERVER['HTTP_REFERER']);
			exit;
		}

		// check star values are valid
		if (($input[star_values] && count(explode(',', $input[star_values])) != 5) ||
			($input[category1values] && count(explode(',', $input[category1values])) != 5) ||
			($input[category2values] && count(explode(',', $input[category2values])) != 5) ||
			($input[category3values] && count(explode(',', $input[category3values])) != 5) ||
			($input[category4values] && count(explode(',', $input[category4values])) != 5))
		{
			register_error(elgg_echo("polls:badstarvalues"));

			$_SESSION['forwarded_form_data'] = $_REQUEST;
			
			forward($_SERVER['HTTP_REFERER']);
			exit;
		}

		if ($editing)
		{
			// Check we're not making it unmoderated while there are still
			// proposed candidates

			$metadata = array(
				'parent_guid' => $poll->getGUID(),
				'state' => 'proposed'
			);

			$options = array(
				'types' => 'object',
				'subtypes' => 'poll_candidate',
				'count' => TRUE,
				'metadata_name_value_pairs' => $metadata
			);

			$count = elgg_get_entities_from_metadata($options);

			if ($input['moderated'] == "no" && $count > 0)
			{
				register_error(elgg_echo("polls:proposednotzero"));

				$_SESSION['forwarded_form_data'] = $_REQUEST;
				
				forward($_SERVER['HTTP_REFERER']);
				exit;
			}
		}


		// check if categories are being changed and get confirmation as necessary
		if ($editing)
		{
			$confirm_needed = FALSE;

			for ($x = 1; $x <= 4; $x++)
			{
				// only need confirmation if they haven't yet answered the question
				if (!get_input('keepordelete_category' . $x))
				{
					$attribute_name = 'category' . $x;
					$vote_count_metadata_name = "votes_count_category" . $x;

					$old = $poll->$attribute_name;
					$new = $input[$attribute_name];

					$votes = $poll->$vote_count_metadata_name;

					if ($old != $new && $votes > 0)
					{
						$confirm_needed = TRUE;
					}
				}
			}


			if ($confirm_needed)
			{
				$title = elgg_echo("polls:edit");

				$body = elgg_view_title($title);
				$body .= elgg_view("forms/polls/confirmedit", array('request' => $_REQUEST));

				$body = elgg_view_layout('two_column_left_sidebar', '', $body);
		
				page_draw($title, $body);

				exit;
			}
		}


		// Save fields - note we always save latest description as both description and annotation
		if (sizeof($input) > 0)
		{
			foreach ($input as $shortname => $value)
			{
				if (!$polls_guid ||
					($polls_guid && ($CONFIG->polls_form[$shortname]['editable'] != FALSE)))
				{
					$poll->$shortname = $value;
				}
			}
		}
		
		
		// Access ids


		$new_access_id = (int)get_input('access_id', 0);
		$poll->access_id = $new_access_id;

		if ($editing && $new_access_id != $old_access_id)
		{
			$access_changed = TRUE;
		}
		else
		{
			$access_changed = FALSE;
		}


		// Write access id
		$poll->write_access_id = (int)get_input('write_access_id', 0);
		
		// Ensure ultimate owner
		$poll->owner_guid = ($poll->owner_guid ? $poll->owner_guid : $_SESSION['user']->guid); 


		// finally save
		if ($poll->save())
		{
			// Now save description as an annotation
			$poll->annotate('poll', $poll->description, $poll->access_id);

			// cascade any change in access_id to the history
			if ($access_changed)
			{
				$annotations = $poll->getAnnotations("", PHP_INT_MAX);

				foreach ($annotations as $annotation)
				{
					update_annotation($annotation->id, $annotation->name, $annotation->value,
							  $annotation->value_type, $annotation->owner_guid, $new_access_id);
				}

				// If poll has any child candidates, we must update them with any change
				// to access permissions (all candidates inherit their read permission
				// from their parent poll)

				$metadata = array(
					'parent_guid' => $poll->getGUID(),
				);

				$options = array(
					'types' => 'object',
					'subtypes' => 'poll_candidate',
					'metadata_name_value_pairs' => $metadata
				);

				if ($children = elgg_get_entities_from_metadata($options))
				{
					foreach ($children as $child)
					{
						$child->access_id = $new_access_id;

						if (!$child->save())
						{
							register_error(elgg_echo('polls:candidate:notsaved'));
						}

						// if the candidate has any votes, we must update them
						// with any changes to access permissions

						$annotations = $child->getAnnotations("", PHP_INT_MAX);

						foreach ($annotations as $annotation)
						{
							update_annotation($annotation->id, $annotation->name, $annotation->value,
									  $annotation->value_type, $annotation->owner_guid, $new_access_id);
						}
					}
				}
			}


			// if the user asked to delete the votes on any categories, do so

			if (get_input('keepordelete_category1') == "delete" ||
				get_input('keepordelete_category2') == "delete" ||
				get_input('keepordelete_category3') == "delete" ||
				get_input('keepordelete_category4') == "delete")
			{
				// find all candidates in the poll

				$metadata = array(
					'parent_guid' => $poll->getGUID(),
				);

				$options = array(
					'types' => 'object',
					'subtypes' => 'poll_candidate',
					'metadata_name_value_pairs' => $metadata
				);

				if ($candidates = elgg_get_entities_from_metadata($options))
				{
					foreach ($candidates as $candidate)
					{
						for ($x = 1; $x <= 4; $x++)
						{
							if (get_input('keepordelete_category' . $x) == "delete")
							{
								// get votes on the candidate
								$votes = $candidate->getAnnotations('poll_stars5_category' . $x, PHP_INT_MAX);

								// delete the votes
								foreach ($votes as $vote)
								{
									$vote->delete();
								}

								// reset the cached count and total in the candidate
								$vote_count_metadata_name = "votes_count_category" . $x;
								$vote_total_metadata_name = "votes_total_category" . $x;
								$candidate->$vote_count_metadata_name = 0;
								$candidate->$vote_total_metadata_name = 0;
							}
						}
					}
				}

				// now reset the cached vote count in the poll
				for ($x = 1; $x <= 4; $x++)
				{
					if (get_input('keepordelete_category' . $x) == "delete")
					{
						$vote_count_metadata_name = "votes_count_category" . $x;
						$poll->$vote_count_metadata_name = 0;
					}
				}
			}


			// add to the river
			if (!$editing)
			{
				add_to_river('river/object/poll/create', 'create', $_SESSION['user']->guid, $poll->guid);
			}
				
			system_message(elgg_echo("polls:saved"));
		
			// Forward to the user's profile
			forward($poll->getUrl());
			exit;
		}
		else
		{
			register_error(elgg_echo('polls:notsaved'));
		}

	}
	else
	{
		register_error(elgg_echo("polls:noaccess"));
	}
	

	// Forward to the user's profile
	forward($poll->getUrl());
	exit;
