/*
 * http://think2loud.com/147-build-an-unsupported-browser-warning-with-jquery/
 */
function badBrowser() {
	// supported options for .browser
	//    webkit (as of jQuery 1.4)
	//    safari (deprecated)
	//    opera
	//    msie
	//    mozilla

	// check for unsupported webkit versions
	if ($.browser.webkit) {
		return true;
	}
	
	// check for unsupported safari versions
	if ($.browser.safari && parseInt($.browser.version) <= 6) {
		return true;
	}
	
	// check for unsupported safari versions
	if ($.browser.opera) {
		return true;
	}
	
	// check for unsupported IE versions
	if ($.browser.msie && parseInt($.browser.version) < 8) {
		return true;
	}

	// check for unsupported FF versions
	if ($.browser.mozilla && parseInt($.browser.version) < 8) {
		return true;
	}

	return false;
}

function getBadBrowser(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end == -1)
				c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return "";
}

function setBadBrowser(c_name, value, expiredays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" + escape(value)
			+ ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}

try {
	$(document).ready(function() {

		if (badBrowser() && getBadBrowser('browserWarning') != 'seen') {
			$(function() {
				$(
						//&nbsp;&nbsp;&nbsp;[<a href='#' id='warningClose'>close</a>] 
						"<div class='browser-error' id='browserWarning'><B>You are using an unsupported browser. Please switch to <a href='http://getfirefox.com'>FireFox 7+</a> or <a href='http://www.microsoft.com/windows/downloads/ie/getitnow.mspx'>Internet Explorer 8+</a>. Thank you!</B> &nbsp;&nbsp;&nbsp;[<a href='#' id='warningClose'>close</a>]</div> ")
						.css({
							'width' : '100%',
							'border-top' : 'solid 1px #000',
							'border-bottom' : 'solid 1px #000',
							'text-align' : 'center',
							padding : '5px 0px 5px 0px'
						}).prependTo("body");
		
				$('#warningClose').click(function() {
					setBadBrowser('browserWarning', 'seen');
					$('#browserWarning').slideUp('slow');
					return false;
				});
			});
		}
	
	}); 
} catch (e) {
	console.log (e.message);    //this executes if jQuery isn't loaded
}
	
	
	
