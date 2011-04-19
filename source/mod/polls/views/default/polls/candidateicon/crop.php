<?php
/**
 * Avatar cropping view
 *
 * @uses vars['entity']
 */

?>
<div id="avatar-croppingtool" class="mtl ptm">
	<label><?php echo elgg_echo('polls:iconcroppingtool'); ?></label>
	<br />
	<p>
		<?php echo elgg_echo("polls:iconcroppingtool:instructions"); ?>
	</p>
	<?php echo elgg_view_form('polls/candidateicon/crop', array(), $vars); ?>
</div>
