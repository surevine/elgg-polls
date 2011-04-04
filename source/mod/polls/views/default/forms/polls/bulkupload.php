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

	$item_guid = get_input('item_guid');
	$item = get_entity($item_guid);

	echo '<div class="contentWrapper">';

	echo "<p>";
	echo elgg_echo('polls:bulkupload:instructions');
	echo "</p>";

	$form = "";

	$form .= "<p>";
	$form .= "<label>";
	$form .= elgg_echo("polls:bulkupload:file");
	$form .= "</label>";

	$form .= "<br />";
	
	$form .= elgg_view("input/file", array('internalname' => 'bulkuploadfile'));

	$form .= "<br />";

	$form .= elgg_view('input/hidden', array('internalname' => 'item_guid', 'value' => $item_guid));

	$form .= elgg_view('input/submit', array('value' => elgg_echo('upload')));

	$form .= "</p>";

	$action_url = $vars['url'] . "action/polls/bulkupload";
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form,
						'enctype' => 'multipart/form-data'));

	echo "</div>";
