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


	$form = "";


	$request = $vars['request'];

	foreach ($request as $name => $value)
	{
		$form .= elgg_view('input/hidden', array('internalname' => $name, 'value' => $value));
	}

	$polls_guid = (int)$request['polls_guid'];
	$poll = get_entity($polls_guid);


	for ($x = 1; $x <= 4; $x++)
	{
		$attribute_name = 'category' . $x;
		$vote_count_metadata_name = "votes_count_category" . $x;

		$old = $poll->$attribute_name;
		$new = $request[$attribute_name];

		$votes = $poll->$vote_count_metadata_name;

		if ($old != $new && $votes > 0)
		{
			$form .= "<p>";

			if ($old == "")
			{
				// adding a category
				$form .= sprintf(elgg_echo('polls:category:confirm:create'), $new, $votes);
				
			}
			else if ($new == "")
			{
				// removing a category
				$form .= sprintf(elgg_echo('polls:category:confirm:delete'), $old, $votes);
			}
			else
			{
				// renaming a category
				$form .= sprintf(elgg_echo('polls:category:confirm:rename'), $old, $votes);
			}

			$form .= "<p>";

			$parameters = array
				(
					'internalname' => 'keepordelete_category' . $x,
					'value' => 'keep',
					'options' => array
						(
							elgg_echo('polls:category:keep') => 'keep',
							elgg_echo('polls:category:delete') => 'delete',
						),
				);

			$form .= elgg_view("input/radio", $parameters);
		}
	}



/* hack input/submit ignores internalname so we have to do it manually
	$form .= elgg_view('input/submit', array('internalname' => 'save', 'value' => elgg_echo('save')));
*/

	$form .= '<input type="submit" class="submit_button" name="save" value="' .
				elgg_echo("save") . '" />';

	$form .= "&nbsp;";

/* hack input/button ignores class so we have to do it manually

	$form .= elgg_view('input/button', array(	'internalname' => 'cancel',
							'value' => elgg_echo('cancel'),
							'class' => 'cancel_button',
							'type' => 'button',
							'js' => 'onclick="javascript:history.back()"',
							));
*/
	$form .= '<input type="button" class="cancel_button" name="cancel" " .
				"onclick="javascript:history.back()" value="' .
			elgg_echo("cancel") . '" />';


	$form .= "</p>";



	$action_url = $vars['url'] . "action/polls/edit";

	echo '<div class="contentWrapper">';
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
	echo '</div>';

