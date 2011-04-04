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

	admin_gatekeeper();
	action_gatekeeper();

	set_context('polls');


	function add_candidate($poll, $parent_guid, $container_guid, $title, $description, $tags)
	{
		$candidate = new ElggObject();

		$candidate->subtype = 'poll_candidate';

		$candidate->container_guid = $container_guid;

		// always go straight to active, even for moderated polls, as user must be a moderator
		// to use bulk upload (currenly even more restricted as must be an admin)
		$candidate->state = 'active';
		
		// Save fields - note we always save latest description as both description and annotation
		$candidate->title = $title;
		$candidate->description = $description;
		$candidate->tags = string_to_tag_array($tags);

		// Validate create
		if (!$candidate->title)
		{
			register_error(elgg_echo("polls:notitle"));
			return FALSE;
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
		if (!$candidate->save())
		{
			register_error(elgg_echo('polls:candidate:notsaved'));
			return FALSE;
		}


		// Now save description as an annotation
		$candidate->annotate('poll_candidate', $candidate->description, $candidate->access_id);

		return TRUE;
	}



	// Get poll
	$parent_guid = (int)get_input('item_guid', 0);
	$poll = get_entity($parent_guid);

	if (!$poll)
	{
		register_error(elgg_echo('polls:candidate:notsaved'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}


	if (!isset($_FILES['bulkuploadfile']) || $_FILES['bulkuploadfile']['error'] != 0)
	{
		register_error(elgg_echo('polls:bulkupload:fileerror'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}


	$file = fopen($_FILES['bulkuploadfile']['tmp_name'], "r");

	if (!$file)
	{
		register_error(elgg_echo('polls:bulkupload:fileerror'));
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}

	// work out container for new candidates
	// group polls store candidates in the group
	// user polls store candidates in the user who adds the candidate (not the user who owns the poll)

	$container_guid = $poll->container_guid;
	$container = get_entity($container_guid);

	if (!$container || !$container instanceof ElggGroup)
	{
		$container_guid = $_SESSION['user']->getGUID();
	}



	// iterate over lines

	$line_num = 0;
	$candidates_added = 0;

	while (($fields = fgetcsv($file)) !== FALSE)
	{
		$line_num++;

		// treat header row differently

		if ($line_num == 1)
		{
			if (count($fields) != 3 ||
				trim(strtolower($fields[0])) != "title" ||
				trim(strtolower($fields[1])) != "description" ||
				trim(strtolower($fields[2])) != "tags")
			{
				fclose($file);
				register_error(elgg_echo('polls:bulkupload:headererror'));
				forward($_SERVER['HTTP_REFERER']);
				exit;
			}
		}
		else
		{
			if (!$fields || count($fields) != 3)
			{
				fclose($file);
				register_error(sprintf(elgg_echo('polls:bulkupload:lineerror'), $candidates_added, $line_num));
				forward($_SERVER['HTTP_REFERER']);
				exit;
			}


			$title = $fields[0];
			$description = $fields[1];
			$tags = $fields[2];

			if (!add_candidate($poll, $parent_guid, $container_guid, $title, $description, $tags))
			{
				fclose($file);
				register_error(sprintf(elgg_echo('polls:bulkupload:lineerror'), $candidates_added, $line_num));
				forward($_SERVER['HTTP_REFERER']);
				exit;
			}

			$candidates_added++;
		}
	}


	fclose($file);

	system_message(sprintf(elgg_echo("polls:bulkupload:saved"), $candidates_added));

	// Forward to the parent poll
	forward($poll->getUrl());
	exit;
