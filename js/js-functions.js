var BackgroundRepeat, BackgroundColor, BackgroundImage, BackgroundImageSmall, FontTextColor, FontHeaderColor, FontLinkColor, TextBackgroundColor, FontTextBackgroundColor


RGB2HEX2 = function(rgb){
   function hex(c){
      c=parseInt(c).toString(16);
      return c.length<2?"0"+c:c;
   }
   return ("#" + hex(rgb.r) + hex(rgb.g) + hex(rgb.b)).toUpperCase();
};

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

parseColor2 = function(colorText){
   var sType = typeof(colorText);
   if (sType == "string"){
      if(/^\#?[0-9A-F]{6}$/i.test(colorText)){
	      return {
		            r: eval('0x'+colorText.substr(colorText.length==6?0:1, 2)),
		            g: eval('0x'+colorText.substr(colorText.length==6?2:3, 2)),
		            b: eval('0x'+colorText.substr(colorText.length==6?4:5, 2)),
		            a: 255
	            };
      }
   }else if ( sType == "object" ){
      if ( colorText.hasOwnProperty("r") &&  
           colorText.hasOwnProperty("g") && 
           colorText.hasOwnProperty("b") ){
         return colorText;
      }           
   }
   return null;
};



function updateBackground()
{
	/*
	jQuery('#user_image').attr('src', BackgroundImageSmall);

	jQuery('.side-section').css('background-color', FontTextBackgroundColor);
	jQuery('.side-section').css('color', FontTextColor);
	jQuery('.side-section a').css('color', FontLinkColor);
	jQuery('.side-section h3').css('color', FontHeaderColor);

	jQuery('#leftcol_user_info').css('color', FontTextColor);
	jQuery('#leftcol_user_info a').css('color', FontLinkColor);

	jQuery('#rightcol_user_info').css('color', FontTextColor);
	jQuery('#rightcol_user_info a').css('color', FontLinkColor);


	jQuery('#leftcol_user_info .headerstyle').css('color', FontHeaderColor);
	jQuery('#leftcol_user_info .normaltextstyle').css('color', FontTextColor);

	jQuery('#rightcol_user_info .headerstyle').css('color', FontHeaderColor);
	jQuery('#rightcol_user_info .normaltextstyle').css('color', FontTextColor);

	rgba_str = 'rgba(' + hexToRgb(FontTextBackgroundColor).r + ',' + hexToRgb(FontTextBackgroundColor).g + ',' + hexToRgb(FontTextBackgroundColor).b +', 0.75)';
	jQuery('.design-side-section').css('background', rgba_str );
	jQuery('.design-side-section').css('color', FontTextColor);
	jQuery('.design-side-section a').css('color', FontLinkColor);
	jQuery('.design-side-section h3').css('color', FontHeaderColor);
	*/

    jQuery('body').css({"background":"url("+BackgroundImage+") "+BackgroundRepeat+" "+BackgroundColor});
	/*
	jQuery('#footer').css('color',FontTextColor);
	jQuery('#footer a').css('color',FontLinkColor);
	jQuery('#footer h5').css('color',FontHeaderColor);

	$("#BackGroundColorSelector").css({"background-color":BackgroundColor});
	$("#FontTextColorSelector").css({"background-color":FontTextColor});
	$("#FontLinkColorSelector").css({"background-color":FontLinkColor});
	$("#FontHeaderColorSelector").css({"background-color":FontHeaderColor});
	$("#FontTextBackgroundColorSelector").css({"background-color":FontTextBackgroundColor});

	BackGroundColorGlobal = BackgroundColor;
	FontTextColorGlobal   = FontTextColor;
	FontLinkColorGlobal   = FontLinkColor;
	FontHeaderColorGlobal = FontHeaderColor;
	FontTextBackgroundColorGlobal = FontTextBackgroundColor;


	jQuery("input[name='form_BackgroundImage']").val(BackgroundImage);
	jQuery("input[name='form_BackgroundColor']").val(BackgroundColor);
	jQuery("input[name='form_TextColor']").val(FontTextColor);
	jQuery("input[name='form_LinkColor']").val(FontLinkColor);
	jQuery("input[name='form_HeaderColor']").val(FontHeaderColor);
	jQuery("input[name='form_TextBackgroundColor']").val(FontTextBackgroundColor);
	*/
}

function setBackgroundValues(xBackgroundRepeat, xBackgroundColor, xBackgroundImage, xBackgroundImageSmall, xFontTextColor, xFontHeaderColor, xFontLinkColor, xFontTextBackgroundColor)
{
	BackgroundRepeat = xBackgroundRepeat;

//	jQuery("#form_TileBackground").removeAttr('checked');
//	if (BackgroundRepeat=="repeat")	{ jQuery("#form_TileBackground").attr('checked', true); }

	FontTextColor      = xFontTextColor;
	FontHeaderColor    = xFontHeaderColor;
	FontLinkColor      = xFontLinkColor;
	BackgroundColor    = xBackgroundColor;
	FontTextBackgroundColor = xFontTextBackgroundColor; 
	BackgroundImage = xBackgroundImage;
	BackgroundImageSmall = xBackgroundImageSmall;

//	for (var i=0; i<=10; i++) {	jQuery('#theme'+i).removeClass("theme-selector-active").addClass("theme-selector-border"); }
//	jQuery('#nobackground').removeClass("theme-selector-active").addClass("theme-selector-border");

	updateBackground();
}

function trim(s) { return jQuery.trim(s); } 

function addslashes (str) {
    // Escapes single quote, double quotes and backslash characters in a string with backslashes  
    // 
    // version: 1107.2516
    // discuss at: http://phpjs.org/functions/addslashes    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ates Goral (http://magnetiq.com)
    // +   improved by: marrtins
    // +   improved by: Nate
    // +   improved by: Onno Marsman    // +   input by: Denny Wardhana
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Oskar Larsson Högfeldt (http://oskar-lh.name/)
    // *     example 1: addslashes("kevin's birthday");
    // *     returns 1: 'kevin\'s birthday'    
	return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}