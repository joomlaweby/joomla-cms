jQuery(function(a){a(".assignmentselection").each(function(){var s=a(this),l=a(this).closest(".control-group").parent();fix=s.parent().hasClass("well-assign"),fix?((l=a(this).closest(".well-assign")).children(":last-child").addClass("assign-options"),l.children().not(":last-child").wrapAll('<div class="control-group assignmentselection">'),l.find(".assignmentselection label").wrap('<div class="control-label"></div>'),l.find(".assignmentselection .control-label").css({"padding-right":"20px"})):(l.children().not(":first-child").wrapAll('<div class="assign-options">'),l.children().filter(":first-child").addClass("assignmentselection")),l.hasClass("assign")||l.addClass("assign"),s.on("change",function(){l.removeClass("alert-success alert-danger"),s.removeClass("custom-select-color-state custom-select-success custom-select-danger"),0<a(this).val()?(l.find(".assign-options").slideDown("fast"),class_="1"==a(this).val()?"alert-success":"alert-danger",l.addClass(class_),s.addClass("custom-select-color-state").addClass("1"==a(this).val()?"custom-select-success":"custom-select-danger")):l.find(".assign-options").slideUp("fast")}).trigger("change")})});
