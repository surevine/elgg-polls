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


	$item = $vars['item'];
	$performed_by = get_entity($item->subject_guid);
	$object = get_entity($item->object_guid);
	
	// set the required url to the user who carried out the action
	$who = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";

	$what = "<a href=\"{$object->getURL()}\">{$object->title}</a>";

	// get the container
	$container = get_entity($object->container_guid);


	if ($container instanceof ElggGroup)
	{
		$group = "<a href=\"" . $container->getURL() . "\">" . $container->name . "</a>";
		$string = sprintf(elgg_echo("polls:river:create:group"), $who, $what, $group);
	}
	else
	{
		$string = sprintf(elgg_echo("polls:river:create"), $who, $what);
	}


	echo $string;
