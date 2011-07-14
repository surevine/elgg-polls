<?php
/**
 * Poll candidate icon view
 *
 * @uses $vars['entity'] The entity the icon represents
 * @uses $vars['size']   topbar, tiny, small, medium (default), large, master
 */

$sizes = array('small', 'medium', 'large', 'master', 'tiny');

// Get size
$size = $vars['size'];
if (!in_array($size, $sizes)) {
	$size = "medium";
}

$entity = $vars['entity'];
$guid = $entity->getGUID();

if ($icontime = $entity->icontime)
{
	$icontime = "{$icontime}";
}
else
{
	$icontime = "default";
}

$filehandler = new ElggFile();
$filehandler->owner_guid = $entity->getOwner();
$filehandler->setFilename("polls/" . $guid . $size . ".jpg");

if ($filehandler->exists())
{
	$url = $vars['url'] . "polls/icon/$guid/$size/$icontime.jpg";
}
else
{
	$url = $vars['url'] . 'mod/polls/images/candidate_' . $size . '.gif';
}

echo '<img src="' . $url . '">';
