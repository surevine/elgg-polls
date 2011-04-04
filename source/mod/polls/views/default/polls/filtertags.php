<?php

/**
	* Polls - a polling module for Elgg
	*
	* @package polls
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	* @author Surevine Limited <elgg@surevine.com>
	* @copyright Surevine Limited 2010
	* @link http://www.surevine.com/
	* 
	* @uses $vars['tags'] The tags to display
	* @uses $vars['tagtype'] The tagtype, optionally
*/

	$url = $vars['baseurl'];

	if (!empty($vars['subtype']))
	{
		$subtype = "&subtype=" . urlencode($vars['subtype']);
	}
	else
	{
		$subtype = "";
	}

	if (!empty($vars['object']))
	{
		$object = "&object=" . urlencode($vars['object']);
	}
	else
	{
		$object = "";
	}

	if (empty($vars['tags']) && !empty($vars['value']))
	{
		$vars['tags'] = $vars['value'];
	}

	if (!empty($vars['tags']))
	{
		$tagstr = "";
		
		if (!is_array($vars['tags']))
		{
			$vars['tags'] = array($vars['tags']);
		}

		foreach ($vars['tags'] as $tag)
		{
			if (!empty($tagstr))
			{
				$tagstr .= ", ";
			}

			if (!empty($vars['type']))
			{
				$type = "&type={$vars['type']}";
			}
			else
			{
				$type = "";
			}

			if (is_string($tag))
			{
				$tagstr .= "<a rel=\"tag\" href=\"{$vars['url']}tag/" . urlencode($tag) .
							"{$type}{$subtype}{$object}\">" . htmlentities($tag, ENT_QUOTES, 'UTF-8') . "</a>";
			}

			$remove_tag_string = '';

			foreach ($vars['tags'] as $thistag)
			{
				if ($thistag == $tag)
				{
					continue;
				}

				if ($remove_tag_string != '')
				{
					$remove_tag_string .= ',';
				}

				$remove_tag_string .= rawurlencode($thistag);
			}

			$tagstr .= '<span class="filter_delete"><a href="' . $url . '?tag=' . $remove_tag_string .
						'" title="' . elgg_echo('polls:filter:clear') .
						'"><span class="polls-accessibility-hidden">' .
						elgg_echo('polls:filter:clear') . '</span></a></span>';
		}
		
		echo $tagstr;
	}
