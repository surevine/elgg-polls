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


	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'polls' => "Polls",
			'polls:mine' => "Your polls",
			'polls:owned' => "%s's polls",
			'polls:all' => "All site polls",
			'polls:new' => "New poll",
			'polls:group' => "Group polls",
			'polls:groupprofile' => "Group polls",
			'polls:edit' => "Edit poll",
			'polls:manage' => "Manage poll",
			'polls:delete' => "Delete poll",
			'polls:history' => "Poll history",
			'polls:view' => "View poll",
			'polls:navigation' => "Poll navigation",
			'polls:viewalltags' => "Tags within this poll",
			'polls:allcandidatetags' => "Tags within this poll",
			'polls:allcandidatetags:description' => 'This is a display of all the tags used by candidates within the poll. Click on a tag to see all the candidates with that tag.',

			'polls:candidate:edit' => "Edit candidate",
			'polls:candidate:delete' => "Delete candidate",
			'polls:candidate:history' => "Candidate history",

			'polls:candidate:heading' => "%s Candidates",
			'polls:candidate:show' => "Show:",

			'polls:candidate:description:active' => "Active candidates can receive votes.",
			'polls:candidate:description:rejected' => "Rejected candidates have been removed by a poll moderator.",
			'polls:candidate:description:proposed' => "Proposed candidates have been suggested by a user but need to be approved by a moderator before they become active.",
			'polls:candidate:description:promoted' => "Promoted candidates have received sufficient votes to be regarded as 'winners'.",
	
			'item:object:poll_candidate' => 'Poll candidates',
			'item:object:poll' => 'Polls',

			'polls:bulkupload' => 'Bulk upload',
			'polls:bulkupload:title' => 'Candidate Bulk Upload: %s',
			'polls:bulkupload:instructions' => 'Automatically create candidates for this poll by uploading a CSV (comma separated variables) file.<p>The file must have a header row containing the field names: "title, description, tags".  Each subsequent row should contain the title, the description, and tags, in three comma separated fields.<p>The description field can contain HTML if the TinyMCE editor is installed.<p>Tags must be comma-separated, so enclose the field in double quotes if it contains multiple tags.  Microsoft Excel will create this format if you save as CSV.',
			'polls:bulkupload:file' => 'CSV file',
			'polls:bulkupload:fileerror' => 'Could not read CSV file',
			'polls:bulkupload:headererror' => 'CSV file does not have correct header row',
			'polls:bulkupload:lineerror' => 'Added first %s candidates before encountering error on line %s',
			'polls:bulkupload:saved' => 'Successfully added %s candidates',

			'polls:welcomemessage' => "Welcome to this Elgg polls plugin. This feature allows you to create polls on any topic and select who can view them and edit them.",
	
	
		/**
		 * Form fields
		 */
	
			'polls:title' => 'Poll Title',
			'polls:description' => 'Description',
			'polls:tags' => 'Tags',	

			'polls:access_id' => 'Access (who can view, add candidates, or vote in this poll)',
			'polls:write_access_id' => 'Moderator access (who can manage candidates, edit the poll settings, or delete the poll)',

			'polls:voting_type' => 'Voting type',
			'polls:thumbs' => 'Thumbs up',
			'polls:rank' => 'Rank',
			'polls:stars' => 'Star rating',

			'polls:display_order' => 'Candidate display order',
			'polls:sort:az' => 'Alphabetical (A-Z)',
			'polls:sort:za' => 'Alphabetical (Z-A)',
			'polls:sort:highestscore' => 'Highest score first',
			'polls:sort:lowestscore' => 'Lowest score first',
			'polls:sort:newest' => 'Newest first',
			'polls:sort:oldest' => 'Oldest first',
			'polls:sort:newestvote' => 'Most recently voted for first',
			'polls:sort:oldestvote' => 'Least recently voted for first',
			'polls:sort:mostvotes' => 'Most votes first',
			'polls:sort:leastvotes' => 'Least votes first',

			'polls:moderated' => 'Nominee requires moderator approval?',
			'polls:moderated:no' => 'No',
			'polls:moderated:yes' => 'Yes',

			'polls:revote' => 'Can voters change their ratings later?',
			'polls:revote:no' => 'No',
			'polls:revote:yes' => 'Yes',

			'polls:redirect_on_vote' => 'After voting redirect to candidate comments page?',
			'polls:redirect_on_vote:no' => 'No',
			'polls:redirect_on_vote:yes' => 'Yes',

			'polls:active_name' => 'Label for candidates eligible for votes',
			'polls:active_description' => 'Description for candidates eligible for votes',
			'polls:promoted_name' => 'Label for candidates removed by the moderator as \'winners\'',
			'polls:promoted_description' => 'Description for candidates removed by the moderator as \'winners\'',
			'polls:rejected_name' => 'Label for candidates removed by the moderator as \'losers\'',
			'polls:rejected_description' => 'Description for candidates removed by the moderator as \'losers\'',
			'polls:proposed_name' => 'Label for candidates waiting moderation',
			'polls:proposed_description' => 'Description for candidates waiting moderation',
	
			'polls:active_name:default' => 'active',
			'polls:promoted_name:default' => 'promoted',
			'polls:rejected_name:default' => 'rejected',
			'polls:proposed_name:default' => 'proposed',

			'polls:category1' => 'Extra voting category 1 (leave blank for none)',
			'polls:category2' => 'Extra voting category 2 (leave blank for none)',
			'polls:category3' => 'Extra voting category 3 (leave blank for none)',
			'polls:category4' => 'Extra voting category 4 (leave blank for none)',
			'polls:category1values' => 'Optional values for category 1 (enter 5 values separated by commas)',
			'polls:category2values' => 'Optional values for category 2 (enter 5 values separated by commas)',
			'polls:category3values' => 'Optional values for category 3 (enter 5 values separated by commas)',
			'polls:category4values' => 'Optional values for category 4 (enter 5 values separated by commas)',
			'polls:star_values' => 'Optional values for stars (enter 5 values separated by commas)',

			'polls:categories:renameareyousure' => 'You are renaming a category that has already received votes. Are you sure?',

			'polls:candidate:howtoadd' => 'You can add a candidate by selecting "%s" from the menu.',
			'polls:candidate:howtopropose' => 'You can propose a candidate by selecting "%s" from the menu.',

			'polls:moveselected' => 'Move selected candidates to',
			'polls:movebutton' => 'Move',

			'polls:candidate:title' => 'Candidate Title',
			'polls:candidate:description' => 'Description',
			'polls:candidate:tags' => 'Tags',	

			'polls:confirmdelete:title' => 'Confirm poll deletion',
			'polls:confirmdelete' => 'Are you sure you want to permanently delete the "%s" poll?',
			'polls:confirmdeletesubcandidate' => 'This poll contains a candidate which will also be deleted.',
			'polls:confirmdeletesubcandidates' => 'This poll contains %d candidates which will also be deleted.',

			'polls:candidate:confirmdelete:title' => 'Confirm candidate deletion',
			'polls:candidate:confirmdelete' => 'Are you sure you want to permanently delete the "%s" candidate?',

			'polls:category:confirm:delete' => 'You are deleting the category "%s" which has already received %s votes.',
			'polls:category:confirm:rename' => 'You are renaming the category "%s" which has already received %s votes.',
			'polls:category:confirm:create' => 'You are creating a category "%s".  The previous category in this slot had already received %s votes.',
			'polls:category:keep' => 'Keep votes',
			'polls:category:delete' => 'Delete votes',

			'polls:settings:newpollaccess' => 'Who can create new polls?',
			'polls:settings:admins' => 'Administrators only',
			'polls:settings:groups' => 'Administrators and group owners',
			'polls:settings:everyone' => 'Everyone',


		
		/**
		 * Status and error messages
		 */
			'polls:noaccess' => 'No access to poll',
			'polls:cantedit' => 'You can not edit this poll',
			'polls:saved' => 'Poll saved',
			'polls:notsaved' => 'Poll could not be saved',
			'polls:notitle' => 'You must specify a title for your poll.',
			'polls:badstarvalues' => 'You must have exactly 5 values for the optional values or leave it blank',
			'polls:proposednotzero' => 'You can\'t make a poll unmoderated until you\'ve approved or rejected all the proposed candidates.',
			'polls:delete:success' => 'Your poll was successfully deleted.',
			'polls:delete:failure' => 'The poll could not be deleted.',
			'polls:candidate:delete:success' => 'Your candidate was successfully deleted.',
			'polls:candidate:saved' => 'Candidate saved',
			'polls:candidate:notsaved' => 'Candidate could not be saved',
			'polls:manage:moved' => 'Moved %s candidates',
			'polls:manage:moved:one' => 'Moved 1 candidate',
			'polls:manage:notmoved' => 'Candidate could not be moved',
			'polls:manage:notselected' => 'No candidates were selected',
			'polls:candidate:comments' => '%s comments',
			'polls:candidate:comments:one' => '1 comment',
	
		/**
		 * Poll
		 */
			'polls:strapline' => 'Last updated %s by %s',

			'polls:candidate:none' => 'This section does not currently have any %s candidates.',
			'polls:candidate:moreinfo' => 'more info',

			'polls:vote:button' => 'Vote',
			'polls:vote:total' => '%s votes',
			'polls:vote:total:one' => '1 vote',
			'polls:vote:total:none' => 'No votes',
			'polls:vote:thumbs:previous' => 'You gave this a thumbs up',
			'polls:vote:stars:previous' => 'You voted %s stars',
			'polls:vote:stars:previous:one' => 'You voted 1 star',
			'polls:vote:stars:previous:category' => 'You voted: %s',
			'polls:vote:notallowed' => 'You are not authorised to vote for this',
			'polls:vote:notallowed:here' => 'Voting is disabled in this view',
			'polls:vote:success' => 'Thank you for your vote',
			'polls:vote:failure' => 'Sorry, your vote could not be registered',
			'polls:vote:changed' => 'Your vote has been changed',
			'polls:vote:changevote' => 'Change your vote',
			'polls:vote:categories:title' => 'Detailed categories',
			'polls:vote:categories:description' => 'Voting in these categories does not affect the overall score of the candidate',



			'polls:iconinstructions' => "The candidate icon is the image that's displayed on your candidate's page. <br /> You can change it as often as you like. (File formats accepted: GIF, JPG or PNG)",
			'polls:icon' => "Candidate icon",
			'polls:createicon' => "Create candidate icon",
			'polls:currenticon' => "Current icon",
			'polls:createicon:header' => "Candidate icon",
			'polls:iconcroppingtool' => "Icon cropping tool",
			'polls:iconcroppingtool:instructions' => "Click and drag a square below to match how you want your icon cropped.  A preview of your cropped icon will appear in the box on the right.  When you are happy with the preview, click 'Create candidate icon'. This cropped image will be used throughout the site as the candidate icon.",
	
			'polls:editicon' => "Edit candidate icon",
			'polls:icon:notfound' => "Sorry; there was a problem uploading your icon.",
			'polls:icon:uploaded' => "Your icon was successfully uploaded.",
			'polls:paginate:others' => "Others",
			'polls:filter:filteredby' => "Filtered by:",
			'polls:filter:clear' => 'clear filter',

	
		/**
		 * History
		 */
			'polls:revision' => 'Revision created %s by %s',
			
		/**
		 * Widget
		 **/
		 
			'polls:num' => 'Number of polls to display',
	
		/**
		 * Submenu items
		 */
			'polls:label:view' => "View poll",
			'polls:label:edit' => "Edit poll",
			'polls:label:manage' => "Manage poll",
			'polls:label:history' => "Poll history",

			'polls:label:candidate:view' => "View candidate",
			'polls:label:candidate:edit' => "Edit candidate",
			'polls:label:candidate:history' => "Candidate history",
	
		/**
		 * Sidebar items
		 */
			'polls:sidebar:this' => "This poll",
			'polls:sidebar:children' => "Sub-polls",
			'polls:sidebar:parent' => "Parent",
	
			'polls:candidate:new' => "Add a candidate",
			'polls:candidate:new:propose' => "Propose a candidate",
			'polls:backtoparent' => "Back to '%s'",


		/**
		 * River items
		 */
			'polls:river:create' => "%s created the %s poll",
			'polls:river:create:group' => "%s created the %s poll in %s",
			'polls:river:addcandidate' => "%s added %s to the %s poll",
			'polls:river:addcandidate:group' => "%s added %s to the %s poll in %s",
	
			'polls:river:vote:thumbs' => "%s voted for %s in the %s poll",
			'polls:river:vote:thumbs:group' => "%s voted for %s in the %s poll in %s",
	
			'polls:river:vote:stars' => "%s gave %s stars to %s in the %s poll",
			'polls:river:vote:stars:one' => "%s gave 1 star to %s in the %s poll",
			'polls:river:vote:stars:group' => "%s gave %s stars to %s in the %s poll in %s",
			'polls:river:vote:stars:group:one' => "%s gave 1 star to %s in the %s poll in %s",
	
			'polls:river:vote:category:stars' => "%s voted %s for %s within %s in the %s poll",
			'polls:river:vote:category:stars:one' => "%s voted 1 for %s within %s in the %s poll",
			'polls:river:vote:category:stars:group' => "%s voted %s for %s stars within %s in the %s poll in %s",
			'polls:river:vote:category:stars:group:one' => "%s voted 1 for %s star within %s in the %s poll in %s",

			'poll:river:annotate' => 'a comment on this poll',
			'poll_candidate:river:annotate' => 'a comment on this poll candidate',
	
			'river:commented:object:poll_candidate' => 'poll candidate',
			'river:commented:object:poll' => 'poll',
	);
					
	add_translation("en",$english);
