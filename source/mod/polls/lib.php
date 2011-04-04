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


	// URL query string modification
	// http://stackoverflow.com/questions/909193/is-there-a-php-library-that-handles-url-parameters-adding-or-removal
	//
	// to add/modify:	url_adjust_var(URL, "name=value")
	// to delete:		url_adjust_var(URL, "name")
	//
	function url_adjust_var($url, $s)
	{
		if (preg_match('/(.*?)\?/', $url, $matches))
		{
			$urlWithoutParams = $matches[1];
		}
		else
		{
			$urlWithoutParams = $url;
		}

		parse_str(parse_url($url, PHP_URL_QUERY), $params);

		$newQueryString = '';
		
		if (strpos($s, '=') !== FALSE)
		{
			list($var, $value) = split('=', $s);
			$params[$var] = urldecode($value);
		}
		else
		{
			unset($params[$s]);
		}

		foreach ($params as $name => $value)
		{
			if ($newQueryString != '')
			{
				$newQueryString .= '&';
			}

			$newQueryString .= rawurlencode($name) . '=' . rawurlencode($value);
		}
		
		if ($newQueryString != '')
		{
			return $urlWithoutParams . '?' . $newQueryString;
		}
		else
		{
			return $urlWithoutParams;
		}
	}


	//
	// url_add_var(URL, "name=value")
	//
	function url_add_var($url, $s)
	{
		return url_adjust_var($url, $s);
	}


	//
	// url_delete_var(URL, "name")
	//
	function url_delete_var($url, $s)
	{
		return url_adjust_var($url, $s);
	}





	function can_create_poll($container)
	{
		// if no container specified => NO
		if (!$container)
		{
			return FALSE;
		}

		// if user can't write to the container => NO
		if (!$container->canWriteToContainer($_SESSION['user']->getGUID()))
		{
			return FALSE;
		}

		// if user is an admin => YES
		if (isadminloggedin())
		{
			return TRUE;
		}

		// get global poll access settings
		$access = get_plugin_setting('newpollaccess', 'polls');

		// if access settings is everyone => YES
		if ($access == "everyone")
		{
			return TRUE;
		}

		// if access is groups only, and it's a group, and it's our group => YES
		if ($access == "groups" &&
			$container instanceof ElggGroup &&
			$container->getOwner() == $_SESSION['user']->getGUID())
		{
			return TRUE;
		}

		// sorry, the answer's no
		return FALSE;
	}


	function view_voting_box($candidate, $category_number = 0)
	{
		global $CONFIG;

		$output = "";

		$star_values = NULL;

		$poll = get_entity($candidate->parent_guid);

		if (!$poll)
		{
			return "";
		}

		if (is_numeric($category_number) && $category_number > 0)
		{
			$doing_category_vote = TRUE;

			$voting_type = "stars5";
			$vote_annotation_type = "poll_stars5_category" . $category_number;

			$vote_count_metadata_name = "votes_count_category" . $category_number;
			$vote_total_metadata_name = "votes_total_category" . $category_number;

			// star input has candidate GUID appended to distinguish from other
			// inputs on the same page
			$star_label = "star" . $candidate->getGUID() . "_" . $category_number;

			$values_metadata_name = 'category' . $category_number . 'values';

			if ($poll->$values_metadata_name)
			{
				$star_values = explode(',', $poll->$values_metadata_name);

				// ignore if less than 5 values given
				if (count($star_values) < 5)
				{
					$star_values = NULL;
				}
			}
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
			}
			else if ($voting_type == "stars5")
			{
				$vote_annotation_type = "poll_stars5";

				// star input has candidate GUID appended to distinguish from other
				// inputs on the same page
				$star_label = "star" . $candidate->getGUID();

				if ($poll->star_values)
				{
					$star_values = explode(',', $poll->star_values);

					// ignore if less than 5 values given
					if (count($star_values) < 5)
					{
						$star_values = NULL;
					}
				}
			}
		}

		// count votes and work out if user can vote

		$canVote = FALSE;

		if (get_context() == "polls" && isloggedin() && $candidate->state == "active")
		{
			// hack count_annotations has a bug where it ignores the owner parameter
			// $countMyVotes = count_annotations($candidate->getGUID(), "", "", $vote_annotation_type,
			//					"", "", $_SESSION['user']->getGUID());

			// instead search for annotations (limit to only 1 since we're
			// only interested in whether they've voted or not)

			$votes = get_annotations($candidate->getGUID(), "", "", $vote_annotation_type,
							"", $_SESSION['user']->getGUID(), 1, 0);

			if ($votes)
			{
				$canVote = FALSE;
				$previousVote = $votes[0]->value;
			}
			else
			{
				$canVote = TRUE;
			}
		}


		// We used to count the votes each time, now we cache the value in the
		// candidate metadata after every vote - hopefully the values should
		// be the same!
		// $numVotes = count_annotations($candidate->getGUID(), "", "", $vote_annotation_type);
		// $averageVote = get_annotations_avg($candidate->getGUID(), "", "", $vote_annotation_type);

		$numVotes = $candidate->$vote_count_metadata_name;

		if ($numVotes)
		{
			$averageVote = $candidate->$vote_total_metadata_name / $numVotes;
		}
		else
		{
			$averageVote = 0;
		}


		if ($numVotes)
		{
			if ($numVotes == 1)
			{
				$totalVotes = elgg_echo("polls:vote:total:one");
			}
			else
			{
				$totalVotes = sprintf(elgg_echo("polls:vote:total"), $numVotes);
			}
		}
		else
		{
			$totalVotes = elgg_echo("polls:vote:total:none");
		}


		// display everything


		$form = "";

		$form .= '<div class="polls_vote_area">';

		$form .= $totalVotes;

		$form .= elgg_view('input/hidden', array('internalname' => 'candidate_guid',
													'value' => $candidate->getGUID()));

		if ($doing_category_vote)
		{
			$form .= elgg_view('input/hidden', array('internalname' => 'category_number',
														'value' => $category_number));
		}


		if ($voting_type == "thumbs")
		{
			$form .= "&nbsp;";

			if ($canVote)
			{
				$form .= "<input class=\"polls_vote_thumb_up\" type=\"submit\" name=\"vote\" value=\"\" />";
			}
			else
			{
				$form .= "<input class=\"polls_vote_thumb_up_disabled\" type=\"submit\"" .
							" name=\"vote\" value=\"\" disabled=\"disabled\" />";
			}
		}
		else if ($voting_type == "stars5")
		{
			$form .= "<br/>";

			// work out parameters

			if ($canVote)
			{
				$readOnly = "false";
				$alreadyVoted = "false";
				$ro_hover = NULL;
			}
			else
			{
				$readOnly = "true";

				if ($previousVote)
				{
					// set flag for re-voting only if re-voting allowed in this poll
					if ($poll->revote == "yes")
					{
						$alreadyVoted = "true";
					}
					else
					{
						$alreadyVoted = "false";
					}

					// do we have titles for the stars?
					if ($star_values)
					{
						// get title
						$vote_title = $star_values[$previousVote - 1];

						// trim whitespace
						$vote_title = trim($vote_title);

						// escape any HTML special characters (quotes, ampersand, etc)
						$vote_title = htmlentities($vote_title, ENT_QUOTES, 'UTF-8');

						// escape it a second time as the title is modified by javascript code
						// which will strip out one set of escaping!
						$vote_title = htmlentities($vote_title, ENT_QUOTES, 'UTF-8');

						$vote_title = " (" . $vote_title . ")";


						if ($doing_category_vote)
						{
							$ro_hover = sprintf(elgg_echo("polls:vote:stars:previous:category"),
									$previousVote . $vote_title);
						}
						else
						{
							if ($previousVote == 1)
							{
								$ro_hover = elgg_echo("polls:vote:stars:previous:one");
							}
							else
							{
								$ro_hover = sprintf(elgg_echo("polls:vote:stars:previous"), $previousVote);
							}

							$ro_hover .= $vote_title;
						}
					}
					else
					{
						if ($doing_category_vote)
						{
							$ro_hover = sprintf(elgg_echo("polls:vote:stars:previous:category"), $previousVote);
						}
						else
						{
							if ($previousVote == 1)
							{
								$ro_hover = elgg_echo("polls:vote:stars:previous:one");
							}
							else
							{
								$ro_hover = sprintf(elgg_echo("polls:vote:stars:previous"), $previousVote);
							}
						}
					}
				}
				else
				{
					$alreadyVoted = "false";

					if (get_context() == "polls")
					{
						$ro_hover = elgg_echo("polls:vote:notallowed");
					}
					else
					{
						$ro_hover = elgg_echo("polls:vote:notallowed:here");
					}
				}
			}

			
			$settings = '{';

			$settings .= 'cancel:\'' . elgg_echo("polls:vote:changevote") . '\', ';
			$settings .= 'alreadyVoted:' . $alreadyVoted . ', ';
			$settings .= 'readOnly:' . $readOnly . ', ';
			$settings .= 'callback:Polls.starRatingCallback' . ', ';

			if ($ro_hover)
			{
				$settings .= 'ro_hover:\'' . $ro_hover . '\', ';
			}

			$settings .= 'curValue:' . $averageVote;

			$settings .= '}';
			
			// output stars

			for ($x = 1; $x <= 5; $x++)
			{
				$form .= '<input name="' . $star_label . '" ';
				$form .= 'type="radio" ';
				$form .= 'class="star ' . $settings . '"';

				$form .= ' value="' . $x . '"';

				// do we have titles for the stars?
				if ($star_values && count($star_values) >= $x)
				{
					// get title
					$title = $star_values[$x - 1];

					// trim whitespace
					$title = trim($title);

					// escape any HTML special characters (quotes, ampersand, etc)
					$title = htmlentities($title, ENT_QUOTES, 'UTF-8');

					// escape it a second time as the title is modified by javascript code
					// which will strip out one set of escaping!
					$title = htmlentities($title, ENT_QUOTES, 'UTF-8');

					// output title
					$form .= ' title="' . $title . '"';
				}

				$form .= '/>';
			}

			// output 6th star as a placeholder for cancel button
			if (get_context() == "polls" && $poll->revote == "yes")
			{
				$form .= '<input name="' . $star_label . '" ';
				$form .= 'type="radio" class="star cancel ' . $settings . '"';
				$form .= ' value="cancel" />';
			}

		}

		$form .= '</div>';

		if (get_context() == "polls_manage")
		{
			// manage screen wraps whole candidate listing in a form, and
			// nested forms aren't allowed in HTML, so we don't wrap the voting
			// button in a form.  It will still be rendered as a button due
			// to the outer form, and since we're disabling it ($canVote == FALSE)
			// for the manage screen, it doesn't matter that the submit URL
			// is wrong

			$output .= $form;
		}
		else
		{
			$action_url = $CONFIG->wwwroot . "action/polls/vote";
			$output .= elgg_view('input/form', array('action' => $action_url, 'body' => $form));
		}

		$output = '<div class="voting-box">' . $output . '</div>';
		
		return $output;
	}



	/**
	 * Returns a list of entities based on the given search criteria.
	 *
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $owner_guid
	 * @param int $limit 
	 * @param int $offset
	 * @param TRUE|FALSE $reverse Reverse sort?
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @param TRUE|FALSE $count If set to TRUE, returns the total number of entities rather than a list. (Default: FALSE)
	 * @return int|array List of ElggEntities, or the total number if count is set to FALSE
	 */
	function polls_get_entities_from_metadata_multi_order_by_title(
				$meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0,
				$limit = 10, $offset = 0, $reverse = FALSE, $site_guid = 0, $count = FALSE, $alpha_page = NULL)
	{
		global $CONFIG;
		
		if (!is_array($meta_array) || sizeof($meta_array) == 0)
		{
			return FALSE;
		}
		
		$where = array();
		
		$mindex = 1;
		$join = "";

		foreach ($meta_array as $meta_name => $meta_value)
		{
			$meta_n = get_metastring_id($meta_name);
			
			if (!is_array($meta_value))
			{
				$meta_value = array($meta_value);
			}
				
			foreach ($meta_value as $value)
			{
				$meta_v = get_metastring_id($value);
	
				$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
	
				if ($meta_name != "")
				{
					$where[] = "m{$mindex}.name_id='$meta_n'";
				}
	
				if ($meta_value != "")
				{
					$where[] = "m{$mindex}.value_id='$meta_v'";
				}
	
				$mindex++;
			}
		}
			
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);

		$limit = (int)$limit;
		$offset = (int)$offset;

		$owner_guid = (int) $owner_guid;

		$site_guid = (int) $site_guid;

		if ($site_guid == 0)
		{
			$site_guid = $CONFIG->site_guid;
		}
			
		if ($entity_type != "")
		{
			$where[] = "e.type = '{$entity_type}'";
		}

		if ($entity_subtype)
		{
			$where[] = "e.subtype = {$entity_subtype}";
		}

		if ($site_guid > 0)
		{
			$where[] = "e.site_guid = {$site_guid}";
		}

		if ($owner_guid > 0)
		{
			$where[] = "e.container_guid = {$owner_guid}";
		}

		if ($alpha_page)
		{
			if ($alpha_page == "other")
			{
				$where[] = "not upper(left(oe.title, 1)) BETWEEN 'A' AND 'Z'";
			}
			else
			{
				$letter = strtoupper(substr($alpha_page, 0, 1));

				if ($letter >= 'A' && $letter <= 'Z')
				{
					$where[] = "upper(left(oe.title, 1)) = '" . $letter . "'";
				}
			}
		}
	
		if ($count)
		{
			$query = "SELECT count(distinct e.guid) as total ";
		}
		else
		{
			$query = "SELECT distinct e.* ";
		}


		if (!$count)
		{
			$query .= ", oe.title ";
		}

		$join .= "LEFT JOIN `{$CONFIG->dbprefix}objects_entity` as oe on e.guid = oe.guid ";

		$query .= " from {$CONFIG->dbprefix}entities e {$join} where";

		foreach ($where as $w)
		{
			$query .= " $w and ";
		}

		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("e"); // Add access controls
		
		if (!$count)
		{
			// Add order by

			if ($reverse)
			{
				$query .= " order by oe.title desc, e.time_updated desc ";
			}
			else
			{
				$query .= " order by oe.title, e.time_updated ";
			}

			// add limit
			$query .= " limit $offset, $limit";
			return get_data($query, "entity_row_to_elggstar");
		}
		else
		{
			if ($count = get_data_row($query))
			{
				return $count->total;
			}
		}

		return FALSE;
	}


	/**
	 * Returns a list of entities based on the given search criteria.
	 *
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $owner_guid
	 * @param int $limit 
	 * @param int $offset
	 * @param string $order_by1 Name of metadata to order by (primnary)
	 * @param string $order_by2 Name of metadata to order by (secondary)
	 * @param TRUE|FALSE $reverse Reverse sort?
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 * @param TRUE|FALSE $count If set to TRUE, returns the total number of entities rather than a list. (Default: FALSE)
	 * @return int|array List of ElggEntities, or the total number if count is set to FALSE
	 */
	function polls_get_entities_from_metadata_multi_order_by_metadata(
				$meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0,
				$limit = 10, $offset = 0, $order_by1 = "", $order_by2 = "", $reverse = FALSE,
				$site_guid = 0, $count = FALSE)
	{
		global $CONFIG;
		
		if (!is_array($meta_array) || sizeof($meta_array) == 0)
		{
			return FALSE;
		}
		
		$where = array();
		
		$mindex = 1;
		$join = "";

		foreach ($meta_array as $meta_name => $meta_value)
		{
			$meta_n = get_metastring_id($meta_name);
			
			if (!is_array($meta_value))
			{
				$meta_value = array($meta_value);
			}
				
			foreach ($meta_value as $value)
			{
				$meta_v = get_metastring_id($value);
	
				$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
	
				if ($meta_name != "")
				{
					$where[] = "m{$mindex}.name_id='$meta_n'";
				}
	
				if ($meta_value != "")
				{
					$where[] = "m{$mindex}.value_id='$meta_v'";
				}
	
				$mindex++;
			}
		}
					
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);

		$limit = (int)$limit;
		$offset = (int)$offset;

		$owner_guid = (int) $owner_guid;
		
		$site_guid = (int) $site_guid;

		if ($site_guid == 0)
		{
			$site_guid = $CONFIG->site_guid;
		}
			
		if ($entity_type != "")
		{
			$where[] = "e.type = '{$entity_type}'";
		}

		if ($entity_subtype)
		{
			$where[] = "e.subtype = {$entity_subtype}";
		}

		if ($site_guid > 0)
		{
			$where[] = "e.site_guid = {$site_guid}";
		}

		if ($owner_guid > 0)
		{
			$where[] = "e.container_guid = {$owner_guid}";
		}
	
		if ($count)
		{
			$query = "SELECT count(distinct e.guid) as total ";
		}
		else
		{
			$query = "SELECT distinct e.* ";
		}


		if ($order_by1)
		{
			$sort_id1 = get_metastring_id($order_by1);
		}

		if ($order_by2)
		{
			$sort_id2 = get_metastring_id($order_by2);
		}

		if ($sort_id1 && !$count)
		{
			$query .= ", s1.sort_column ";

			$join .= "LEFT JOIN (SELECT md.entity_guid, v.string as sort_column " .
				 "FROM `{$CONFIG->dbprefix}metadata` as md " .
				 "JOIN {$CONFIG->dbprefix}metastrings as v on md.value_id = v.id " .
				 "WHERE `name_id` = {$sort_id1}) as s1 on e.guid = s1.entity_guid ";
		}

		if ($sort_id2 && !$count)
		{
			$query .= ", s2.sort_column ";

			$join .= "LEFT JOIN (SELECT md.entity_guid, v.string as sort_column " .
				 "FROM `{$CONFIG->dbprefix}metadata` as md " .
				 "JOIN {$CONFIG->dbprefix}metastrings as v on md.value_id = v.id " .
				 "WHERE `name_id` = {$sort_id2}) as s2 on e.guid = s2.entity_guid ";
		}

		$query .= " from {$CONFIG->dbprefix}entities e {$join} where";

		foreach ($where as $w)
		{
			$query .= " $w and ";
		}

		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("e"); // Add access controls
		
		if (!$count)
		{
			// Add order by

			$query .= " order by ";

			if ($sort_id1)
			{
				if ($reverse)
				{
					$query .= "s1.sort_column desc, ";
				}
				else
				{
					$query .= "s1.sort_column, ";
				}
			}

			if ($sort_id2)
			{
				if ($reverse)
				{
					$query .= "s2.sort_column desc, ";
				}
				else
				{
					$query .= "s2.sort_column, ";
				}
			}

			if ($reverse)
			{
				$query .= "e.time_updated desc ";
			}
			else
			{
				$query .= "e.time_updated ";
			}

			// add limit
			$query .= " limit $offset, $limit";

			return get_data($query, "entity_row_to_elggstar");
		}
		else
		{
			if ($count = get_data_row($query))
			{
				return $count->total;
			}
		}

		return FALSE;
	}
	
	/**
	 * Returns a viewable list of entities based on the given search criteria.
	 *
	 * @see elgg_view_entity_list
	 * 
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $owner_guid
	 * @param int $limit 
	 * @param string $order_by1 Name of metadata to order by (primary)
	 * @param string $order_by2 Name of metadata to order by (secondary)
	 * @param TRUE|FALSE $reverse Reverse sort?
	 * @param TRUE|FALSE $fullview Whether or not to display the full view (default: TRUE)
	 * @param TRUE|FALSE $viewtypetoggle Whether or not to allow users to toggle to the gallery view. Default: TRUE
	 * @param TRUE|FALSE $pagination Display pagination? Default: TRUE
	 * @return string List of ElggEntities suitable for display
	 */
	function polls_list_entities_from_metadata_multi_order_by_metadata(
				$meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10,
				$order_by1 = "", $order_by2 = "", $reverse = FALSE, $fullview = TRUE,
				$viewtypetoggle = TRUE, $pagination = TRUE) {
		
		$offset = (int) get_input('offset');
		$limit = (int) $limit;
		$count = polls_get_entities_from_metadata_multi_order_by_metadata($meta_array, $entity_type,
					$entity_subtype, $owner_guid, $limit, $offset, $order_by1, $order_by2, $reverse,
					$site_guid, TRUE);
		$entities = polls_get_entities_from_metadata_multi_order_by_metadata($meta_array, $entity_type,
					$entity_subtype, $owner_guid, $limit, $offset, $order_by1, $order_by2, $reverse,
					$site_guid, FALSE);
	
		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview,
										$viewtypetoggle, $pagination);
		
	}


	/**
	 * Returns a viewable list of entities based on the given search criteria.
	 *
	 * @see elgg_view_entity_list
	 * 
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $owner_guid
	 * @param int $limit 
	 * @param TRUE|FALSE $reverse Reverse sort?
	 * @param TRUE|FALSE $fullview Whether or not to display the full view (default: TRUE)
	 * @param TRUE|FALSE $viewtypetoggle Whether or not to allow users to toggle to the gallery view. Default: TRUE
	 * @param TRUE|FALSE $pagination Display pagination? Default: TRUE
	 * @return string List of ElggEntities suitable for display
	 */
	function polls_list_entities_from_metadata_multi_order_by_title(
				$meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10,
				$reverse = FALSE, $fullview = TRUE, $viewtypetoggle = TRUE, $pagination = TRUE,
				$alpha_page = NULL)
	{
		
		$offset = (int) get_input('offset');
		$limit = (int) $limit;

		$count = polls_get_entities_from_metadata_multi_order_by_title($meta_array, $entity_type,
					$entity_subtype, $owner_guid, $limit, $offset, $reverse, $site_guid, TRUE, $alpha_page);

		$entities = polls_get_entities_from_metadata_multi_order_by_title($meta_array, $entity_type,
					$entity_subtype, $owner_guid, $limit, $offset, $reverse, $site_guid, FALSE, $alpha_page);
	
		return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview,
										$viewtypetoggle, $pagination);
		
	}



	/**
	 * Returns an array describing the alphabetical distribution of titles of entities based on the given search criteria.
	 *
	 * @param array $meta_array Array of 'name' => 'value' pairs
	 * @param string $entity_type The type of entity to look for, eg 'site' or 'object'
	 * @param string $entity_subtype The subtype of the entity.
	 * @param int $owner_guid
	 * @param int $site_guid The site to get entities for. Leave as 0 (default) for the current site; -1 for all sites.
	 *
	 * @return array List of pairs, first letter of title (upper case) => number of occurrences of titles beginning
	 * with that letter.  Sparse array (if no titles with that letter then no entry in the array.  Any non alphabetical
	 * titles are counted in an entry named 'other'.
	 */
	function polls_get_title_distibution_of_entities_from_metadata_multi(
				$meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $site_guid = 0)
	{
		global $CONFIG;
		
		if (!is_array($meta_array) || sizeof($meta_array) == 0)
		{
			return FALSE;
		}
		
		$where = array();
		
		$mindex = 1;
		$join = "";

		foreach ($meta_array as $meta_name => $meta_value)
		{
			$meta_n = get_metastring_id($meta_name);
			
			if (!is_array($meta_value))
			{
				$meta_value = array($meta_value);
			}
				
			foreach ($meta_value as $value)
			{
				$meta_v = get_metastring_id($value);
	
				$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid "; 
	
				if ($meta_name != "")
				{
					$where[] = "m{$mindex}.name_id='$meta_n'";
				}
	
				if ($meta_value != "")
				{
					$where[] = "m{$mindex}.value_id='$meta_v'";
				}
	
				$mindex++;
			}
		}
					
		$entity_type = sanitise_string($entity_type);
		$entity_subtype = get_subtype_id($entity_type, $entity_subtype);

		$owner_guid = (int) $owner_guid;
		
		$site_guid = (int) $site_guid;

		if ($site_guid == 0)
		{
			$site_guid = $CONFIG->site_guid;
		}
			
		if ($entity_type != "")
		{
			$where[] = "e.type = '{$entity_type}'";
		}

		if ($entity_subtype)
		{
			$where[] = "e.subtype = {$entity_subtype}";
		}

		if ($site_guid > 0)
		{
			$where[] = "e.site_guid = {$site_guid}";
		}

		if ($owner_guid > 0)
		{
			$where[] = "e.container_guid = {$owner_guid}";
		}
	
		$query = "SELECT IF (upper(left(oe.title, 1)) BETWEEN 'A' AND 'Z', " .
			"upper(left(oe.title, 1)), 'other') AS letter, count(oe.title) AS occurences ";

		$join .= "LEFT JOIN `{$CONFIG->dbprefix}objects_entity` as oe on e.guid = oe.guid ";

		$query .= " from {$CONFIG->dbprefix}entities e {$join} where";


		foreach ($where as $w)
		{
			$query .= " $w and ";
		}

		$query .= get_access_sql_suffix("e"); // Add access controls
		$query .= ' and ' . get_access_sql_suffix("e"); // Add access controls
		
		// Add order by (use length first so that 'other' appears at end of list)
		$query .= " group by letter order by length(letter), letter ";

		$result = get_data($query);

		$distribution_array = array();

		if (is_array($result) && !empty($result))
		{
			foreach ($result as $row)
			{
				$row = (array) $row;

				if (is_array($row) && $row['letter'] && $row['occurences'])
				{
					$distribution_array[$row['letter']] = (int) $row['occurences'];
				}
			}
		}

		return $distribution_array;

	}

	function polls_get_all_candidate_tags_for_a_poll($poll_guid)
	{
		global $CONFIG;
		
		$poll_guid = (int) $poll_guid;
		
		$parent_guid_msid = get_metastring_id('parent_guid');
		$tags_msid = get_metastring_id('tags');
		
		$sql = "SELECT DISTINCT msv.string FROM".
			" {$CONFIG->dbprefix}entities e".
			"	INNER JOIN {$CONFIG->dbprefix}metadata md1 ON e.guid = md1.entity_guid".
			" INNER JOIN {$CONFIG->dbprefix}metastrings msv1 ON md1.value_id = msv1.id".
			" INNER JOIN {$CONFIG->dbprefix}metadata md ON e.guid = md.entity_guid".
			" INNER JOIN {$CONFIG->dbprefix}metastrings msv ON md.value_id = msv.id".
			" WHERE md1.name_id = {$parent_guid_msid}".
			" AND md.name_id = {$tags_msid}".
			" AND msv1.string = {$poll_guid}".
			" ORDER BY msv.string ASC;";
		
		$data = get_data($sql);
		
		$tags = array();
		
		foreach ($data as $row)
		{
			$tags[] = $row->string;
		}
		
		return $tags;
	}
