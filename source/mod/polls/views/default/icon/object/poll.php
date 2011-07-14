<?php
/**
 * Poll icon view
 *
 * @uses $vars['entity'] The entity the icon represents
 * @uses $vars['size']   topbar, tiny, small, medium (default), large, master
 */

$sizes = array('small', 'medium', 'large', 'tiny');

// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

echo '<img src="' . $vars['url'] . 'mod/polls/images/poll_' . $vars['size'] . '.gif">';
