/*!
 * jQuery lockfixed plugin
 * http://www.directlyrics.com/code/lockfixed/
 *
 * Copyright 2012 Yvo Schaap
 * Released under the MIT license
 * http://www.directlyrics.com/code/lockfixed/license.txt
 *
 * Date: Sun Jan 27 2013 12:00:00 GMT
 */
(function(d){d.extend({lockfixed:function(a,b){b&&b.offset?(b.offset.bottom=parseInt(b.offset.bottom,10),b.offset.top=parseInt(b.offset.top,10)):b.offset={bottom:100,top:0};if((a=d(a))&&a.offset()){var h=a.offset().top;a.offset();a.outerHeight(!0);var j=a.outerWidth(),m=a.css("position"),n=a.css("top"),c=parseInt(a.css("marginTop"),10),k=d(document).height()-b.offset.bottom,f=0,g=!1;if(!0===b.forcemargin||navigator.userAgent.match(/\bMSIE (4|5|6)\./)||navigator.userAgent.match(/\bOS (3|4|5|6)_/)||
navigator.userAgent.match(/\bAndroid (1|2|3|4)\./i))g=!0;d(window).bind("scroll resize orientationchange load",a,function(){var l=a.outerHeight(),e=d(window).scrollTop();if(!g||!(document.activeElement&&"INPUT"===document.activeElement.nodeName))e>=h-(c?c:0)-b.offset.top?(f=k<e+l+c+b.offset.top?e+l+c+b.offset.top-k:0,g?a.css({marginTop:parseInt((c?c:0)+(e-h-f)+2*b.offset.top,10)+"px"}):a.css({position:"fixed",top:b.offset.top-f+"px",width:j+"px"})):a.css({position:m,top:n,width:j+"px",marginTop:(c?
c:0)+"px"})})}}})})(jQuery);