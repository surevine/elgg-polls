/*
 ### jQuery Star Rating Plugin v2.5 - 2008-09-10 ###
 * http://www.fyneworks.com/ - diego@fyneworks.com
 * Dual licensed under the MIT and GPL licenses:
 *		http://www.opensource.org/licenses/mit-license.php
 *		http://www.gnu.org/licenses/gpl.html
 ###
 Project: http://plugins.jquery.com/project/MultipleFriendlyStarRating
 Website: http://www.fyneworks.com/jquery/star-rating/
*/
/*
	Based on http://www.phpletter.com/Demo/Jquery-Star-Rating-Plugin/
 Original comments:
	This is hacked version of star rating created by <a href="http://php.scripts.psu.edu/rja171/widgets/rating.php">Ritesh Agrawal</a>
	It thansform a set of radio type input elements to star rating type and remain the radio element name and value,
	so could be integrated with your form. It acts as a normal radio button.
	modified by : Logan Cai (cailongqun[at]yahoo.com.cn)
*/
/*
	Additionally modifed by Surevine Ltd to integrate into polls module
*/

/*# AVOID COLLISIONS #*/
;if(window.jQuery) (function($){
/*# AVOID COLLISIONS #*/
	
	// default settings
	$.rating =
	{
		cancel: 'Cancel Rating',	// advisory title for the 'cancel' link
		cancelValue: '',			// value to submit when user click the 'cancel' link
		ro_hover: 'Read only',		// Hover text when already voted
		
		// width of star image/star div
		starImageWidth: 16,
		starDivWidth: 17,
		
		// NB.: These don't need to be defined (can be undefined/null) so let's save some code!
		// readOnly: false,			// disable rating plugin interaction/ values cannot be changed
		// alreadyVoted: false,		// like readOnly but displays cancel (change vote) button which re-activates control
		// focus: function(){},		// executed when stars are focused
		// blur: function(){},		// executed when stars are focused
		// callback: function(){},	// executed when a star is clicked
		// curValue: 0,				// current value to be displayed before they vote

		// default callback is to auto submit form unless overridden
		callback: function(value, link)
		{
			this.form.submit();
		},
		
		// required properties:
		groups: {}, // allows multiple star ratings on one page

		event:
		{
			// plugin event handlers

			// fill to the current mouse position.
			fill: function(n, el, settings, state)
			{
				// if(window.console) console.log(['fill', $(el), $(el).prevAll('.star_group_'+n), arguments]);

				this.drain(n);

				if ($.rating.groups[n].splitStars)
				{
					// hide the 2 static split stars and show the dynamic star
					$($.rating.groups[n].current).next('.star_group_' + n).andSelf().hide();
					$($.rating.groups[n].current).prev('.star_group_' + n).show();
				}

				$(el).prevAll('.star_group_' + n).andSelf().addClass('star_' + (state || 'hover'));

				// focus handler, as requested by focusdigital.co.uk
				var lnk = $(el).children('a');
				val = lnk.text();

				if (settings.focus)
					settings.focus.apply($.rating.groups[n].valueElem[0], [val, lnk[0]]);
			},

			// drain all the stars
			drain: function(n, el, settings)
			{
				// if(window.console) console.log(['drain', $(el), $(el).prevAll('.star_group_'+n), arguments]);

				$.rating.groups[n].valueElem.siblings('.star_group_' + n)
					.removeClass('star_on')
					.removeClass('star_hover');
			},

			// Reset the stars to the default index
			reset: function(n, el, settings)
			{
				if ($.rating.groups[n].splitStars)
				{
					// hide the dynamic star and show the 2 static split stars
					$($.rating.groups[n].current).prev('.star_group_' + n).hide();
					$($.rating.groups[n].current).next('.star_group_' + n).andSelf().show();
				}

				if (!$($.rating.groups[n].current).is('.cancel'))
				{

					$($.rating.groups[n].current).prevAll('.star_group_' + n).andSelf().addClass('star_on');
				}

				// blur handler, as requested by focusdigital.co.uk
				var lnk = $(el).children('a');
				val = lnk.text();

				if (settings.blur)
					settings.blur.apply($.rating.groups[n].valueElem[0], [val, lnk[0]]);
			},

			// Selected a star or cancelled
			click: function(n, el, settings)
			{
				// remove mouse handlers and hover effects after vote
				$.rating.groups[n].valueElem.siblings('.star_group_' + n)
					.removeClass('star_live')
					.unbind();

				var lnk = $(el).children('a');
				val = lnk.text();

				// Set value
				$.rating.groups[n].valueElem.val(val);

				// click callback, as requested here: http://plugins.jquery.com/node/1655
				if (settings.callback)
					settings.callback.apply($.rating.groups[n].valueElem[0], [val, lnk[0]]);
			}
		}
	};
	
	$.fn.rating = function(instanceSettings)
	{
		if (this.length == 0)
			return this; // quick fail
		
		instanceSettings = $.extend(
			{} /* new object */,
			$.rating /* global settings */,
			instanceSettings || {} /* just-in-time settings */
		);
		
		// loop through each matched element
		this.each(function(i)
		{
			
			var settings = $.extend(
				{} /* new object */,
				instanceSettings || {} /* current call settings */,
				($.metadata? $(this).metadata(): ($.meta?$(this).data():null)) || {} /* metadata settings */
			);
			
			//// if(window.console) console.log([this.name], '#');
			
			// Generate internal control ID - ignore square brackets in element names
			var n = (this.name || 'unnamed-rating').replace(/\[|\]/, "_");

			// Grouping
			if (!$.rating.groups[n])
				$.rating.groups[n] = {count: 0};

			i = $.rating.groups[n].count;
			$.rating.groups[n].count++;
			
			// Accept readOnly setting from 'disabled' property
			$.rating.groups[n].readOnly = $.rating.groups[n].readOnly || settings.readOnly || $(this).attr('disabled');
			
			// Things to do with the first element...
			if (i == 0)
			{
				// Create value element (disabled if readOnly)
				$.rating.groups[n].valueElem = $('<input type="hidden" name="' + n + '" value=""' + (settings.readOnly ? ' disabled="disabled"' : '') + '/>');
				// Insert value element into form
				$(this).before($.rating.groups[n].valueElem);
			}

			// Things to do with the last element...
			if (i == 5)
			{
				// replace with 'cancel' button
				cancelButton =
					$('<div class="cancel"><a title="' + settings.cancel + '">' + settings.cancelValue + '</a></div>')
						.css('visibility', 'hidden')
						.mouseover(function(){ $.rating.event.drain(n, this, settings); $(this).addClass('star_on'); })
						.mouseout(function(){ $.rating.event.reset(n, this, settings); $(this).removeClass('star_on'); })
						.click(
							function()
							{
								$.rating.groups[n].valueElem.siblings('.star_group_' + n)
									.removeClass('star_readonly')
									.addClass('star_live')
									.mouseover(function()
										{
											$.rating.event.drain(n, this, settings);
											$.rating.event.fill(n, this, settings, 'hover');
										})
									.mouseout(function()
										{
											$.rating.event.drain(n, this, settings);
											$.rating.event.reset(n, this, settings);
										})
									.click(function()
										{
											$.rating.event.click(n, this, settings);
										})
									.each(function()
										{
											link = $(this).children('a');
											starNum = link.text() - 1;
											hoverText = $.rating.groups[n].hover[starNum];
											link.attr('title', hoverText);
										});

								$.rating.groups[n].valueElem.removeAttr("disabled");

								$(this).css('visibility', 'hidden');
							}
						);

				$(this).before(cancelButton);

				// remove this checkbox
				$(this).remove();

				if (settings.alreadyVoted)
				{
					$(cancelButton).css('visibility', 'visible');
				}
			}
			else
			{
				hoverText = (this.title || this.value);

				if ($.rating.groups[n].readOnly)
				{
					if (!$.rating.groups[n].hover)
						$.rating.groups[n].hover = new Array();

					$.rating.groups[n].hover[i] = hoverText;

					hoverText = settings.ro_hover;
				}

				// insert rating option right after preview element
				eStar = $('<div class="star"><a title="' + hoverText + '">' + this.value + '</a></div>');
				$(this).after(eStar);
				
				// Remember group name so controls within the same container don't get mixed up
				$(eStar).addClass('star_group_' + n);
				
				// readOnly?
				if ($.rating.groups[n].readOnly)
				{
					// Mark star as readOnly so user can customize display
					$(eStar).addClass('star_readonly');
				}
				else
				{
					$(eStar)

					// Enable hover css effects
					.addClass('star_live')

					// Attach mouse events
					.mouseover(function(){ $.rating.event.drain(n, this, settings); $.rating.event.fill(n, this, settings, 'hover'); })
					.mouseout(function(){ $.rating.event.drain(n, this, settings); $.rating.event.reset(n, this, settings); })
					.click(function(){ $.rating.event.click(n, this, settings); });
				};


				// current star?
				if (settings.curValue > i && settings.curValue <= i + 1)
				{
					var splitWidth = Math.round((settings.curValue - i) * settings.starImageWidth);

					// need to split current star into 2 pieces?
					if (splitWidth > 0)
					{
						$.rating.groups[n].splitStars = true;

						// create 2 fractional stars used for displaying in 'reset' mode

						star1 = $('<div class="star"><a title="' + hoverText + '">' + this.value + '</a></div>');
						star2 = $('<div class="star"><a title="' + hoverText + '">' + this.value + '</a></div>');

						$(star1).addClass('star_group_' + n);
						$(star2).addClass('star_group_' + n);


						// restrict star's width and hide overflow (already in CSS)
						$(star1).width(splitWidth);
						$(star2).width(settings.starDivWidth - splitWidth);

						// move the star left by using a negative margin
						// this is work-around to IE's stupid box model (position:relative doesn't work)
						$(star2).find('a').css({ 'margin-left' : '-' + splitWidth + 'px' })


						if ($.rating.groups[n].readOnly)
						{
							$(star1).addClass('star_readonly');
							$(star2).addClass('star_readonly');
						}
						else
						{
							$(star1).addClass('star_live')
							$(star2).addClass('star_live')
							$(star1).mouseover(function(){ $.rating.event.drain(n, this, settings); $.rating.event.fill(n, this, settings, 'hover'); })
							$(star2).mouseover(function(){ $.rating.event.drain(n, this, settings); $.rating.event.fill(n, this, settings, 'hover'); })
						}

						$(eStar).after(star1);
						$(star1).after(star2);

						// first star is 'current' star
						$.rating.groups[n].current = star1;

						// hide 'dynamic' input star
						$(eStar).hide();
					}
					else
					{
						$.rating.groups[n].current = eStar;
					}

				}
				
				// remove this checkbox
				$(this).remove();
			}
			
		}); // each element
			
		// initialize groups...
		for (n in $.rating.groups)
		{
			(function(c, v, n)
			{
				if (!c)
					return;

				$.rating.event.reset(n, c, instanceSettings || {});

				$(v).val($(c).children('a').text());
			})
			($.rating.groups[n].current, $.rating.groups[n].valueElem, n);
		};
		
		return this; // don't break the chain...
	};
	
	
	
	/*
		### Default implementation ###
		The plugin will attach itself to file inputs
		with the class 'multi' when the page loads
	*/
	$(function(){ $('input[@type=radio].star').rating(); });
	
	
	
/*# AVOID COLLISIONS #*/
})(jQuery);
/*# AVOID COLLISIONS #*/
