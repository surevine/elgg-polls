<p>

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

		echo elgg_echo("polls:settings:newpollaccess");

		$newpollaccess = $vars['entity']->newpollaccess;

		if (!$newpollaccess)
		{
			$newpollaccess = 'admins';
		}

		$parameters = array(
								'internalname' => 'params[newpollaccess]',
								'value' => $newpollaccess,
								'options_values' => array(
								'admins' => elgg_echo('polls:settings:admins'),
								'groups' => elgg_echo('polls:settings:groups'),
								'everyone' => elgg_echo('polls:settings:everyone'),
							));

		echo elgg_view("input/pulldown", $parameters);

?>

</p>
