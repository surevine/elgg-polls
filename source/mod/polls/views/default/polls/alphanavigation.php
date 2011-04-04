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

	$baseurl = $vars['baseurl'];
	$alpha_page = $vars['alpha_page'];
	$distribution = $vars['distribution'];

	echo '<div class="polls_alpha_navigation">';
	echo '<ul>';
	
	foreach (range('A','Z') as $c)
	{
		$thisurl = url_add_var($baseurl, "alpha=" . $c);
	
		if ($alpha_page == $c)
		{
			echo '<li><a class="current" href="' . $thisurl . '">' . $c . '</a></li>';
		}
		else if ($distribution[$c])
		{
			echo '<li><a href="' . $thisurl . '">' . $c . '</a></li>';
		}
		else
		{
			echo '<li><a class="empty" href="' . $thisurl . '">' . $c . '</a></li>';
		}
	}
	
	$thisurl = url_add_var($baseurl, "alpha=other");
	
	if ($alpha_page == "other")
	{
		echo '<li><a class="current" href="' . $thisurl . '">&nbsp;' .
				elgg_echo("polls:paginate:others") . '&nbsp;</a></li>';
	}
	else if ($distribution['other'])
	{
		echo '<li><a href="' . $thisurl . '">&nbsp;' . elgg_echo("polls:paginate:others") .
				'&nbsp;</a></li>';
	}
	else
	{
		echo '<li><a class="empty" href="' . $thisurl . '">&nbsp;' .
				elgg_echo("polls:paginate:others") . '&nbsp;</a></li>';
	}
	
	echo '</ul>';
	echo '</div>';
