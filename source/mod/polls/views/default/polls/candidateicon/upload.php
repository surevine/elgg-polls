<?php
/**
 * Avatar upload view
 *
 * @uses $vars['entity']
 */

$user_avatar = $vars['entity']->getIconURL('medium');

$current_label = elgg_echo('polls:currenticon');

$form_params = array(
	'enctype' => 'multipart/form-data',
);
$upload_form = elgg_view_form('polls/candidateicon/upload', $form_params, $vars);

?>

<p class="mtm">
	<?php echo elgg_echo('polls:iconinstructions'); ?>
</p>

<?php

$image = <<<HTML
<div id="current-user-avatar" class="mrl prl">
	<label>$current_label</label><br />
	<img src="$user_avatar" alt="avatar" />
</div>
HTML;

$body = <<<HTML
<div id="avatar-upload">
	$upload_form
</div>
HTML;

echo elgg_view_image_block($image, $upload_form);
