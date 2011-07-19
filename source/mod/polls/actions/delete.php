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

	$item_guid = get_input('item_guid');

	// if they didn't hit the 'delete' button then cancel and go
	// back to viewing the item

	if (!get_input('delete'))
	{
		forward('pg/polls/view/' . $item_guid);
	}

	
	if ($item = get_entity($item_guid))
	{
		if ($item->canEdit())
		{
			if ($item->getSubtype() == 'poll')
			{
				// first delete all candidates under this poll

				$metadata = array(
					'parent_guid' => $item->getGUID(),
				);

				$options = array(
					'types' => 'object',
					'subtypes' => 'poll_candidate',
					'metadata_name_value_pairs' => $metadata
				);

				if ($children = elgg_get_entities_from_metadata($options))
				{
					foreach ($children as $child)
					{
						if (!$child->delete())
						{
							register_error(elgg_echo('polls:delete:failure'));
							forward($_SERVER['HTTP_REFERER']);
						}
					}
				}

				// now delete the poll

				if ($item->delete())
				{
					system_message(elgg_echo('polls:delete:success'));

					forward('pg/polls/owned/' . $_SESSION['user']->username);
					exit;
				}
			}
			else
			{
				// only allowed to delete the candidate if you
				// have write access to the poll

				$poll_guid = $item->parent_guid;
				$poll = get_entity($poll_guid);

				if ($poll && $poll->canEdit())
				{
					// just deleting a candidate

					if ($item->delete())
					{
						system_message(elgg_echo('polls:candidate:delete:success'));

						forward($poll->getURL());
						exit;
					}
				}
			}
		}
	}
	
	register_error(elgg_echo('polls:delete:failure'));
	forward($_SERVER['HTTP_REFERER']);
