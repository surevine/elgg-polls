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
?>

<div class="contentWrapper">
<!-- grab the required js for icon cropping -->
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/polls/views/default/js/jquery.imgareaselect-0.4.2.js"></script>

<p>
	<?php echo elgg_echo('polls:iconinstructions'); ?>
</p>

<div id="current_user_avatar">

	<label>
		<?php echo elgg_echo('polls:currenticon'); ?>
	</label>

<?php 

	$icon = $item->getIcon('medium');
	echo "<img src=\"{$icon}\" alt=\"icon\" />";

?>

</div>

<div id="profile_picture_form">

<?php

	$form = "";

	$form .= "<p>";
	$form .= "<label>";
	$form .= elgg_echo("polls:editicon");
	$form .= "</label>";

	$form .= "<br />";
	
	$form .= elgg_view("input/file", array('internalname' => 'candidateicon'));

	$form .= "<br />";

	$form .= elgg_view('input/hidden', array('internalname' => 'item_guid', 'value' => $item_guid));

	$form .= elgg_view('input/submit', array('value' => elgg_echo('upload')));

	$form .= "</p>";

	$action_url = $vars['url'] . "action/polls/iconupload";
	echo elgg_view('input/form', array('action' => $action_url, 'body' => $form,
					'enctype' => 'multipart/form-data'));
?>

</div>

<?php	
	// check if we have a custom icon

	$icontime = $item->icontime;

	if ($icontime && $icontime != "default")
	{
		$master_image = $vars['url'] . "pg/polls/icon/$item_guid/master/$icontime.jpg";
?>
		<div id="profile_picture_croppingtool">	

			<label>
				<?php echo elgg_echo('polls:iconcroppingtool'); ?>
			</label>

			<br />

			<p>	
				<?php
					echo elgg_echo("polls:iconcroppingtool:instructions");
				?>
			</p>

		<script>

			//function to display a preview of the users cropped section
			function preview(img, selection) {
			var origWidth = $("#user_avatar").width(); //get the width of the users master photo
			var origHeight = $("#user_avatar").height(); //get the height of the users master photo
			var scaleX = 100 / selection.width; 
			var scaleY = 100 / selection.height; 
			$('#user_avatar_preview > img').css({ 
				width: Math.round(scaleX * origWidth) + 'px', 
				height: Math.round(scaleY * origHeight) + 'px', 
				marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
				marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
			 }); 
			} 
			
			//variables for the newly cropped avatar
			var $x1, $y1, $x2, $y2, $w, $h;
			
			function selectChange(img, selection){

				//populate the form with the correct coordinates once a user has cropped their image
				document.getElementById('x_1').value = selection.x1;
				document.getElementById('x_2').value = selection.x2;
				document.getElementById('y_1').value = selection.y1;
				document.getElementById('y_2').value = selection.y2;

			 }
			 
			$(document).ready(function () {

				//get and set the coordinates
				$x1 = $('#x1');
				$y1 = $('#y1');
				$x2 = $('#x2');
				$y2 = $('#y2');
				$w = $('#w');
				$h = $('#h');


				$('<div id="user_avatar_preview"><img src="<?php echo $master_image; ?>" /></div>') 
				.insertAfter($('#user_avatar'));

				$('<div id="user_avatar_preview_title"><label>Preview</label></div>').insertBefore($('#user_avatar_preview'));

			}); 
			
			$(window).load(function () { 

				//this produces the coordinates
				$('#user_avatar').imgAreaSelect({ selectionOpacity: 0, onSelectEnd: selectChange });
				//show the preview
				$('#user_avatar').imgAreaSelect({ aspectRatio: '1:1', onSelectChange: preview });

			});
		 
		</script>

			<p>
				<img id="user_avatar" src="<?php echo $master_image; ?>"
						alt="<?php echo elgg_echo("polls:icon"); ?>" />
			</p>

			<div class="clearfloat">
			</div>

<?php
			$form = "";

			$form .= elgg_view('input/hidden', array('internalname' => 'item_guid', 'value' => $item_guid));

			$form .= elgg_view('input/hidden',
							array('internalname' => 'x_1', 'value' => $item->x1, 'js' => 'id="x_1"'));
			$form .= elgg_view('input/hidden',
							array('internalname' => 'x_2', 'value' => $item->x2, 'js' => 'id="x_2"'));
			$form .= elgg_view('input/hidden',
							array('internalname' => 'y_1', 'value' => $item->y1, 'js' => 'id="y_1"'));
			$form .= elgg_view('input/hidden',
							array('internalname' => 'y_2', 'value' => $item->y2, 'js' => 'id="y_2"'));

			$form .= elgg_view('input/submit', array('value' => elgg_echo('polls:createicon')));

			$action_url = $vars['url'] . "action/polls/cropicon";
			echo elgg_view('input/form', array('action' => $action_url, 'body' => $form));
?>
		</div>
<?php
	}
?>

<div class="clearfloat">
</div>
</div>

