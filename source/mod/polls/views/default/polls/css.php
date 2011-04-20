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

.polls-accessibility-hidden {
	position: absolute;
	left: -10000px;
	overflow: hidden;
}

#polls_poll {
	zoom: 1;
}

.polls-state-description {
	margin-top: 1em;
}

.polls_alpha_navigation
{
	margin: 10px 0 0px 0;
	padding: 0;
	float: left;
	display: inline;
}

.polls_alpha_navigation ul
{
	list-style: none;
	padding-left: 0;
}

.polls_alpha_navigation ul li
{
	float: left;
	display: inline;
	margin: 0 0 0 0;
	background: white;
}

.polls_alpha_navigation a
{
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	background:#FFFFFF none repeat scroll 0 0;
	border:1px solid #4690D6;
	color:#4690D6;
	cursor:pointer;
	float:left;
	display: inline;
	font-size:12px;
	font-weight:normal;
	margin:0 3px 0 0;
	padding:0 4px;
	text-align:center;
}

.polls_alpha_navigation li a:hover
{
	background:#4690d6;
	color:white;
	text-decoration: none;
}

.polls_alpha_navigation li a.empty:hover
{
	background: white;
	color:#4690D6;
}

.polls_alpha_navigation li a.current
{
	background: #4690D6;
	color:white !important;
}

.polls_alpha_navigation li a.empty
{
/*	color: #DDDDDD; */
	border: none;
}

#polls_poll .pagination {
	margin: 5px 0;
	padding: 0;
	-moz-border-radius: 0;
	border: none;
}

.search_listing .elgg-body .owner_timestamp
{
	float: none;
}

.polls_detailed_category
{
	/* setting width explicitly is a bit of a hack as we'll have to
	   change it if the overall page width changes in
	   two_column_left_sidebar_maincontent but it seems to be the
	   only way to get the two column layout to work consistently */

	width: 315px;

	float: left;

	position: relative;
	border: 2px solid #cccccc;
	margin: 0 5px 10px 5px;
	padding: 5px;

	border: 2px solid #CCCCCC;
	-moz-border-radius-bottomleft: 8px;
	-moz-border-radius-bottomright: 8px;
	-moz-border-radius-topleft: 8px;
	-moz-border-radius-topright: 8px;
	background: white none repeat scroll 0 0;
	display: block;
}

.polls_categories_header
{
	padding-top: 10px;
}

.polls_categories_footer
{
	clear: both;
	margin: 0 0 5px 0;
}

.polls_vote_area input.star
{
	visibility: hidden;
	margin: 0;
	padding: 0;
	width: 17px;
	height: 16px;
}

.polls_delete input.submit_button
.polls_delete input.cancel_button
{
	margin: 0 10px 0 0;
}

.polls_manage input.submit_button
{
	margin: 0;
}

.pollswelcome p
{
	margin:0 0 5px 0;
}

#group_polls_widget
{
	margin: 0 0 20px 0;
	padding: 0 0 5px 0;
	background: white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}

#group_polls_widget .search_listing
{
	border: 2px solid #cccccc;
}

#polls_poll .search_listing
{
	border: 2px solid #cccccc;
	margin: 0 0 5px 0;
	height: 100%;
}

#polls_poll .search_listing .search_listing_icon
{
}

.polls_manage .search_listing .search_listing_icon .icon
{
	float:left;
}

.polls_manage .search_listing .elgg-body
{
	margin-left: 85px;
}

#polls_poll .elgg-body .candidate_title
{
	padding: 0;
}

#polls_poll .polls_manage .moveto
{
	padding: 5px 0 0 0;
}

#polls_poll .elgg-body .owner_timestamp
{
	padding: 0;
}

#polls_poll .elgg-body .candidate_tagline
{
	padding: 2px 0 0 0;
}

#polls_poll .elgg-body .comments
{
	padding: 0 12px 1px 16px;
	margin: 10px 0 4px 0;
	background: transparent url(<?php echo $vars['url']; ?>mod/polls/images/icon_comment.gif) no-repeat scroll left 1px;
	font-size: 86%;
}

.filter_delete
{
	cursor: pointer;
	width: 14px;
	height: 14px;	
	margin-left: 5px;
	zoom: 1;
	display: -moz-inline-stack;
	display: inline-block;
	vertical-align: middle;
	position:relative;
}

.filter_delete a {
	display: block;
	width: 14px;
	height: 14px;	
	background: url("<?php echo $vars['url']; ?>_graphics/icon_customise_remove.png") no-repeat;
}

.filter_delete a:hover
{
	background-position: 0 -16px;
}

.owner_timestamp
{
	float: left;
}

.polls_manage_checkbox
{
	float: left;
	margin: 10px 10px 10px 5px;
}

#polls_poll .strapline,
#polls_candidate .strapline
{
	text-align: right;
	border-top: 1px solid #efefef;
	margin: 10px 0 10px 0;
	clear: both;
}

#polls_poll img[align="left"] {
	margin: 10px 20px 10px 0;
	float: left;
}

#polls_poll img[align="right"] {
	margin: 10px 0 10px 10px;
	float: right;
}

#polls_poll .more-info-link {
	margin: 1em 0 0 0;
	font-size: 86%;
}

#polls_poll .more-info-data {
	padding: 1em 0 0 0;
}

.voting-box
{
	float: right;
	margin-left: 10px;
	margin-bottom: 10px;
}

.polls_vote_area_loading
{
	background: white url(<?php echo $vars['url']; ?>_graphics/ajax_loader.gif) center center no-repeat;
	position: absolute;
}

input.polls_vote_thumb_up
{
	background: #77FF77 url(<?php echo $vars['url']; ?>mod/polls/images/thumbs_up_on_green.gif) no-repeat scroll 0 !important;
	border: medium none !important;
	height: 40px;
	margin: 0;
	min-width: 40px;
	padding: 0;
	width: 40px;
}

input.polls_vote_thumb_up_disabled
{
	background: #BBBBBB url(<?php echo $vars['url']; ?>mod/polls/images/thumbs_up_on_grey.gif) no-repeat scroll 0 !important;
	border: medium none !important;
	height: 40px;
	margin: 0;
	min-width: 40px;
	padding: 0;
	width: 40px;
}

.river_object_poll_candidate_create
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/candidate_river.gif) no-repeat left -1px;
}

.river_object_poll_create
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/poll_river.gif) no-repeat left -1px;
}

.river_object_poll_candidate_vote
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/vote_river.gif) no-repeat left -1px;
}

.river_object_poll_candidate_vote1,
.river_object_poll_candidate_vote2,
.river_object_poll_candidate_vote3,
.river_object_poll_candidate_vote4,
.river_object_poll_candidate_vote5
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/vote_river_stars.gif) no-repeat left -1px;
}

<?php
	for ($vote = 1; $vote <= 5; ++$vote)
	{
		for ($category = 1; $category <= 4; ++$category)
		{
			if (($vote > 1) || ($category > 1 ))
			{
				print(',');
			}
				
			print(".river_object_poll_candidate_vote{$vote}_category{$category}");
		}
	}
?>
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/vote_river_stars.gif) no-repeat left -1px;
}

.river_object_poll_comment,
.river_object_poll_candidate_comment
{
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}




/* jQuery.Rating Plugin CSS - http://www.fyneworks.com/jquery/star-rating/ */

div.rating
{
	cursor: pointer;
	margin: 2em;
	clear: both;
	display: block
}

div.rating:after
{
	content: '.';
	display: block;
	height: 0;
	width: 0;
	clear: both;
	visibility: hidden
}

div.cancel,div.star
{
	float: left;
	width: 17px;
	height: 16px;
	text-indent: -999em;
	cursor: pointer;
	display: block;
	background: transparent;
	overflow: hidden
}

div.cancel, div.cancel a
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/delete.gif) no-repeat 0 -16px;
}

div.cancel, .polls_vote_area input.cancel
{
	margin-left: 6px;
}

div.star, div.star a
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/star.gif) no-repeat 0 0px;
}

.polls_detailed_category * div.star,
.polls_detailed_category * div.star a
{
	background: url(<?php echo $vars['url']; ?>mod/polls/images/blob.gif) no-repeat 0 0px;
}

div.cancel a,
div.star a,
.polls_detailed_category * div.cancel a,
.polls_detailed_category * div.star a
{
	display: block;
	width: 16px;
	height: 100%;
	background-position: 0 0px;
	border: 1 solid #FF0000
}

div.star_on a,
.polls_detailed_category * div.star_on a
{
	background-position: 0 -32px!important
}

div.star_hover a,
div.star_live a:hover,
.polls_detailed_category * div.star_hover a,
.polls_detailed_category * div.star_live a:hover
{
	background-position: 0 -16px
}

/* Read Only CSS */
div.star_readonly a,
div.star_readonly a:hover,
.polls_detailed_category * div.star_readonly a,
.polls_detailed_category * div.star_readonly a:hover
{
	cursor: default !important
}

/* Partial Star CSS */
div.star,
.polls_detailed_category * div.star
{
	background: transparent!important;
	overflow: hidden!important
}

/* END jQuery.Rating Plugin CSS */

/* ALL TAGS PAGE */
.polls-alltags ul {
	list-style-type: none;
	padding: 0;
	margin: 0;
}

.polls-alltags li {
	float: left;
}

.polls-alltags li a
{
	-moz-border-radius-bottomleft:4px;
	-moz-border-radius-bottomright:4px;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	background:#FFFFFF none repeat scroll 0 0;
	border:1px solid #4690D6;
	color:#4690D6;
	cursor:pointer;
	float:left;
	display: inline;
	font-size:12px;
	font-weight:normal;
	margin:5px 5px 0 0;
	padding:4px 4px;
	text-align:center;
	white-space: nowrap;
}

.polls-alltags li a:hover
{
	background:#4690d6;
	color:white;
	text-decoration: none;
}

.elgg-polls-tabs-container
{
	margin-top: 1em;
}

#polls_candidate .elgg-polls-candidate-icon
{
	float: left;
	margin: 0 10px 10px 0;
}

#polls_candidate .elgg-output
{
	text-align: justify;
}