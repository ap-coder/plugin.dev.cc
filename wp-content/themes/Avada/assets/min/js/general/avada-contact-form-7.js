jQuery(document).ajaxComplete(function(){var a="";jQuery(".wpcf7-form .fusion-slider-loading").hide(),jQuery(".wpcf7-response-output").each(function(){jQuery(this).hasClass("wpcf7-mail-sent-ng")&&!jQuery(this).find(".alert-icon").length&&(jQuery(this).addClass("fusion-alert error fusion-danger"),jQuery(this).hasClass("alert-dismissable")&&(a='<button class="close toggle-alert" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'),a+='<div class="fusion-alert-content-wrapper"><span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle"></i></span><span class="fusion-alert-content">'+jQuery(this).html()+"</span>",jQuery(this).html(a)),jQuery(this).hasClass("wpcf7-validation-errors")&&!jQuery(this).find(".alert-icon").length&&(jQuery(this).addClass("fusion-alert error fusion-danger"),jQuery(this).hasClass("alert-dismissable")&&(a='<button class="close toggle-alert" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'),a+='<div class="fusion-alert-content-wrapper"><span class="alert-icon"><i class="fa-lg fa fa-exclamation-triangle"></i></span><span class="fusion-alert-content">'+jQuery(this).html()+"</span>",jQuery(this).html(a)),jQuery(this).hasClass("wpcf7-mail-sent-ok")&&!jQuery(this).find(".alert-icon").length&&(jQuery(this).addClass("fusion-alert success fusion-success"),jQuery(this).hasClass("alert-dismissable")&&(a='<button class="close toggle-alert" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>'),a+='<div class="fusion-alert-content-wrapper"><span class="alert-icon"><i class="fa-lg fa fa-check-circle"></i></span><span class="fusion-alert-content">'+jQuery(this).html()+"</span>",jQuery(this).html(a))}),jQuery(".wpcf7-response-output.fusion-alert .close").click(function(a){a.preventDefault(),jQuery(this).parent().slideUp()})}),jQuery(document).ready(function(){jQuery('<div class="fusion-slider-loading"></div>').insertAfter(".wpcf7 .ajax-loader"),jQuery(".wpcf7 .ajax-loader").remove(),jQuery(".wpcf7-form .wpcf7-submit").on("click",function(){jQuery(this).parents(".wpcf7-form").find(".fusion-slider-loading").show()})});