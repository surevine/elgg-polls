<style type="text/css">
#polls_widget .pagination
{
	display:none;
}
</style>
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


	$num_display = (int) $vars['entity']->polls_num;

	$polls = list_entities("object", "poll", page_owner(), $num_display, FALSE);

	echo "<div id=\"polls_widget\">" . $polls . "</div>";

