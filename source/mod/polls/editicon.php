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


	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	// Get the current poll's owner
	if ($container = $item->container_guid)
	{
		elgg_set_page_owner_guid($container);
	}

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner === FALSE || is_null($page_owner))
	{
		$page_owner = $_SESSION['user'];
		elgg_set_page_owner_guid($page_owner->getGUID());
	}

	// set title
	$area2 = elgg_view('polls/breadcrumbs', array('item' => $item, 'extra' => array(
		array('title' => elgg_echo('polls:editicon'), url => ''),
	)));
	
	$area2 .= elgg_view_title(elgg_echo('polls:createicon:header'));


	if (($item) && $item->canEdit() && $item->getSubtype() == 'poll_candidate')
	{
		$area2 .= elgg_view("polls/candidateicon/upload", array('entity' => $item));
		$area2 .= elgg_view("polls/candidateicon/crop", array('entity' => $item));
	}
	else
	{
		$area2 .= elgg_echo("polls:noaccess");
	}

		
	// Get the form and correct canvas area
	$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw the page
	echo elgg_view_page(elgg_echo("polls:editicon"),$body);

