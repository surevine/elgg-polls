<div id="group_polls_widget">
<h2><?php echo elgg_echo("polls:groupprofile"); ?></h2>

/**
	* Polls - a polling module for Elgg
	*
	* @package polls
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	* @author Surevine Limited <elgg@surevine.com>
	* @copyright Surevine Limited 2010
	* @link http://www.surevine.com/
*/

<?php

	$objects = list_entities("object", "poll", page_owner(), 5, FALSE);
	echo $objects;
	
?>
</div>
