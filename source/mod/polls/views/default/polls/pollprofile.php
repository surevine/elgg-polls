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


	// Output body
	$entity = $vars['entity'];
	
	$rev = (int)get_input('rev');
	if ($rev)
	{	
		$latest = get_annotation($rev);	
	}
	else
	{
		$latest = $entity->getAnnotations('poll', 1, 0, 'desc');

		if ($latest)
		{
			$latest = $latest[0];
		}
	}

	if ($entity && $entity->moderated == "no")
	{
		$poll_is_moderated = FALSE;
	}
	else
	{
		$poll_is_moderated = TRUE;
	}


	$tag_string = get_input('tag');
	$tag_filter = NULL;
	
	if ($tag_string != '')
	{
		$tag_filter = explode(',', get_input('tag'));
	}

	$view_type = get_input('view_type');
	$state = NULL;
	if ($view_type !== '')
	{
		// don't assume the URL parameter is named the same as
		// the database parameter

		if ($view_type == "active")
		{
			$state = "active";
		}
		else if ($view_type == "promoted")
		{
			$state = "promoted";
		}
		else if ($view_type == "rejected")
		{
			$state = "rejected";
		}
		else if ($view_type == "proposed" && $poll_is_moderated)
		{
			$state = "proposed";
		}
	}

	if (!$state)
	{
		$state = "active";
	}


?>	
	
	<div class="contentWrapper">
	<div id="polls_poll">
		
<?php	
	function display_tab($poll, $state, $link_name, $link_text)
	{
		global $CONFIG;

		$metadata = array(
			'parent_guid' => $poll->getGUID(),
			'state' => $link_name
		);

		$options = array(
			'types' => 'object',
			'subtypes' => 'poll_candidate',
			'count' => TRUE,
			'metadata_name_value_pairs' => $metadata
		);

		$count = elgg_get_entities_from_metadata($options);

		$link_text .= " (" . $count . ")";

		// don't make it a link if we're already on that page
		if ($state == $link_name)
		{
			$class = 'elgg-state-selected';
		}
		else
		{
			$class = '';
		}

		echo '<li class="' . $class . '">';
			
		if (get_context() == "polls_manage")
		{
			echo '<a href="' . $CONFIG->wwwroot . "pg/polls/manage/" . $poll->getGUID() .
						'/' . $link_name . '">';
		}
		else
		{
			echo '<a href="' . $CONFIG->wwwroot . "pg/polls/view/" . $poll->getGUID() .
						'/' . $link_name . '">';
		}

		echo $link_text;
		echo '</a></li>';
	}
	
	if ($entity)
	{
		echo elgg_view('output/longtext', array('value' => $latest->value));


		$active_name = $entity->active_name;
		$promoted_name = $entity->promoted_name;
		$rejected_name = $entity->rejected_name;
		$proposed_name = $entity->proposed_name;

		if (!$active_name)
		{
			$active_name = elgg_echo("polls:active_name:default");
		}

		if (!$promoted_name)
		{
			$promoted_name = elgg_echo("polls:promoted_name:default");
		}

		if (!$rejected_name)
		{
			$rejected_name = elgg_echo("polls:rejected_name:default");
		}

		if (!$proposed_name)
		{
			$proposed_name = elgg_echo("polls:proposed_name:default");
		}



		// display candidates

		echo '<div id="elgg_horizontal_tabbed_nav" class="elgg-polls-tabs-container"><ul class="elgg-menu elgg-menu-filter elgg-menu-hz elgg-menu-filter-default">';
//		echo elgg_echo("polls:candidate:show");

		display_tab($entity, $state, "active", ucfirst($active_name));

		display_tab($entity, $state, "promoted", ucfirst($promoted_name));

		display_tab($entity, $state, "rejected", ucfirst($rejected_name));

		if ($poll_is_moderated)
		{
			display_tab($entity, $state, "proposed", ucfirst($proposed_name));
		}
		
		echo '</ul></div>';

		$field_name = $state . "_name";
		$state_name = $entity->$field_name;

		if (!$state_name)
		{
			$state_name = elgg_echo("polls:{$state}_name:default");
		}

/*
		echo "<h3>";
		echo sprintf(elgg_echo("polls:candidate:heading"), ucfirst($state_name));
		echo "</h3>";
*/

		echo '<div class="polls-state-description">';
		
		$state_description_metaname = $state . '_description';
		
		$state_description = $entity->$state_description_metaname;
		
		if ($state_description == '')
		{
			$state_description = elgg_echo("polls:candidate:description:" . $state);
		}

		echo autop($state_description);
		
		echo '</div>';
		
		$metadata_search_array = array("parent_guid" => $entity->guid, "state" => $state);

		if (!empty($tag_filter))
		{
			$metadata_search_array["tags"] = $tag_filter;

			// remove query string from url
			$url_split = explode('?', $_SERVER['REQUEST_URI']);
			$url = $url_split[0];

			echo '<div class="elgg-polls-filter">';
			echo elgg_echo("polls:filter:filteredby") . " ";
			echo elgg_view('polls/filtertags', array('tags' => $tag_filter, 'baseurl' => $url));

//			echo '<a class="filter_delete" href="' . $url . '">' . elgg_echo("polls:filter:clear") . '</a>';

			echo '</div>';
		}



		echo "<p>";

		$limit = get_input("limit", 25);

		$display_order = $entity->display_order;

		// display order za no longer available but test for it in case we have legacy data
		if ($display_order == "az" || $display_order == "za")
		{
			$count = polls_get_entities_from_metadata_multi_order_by_title($metadata_search_array,
								"object", "poll_candidate", 0, 0, 0, "", 0, TRUE);


			// display A-Z pagination links if we can't fit them all on one page

			if ($count > $limit)
			{
				$distribution = polls_get_title_distibution_of_entities_from_metadata_multi(
								$metadata_search_array, "object", "poll_candidate");


				// get which alphabetic page we're on

				$alpha_page = get_input('alpha');

				if ($alpha_page && $alpha_page != "other")
				{
					$alpha_page = strtoupper(substr($alpha_page, 0, 1));

					if ($alpha_page < 'A' || $alpha_page > 'Z')
					{
						$alpha_page = NULL;
					}
				}

				// if no page specified, go to first page with content

				if (!$alpha_page)
				{
					if (is_array($distribution) && !empty($distribution))
					{
						// get first key in array
						reset($distribution);
						$alpha_page = key($distribution);
					}
					else
					{
						$alpha_page = "A";
					}
				}


				$baseurl = url_delete_var($_SERVER['REQUEST_URI'], "offset");

				echo elgg_view('polls/alphanavigation', array(
					'baseurl' => $baseurl,
					'alpha_page' => $alpha_page,
					'distribution' => $distribution,
				));
				echo '<div class="clearfloat">';
				echo '</div>';
				echo '<p>';
			}
			else
			{
				$alpha_page = NULL;
			}
			
			$limit = get_input("limit", 2147483647); // Show all unless told otherwise
			
			
			$objects = polls_list_entities_from_metadata_multi_order_by_title(
							$metadata_search_array, "object", "poll_candidate", 0,
							$limit, FALSE, FALSE, FALSE, TRUE, $alpha_page);
		}
		else
		{
			$sort_by1 = "";
			$sort_by2 = "";
			$reverse = FALSE;

			if ($display_order == "mostvotes")
			{
				$sort_by1 = "votes_count";
				$sort_by2 = "votes_score";
				$reverse = TRUE;
			}
			else if ($display_order == "leastvotes")
			{
				$sort_by1 = "votes_count";
				$sort_by2 = "votes_score";
			}
			else if ($display_order == "highestscore")
			{
				$sort_by1 = "votes_score";
				$sort_by2 = "votes_count";
				$reverse = TRUE;
			}
			else if ($display_order == "lowestscore")
			{
				$sort_by1 = "votes_score";
				$sort_by2 = "votes_count";
			}
			else if ($display_order == "newestvote")
			{
				$sort_by1 = "votes_time";
				$reverse = TRUE;
			}
			else if ($display_order == "oldestvote")
			{
				$sort_by1 = "votes_time";
			}
			else if ($display_order == "newest")
			{
				$sort_by1 = "";
				$reverse = TRUE;
			}
			else if ($display_order == "oldest")
			{
				$sort_by1 = "";
			}

			$objects = polls_list_entities_from_metadata_multi_order_by_metadata(
							$metadata_search_array, "object", "poll_candidate", 0,
							$limit, $sort_by1, $sort_by2, $reverse, FALSE, FALSE);
		}

		if (empty($objects))
		{
			echo "<p>";
			echo sprintf(elgg_echo("polls:candidate:none"), $state_name);

			if ($state == "active" || $state == "proposed")
			{
				if ($poll_is_moderated)
				{
					echo " " . sprintf(elgg_echo("polls:candidate:howtopropose"),
										elgg_echo("polls:candidate:new:propose"));
				}
				else
				{
					echo " " . sprintf(elgg_echo("polls:candidate:howtoadd"), elgg_echo("polls:candidate:new"));
				}
			}
		}
		else
		{
			if (get_context() == "polls_manage")
			{
				$form = "";

				$form .= '<div class="polls_manage">';

				$form .= $objects;

				$form .= '<p class="moveto">';
				$form .= elgg_echo("polls:moveselected");

				$options = array();

				if ($state != "active")
				{
					$options['active'] = ucfirst($active_name);
				}

				if ($state != "promoted")
				{
					$options['promoted'] = ucfirst($promoted_name);
				}

				if ($state != "rejected")
				{
					$options['rejected'] = ucfirst($rejected_name);
				}

				if ($poll_is_moderated && $state != "proposed")
				{
					$options['proposed'] = ucfirst($proposed_name);
				}

				$default = "";

				if ($state == "active")
				{
					$default = "promoted";
				}
				else if ($state == "promoted")
				{
					$default = "active";
				}
				else if ($state == "rejected")
				{
					$default = "active";
				}
				else if ($state == "proposed")
				{
					$default = "active";
				}

				$parameters = array(
							'internalname' => 'moveto',
							'value' => $default,
							'options_values' => $options,
						);

				$form .= elgg_view("input/pulldown", $parameters);

				$form .= "&nbsp;&nbsp;";

				$form .= elgg_view('input/submit', array('value' => elgg_echo('polls:movebutton')));

				$form .= '</div>';

				$action_url = $vars['url'] . "action/polls/manage";
				echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
			}
			else
			{
				echo $objects;
			}
		}
		
		if (($display_order == "az" || $display_order == "za") && ($count > $limit))
		{
			echo elgg_view('polls/alphanavigation', array(
															'baseurl' => $baseurl,
															'alpha_page' => $alpha_page,
															'distribution' => $distribution,
														));
			echo '<div class="clearfloat"></div>';
		}		
?>
		<!-- display tags -->
		<p class="tags">
			<?php

				echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));
			
			?>
		</p>
<?php
		// last edit & by whom

		echo "<p class=\"strapline\">";

		$time_updated = $latest->time_created;
		$owner_guid = $latest->owner_guid;
		$owner = get_entity($owner_guid);
	
		echo sprintf(elgg_echo("polls:strapline"),
				friendly_time($time_updated),
				"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>");
		
		echo "</p>";


	}

?>

</div>
</div>
