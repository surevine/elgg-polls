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


	function draw_input_box($internal_name, $type, $disabled, $entity, $default_value, $options)
	{
		$box = "";

		$box .= "<p>";
		$box .= "<label>";
		$box .= elgg_echo("polls:candidate:{$internal_name}");
		$box .= "</label>";
		$box .= "<br />";

		if ($entity)
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
			$parameters['options'] = $options;
		}

		$box .= elgg_view("input/{$type}", $parameters);

		$box .= "</p>";

		return $box;
	}

	$entity = $vars['entity'];

	$parent_guid = get_input('parent_guid');
	$container_guid = get_input('container_guid');

	if (!$container_guid)
	{
		$container_guid = page_owner();
	}

	$form = "";

	if (is_array($vars['config']->candidate_form) && sizeof($vars['config']->candidate_form) > 0)
	{
		foreach ($vars['config']->candidate_form as $shortname => $details)
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

				$form .= draw_input_box($shortname, $type, $disabled, $entity, $default, $options);
			}
		}
	}

	$form .= "<p>";

	if ($entity)
	{
		$form .= elgg_view('input/hidden',
							array('internalname' => 'candidate_guid', 'value' => $entity->getGUID()));
	}

	if ($container_guid)
	{
		$form .= elgg_view('input/hidden',
							array('internalname' => 'container_guid', 'value' => $container_guid));
	}

	if ($entity)	
	{
		$form .= elgg_view('input/hidden',
							array('internalname' => 'parent_guid', 'value' => $entity->parent_guid));
		$form .= elgg_view('input/hidden',
							array('internalname' => 'owner_guid', 'value' => $entity->owner_guid));
	}
	else
	{
		$form .= elgg_view('input/hidden',
							array('internalname' => 'parent_guid', 'value' => $parent_guid));
		$form .= elgg_view('input/hidden',
							array('internalname' => 'owner_guid', 'value' => page_owner()));
	}

	$form .= elgg_view('input/submit', array('value' => elgg_echo('save')));

	$form .= "</p>";


	$action_url = $vars['url'] . "action/polls/editcandidate";

	echo '<div class="contentWrapper">';
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
	echo '</div>';
