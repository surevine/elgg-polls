/*
 * imgAreaSelect jQuery plugin
 * version 0.4.2
 *
 * Copyright (c) 2008 Michal Wojciechowski (odyniec.net)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt) 
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://odyniec.net/projects/imgareaselect/
 *
 */

jQuery.imgAreaSelect = function (img, options) {
	var $area = jQuery('<div></div>'),
		$border1 = jQuery('<div></div>'),
		$border2 = jQuery('<div></div>'),
		$outLeft = jQuery('<div></div>'),
		$outTop = jQuery('<div></div>'),
		$outRight = jQuery('<div></div>'),
		$outBottom = jQuery('<div></div>'),
		imgOfs, imgWidth, imgHeight, zIndex = 0, fixed = false, $p,
		startX, startY, moveX, moveY,
		resizeMargin = 10, resize = [ ], V = 0, H = 1,
		d, aspectRatio,
		x1, x2, y1, y2, x, y,
		selection = { x1: 0, y1: 0, x2: 0, y2: 0, width: 0, height: 0 };

	var $a = $area.add($border1).add($border2);
	var $o = $outLeft.add($outTop).add($outRight).add($outBottom);

	function update()
	{
		$a.css({
			left: (selection.x1 + imgOfs.left) + 'px',
			top: (selection.y1 + imgOfs.top) + 'px',
			width: Math.max(selection.width - options.borderWidth * 2, 0) + 'px',
			height: Math.max(selection.height - options.borderWidth * 2, 0) + 'px'
		});
		$outLeft.css({ left: imgOfs.left + 'px', top: imgOfs.top + 'px',
			width: selection.x1 + 'px', height: imgHeight + 'px' });
		$outTop.css({ left: imgOfs.left + selection.x1 + 'px', top: imgOfs.top + 'px',
			width: selection.width + 'px', height: selection.y1 + 'px' });
		$outRight.css({ left: imgOfs.left + selection.x2 + 'px', top: imgOfs.top + 'px',
			width: imgWidth - selection.x2 + 'px', height: imgHeight + 'px' });
		$outBottom.css({ left: imgOfs.left + selection.x1 + 'px', top: imgOfs.top + selection.y2 + 'px',
			width: selection.width + 'px', height: imgHeight - selection.y2 + 'px' });
	}

	function areaMouseMove(event)
	{
		x = event.pageX - selection.x1 - imgOfs.left;
		y = event.pageY - selection.y1 - imgOfs.top;

		resize = [ ];

		if (options.resizable) {
			if (y <= resizeMargin)
				resize[V] = 'n';
			else if (y >= selection.height - resizeMargin)
				resize[V] = 's';
			if (x <= resizeMargin)
				resize[H] = 'w';
			else if (x >= selection.width - resizeMargin)
				resize[H] = 'e';
		}

		$border2.css('cursor', resize.length ? resize.join('') + '-resize' :
			options.movable ? 'move' : '');
	}

	function areaMouseDown(event)
	{
		if (event.which != 1) return false;

		if (options.resizable && resize.length > 0) {
			jQuery('body').css('cursor', resize.join('') + '-resize');

			x1 = (resize[H] == 'w' ? selection.x2 : selection.x1) + imgOfs.left;
			y1 = (resize[V] == 'n' ? selection.y2 : selection.y1) + imgOfs.top;

			jQuery(document).mousemove(selectingMouseMove);
			$border2.unbind('mousemove', areaMouseMove);

			jQuery(document).one('mouseup', function () {
				resize = [ ];

				jQuery('body').css('cursor', '');

				if (options.autoHide)
					$a.add($o).hide();

				options.onSelectEnd(img, selection);

				jQuery(document).unbind('mousemove', selectingMouseMove);
				$border2.mousemove(areaMouseMove);
			});
		}
		else if (options.movable) {
			moveX = selection.x1 + imgOfs.left;
			moveY = selection.y1 + imgOfs.top;
			startX = event.pageX;
			startY = event.pageY;

			jQuery(document)
				.mousemove(movingMouseMove)
				.one('mouseup', function () {
					options.onSelectEnd(img, selection);

					jQuery(document).unbind('mousemove', movingMouseMove);
				});
		}
		else
			jQuery(img).mousedown(event);

		return false;
	}

	function aspectRatioXY()
	{
		x2 = Math.max(imgOfs.left, Math.min(imgOfs.left + imgWidth,
			x1 + Math.abs(y2 - y1) * aspectRatio * (x2 > x1 ? 1 : -1)));
		y2 = Math.round(Math.max(imgOfs.top, Math.min(imgOfs.top + imgHeight,
			y1 + Math.abs(x2 - x1) / aspectRatio * (y2 > y1 ? 1 : -1))));
		x2 = Math.round(x2);
	}

	function aspectRatioYX()
	{
		y2 = Math.max(imgOfs.top, Math.min(imgOfs.top + imgHeight,
			y1 + Math.abs(x2 - x1) / aspectRatio * (y2 > y1 ? 1 : -1)));
		x2 = Math.round(Math.max(imgOfs.left, Math.min(imgOfs.left + imgWidth,
			x1 + Math.abs(y2 - y1) * aspectRatio * (x2 > x1 ? 1 : -1))));
		y2 = Math.round(y2);
	}

	function selectingMouseMove(event)
	{
		x2 = !resize.length || resize[H] || aspectRatio ? event.pageX : selection.x2 + imgOfs.left;
		y2 = !resize.length || resize[V] || aspectRatio ? event.pageY : selection.y2 + imgOfs.top;

		if (options.minWidth && Math.abs(x2 - x1) < options.minWidth) {
			x2 = x1 - options.minWidth * (x2 < x1 ? 1 : -1);

			if (x2 < imgOfs.left)
				x1 = imgOfs.left + options.minWidth;
			else if (x2 > imgOfs.left + imgWidth)
				x1 = imgOfs.left + imgWidth - options.minWidth;
		}

		if (options.minHeight && Math.abs(y2 - y1) < options.minHeight) {
			y2 = y1 - options.minHeight * (y2 < y1 ? 1 : -1);

			if (y2 < imgOfs.top)
				y1 = imgOfs.top + options.minHeight;
			else if (y2 > imgOfs.top + imgHeight)
				y1 = imgOfs.top + imgHeight - options.minHeight;
		}

		x2 = Math.max(imgOfs.left, Math.min(x2, imgOfs.left + imgWidth));
		y2 = Math.max(imgOfs.top, Math.min(y2, imgOfs.top + imgHeight));

		if (aspectRatio)
			if (Math.abs(x2 - x1) / aspectRatio > Math.abs(y2 - y1))
				aspectRatioYX();
			else
				aspectRatioXY();

		if (options.maxWidth && Math.abs(x2 - x1) > options.maxWidth) {
			x2 = x1 - options.maxWidth * (x2 < x1 ? 1 : -1);
			if (aspectRatio) aspectRatioYX();
		}

		if (options.maxHeight && Math.abs(y2 - y1) > options.maxHeight) {
			y2 = y1 - options.maxHeight * (y2 < y1 ? 1 : -1);
			if (aspectRatio) aspectRatioXY();
		}

		selection.x1 = Math.min(x1, x2) - imgOfs.left;
		selection.x2 = Math.max(x1, x2) - imgOfs.left;
		selection.y1 = Math.min(y1, y2) - imgOfs.top;
		selection.y2 = Math.max(y1, y2) - imgOfs.top;
		selection.width = Math.abs(x2 - x1);
		selection.height = Math.abs(y2 - y1);

		update();

		options.onSelectChange(img, selection);

		return false;		
	}

	function movingMouseMove(event)
	{
		x1 = Math.max(imgOfs.left, Math.min(moveX + event.pageX - startX,
			imgOfs.left + imgWidth - selection.width));
		y1 = Math.max(imgOfs.top, Math.min(moveY + event.pageY - startY,
			imgOfs.top + imgHeight - selection.height));
		x2 = x1 + selection.width;
		y2 = y1 + selection.height;

		selection.x1 = x1 - imgOfs.left;
		selection.y1 = y1 - imgOfs.top;
		selection.x2 = x2 - imgOfs.left;
		selection.y2 = y2 - imgOfs.top;
		
		update();

		options.onSelectChange(img, selection);
		event.preventDefault();

		return false;
	}

	function imgMouseDown(event)
	{
		if (event.which != 1) return false;

		startX = x1 = event.pageX;
		startY = y1 = event.pageY;
		selection.x1 = selection.x2 = x1 - imgOfs.left;
		selection.y1 = selection.y2 = y1 - imgOfs.top;
		selection.width = 0;
		selection.height = 0;

		resize = [ ];

		update();
		$a.add($o).show();

		jQuery(document).mousemove(selectingMouseMove);
		$border2.unbind('mousemove', areaMouseMove);

		options.onSelectStart(img, selection);

		jQuery(document).one('mouseup', function () {
			if (options.autoHide)
				$a.add($o).hide();

			options.onSelectEnd(img, selection);

			jQuery(document).unbind('mousemove', selectingMouseMove);
			$border2.mousemove(areaMouseMove);
		});

		return false;
	}

	this.setOptions = function(newOptions)
	{
		options = jQuery.extend(options, newOptions);

		if (newOptions.x1 != null) {
			selection.x1 = newOptions.x1;
			selection.y1 = newOptions.y1;
			selection.x2 = newOptions.x2;
			selection.y2 = newOptions.y2;
			newOptions.show = true;
		}

		imgWidth = jQuery(img).width();
		imgHeight = jQuery(img).height();
		imgOfs = jQuery(img).offset();
		
		$p = jQuery(img);

		while ($p.length && !$p.is('body')) {
			if (!isNaN($p.css('z-index')) && $p.css('z-index') > zIndex)
				zIndex = $p.css('z-index');
			if ($p.css('position') == 'fixed') fixed = true;

			$p = $p.parent();
		}
	
		x1 = selection.x1 + imgOfs.left;
		y1 = selection.y1 + imgOfs.top;
		x2 = selection.x2 + imgOfs.left;
		y2 = selection.y2 + imgOfs.top;
		selection.width = x2 - x1;
		selection.height = y2 - y1;
		
		update();
		
		if (newOptions.hide)
			$a.add($o).hide();
		else if (newOptions.show)
			$a.add($o).show();

		$a.css({ borderWidth: options.borderWidth + 'px' });
		$area.css({ backgroundColor: options.selectionColor, opacity: options.selectionOpacity });
		$border1.css({ borderStyle: 'solid', borderColor: options.borderColor1 });
		$border2.css({ borderStyle: 'dashed', borderColor: options.borderColor2 });
		$o.css({ opacity: options.outerOpacity, backgroundColor: options.outerColor });

		aspectRatio = options.aspectRatio && (d = options.aspectRatio.split(/:/)) ?
			d[0] / d[1] : null;

		if (options.disable || options.enable === false) {
			$a.unbind('mousemove', areaMouseMove).unbind('mousedown', areaMouseDown);
			jQuery(img).add($o).unbind('mousedown', imgMouseDown);
		}
		else if (options.enable || options.disable === false) {
			if (options.resizable || options.movable)
				$a.mousemove(areaMouseMove).mousedown(areaMouseDown);

			jQuery(img).add($o).mousedown(imgMouseDown);
		}

		options.enable = options.disable = undefined;
	};

	if (jQuery.browser.msie)
		jQuery(img).attr('unselectable', 'on');
		
	$a.add($o).css({ display: 'none', position: fixed ? 'fixed' : 'absolute', overflow: 'hidden', 
		zIndex: zIndex > 0 ? zIndex : null });
	$area.css({ borderStyle: 'solid' });

	jQuery('body').append($o.add($a));

	initOptions = {
		borderColor1: '#000',
		borderColor2: '#fff',
		borderWidth: 1,
		movable: true,
		resizable: true,
		selectionColor: '#fff',
		selectionOpacity: 0.2,
		outerColor: '#000',
		outerOpacity: 0.2,
		onSelectStart: function () {},
		onSelectChange: function () {},
		onSelectEnd: function () {}
	};

	options = jQuery.extend(initOptions, options);
	this.setOptions(options);
};

jQuery.fn.imgAreaSelect = function (options) {
	options = options || {};

	this.each(function () {
		if (jQuery(this).data('imgAreaSelect'))
			jQuery(this).data('imgAreaSelect').setOptions(options);
		else {
			if (options.enable === undefined && options.disable === undefined)
				options.enable = true;

			jQuery(this).data('imgAreaSelect', new jQuery.imgAreaSelect(this, options));
		}
	});

	return this;
};
