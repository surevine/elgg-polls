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
	action_gatekeeper();

	$candidate_guid = get_input('candidate_guid');
	$candidate = get_entity($candidate_guid);

	$is_ajax = ( get_input('ajax') != '' ? TRUE : FALSE );
	
	if ($candidate && $candidate->state == "active")
	{
		$poll = get_entity($candidate->parent_guid);


		// work out type of voting

		$category_number = get_input('category_number');

		if (is_numeric($category_number) && $category_number > 0)
		{
			$doing_category_vote = TRUE;

			$voting_type = "stars5";
			$vote_annotation_type = "poll_stars5_category" . $category_number;

			// star input has candidate GUID and category number appended to
			// distinguish from other inputs on the same page
			$vote = get_input('star' . $candidate_guid . "_" . $category_number);

			$vote_count_metadata_name = "votes_count_category" . $category_number;
			$vote_total_metadata_name = "votes_total_category" . $category_number;
		}
		else
		{
			$doing_category_vote = FALSE;

			$voting_type = $poll->voting_type;
			$vote_count_metadata_name = "votes_count";
			$vote_total_metadata_name = "votes_total";

			if ($voting_type == "thumbs")
			{
				$vote_annotation_type = "poll_thumbs";
				$vote = "1";
			}
			else if ($voting_type == "stars5")
			{
				$vote_annotation_type = "poll_stars5";

				// star input has candidate GUID appended to distinguish from other
				// inputs on the same page
				$vote = get_input('star' . $candidate_guid);
			}
		}


		// check if they've already voted

		// hack count_annotations has a bug where it ignores the owner parameter
		// $countMyVotes = count_annotations($candidate->getGUID(), "", "", $vote_annotation_type,
		//										"", "", $_SESSION['user']->getGUID());

		// instead search for annotations (limit to only 1 since we're
		// only interested in whether they've voted or not)

		$voteList = get_annotations($candidate->getGUID(), "", "", $vote_annotation_type,
										"", $_SESSION['user']->getGUID(), 1, 0);

		if ($voteList)
		{
			// assume only one vote already - if for some reason there's more than
			// one then we change the first one we find

			$voteAnnotation = $voteList[0];

			// remove old value from total
			$candidate->$vote_total_metadata_name -= $voteAnnotation->value;

			// add new value to total
			$candidate->$vote_total_metadata_name += $vote;

			// change annotation
			$voteAnnotation->value = (int) $vote;
			$voteAnnotation->save();

			if (!$is_ajax)
			{
				system_message(elgg_echo('polls:vote:changed'));
			}
		}
		else
		{
			// add the vote - same access id as the poll and the candidate
			$candidate->annotate($vote_annotation_type, $vote, $poll->access_id, 0, "integer");

			$candidate->$vote_count_metadata_name++;
			$candidate->$vote_total_metadata_name += $vote;

			$poll->$vote_count_metadata_name++;

			if (!$is_ajax)
			{
				system_message(elgg_echo('polls:vote:success'));
			}
		}


		if ($doing_category_vote)
		{
			// we append the number of stars to the river description 'vote' to give 'vote1', 'vote2', etc
			// so we can display how many stars the candidate received in the river
			add_to_river('river/polls/vote_stars5_category', 'vote' . $vote . '_category' .
							$category_number, $_SESSION['user']->guid, $candidate_guid);
		}
		else
		{
			$candidate->votes_time = time();

			if ($voting_type == "thumbs")
			{
				$candidate->votes_score = $candidate->votes_total;
				add_to_river('river/polls/vote_thumbs', 'vote', $_SESSION['user']->guid, $candidate_guid);
			}
			else
			{
				// This is fine for the moment as it can never be over 5 (therefore 1 unit digit)
				// If it goes to 10 or over it could cause problems as it is alphanumerically sorted
				// in the database
				$candidate->votes_score = $candidate->votes_total / $candidate->votes_count;

				// we append the number of stars to the river description 'vote' to give 'vote1', 'vote2', etc
				// so we can display how many stars the candidate received in the river
				add_to_river('river/polls/vote_stars5', 'vote' . $vote,
								$_SESSION['user']->guid, $candidate_guid);
			}
		}

		if ($is_ajax)
		{
			$return = array('success' => TRUE);
			
			if (($poll->redirect_on_vote == "yes") && ($candidate->getURL() != $_SERVER['HTTP_REFERER']))
			{
				$return['redirect'] = $candidate->getURL();
			}
				
			echo json_encode($return);
			exit;
		}
		else
		{
			if ($poll->redirect_on_vote == "yes")
			{
				forward($candidate->getURL());
			}
			else
			{
				forward($_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	register_error(elgg_echo('polls:vote:failure'));
	forward($_SERVER['HTTP_REFERER']);

