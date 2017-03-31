
/**
* @version 1.0.1
* @package PWebFBLikeBox
* @copyright © 2012 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*
* Mootools 1.11+, 1.2.4+
*/
pwebFBLikeBox=new Class({Implements:[Options],options:{prefix:"pwebfblikebox",open:"click",close:"click",position:"left",top:-1,layout:"box"},initialize:function(a){this.setOptions(a);this.hidden=true;this.fx=Fx.Tween?new Fx.Tween(this.options.prefix,{property:this.options.position}):new Fx.Style(this.options.prefix,this.options.position);this.width=0-$(this.options.prefix).getSize().size.x;$(this.options.prefix).setStyle(this.options.position,this.width).inject($$("body")[0]);if(this.options.open==this.options.close){$(this.options.prefix).addEvent(this.options.open,function(c){new Event(c).stop();this.toggleBox()}.bind(this))}else{$(this.options.prefix).addEvent(this.options.open,function(c){new Event(c).stop();this.toggleBox(1)}.bind(this));$(this.options.prefix).addEvent(this.options.close,function(c){new Event(c).stop();this.toggleBox(0)}.bind(this))}if(this.options.layout=="slidebox"){if(this.options.top>=0){$(this.options.prefix).setStyle("top",this.options.top)}}else{if(this.options.layout=="sidebar"){var b=parseInt($(this.options.prefix).getStyle("padding-top"))+parseInt($(this.options.prefix).getStyle("padding-bottom"))+parseInt($(this.options.prefix).getStyle("border-top"))+parseInt($(this.options.prefix).getStyle("border-bottom"));$(this.options.prefix).setStyles({top:0,height:window.getHeight()-b});if(this.options.top>=0){$(this.options.prefix).getFirst().setStyle("top",this.options.top)}}}},toggleBox:function(a){if(typeof a=="undefined"){a=-1}if((!this.hidden&&a==-1)||a==0){this.fx.start(this.width);this.hidden=true}else{if((this.hidden&&a==-1)||a==1){this.fx.start(0);this.hidden=false}}}});pwebFBLikeBox.implement(new Options);