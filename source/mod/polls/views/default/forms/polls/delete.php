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


	$item = $vars['entity'];

	$form .= "<p>";

	if ($item->getSubtype() == 'poll')
	{
		$form .= sprintf(elgg_echo("polls:confirmdelete"), $item->title);

		$count = get_entities_from_metadata("parent_guid", $item->guid,
											"object", "poll_candidate", 0, 0, 0, "", 0, TRUE);

		if ($count > 0)
		{
			$form .= "<p>";

			if ($count > 1)
			{
				$form .= sprintf(elgg_echo("polls:confirmdeletesubcandidates"), $count);
			}
			else
			{
				$form .= elgg_echo("polls:confirmdeletesubcandidate");
			}
		}
	}
	else
	{
		$form .= sprintf(elgg_echo("polls:candidate:confirmdelete"), $item->title);
	}


	$form .= "<p>";

	$form .= elgg_view('input/hidden',
						array('internalname' => 'item_guid', 'value' => $item->getGUID()));

/* hack input/submit ignores internalname so we have to do it manually
	$form .= elgg_view('input/submit',
						array('internalname' => 'delete', 'value' => elgg_echo('delete')));
*/

	$form .= '<input type="submit" class="submit_button" name="delete" value="' .
				elgg_echo("delete") . '" />';

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



	$action_url = $vars['url'] . "action/polls/delete";

	echo '<div class="polls_delete">';
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
	echo '</div>';

