var new_id = 0;
var order = 0;

jQuery.noConflict();
jQuery(function($) {
	order = parseInt($("#last_order").val());
	//$("div#wpbody-content").css({"width": "-=165"});
	$("div.form_designer div.settings").hide();
	$("#settings_entry_subject").hide();
	$("#datastream").hide();
	$("#settings_entry_email").show();
	$("#settings_entry_min").show();
	$("#settings_entry_max").show();
	$("#settings_required_data").hide();
	
	/* Click Event :: Form Element*/
	addClickManagerEvent();
	
	/* Click Event :: Save */
	$("div.form_designer span.save").click(function() {
		$("#actionizer").submit();
	});
	
	/* Click Event :: Delete */
	$("div.form_designer .my_form span.delete").click(function() {
		if(confirm('Möchten Sie dieses Element wirklich Löschen?')) {
			var id = $(this).attr("id");
			$("#delete_data").val($("#delete_data").val() + id + ";");
			$(this).parent().parent().remove();
		}
	});
	
	/* Click Event :: UP */
	$("div.form_designer .my_form span.up").click(function() {
		var id = $(this).attr("id");
		$("#order_data").val("UP_" + id + "");
		setTimeout('submitData();', 1000);
	});
	
	/* Click Event :: DOWN */
	$("div.form_designer .my_form span.down").click(function() {
		var id = $(this).attr("id");
		$("#order_data").val("DOWN_" + id + "");
		setTimeout('submitData();', 1000);
	});
	
	/* Change Event */
	// Name
	$("#settings_name").change(function() {
		var id = $("#settings_id").val();
		var required = $('[name="field_' + id + '_required"]').val();
		$('[name="field_' + id + '_name"]').val($(this).val());
		$('div.my_form div#' + id + " label").html($(this).val() + ":" + (required == 1 ? "<strong>*</strong>" : ""));
	});
	
	// Inhalt
	$("#settings_value").change(function() {
		var id = $("#settings_id").val();
		$('[name="field_' + id + '_value"]').val($(this).val());
		$('div.my_form div#' + id + ' div[class*="fake_"]').html($(this).val());
	});
	
	// Erforderlich
	$("#settings_required").change(function() {
		var id = $("#settings_id").val();
		$('[name="field_' + id + '_required"]').val(($(this).val() == "yes" ? "1" : "0"));
		var required = $('[name="field_' + id + '_required"]').val();
		$('div.my_form div#' + id + " label").html($('[name="field_' + id + '_name"]').val() + ":" + (required == 1 ? "<strong>*</strong>" : ""));
		if(required == 1) {
			$("#settings_required_data").show();
		} else {
			$("#settings_required_data").hide();
		}
	});
	
	// Required :: NULL
	$("#settings_required_null").change(function() {
		var newdata;
		var id 		= $("#settings_id").val();
		var type	= $('[name="field_' + id + '_type"]').val();
		var data	= $('[name="field_' + id + '_required_data"]').val();
		data = data.split(";");
		/*
			0 = NULL
			1 = MIN
			2 = MAX
			3 = MAIL
			4 = SUBJECT FIELD
		*/
		if(type == "list") {
			newdata = ($(this).val() == "yes" ? "1" : "0") + ";0;0;0;" + data[4] + ";";
		} else {
			newdata = ($(this).val() == "yes" ? "1" : "0") + ";" + data[1] + ";" + data[2] + ";" + data[3] + ";" + data[4] + ";";
		}
		$('[name="field_' + id + '_required_data"]').val(newdata);
	});
	
	// Required :: MIN
	$("#settings_required_min").change(function() {
		var newdata;
		var id 		= $("#settings_id").val();
		var type	= $('[name="field_' + id + '_type"]').val();
		var data	= $('[name="field_' + id + '_required_data"]').val();
		data = data.split(";");
		/*
			0 = NULL
			1 = MIN
			2 = MAX
			3 = MAIL
			4 = SUBJECT FIELD
		*/
		if(type == "list") {
			newdata = data[0] + ";0;0;0;" + data[4] + ";";
		} else {
			newdata = data[0] + ";" + $(this).val() + ";" + data[2] + ";" + data[3] + ";" + data[4] + ";";
		}
		$('[name="field_' + id + '_required_data"]').val(newdata);
	});
	
	// Required :: MAX
	$("#settings_required_max").change(function() {
		var newdata;
		var id 		= $("#settings_id").val();
		var type	= $('[name="field_' + id + '_type"]').val();
		var data	= $('[name="field_' + id + '_required_data"]').val();
		data = data.split(";");
		/*
			0 = NULL
			1 = MIN
			2 = MAX
			3 = MAIL
			4 = SUBJECT FIELD
		*/
		if(type == "list") {
			newdata = data[0] + ";0;0;0;" + data[4] + ";";
		} else {
			newdata = data[0] + ";" + data[1] + ";" + $(this).val() + ";" + data[3] + ";" + data[4] + ";";
		}
		$('[name="field_' + id + '_required_data"]').val(newdata);
	});
	
	// Required :: MAIL
	$("#settings_required_email").change(function() {
		var newdata;
		var id 		= $("#settings_id").val();
		var type	= $('[name="field_' + id + '_type"]').val();
		var data	= $('[name="field_' + id + '_required_data"]').val();
		data = data.split(";");
		/*
			0 = NULL
			1 = MIN
			2 = MAX
			3 = MAIL
			4 = SUBJECT FIELD
		*/
		if(type == "list") {
			newdata = data[0] + ";0;0;0;" + data[4] + ";";
		} else {
			newdata = data[0] + ";" + data[1] + ";" + data[2] + ";" + ($(this).val() == "yes" ? "1" : "0") + ";" + data[4] + ";";
		}
		$('[name="field_' + id + '_required_data"]').val(newdata);
	});
	
	// Required :: SUBJECT
	$("#settings_subject").change(function() {
		var newdata;
		var id 		= $("#settings_id").val();
		var type	= $('[name="field_' + id + '_type"]').val();
		var data	= $('[name="field_' + id + '_required_data"]').val();
		data = data.split(";");
		/*
			0 = NULL
			1 = MIN
			2 = MAX
			3 = MAIL
			4 = SUBJECT FIELD
		*/
		if(type == "list") {
			newdata = data[0] + ";0;0;0;" + ($(this).val() == "yes" ? "1" : "0") + ";";
		} else {
			newdata = data[0] + ";" + data[1] + ";" + data[2] + ";" + data[3] + ";0;";
		}
		$('[name="field_' + id + '_required_data"]').val(newdata);
	});
});

function submitData() {
	jQuery.noConflict();
	jQuery(function($) {
		$("#actionizer").submit();
	});
}

function addClickManagerEvent() {
	jQuery.noConflict();
	jQuery(function($) {
		$("div.form_designer span.data").click(function() {
			$("#datastream").show();
			$("#datastream").html("");
			var id			= $("#settings_id").val();
			var data		= $('[name="field_' + id + '_subject_data"]').val();
			var subjects	= data.split(";");
			
			for(i = 0; i < subjects.length; i++) {
				if(subjects[i] != "") {
					if($("#settings_subject").val() == "yes") {
						var dat = subjects[i].split(":");
						$("#datastream").append("<div class=\"entry\"><input type=\"text\" class=\"entry_subject_data\" name=\"name\" value=\"" + dat[0] + "\" /><input type=\"text\" class=\"entry_subject_data\" name=\"value\" value=\"" + dat[1] + "\" /><span class=\"del\"></span></div>");
					} else {
						$("#datastream").append("<div class=\"entry\"><input type=\"text\" class=\"entry_subject_data\" name=\"entry\" value=\"" + subjects[i] + "\" /><span class=\"del\"></span></div>");
					}
				}
			}
			
			$("#datastream").append("<input type=\"button\" name=\"entry_add\" value=\"+\" /> <input type=\"button\" name=\"entry_save\" value=\"Speichern\" />");
			
			$("#datastream span.del").click(function() {
				if(confirm('Möchten Sie dieses Element wirklich Löschen?')) {
					$(this).parent().remove();
				}
			});
			
			$('input[name="entry_add"]').click(function() {
				if($("#settings_subject").val() == "yes") {
					$("#datastream").prepend("<div class=\"entry\"><input type=\"text\" class=\"entry_subject_data\" name=\"name\" value=\"\" /><input type=\"text\" class=\"entry_subject_data\" name=\"value\" value=\"\" /><span class=\"del\"></span></div>");
				} else {
					$("#datastream").prepend("<div class=\"entry\"><input type=\"text\" class=\"entry_subject_data\" name=\"entry\" value=\"\" /><span class=\"del\"></span></div>");
				}
			});
			
			$('input[name="entry_save"]').click(function() {
				var id		= $("#settings_id").val();
				var data	= "";
				
				if($("#settings_subject").val() == "yes") {
					for(i = 0; i < $(".entry_subject_data").length; i++) {
						if($(".entry_subject_data")[i].name == "name") {
							data += $(".entry_subject_data")[i].value + ":";
						} else if($(".entry_subject_data")[i].name == "value") {
							data += $(".entry_subject_data")[i].value + ";";
						}
					}
				} else {
					for(i = 0; i < $(".entry_subject_data").length; i++) {
						data += $(".entry_subject_data")[i].value + ";";
					}
				}
				
				$('[name="field_' + id + '_subject_data"]').val(data);
				$("#datastream").hide();
			});
		});
		
		$("div.form_designer div.my_form div.field").click(function() {
			$("div.form_designer div.settings").show();
			$("#datastream").hide();
			
			var id			= $(this).attr("id");
			var type		= $('[name="field_' + id + '_type"]').val();
			var name		= $('[name="field_' + id + '_name"]').val();
			var required	= $('[name="field_' + id + '_required"]').val();
			var data		= $('[name="field_' + id + '_required_data"]').val();
			var value		= $('[name="field_' + id + '_value"]').val();
			var order		= $('[name="field_' + id + '_order"]').val();
			
			// Fill Settings
			$("#settings_id").val(id);
			$("#settings_name").val(name);
			$("#settings_type").html(type);
			$("#settings_value").val(value);
			$("#settings_required").val((required == "1" ? "yes" : "no"));
			
			if(required == "1") {
				data = data.split(";");
				/*
					0 = NULL
					1 = MIN
					2 = MAX
					3 = MAIL
					4 = SUBJECT FIELD
				*/
				$("#settings_required_data").show();
				
				$("#settings_required_null").val((data[0] == "1" ? "yes" : "no"));
				$("#settings_required_min").val(data[1]);
				$("#settings_required_max").val(data[2]);
				$("#settings_required_email").val((data[3] == "1" ? "yes" : "no"));
				
				if(type == "list") {
					$("#settings_entry_subject").show();
					$("#settings_subject").val((data[4] == "1" ? "yes" : "no"));
					$("#settings_entry_email").hide();
					$("#settings_required_email").val("no");
					$("#settings_entry_min").hide();
					$("#settings_entry_max").hide();
				} else {
					$("#settings_entry_subject").hide();
					$("#settings_entry_email").show();
					$("#settings_entry_min").show();
					$("#settings_entry_max").show();
				}
			} else {
				$("#settings_required_data").hide();
			}
		});
	});
}

function addElement(type) {
	jQuery.noConflict();
	jQuery(function($) {
		var html 			= "";
		var id				= "new_" + new_id;
		var name			= "Neues Element";
		var required		= "0";
		var required_data	= "0;0;0;0;0;";
		var value			= "";
		var subject_data	= "";
		
		html += "<div class=\"field type_" + type + "\" id=\"" + id +  "\">\n";
		html += "<label>" + name  +  ":</label>\n";
		html += "<div class=\"fake_" + type + "\"></div>\n";
		html += "<input type=\"hidden\" name=\"field_" + id + "_type\" value=\"" + type + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_name\" value=\"" + name + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_required\" value=\"" + required + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_required_data\" value=\"" + required_data + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_value\" value=\"" + value + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_order\" value=\"" + order + "\" />";
		html += "<input type=\"hidden\" name=\"field_" + id + "_subject_data\" value=\"" + subject_data + "\" />";
		html += "<div class=\"bar\">\n";
		html += "<span class=\"up\" id=\"" + id + "\"></span>";
		html += "<span class=\"down\" id=\"" + id + "\"></span>";
		html += "<span class=\"delete\" id=\"" + id + "\"></span>";
		html += "<div class=\"clear\"></div>";
		html += "</div>\n";
		html += "</div>\n";
		
		// DELETE
		$("span.delete#" + id + "").click(function() {
			if(confirm('Möchten Sie dieses Element wirklich Löschen?')) {
				$(this).parent().parent().remove();
			}
		});
		
		/* Click Event :: UP */
		$("span.up#" + id + "").hide();
		
		/* Click Event :: DOWN */
		$("span.down#" + id + "").hide();
		
		$("div.form_designer div.my_form form").append(html);
	});
	new_id += 1;
	order += 1;
	addClickManagerEvent();
}