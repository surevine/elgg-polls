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
?>
	<p>
		<?php echo elgg_echo("polls:num"); ?>
		<input type="text" name="params[polls_num]"
				value="<?php echo htmlentities($vars['entity']->polls_num); ?>" />	
	</p>
