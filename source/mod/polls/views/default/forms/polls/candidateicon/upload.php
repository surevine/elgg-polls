<?php
/**
 * Avatar upload form
 * 
 * @uses $vars['entity']
 */

echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['entity']->guid));
?>
<div>
	<label><?php echo elgg_echo("polls:editicon"); ?></label><br />
	<?php echo elgg_view("input/file",array('name' => 'candidateicon')); ?>
<br />
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('upload'))); ?>
</div>
