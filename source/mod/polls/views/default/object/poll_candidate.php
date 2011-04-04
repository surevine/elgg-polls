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

	if ($vars['full'])
	{
		echo elgg_view("polls/candidateprofile", $vars);
	}
	else
	{
		if (get_input('search_viewtype') == "gallery")
		{
			echo elgg_view('polls/candidategallery', $vars);
		}
		else
		{
			echo elgg_view("polls/candidatelisting", $vars);
		}
	}
