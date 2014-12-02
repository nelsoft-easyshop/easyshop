/*! sidebarTabs v3.0 ~ Sean Catchpole - Copyright 2010 MIT/GPL */
;(function($){

/* Changes for 3.0
 - return removed (it was depreciated)
 - passing arguments changed (id,s,e)
 - refresh of code speed and accuracy
 - items now object instead of id's
 - multiple tabs can now point to same id
 - removed autoloading jQuery
 - added item classes support
 - toggle visibility
 - update or remove sidebarTabs
 - grouped areas
 - extend sidebarTabs
*/

/* Options (in any order):

 start (number|string)
    Index number of default tab. ex: $(...).sidebarTabs(0)
    String of id of default tab. ex: $(...).sidebarTabs("#tab1")
    default: class "selected" or index 0
    Passing -1 or null will force it to not select a default tab

 change (boolean)
    True - Url will change. ex: $(...).sidebarTabs(true)
    False - Url will not change. ex: $(...).sidebarTabs(false)
    default: false

 click (function)
    Function will be called when a tab is clicked. ex: $(...).sidebarTabs(function(id,s){...})
    If the function returns false, sidebarTabs will not take any action,
    otherwise sidebarTabs will show/hide the content (as usual).
    The function is passed three variables:
      The id of the element to be shown
      The settings object which has the following additional items:
        area     - the original area $(area).sidebarTabs();
        tabarea  - the current tab area used to find tabs
        tabs     - a jQuery list of tabs
        items    - a jQuery list of the elements the tabs point to
        tab(id)  - a helper function to find a tab with id
        item(id) - a helper function to find an item with id
      The event object that triggered sidebarTabs

 selected (string)
    Class to use for selected. ex: $(...).sidebarTabs(".current")
    default: ".selected"

 event (string)
    Event to trigger sidebarTabs on. ex: $(...).sidebarTabs("!mouseover")
    default: "!click"
    To bind multiple events, call sidebarTabs multiple times
      ex: $(...).sidebarTabs("!click").sidebarTabs("!focus")

 toggle (boolean)
    True - Toggle visibility of tab content. ex: $(...).sidebarTabs("!true")
    False - Ignore clicks on tabs already selected. ex: $(...).sidebarTabs("!false")
    default: false

 grouped (boolean)
    True - Groups all tabs in area together. ex: $(...).sidebarTabs(":grouped")
    False - jQuery selector is seperated into tab areas. ex: $(...).sidebarTabs(":!grouped")
    default: false

 update (boolean)
    True - Rebinds sidebarTabs ex: $(...).sidebarTabs(":update");
    False - Cancels update ex: $(...).sidebarTabs(":!update");

 remove (boolean)
    True - Removes sidebarTabs ex: $(...).sidebarTabs(":remove");
    False - Cancels removal ex: $(...).sidebarTabs(":!remove");

*/

// Helper functions
var sidebarTabs, //shortcut
undefined,  //speed up
href = function(e){ return $(e).attr("href"); },
type = function(o){ //reliable
  return o===null && "Null"
      || o===undefined && "Undefined"
      || ({}).toString.call(o).slice(8,-1);
};

$.fn.sidebarTabs = function(){
  var s = sidebarTabs.args.apply(this,arguments),
  action = s.update&&"update" || s.remove&&"remove" || "bind";
  s.area = this; //save context
  sidebarTabs[action](s);
  return this; //chainable
};

sidebarTabs = $.sidebarTabs = function(tabarea,options,data){
  // Settings
  var e, tabs, items, test=$(), meta = $.metadata?$(tabarea).metadata():{}, //metadata
  s = {tab:sidebarTabs.tab,item:sidebarTabs.item}; //helpers
  s = $.extend(s,sidebarTabs.settings,meta,options||{}); //settings
  s.tabarea = $(tabarea); //save context
  s.data = data||"sidebarTabs"+ +new Date; //save expando

  // Play nice
  $.each({selected:'.',event:'!',start:'#'},function(n,c){
    if(type(s[n])=="String" && s[n].indexOf(c)==0)
      s[n] = s[n].substr(1); }); //removes type characters
  if(s.start===null) s.start=-1; //no tab selected

  // Find tabs
  items = []; //save elements
  s.tabs = tabs = $("a[href^=#]",tabarea); //save tabs
  tabs.each(function(){ //add items
    test = s.item(href(this));
    if(test.length) items=items.concat(test.get());
  });
  s.items = $(items).hide(); //hide items

  // Save Settings
  e="sidebarTabs."+s.event;
  data=s.tabarea.data("sidebarTabs")||{};
  data[e]=s;
  s.tabarea.data("sidebarTabs",data);

  // Bind sidebarTabs
  tabs.trigger(e).data(s.data,s)
      .bind(e,{s:s},function(){ //wrapper function due to jQuery bug
        return sidebarTabs.unbind.apply(this,arguments); })
      .bind(s.event,{s:s},sidebarTabs.find);

  // Select default tab
     type(s.start) == "Number" && (s.start<0 || (test=tabs.eq(s.start)).length)
  || type(s.start) == "String" && (test=tabs.filter("a[href=#"+s.start+"]")).length
  || (test=tabs.filter('.'+s.selected).removeClass(s.selected)).length
  || (s.start===undefined && (test=tabs.eq(0)).length);
  if(test.length) test.trigger(s.event);

  return s; //return current settings (be creative)
};

// Parse arguments into settings
sidebarTabs.args = function(){
  var a,i=0,s={},args=arguments,
  // Handle string flags .!:
  str = function(_,a){
    if(a.indexOf('.')==0) s.selected = a;
    else if(a.indexOf('!')==0)
      if(/^!(true|false)$/i.test(a)) s.toggle = /^!true$/i.test(a);
      else s.event = a;
    else if(a.indexOf(':')==0) {
      a=a.substr(1).toLowerCase();
      if(a.indexOf('!')==0) s[a.substr(1)]=false;
      else s[a]=true;
    } else if(a) s.start = a;
  };
  // Loop through arguments matching options
  while(i<args.length) {
    a=args[i++];
    switch(type(a)){
      case "Object"   : $.extend(s,a); break;
      case "Boolean"  : s.change = a;  break;
      case "Number"   : s.start = a;   break;
      case "Function" : s.click = a;   break;
      case "Null"     : s.start = a;   break;
      case "String"   : $.each(a.split(/\s+/g),str);
      default: break;
    }
  }
  return s; //settings object
};

// Bind sidebarTabs
sidebarTabs.bind = function(s){
  if(!s) return;
  var data = "sidebarTabs"+ +new Date; //instance expando
  if(s.grouped) $.sidebarTabs(s.area,s,data);
  else s.area.each(function(){ $.sidebarTabs(this,s,data); });
};

// Rebind sidebarTabs
sidebarTabs.update = function(s){
  if(!s) return;
  s.update=false;
  var self,data,n,e = s.event;
  e = (e+"").indexOf('!')==0 && e.substr(1) || e;
  e = e?"sidebarTabs."+e:"";
  return s.area.each(function(){
    self = $(this);
    data = self.data("sidebarTabs");
    if(!data) return;
    if(e) {
      n=$.extend({},data[e],s);
      sidebarTabs.remove(data[e])
      sidebarTabs(n.tabarea,n,n.data);
    } else for(e in data) {
      if(!Object.hasOwnProperty.call(data, e)) continue;
      n=$.extend({},data[e],s);
      sidebarTabs.remove(data[e]);
      sidebarTabs(n.tabarea,n,n.data);
    }
  });
};

// Unbind sidebarTabs
sidebarTabs.remove = function(s){
  if(!s) return;
  var data,tabs,e = s.event;
  e = (e+"").indexOf('!')==0 && e.substr(1) || e;
  e = "sidebarTabs"+(e?"."+e:"");
  return s.area.each(function(){
    data=$(this).data("sidebarTabs");
    delete data["sidebarTabs."+s.event];
    $(this).data("sidebarTabs",data);
    tabs = s.tabs || $("a[href^=#]",this); //save tabs
    if(!tabs.length && $(this).is("a[href^=#]")) tabs = $(this);
    tabs.trigger(e);
  });
};

// Find tabs
sidebarTabs.find = function(e){
  // Save self since clicked tab may not be the first tab in the tabarea
  var self=this, ret=false, s=e.data.s;
  // Find first tab within each tabset
  $("a[href="+href(this)+"]:first",s.area).each(function(){
    var t = $(this).data(s.data); //tab's settings
    if(t) ret=sidebarTabs.showtab.call(t.tabarea==s.tabarea?self:this,t,e)||ret;
  });
  return ret;
};

// Show tab
sidebarTabs.showtab = function(s,e){
  if(!s || !s.toggle && $(this).is('.'+s.selected))
    return s&&s.change; //return if already selected
  var id = href(this); //find id
  if(s.click && s.click.call(this,id,s,e)==false) return s.change; //call custom func
  if(s.toggle && $(this).is('.'+s.selected)) id=null; //hide items
  return sidebarTabs.show.call(this,id,s,e); //call default func
};

// Show item
sidebarTabs.show = function(id,s){
  s.tabs.removeClass(s.selected); //clear tabs
  s.tab(id).addClass(s.selected); //select tab(s)
  s.items.hide(); //hide all items
  s.item(id).show(); //show item(s)
  return s.change; //option for changing url
};

// Unbind sidebarTabs
sidebarTabs.unbind = function(e){
  var s = e.data.s;
  $(this).removeData(s.data)
  .unbind("sidebarTabs."+s.event);
  return false;
};

// Extend sidebarTabs
sidebarTabs.extend = function(){
  var args = arguments;
  return function(){
    [].push.apply(args,arguments);
    this.sidebarTabs.apply(this,args);
  };
};

// Matching tabs
sidebarTabs.tab = function(id){
  if(!id) return $([]);
  return $("a[href="+id+"]",this.tabarea);
};

// Matching items
sidebarTabs.item = function(id){
  if(!id) return $([]);
  var item = $(id);
  return item.length?item:$('.'+id.substr(1));
};

// Defaults
sidebarTabs.settings = {
  start:undefined,
  change:false,
  click:null,
  selected:".selected",
  event:"!click",
  toggle:false,
  grouped:false
};

// Version
sidebarTabs.version = "3.0";

// Auto-run
$(function(){ $(".sidebarTabs").sidebarTabs(); });

})(jQuery);
