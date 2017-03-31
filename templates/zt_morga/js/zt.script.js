window.addEvent('load', function(){

	var StyleCookie = new Hash.Cookie('ZTMorgaStyleCookieSite');
	var settings = { colors: '' };
	var style_1, style_2, style_3;
	
	if($('ztcolor1')){$('ztcolor1').addEvent('click', function(e) {
		e = new Event(e).stop();
		if (style_2) style_2.remove();
		new Asset.css(ztpathcolor + 'blue.css', {id: 'blue'});
		style_2 = $('blue');
		settings['blue'] = ztpathcolor + 'blue.css';
		StyleCookie.empty();
		StyleCookie.extend(settings);
	});}

	
	if($('ztcolor2')){$('ztcolor2').addEvent('click', function(e) {
		e = new Event(e).stop();
		if (style_1) style_1.remove();
		new Asset.css(ztpathcolor + 'orange.css', {id: 'orange'});
		style_1 = $('orange');
		settings['colors'] = ztpathcolor + 'orange.css';
		StyleCookie.empty();
		StyleCookie.extend(settings);
	});}

	
	if($('ztcolor3')){$('ztcolor3').addEvent('click', function(e) {
		e = new Event(e).stop();
		if (style_3) style_3.remove();
		new Asset.css(ztpathcolor + 'black.css', {id: 'black'});
		style_3 = $('black');
		settings['colors'] = ztpathcolor + 'black.css';
		StyleCookie.empty();
		StyleCookie.extend(settings);
	});}

});
