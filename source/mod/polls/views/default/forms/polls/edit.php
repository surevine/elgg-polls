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


	function draw_input_box($internal_name, $type, $disabled, ElggEntity $entity,
							$default_value, $options)
	{
		// If the input type is hidden then don't show the label.
		$show_label = ($type != 'hidden');
		
		$box = "";

		if($show_label)
		{
			$box .= "<p>";
			$box .= "<label>";
			$box .= elgg_echo("polls:{$internal_name}");
			$box .= "</label>";
			$box .= "<br />";
		}
		
		if ($_SESSION['forwarded_form_data'][$internal_name])
		{
			$value = $_SESSION['forwarded_form_data'][$internal_name];
		}
		else if ($entity && !is_null($entity->getMetaData($internal_name)))
		{
			$value = $entity->$internal_name;
		}
		else
		{
			$value = $default_value;
		}

		$parameters = array(	'internalname' => $internal_name,
					'value' => $value,
					'disabled' => $disabled);

		if ($options)
		{
			// input/access has changed to only use options_values

			if ($type == 'access')
			{
				$parameters['options_values'] = $options;
			}
			else
			{
				$parameters['options'] = $options;
			}
		}

		$box .= elgg_view("input/{$type}", $parameters);

		if($show_label)
		{
			$box .= "</p>";
		}

		return $box;
	}

	$entity = $vars['entity'];

	$container_guid = get_input('container_guid');

	if (!$container_guid)
	{
		$container_guid = page_owner();
	}

	$form = "";

	if (is_array($vars['config']->polls_form) && sizeof($vars['config']->polls_form) > 0)
	{
		foreach ($vars['config']->polls_form as $shortname => $details)
		{
			$disabled = FALSE;
			$default = "";
			$options = NULL;

			if ($details['type'])
			{
				$type = $details['type'];

				if ($details['disabled'])
				{
					$disabled = $details['disabled'];
				}

				if ($details['editable'] == FALSE && $entity)
				{
					$disabled = TRUE;
				}

				if ($details['default'])
				{
					$default = $details['default'];
				}

				if ($details['options'])
				{
					$options = $details['options'];
				}


				// any special overrides?

				if ($details['special'])
				{
					if ($details['special'] == "access_id")
					{
						$container = get_entity($container_guid);

						if ($container instanceof ElggGroup)
						{
							// non-members can't write to a group so
							// don't allow 'logged-in' or 'public' access

							$options = get_write_access_array();
							unset($options[ACCESS_LOGGED_IN]);
							unset($options[ACCESS_PUBLIC]);
							
							// set default to be group access for this group
							$default = $container->group_acl;
						}
					}
					else if ($details['special'] == "write_access_id")
					{
						$options = get_write_access_array();
						unset($options[ACCESS_FRIENDS]);

						$container = get_entity($container_guid);

						if ($container instanceof ElggGroup)
						{
							// non-members can't write to a group so
							// don't allow 'logged-in' or 'public' access

							unset($options[ACCESS_LOGGED_IN]);
							unset($options[ACCESS_PUBLIC]);
						}
					}
					else if ($details['special'] == "display_order")
					{
						// display order 'za' is obsolete, so map any existing
						// 'za' orders to 'az'
						if ($entity && $entity->$shortname == "za")
						{
							$entity->$shortname = "az";
						}
					}
				}

				$form .= draw_input_box($shortname, $type, $disabled, $entity, $default, $options);
			}
		}
	}

	unset($_SESSION['forwarded_form_data']);

	$form .= "<p>";

	if ($entity)
	{
		$form .= elgg_view('input/hidden',
						array('internalname' => 'polls_guid', 'value' => $entity->getGUID()));
	}

	if ($container_guid)
	{
		$form .= elgg_view('input/hidden',
						array('internalname' => 'container_guid', 'value' => $container_guid));
	}

	if ($entity)	
	{
		$form .= elgg_view('input/hidden',
						array('internalname' => 'owner_guid', 'value' => $entity->owner_guid));
	}
	else
	{
		$form .= elgg_view('input/hidden',
						array('internalname' => 'owner_guid', 'value' => page_owner()));
	}


	$form .= elgg_view('input/submit', array('value' => elgg_echo('save')));


	$form .= "</p>";


	$action_url = $vars['url'] . "action/polls/edit";

	echo '<div class="contentWrapper">';
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
	echo '</div>';

