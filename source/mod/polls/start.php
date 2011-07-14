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

	/**
	 * Initialise the polls plugin.
	 *
	 */
	function polls_init()
	{
		global $CONFIG;

		elgg_register_library('elgg:polls', elgg_get_plugins_path() . 'polls/lib.php');

		// Set up the menu
		$item = new ElggMenuItem('polls', elgg_echo('polls'), 'polls/world');
		elgg_register_menu_item('site', $item);

		// Extend hover-over menu	
		elgg_extend_view('profile/menu/links','polls/menu');
		elgg_extend_view('metatags', 'polls/metatags');
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('polls', 'polls_page_handler');
		
		// Register a url handler
		register_entity_url_handler('polls_url', 'object', 'poll');
		register_entity_url_handler('polls_url', 'object', 'poll_candidate');
		
		// Register some actions
		register_action("polls/edit", FALSE, $CONFIG->pluginspath . "polls/actions/edit.php");
		register_action("polls/editcandidate", FALSE, $CONFIG->pluginspath . "polls/actions/editcandidate.php");
		elgg_register_action("polls/candidateicon/upload", $CONFIG->pluginspath . "polls/actions/iconupload.php");
		elgg_register_action("polls/candidateicon/crop", $CONFIG->pluginspath . "polls/actions/cropicon.php");
		register_action("polls/delete", FALSE, $CONFIG->pluginspath . "polls/actions/delete.php");
		register_action("polls/vote", FALSE, $CONFIG->pluginspath . "polls/actions/vote.php");
		register_action("polls/manage", FALSE, $CONFIG->pluginspath . "polls/actions/manage.php");
		register_action("polls/bulkupload", FALSE, $CONFIG->pluginspath . "polls/actions/bulkupload.php");
		
		// Extend some views
		elgg_extend_view('css','polls/css');
		elgg_extend_view('js/initialise_elgg','polls/js');
		elgg_extend_view('groups/menu/links', 'polls/menu'); // Add to groups context
		// this view has not been completed and was never activated
		//elgg_extend_view('groups/tool_latest', 'polls/groupprofile_polls'); // Add to groups context
		
		// Register entity type
		register_entity_type('object', 'poll_candidate');
		register_entity_type('object', 'poll');

		// add link to owner block
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'polls_owner_block_menu');

		
		// Run the DB fix script if they haven't run yet
		$plugin_revision = (int) get_plugin_setting('plugin_revision', 'polls');
		
		if ($plugin_revision < 1)	// 1 is the current version
		{
			polls_run_upgrade($plugin_revision);
		}
		
		// add a widget
		//add_widget_type('polls', "Polls", "This is a list of your polls.");


		// Language short codes must be of the form "polls:key"
		// where key is the array key below
		$CONFIG->polls_form = array
		(
				'title' => array('type' => 'text', 'editable' => FALSE),

				'description' => array('type' => 'longtext', 'editable' => TRUE),

				'tags' => array('type' => 'tags', 'editable' => TRUE),


				'voting_type' => array	(
											'type' => 'radio',
											'disabled' => FALSE,
											'default' => 'thumbs',
											'editable' => FALSE,
											'options' => array	(
																	elgg_echo('polls:voting_type:thumbs') => 'thumbs',
																	elgg_echo('polls:voting_type:stars') => 'stars5',
																	elgg_echo('polls:voting_type:custom_options') => 'custom_options',
																	// elgg_echo('polls:rank') => 'rank',
																)
										),

				'star_values' => array('type' => 'text', 'editable' => TRUE, 'default' => ''),

				'vote_options' => array('type' => 'text', 'editable' => FALSE, 'default' => ''),
										
				'vote_option_colours' => array('type' => 'text', 'editable' => TRUE, 'default' => ''),

				'vote_option_values' => array('type' => 'text', 'editable' => FALSE, 'default' => ''),

				'moderated' => array	(
											'type' => 'radio',
											'disabled' => FALSE,
											'default' => 'no',
											'editable' => TRUE,
											'options' => array	(
																	elgg_echo('polls:moderated:no') => 'no',
																	elgg_echo('polls:moderated:yes') => 'yes',
																)
										),

				'revote' => array	(
										'type' => 'radio',
										'disabled' => FALSE,
										'default' => 'yes',
										'editable' => TRUE,
										'options' => array	(
																elgg_echo('polls:revote:yes') => 'yes',
																elgg_echo('polls:revote:no') => 'no',
															)
									),

				'display_order' => array (
											'type' => 'radio',
											'disabled' => FALSE,
											'default' => 'newest',
											'editable' => TRUE,
											'options' => array	(
																	elgg_echo('polls:sort:az') => 'az',
																	elgg_echo('polls:sort:highestscore') => 'highestscore',
																	elgg_echo('polls:sort:lowestscore') => 'lowestscore',
																	elgg_echo('polls:sort:newest') => 'newest',
																	elgg_echo('polls:sort:oldest') => 'oldest',
																	elgg_echo('polls:sort:newestvote') => 'newestvote',
																	elgg_echo('polls:sort:oldestvote') => 'oldestvote',
																	elgg_echo('polls:sort:mostvotes') => 'mostvotes',
																	elgg_echo('polls:sort:leastvotes') => 'leastvotes',
																),
											'special' => 'display_order'
										 ),

				'redirect_on_vote' => array	(
												'type' => 'radio',
												'disabled' => FALSE,
												'default' => 'no',
												'editable' => TRUE,
												'options' => array	(
																		elgg_echo('polls:redirect_on_vote:no') => 'no',
																		elgg_echo('polls:redirect_on_vote:yes') => 'yes',
																	)
											),

				'category1'			=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category1values'	=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category2'			=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category2values'	=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category3'			=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category3values'	=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category4'			=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),
				'category4values'	=> array('type' => 'text', 'editable' => TRUE, 'default' => ''),

				'active_name'	=> array('type' => 'text', 'editable' => TRUE, 'default' => elgg_echo('polls:active_name:default')),
				'promoted_name'	=> array('type' => 'text', 'editable' => TRUE, 'default' => elgg_echo('polls:promoted_name:default')),
				'rejected_name'	=> array('type' => 'text', 'editable' => TRUE, 'default' => elgg_echo('polls:rejected_name:default')),
				'proposed_name'	=> array('type' => 'text', 'editable' => TRUE, 'default' => elgg_echo('polls:proposed_name:default')),
				'active_description'	=> array('type' => 'longtext', 'editable' => TRUE, 'default' => elgg_echo('polls:candidate:description:active')),
				'promoted_description'	=> array('type' => 'longtext', 'editable' => TRUE, 'default' => elgg_echo('polls:candidate:description:promoted')),
				'rejected_description'	=> array('type' => 'longtext', 'editable' => TRUE, 'default' => elgg_echo('polls:candidate:description:rejected')),
				'proposed_description'	=> array('type' => 'longtext', 'editable' => TRUE, 'default' => elgg_echo('polls:candidate:description:proposed')),


				'access_id' => array	(
											'type' => 'access',
											'disabled' => FALSE,
											'editable' => TRUE,
											'default' => 1,
											'special' => 'access_id'
										),

				'write_access_id' => array( 'type' => 'access', 'editable' => TRUE, 'special' => 'write_access_id'),
		);


		// Language short codes must be of the form "polls:candidate:key"
		// where key is the array key below
		$CONFIG->candidate_form = array
		(
			'title' => array('type' => 'text', 'editable' => FALSE),

			'description' => array('type' => 'longtext', 'editable' => TRUE),

			'tags' => array('type' => 'tags', 'editable' => TRUE),

			// no access as candidates inherit it from the poll
		);


	}
	
	function polls_url($entity)
	{
		global $CONFIG;
		return $CONFIG->url . "pg/polls/view/{$entity->guid}";
	}


	/**
	 * Add owner block link
	 */
	function polls_owner_block_menu($hook, $type, $return, $params)
	{
		if (elgg_instanceof($params['entity'], 'group'))
		{
			$group = $params['entity'];

			$url = 'polls/owned/' . $params['entity']->username;
			$label = elgg_echo('polls:group');

			if (isset($group->group_type))
			{
				// check for a typed group (implemented by separate Typed Groups plugin)
				$types = elgg_trigger_plugin_hook('typed_groups:get_group_types', 'all', NULL, array());
	 
				if (!is_null($types))
				{
					$type = $types[$group->group_type];

					if (!is_null($type))
					{
						$label = sprintf(elgg_echo('polls:group:typed'), $type['name']);
					}
				}
			}

			$item = new ElggMenuItem('polls:group', $label, $url);
			$return[] = $item;
		}

		return $return;
	}

	
	/**
	 * Sets up submenus for the polls system.  Triggered on pagesetup.
	 *
	 */
	function polls_submenus()
	{
		global $CONFIG;

		$page_owner = page_owner_entity();
		$context = get_context();

		if (isloggedin() && ($context == "polls" || $context == "polls_manage"))
		{
			if (page_owner() == $_SESSION['user']->getGUID())
			{
				$url = 'polls/owned/' . $_SESSION['user']->username;
				$item = new ElggMenuItem('polls:1st:mine', elgg_echo("polls:mine"), $url);
				elgg_register_menu_item('page', $item);

				$url = 'polls/world';
				$item = new ElggMenuItem('polls:1st:all', elgg_echo("polls:all"), $url);
				elgg_register_menu_item('page', $item);
			}
		}
	}


	function polls_context_sensitive_menus($command, $item_guid)
	{
		global $CONFIG;

		$entity = get_entity($item_guid);

		if (!$entity)
		{
			return;
		}

		if ($entity->getSubtype() == 'poll')
		{
			$url = "polls/view/{$item_guid}";
			$item = new ElggMenuItem('polls:view', elgg_echo("polls:label:view"), $url);
			elgg_register_menu_item('page', $item);

			if ($entity->canEdit())
			{
				// Only allow admin or owner to actually edit the poll
				if (isadminloggedin() || ($entity->getOwner() == get_loggedin_userid()))
				{
					$url = "polls/edit/{$item_guid}";
					$item = new ElggMenuItem('polls:edit', elgg_echo("polls:label:edit"), $url);
					elgg_register_menu_item('page', $item);
				}

				$url = "polls/manage/{$item_guid}";
				$item = new ElggMenuItem('polls:manage', elgg_echo("polls:manage"), $url);
				elgg_register_menu_item('page', $item);

				$url = "polls/delete/{$item_guid}";
				$item = new ElggMenuItem('polls:delete', elgg_echo("polls:delete"), $url);
				elgg_register_menu_item('page', $item);

				$url = "polls/bulkupload/{$item_guid}";
				$item = new ElggMenuItem('polls:bulkupload', elgg_echo("polls:bulkupload"), $url);
				elgg_register_menu_item('page', $item);
			}

			$url = "polls/history/{$item_guid}";
			$item = new ElggMenuItem('polls:history', elgg_echo("polls:label:history"), $url);
			elgg_register_menu_item('page', $item);

			$url = "polls/alltags/{$item_guid}";
			$item = new ElggMenuItem('polls:tags', elgg_echo("polls:viewalltags"), $url);
			elgg_register_menu_item('page', $item);
		}
		else if ($entity->getSubtype() == 'poll_candidate')
		{
			$url = "polls/view/{$item_guid}";
			$item = new ElggMenuItem('polls:candidate:view', elgg_echo("polls:label:candidate:view"), $url);
			elgg_register_menu_item('page', $item);

			if ($entity->canEdit())
			{
				$url = "polls/edit/{$item_guid}";
				$item = new ElggMenuItem('polls:candidate:edit', elgg_echo("polls:label:candidate:edit"), $url);
				elgg_register_menu_item('page', $item);

				$url = "polls/editicon/{$item_guid}";
				$item = new ElggMenuItem('polls:editicon', elgg_echo("polls:editicon"), $url);
				elgg_register_menu_item('page', $item);
			}

			// only allowed to delete the candidate if you
			// have write access to the poll

			$poll_guid = $entity->parent_guid;
			$poll = get_entity($poll_guid);

			if ($poll && $poll->canEdit())
			{
				$url = "polls/delete/{$item_guid}";
				$item = new ElggMenuItem('polls:candidate:delete', elgg_echo("polls:candidate:delete"), $url);
				elgg_register_menu_item('page', $item);
			}

			$url = "polls/history/{$item_guid}";
			$item = new ElggMenuItem('polls:candidate:history', elgg_echo("polls:label:candidate:history"), $url);
			elgg_register_menu_item('page', $item);
		}

		// load any other submenus items
		trigger_plugin_hook('polls:submenu', $entity->getSubtype(),
								array('command' => $command, 'item' => $entity));
	}



	/**
	 * Polls page handler.
	 *
	 * @param array $page
	 */
	function polls_page_handler($page)
	{
		global $CONFIG;

		if (isset($page[0]))
		{
			elgg_push_breadcrumb(elgg_echo('polls'), 'polls/world');

			$command = $page[0];

			// generate submenus as appropriate
			if (isset($page[1]))
			{
				$item_guid = $page[1];
				polls_context_sensitive_menus($command, $item_guid);
			}


			// See what context we're using
			switch($command)
			{
				case "new" :
					include($CONFIG->pluginspath . "polls/new.php");
					break;

				case "newcandidate" :
					include($CONFIG->pluginspath . "polls/newcandidate.php");
					break;

				case "world":
					include($CONFIG->pluginspath . "polls/world.php");
					break;

				case "owned" :
					// Owned by a user

					if (isset($page[1]))
					{
						set_input('username', $page[1]);
					}

					include($CONFIG->pluginspath . "polls/index.php");	
					break;

				case "delete" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					include($CONFIG->pluginspath . "polls/delete.php");
					break;

				case "bulkupload" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					include($CONFIG->pluginspath . "polls/bulkupload.php");
					break;

				case "edit" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}
						
					include($CONFIG->pluginspath . "polls/edit.php");
					break;

				case "editicon" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}
						
					include($CONFIG->pluginspath . "polls/editicon.php");
					break;

				case "view" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					if (isset($page[2]))
					{
						set_input('view_type', $page[2]);
					}

					include($CONFIG->pluginspath . "polls/view.php");
					break;

				case "manage" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					if (isset($page[2]))
					{
						set_input('view_type', $page[2]);
					}

					include($CONFIG->pluginspath . "polls/manage.php");
					break;

				case "history" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					include($CONFIG->pluginspath . "polls/history.php");
					break; 				

				case "icon" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					if (isset($page[2]))
					{
						set_input('size', $page[2]);
					}

					include($CONFIG->pluginspath . "polls/icon.php");
					break;
					
				case "alltags" :
					if (isset($item_guid))
					{
						set_input('item_guid', $item_guid);
					}

					include($CONFIG->pluginspath . "polls/alltags.php");
					break;
					
				default:
					// do nothing
					break;
			}

			// see if anything else wants to handle it
			trigger_plugin_hook('polls:pagehandler', 'poll', array('page_array' => $page));
		}
		
	}


	
	/**
	 * Extend permissions checking to extend can-edit for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function polls_write_permission_check($hook, $entity_type, $returnvalue, $params)
	{
		$user = $params['user'];
		$item = $params['entity'];


		// got a user?

		if ($user)
		{
			// if a poll, check if we have write access

			if ($item && $item->getSubtype() == 'poll')
			{
				// hack - get function (overloaded ->) doesn't cope if the
				// metadata has the value 0, which this could have, instead
				// use the manual api
				//$write_permission = $item->write_access_id;
				$write_permission_array = get_metadata_byname($item->getGUID(), "write_access_id");
				$write_permission = $write_permission_array["value"];

				if (!is_null($write_permission))
				{
					$list = get_access_array($user->guid);
						
					if ($write_permission != 0 && in_array($write_permission, $list))
					{
						return TRUE;
					}
				}
			}


			// if a candidate, check if we have write access to its parent poll

			if ($item && $item->getSubtype() == 'poll_candidate')
			{
				// get poll which contains this candidate

				$poll_guid = $item->parent_guid;
				$poll = get_entity($poll_guid);

				// if the user has write permission on the poll, then they
				// automatically have write permission on the candidates

				if ($poll && $poll->canEdit())
				{
					return TRUE;
				}
			}
		}

		return $returnvalue;
	}


	// allow users to update the cached vote totals in candidates, without allowing
	// them to edit anything else in the candidate - needed when they vote for a candidate

	function polls_write_permission_check_metadata($hook, $entity_type, $returnvalue, $params)
	{
		$user = $params['user'];
		$item = $params['entity'];
		$metadata = $params['metadata'];

		if (!$returnvalue && $item && $item->getSubtype() == 'poll_candidate')
		{
			$name = $metadata->name;

			if(preg_match('/^votes_count/', $name) ||
				$name == "votes_total" ||
				$name == "votes_time" ||
				$name == "votes_score" ||
				strpos($name, "votes_count_category") === 0 ||
				strpos($name, "votes_total_category") === 0)
			{
				return TRUE;
			}
		}

		return $returnvalue;
	}
	
	// write permission plugin hooks
	register_plugin_hook('permissions_check', 'object', 'polls_write_permission_check');
	register_plugin_hook('permissions_check:metadata', 'object',
							'polls_write_permission_check_metadata');


	/**
	 * This function will run any db scripts which need to be run if the current
	 * version is too low.
	 * @param $current_revision
	 * @return unknown_type
	 */
	function polls_run_upgrade($current_revision)
	{
		global $CONFIG;
		
		$dbprefix = $CONFIG->dbprefix;
		
		switch ($current_revision)
		{
			case 0:
				/**
				 * This changes all the text vote annotations to integer
				 */
				$sql = <<<SQL
					UPDATE {$dbprefix}annotations AS a
					SET value_type = "integer"
					WHERE a.value_type = "text"
					AND (
						SELECT est.subtype
						FROM {$dbprefix}entities AS e
						INNER JOIN {$dbprefix}entity_subtypes AS est ON e.subtype = est.id
						WHERE e.guid = a.entity_guid
					) = "poll_candidate"
					AND (
						SELECT ms.string
						FROM {$dbprefix}metastrings ms
						WHERE ms.id = a.name_id
					) IN (
						"poll_stars5",
						"poll_thumbs",
						"poll_stars5_category1",
						"poll_stars5_category2",
						"poll_stars5_category3",
						"poll_stars5_category4",
						"poll_stars5_category5"
					);
SQL;
	
				$link = get_db_link('write');

				if (execute_query($sql, $link))
				{
					$current_revision = 1;
				}
				else
				{
					break;	// Stop executing the update script
				}
		}
		
		set_plugin_setting('plugin_revision', $current_revision, 'polls');
	}
	
	// Make sure the polls initialisation function is called on initialisation
	register_elgg_event_handler('init','system','polls_init');
	register_elgg_event_handler('pagesetup','system','polls_submenus');
