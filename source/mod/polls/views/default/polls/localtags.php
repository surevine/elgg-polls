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

	$taglist = $vars['tags'];
	$url = $vars['baseurl'];

	// +++ Construct a string of the current tags
	// @TODO Pass this in rather than bodgily using get_input here
	$current_tags_string = get_input('tag', '');
	$current_tags = explode(',', $current_tags_string);
	// ---
	
	if (!empty($taglist))
	{
		$tagstr = "";

		if (!is_array($taglist))
		{
			$taglist = array($taglist);
		}

		echo '<span>';
		echo '<span class="elgg-icon elgg-polls-icon-localtags"></span>';
		echo '<ul class="elgg-tags">';
		
		foreach ($taglist as $tag)
		{
			if (is_string($tag))
			{
				if (in_array($tag, $current_tags))
				{
					$tagstr .= '<li>' . htmlentities($tag, ENT_QUOTES, 'UTF-8') . '</li>';
				}
				else
				{
					if ($current_tags_string == '')
					{
						$tags_string = $tag;
					}
					else
					{
						$tags_string = $current_tags_string . ',' . $tag;
					}
					
					$tagstr .= '<li><a rel="tag" href="' . $url . '?tag=' . rawurlencode($tags_string) . '">' . 
						htmlentities($tag, ENT_QUOTES, 'UTF-8') . '</a></li>';
				}
									
			}
		}

		echo $tagstr;
		echo '</ul>';
		echo '</span>';
	}
