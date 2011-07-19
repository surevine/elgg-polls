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

	$candidate = $vars['entity'];

	$icon = elgg_view_entity_icon($candidate, 'small');

	$info = view_voting_box($candidate);

	$info .= "<h3><a href=\"" . $candidate->getUrl() . "\">" . $candidate->title . "</a></h3>";

	
	$latest = $candidate->getAnnotations('poll_candidate', 1, 0, 'desc');

	if ($latest)
	{
		$latest = $latest[0];
	
		$time_updated = $latest->time_created;
		$owner_guid = $latest->owner_guid;
		$owner = get_entity($owner_guid);
			
		$info .= "<div class=\"elgg-subtext\">";
		$info .= sprintf(elgg_echo("polls:strapline"), friendly_time($time_updated),
						"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>");
		$info .= "</div>";
	}

	// only show comments, tags and more info if in polls context (so they don't
	// show up in search listings)

	$context = get_context();

	if ($context == "polls" || $context == "polls_manage")
	{
		$info .= '<div class="candidate_tagline">';

		$num_comments = elgg_count_comments($candidate);

		if ($num_comments > 0)
		{
			$info .= '<span class="comments">';
			$info .= "<a href=\"" . $candidate->getUrl() . "\">";

			if ($num_comments == 1)
			{
				$info .= elgg_echo("polls:candidate:comments:one");
			}
			else
			{
				$info .= sprintf(elgg_echo("polls:candidate:comments"), $num_comments);
			}

			$info .= "</a>";
			$info .= '</span>';
		}

		// remove query string from url
		$url_split = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url_split[0];

		$info .= elgg_view('polls/localtags', array('baseurl' => $url, 'tags' => $candidate->tags));

		$info .= "</div>";



		// get description for more info

		$description = "";
		$annotations = $candidate->getAnnotations('poll_candidate', 1, 0, 'desc');

		if ($annotations)
		{
			$annotation = $annotations[0];

			if ($annotation)
			{
				$description = $annotation->value;

				if ($description)
				{
					$info .= '<p class="more-info-link">';
					$info .= '<a class="elgg-toggler" href="#polls_candidate_' . $candidate->getGUID() . '">';
					$info .= elgg_echo('polls:candidate:moreinfo');
					$info .= '</a>';
					$info .= '</p>';
					$info .= '<div class="hidden more-info-data" id="polls_candidate_' . $candidate->getGUID() . '">';
					$info .= $description;
					$info .= '</div>';
				}
			}
		}
	}


	if (get_context() == "polls_manage")
	{
		$checkbox = '<div class="polls_manage_checkbox">';
		$checkbox .= elgg_view('input/checkboxes', array(
								'name' => 'manage_guid[]',
								'default' => FALSE,
								'options' => array('' => $candidate->getGUID())
								));
		$checkbox .= '</div>';

		echo elgg_view_image_block($checkbox . $icon, $info);
	}
	else
	{
		echo elgg_view_image_block($icon, $info);
	}

