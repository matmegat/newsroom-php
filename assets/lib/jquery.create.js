(function($, undefined) {
   
   // create elements without raw html
   $.create = function(tag, properties) {
      var element = $(document.createElement(tag));
      if (properties === undefined) return element;
      for (var idx in properties) 
         element.attr(idx, properties[idx]);
      return element;
   };

   // chained version of create
   $.fn.create = function(tag, properties) {
      var element = $.create(tag, properties);
      element.prevObject = this;
      this.append(element);
      return element;
   };
      
})(jQuery);
