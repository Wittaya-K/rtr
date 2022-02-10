(function () {
  "use strict";
  var jQueryPlugin = (window.jQueryPlugin = function (ident, func) {
    return function (arg) {
      if (this.length > 1) {
        this.each(function () {
          var $this = $(this);

          if (!$this.data(ident)) {
            $this.data(ident, func($this, arg));
          }
        });

        return this;
      } else if (this.length === 1) {
        if (!this.data(ident)) {
          this.data(ident, func(this, arg));
        }

        return this.data(ident);
      }
    };
  });
})();

(function () {
  "use strict";
  function Pass_Show_Hide($root) {
    const element = $root;
    const pass_target = $root.first("data-password");
    const pass_elemet = $root.find("[data-pass-target]");
    const pass_show_hide_btn = $root.find("[data-pass-show-hide]");
    const pass_show = $root.find("[data-pass-show]");
    const pass_hide = $root.find("[data-pass-hide]");
    $(pass_hide).hide();
    $(pass_show_hide_btn).click(function () {
      if (pass_elemet.attr("type") === "password") {
        pass_elemet.attr("type", "text");
        $(pass_show).hide();
        $(pass_hide).show();
      } else {
        pass_elemet.attr("type", "password");
        $(pass_hide).hide();
        $(pass_show).show();
      }
    });
  }
  $.fn.Pass_Show_Hide = jQueryPlugin("Pass_Show_Hide", Pass_Show_Hide);
  $("[data-password]").Pass_Show_Hide();
  
  function aksSelect($root) {
    const element = $root;
    element.hide();
    
    const options = $root.find("option");
    const firstoption = $root.find("option:first");
    const selectedoption = $root.find("option:selected");
    const id = "aks-select-" + Math.floor(Math.random() * 100);
    var html = '<div class="aks-select-box" id="'+ id +'">';
    function activeOption(option){
      return '<div class="aks-select-active" data-option="'+ option.val() +'"><div class="aks-select-arrow"><svg viewBox="0 0 24 24" width="17" height="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" shape-rendering="geometricPrecision"><path d="M6 9l6 6 6-6"></path></svg></div><span>'+ option.text() +'</span></div>';
    }
      html += activeOption($(selectedoption));
     
    html += '<div class="aks-select-list"><div class="aks-select-p">';
    $(options).each(function() {
    html += '<div class="aks-select-item" data-option="'+ $(this).val() +'">'+ $(this).text() +'</div>';  
		});
    html += '</div></div>';
    html += '</div>';
    $(html).insertBefore(element);
    const optionactive = $("#" + id).find(".aks-select-active");
    const optionactivetext = $("#" + id).find(".aks-select-active span");
    const optionlist = $("#" + id).find(".aks-select-list");
    const optionitem = $("#" + id).find(".aks-select-item");
    $(optionactive).on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(optionlist).toggleClass("opened");
      $(optionactive).toggleClass("focus");
    }); 
    $('body').on("click", function (e) {
    $(optionlist).removeClass("opened");
    $(optionactive).removeClass("focus");
    });

     $(optionitem).on("click", function (e) {
      var optionvalue = $(this).attr("data-option");
      var optiontext = $(this).text();
      $(optionactivetext).html(optiontext);
      $(element).val(optionvalue); 
      $(this).addClass("active");
      $(this).siblings().removeClass("active");			
	    if($(this).hasClass("active")){
      const options1 = $root.find('option[value="'+ optionvalue +'"]');
	    $(options1).attr('selected', 'selected');
	    $(options1).siblings().removeAttr('selected', 'selected');	
	    }

       
    });
    
  }
  $.fn.aksSelect = jQueryPlugin("aksSelect", aksSelect);
  $("[data-select]").aksSelect();
  
  function Textarea_Auto_Height($root) {
    const element = $root;
    const textarea_auto_height = $root.first("textarea-auto-height");
    function textarea_auto_height_resize(){
    var rows = $(textarea_auto_height).attr('rows');
      
    function textarea_resize ($auto_height) {
    $auto_height.css('height', 'auto');
    $auto_height.css('height', $auto_height[0].scrollHeight+'px');
    }  
      
    textarea_auto_height.each(function(){
     $(this).attr('rows', rows);
     textarea_resize($(this));
    });

   $(textarea_auto_height)
  .keyup(function () {
   textarea_resize($(this));
  })
  .keyup();
    $(textarea_auto_height).css('overflow','hidden');
    $(textarea_auto_height).css('resize','none');  
}
textarea_auto_height_resize();
  }
  $.fn.Textarea_Auto_Height = jQueryPlugin("Textarea_Auto_Height", Textarea_Auto_Height);
  $("[data-textarea-auto-height]").Textarea_Auto_Height();
  
  function Guantity($root) {
    const element = $root;
    const quantity = $root.first("data-quantity");
    const quantity_target = $root.find("[data-quantity-target]");
    const quantity_minus = $root.find("[data-quantity-minus]");
    const quantity_plus = $root.find("[data-quantity-plus]");
    var quantity_ = quantity_target.val();
    $(quantity_minus).click(function () {
      quantity_target.val(--quantity_);
    });
    $(quantity_plus).click(function () {
      quantity_target.val(++quantity_);
    });
  }
  $.fn.Guantity = jQueryPlugin("Guantity", Guantity);
  $("[data-quantity]").Guantity();
  
  function aksPin($root) {
    const element = $root;
    const pininputs = $root.find("input");
    $(pininputs).on("keyup", function (e) {
      if (e.keyCode == 8 || e.keyCode == 48) {
        $(e.currentTarget).prev().select();
        $(e.currentTarget).prev().focus();
      } else {
        if ($(e.currentTarget).val() != "") {
          $(e.currentTarget).next().select();
          $(e.currentTarget).next().focus();
        }
      }
    });
      
  }
  $.fn.aksPin = jQueryPlugin("aksPin", aksPin);
  $("[data-pin]").aksPin();
  
    function aksNumpad($root) {
    const element = $root;
    const numpadBtn = $root.find("[data-numpad-val]");
    const numpadDelete = $root.find("[data-numpad-del]");
    const numpadTarget = $root.find("[data-numpad-target]");
    $(numpadBtn).click(function(){            
      $(numpadTarget).val(numpadTarget.val() + $(this).attr("data-numpad-val"));
      $(numpadTarget).focus();
    });
    $(numpadDelete).click(function(){            
      $(numpadTarget).val($(numpadTarget).val().slice(0, -1));
      $(numpadTarget).focus();
   });
      
      
    }
  $.fn.aksNumpad = jQueryPlugin("aksNumpad", aksNumpad);
  $("[data-numpad]").aksNumpad();
  
  
    function loadingBtn($root) {
    const element = $root;
    const timeout = element.attr("data-loading") ?? 3000;

    $(element).click(function (e) {
      var lastHtml = $(element).html();
      $(element).html('<div class="aks-spinner"></div>');
      setTimeout(function () {
        $(element).html(lastHtml);
      }, timeout);
    });
  }
  $.fn.loadingBtn = jQueryPlugin("loadingBtn", loadingBtn);
  $("[data-loading]").loadingBtn();
  
})();