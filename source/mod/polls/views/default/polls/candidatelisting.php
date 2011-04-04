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


	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/lib.php");

	$candidate = $vars['entity'];

	$icon = elgg_view(
			"graphics/icon", array	(
										'entity' => $candidate,
										'size' => 'small',
									)
		);


	$info = view_voting_box($candidate);

	$info .= '<p class="candidate_title">';
	$info .= "<b><a href=\"" . $candidate->getUrl() . "\">" . $candidate->title . "</a></b>";
	$info .= '</p>';

	
	$latest = $candidate->getAnnotations('poll_candidate', 1, 0, 'desc');

	if ($latest)
	{
		$latest = $latest[0];
	
		$time_updated = $latest->time_created;
		$owner_guid = $latest->owner_guid;
		$owner = get_entity($owner_guid);
			
		$info .= "<p class=\"owner_timestamp\">";
		$info .= sprintf(elgg_echo("polls:strapline"), friendly_time($time_updated),
						"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>");
		$info .= "</p>";
	}

	// only show comments, tags and more info if in polls context (so they don't
	// show up in search listings)

	$context = get_context();

	if ($context == "polls" || $context == "polls_manage")
	{
		$info .= '<p class="candidate_tagline">';

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

		$tag_string = elgg_view('polls/localtags', array('baseurl' => $url, 'tags' => $candidate->tags));

		if ($tag_string)
		{
			$info .= '<span class="tags">';
			$info .= $tag_string;
			$info .= '</span>';
		}

		$info .= "</p>";



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
					$info .= '<p>';
					$info .= '<a class="collapsibleboxlink">';
					$info .= elgg_echo('polls:candidate:moreinfo');
					$info .= '</a>';
					$info .= '</p>';
					$info .= '<div class="collapsible_box">';
					$info .= $description;
					$info .= '</div>';
				}
			}
		}
	}


	if (get_context() == "polls_manage")
	{
		$checkbox = '<div class="polls_manage_checkbox">';
		$checkbox .= elgg_view('input/checkboxes', array('internalname' => 'manage_guid[]',
								'options' => array('' => $candidate->getGUID())));
		$checkbox .= '</div>';

		echo elgg_view_listing($checkbox . $icon, $info);
	}
	else
	{
		echo elgg_view_listing($icon, $info);
	}

