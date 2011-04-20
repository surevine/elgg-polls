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


	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/lib.php");

	// Output body
	$entity = $vars['entity'];
	
	$rev = (int)get_input('rev');
	
	if ($rev)
	{	
		$latest = get_annotation($rev);	
	}
	else
	{
		$latest = $entity->getAnnotations('poll_candidate', 1, 0, 'desc');

		if ($latest)
		{
			$latest = $latest[0];
		}
	}
	
?>	
	<div class="contentWrapper">
	<div id="polls_candidate">
	
<?php	
	if ($entity)
	{
		$parent_guid = $entity->parent_guid;
		$poll = get_entity($parent_guid);


		// voting area

		echo view_voting_box($entity);


		$icontime = $entity->icontime;

		if ($icontime)
		{
			echo "<img src=\"" . $vars['url'] . "pg/polls/icon/" . $entity->getGUID() .
							"/large/$icontime.jpg" . "\" border=\"0\" class=\"elgg-polls-candidate-icon\" />";
		}


		echo "<p>";

		echo elgg_view('output/longtext', array('value' => $latest->value));

?>
		<!-- display tags -->
		<div>
			<?php
				$url = "";

				if ($poll)
				{
					$url = $poll->getURL();
				}

				echo elgg_view('polls/localtags', array('baseurl' => $url, 'tags' => $vars['entity']->tags));
			
			?>
		</div>
<?php

		// last edit & by whom
?>

		<p class="strapline">
			<?php

				$time_updated = $latest->time_created;
				$owner_guid = $latest->owner_guid;
				$owner = get_entity($owner_guid);

				echo sprintf(elgg_echo("polls:strapline"),
								friendly_time($time_updated),
								"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
				);
			?>
		</p>

		</div>
		</div>


<?php

		// supplemental voting categories

		$category_section = "";

		for ($x = 1; $x <= 4; $x++)
		{
			$attribute_label = "category" . $x;

			if ($poll & $poll->$attribute_label != "")
			{
				$category_section .= '<div class="polls_detailed_category">';
				$category_section .= view_voting_box($entity, $x);
				$category_section .= $poll->$attribute_label;
				$category_section .= "</div>";
			}
		}

		if ($category_section != "")
		{
			echo '<div class="contentWrapper">';

			echo "<h3>";
			echo elgg_echo("polls:vote:categories:title");
			echo "</h3>";

			echo '<p class="polls_categories_header">';
			echo elgg_echo("polls:vote:categories:description");
			echo $category_section;
			echo '<p class="polls_categories_footer">';

			echo '</div>';
		}

	}
