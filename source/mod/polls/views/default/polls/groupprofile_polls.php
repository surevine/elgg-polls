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

    $options = array(
        'types' => 'object',
		'subtypes' => 'poll',
		'container_guids' => page_owner(),
		'limit' => 5,
		'full_view' => FALSE
	);

	$objects = elgg_list_entities($options);

	echo $objects;
	
?>
</div>
