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

	global $CONFIG;
?>

$(document).ready(function() {
	$('input.polls_vote_thumb_up').click(function() {
		Polls.postVote(this);
		return false;
	});
});

var Polls = {
	starRatingCallback : function(value, link)
	{
		Polls.postVote(this);
	},
	
	postVote : function(inputElement)
	{
		var thisObj = inputElement;	// Keep a reference to this!
		var thisForm = $(thisObj.form);
		var oldVotingBox = thisForm.parents('.voting-box');
	
		var data = {
			"ajax" : "yes"
		};
		
		thisForm.find(':input').each(function() {
			data[$(this).attr('name')] = $(this).val();
		});

		Polls.showLoading(oldVotingBox, true);

		$.ajax({
			"url" : "<?php echo $CONFIG->url; ?>action/polls/vote",
			"type" : "post",
			"data" : data,
			"dataType" : "json",
			"success" : function(returnedData, textStatus) {
				if(returnedData.success && returnedData.redirect)
				{
					// Not much we can do about this...
					window.location.href = returnedData.redirect;
					return;
				}
			
				if(!returnedData.success)
				{
					thisForm.submit();
					return;
				}
			
				var thisData = {
					"ajax" : "yes",
					"candidate_guid" : data.candidate_guid
				};
				
				if(data.category_number)
					thisData.category_number = data.category_number;
			
				$.ajax({
					"url" : "<?php echo $CONFIG->url; ?>mod/polls/ajax/votingbox.php",
					"data" : thisData,
					"type" : "get",
					"dataType" : "html",
					"success" : function(htmlData, textStatus) {
						var newBoxParent = oldVotingBox.parent();
						thisForm.parents('.voting-box').replaceWith(htmlData);
						
						var newVotingBox = newBoxParent.children('.voting-box');

						// We need to remove the existing rating from the star rating groups
						$.rating.groups[$(thisObj).attr('name')] = null;
						
						$('input[@type=radio].star').rating();

						Polls.showLoading(newVotingBox, false);
					}
				});
			},
			"error" : function(request, textStatus, errorThrown) {
				// The AJAX call failed for some reason, let's just try submitting
				// the form instead
				thisForm.submit();
			}
		});
	},

	showLoading : function(votingBox, show)
	{
		var boxEl = $(votingBox);
		var innerBox = boxEl.find('.polls_vote_area');	// This is where we will get the size from

		var loadingBox = boxEl.siblings('.polls_vote_area_loading');
		
		if(show) 
		{
			if(loadingBox.length == 0)
			{
				loadingBox = $('<div />');
				
				var position = innerBox.position();
			
				loadingBox
					.hide()
					.addClass('polls_vote_area_loading')
					.width(innerBox.outerWidth(true))
					.height(innerBox.outerHeight(true))
					.css('left', position.left)
					.css('top', position.top);
			
				boxEl.after(loadingBox);
			}
			
			loadingBox
				.css('opacity', 0)
				.css('display', 'block');
			
			loadingBox.fadeTo('fast', 0.9);
		}
		else
		{
			loadingBox.fadeOut('fast');
		}
	}
};
