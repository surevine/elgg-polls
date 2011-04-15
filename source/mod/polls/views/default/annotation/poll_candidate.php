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

	$annotation = $vars['annotation'];
	$entity = get_entity($annotation->entity_guid);
	
	$icon = elgg_view(
		"annotation/icon", array	(
										'annotation' => $vars['annotation'],
										'size' => 'small',
									)
	);
	
	$owner_guid = $annotation->owner_guid;
	$owner = get_entity($owner_guid);
			
	$rev = sprintf(elgg_echo('polls:revision'), 
		elgg_view_friendly_time($annotation->time_created),
		
		"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
	);
	
	$link = $entity->getURL() . "?rev=" . $annotation->id;
	
	$info = <<< END
	
<div><a href="$link">{$entity->title}</a></div>
<div>$rev</div>
END;

	echo elgg_view_image_block($icon, $info);
