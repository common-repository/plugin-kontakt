<?php
	$last_order = 0;
?>
<div class="form_designer">
	<div class="top">
		<h2><span class="save"><?php print __("Save", "pk");?></span> <?php print __("Formular Designer", "pk");?></h2>
	</div>
	<div class="bottom">
		<div id="datastream"></div>
		<div class="left">
			<h2><?php print __("Elements", "pk");?></h2>
			<div class="entry">
				<ul>
					<li class="input" onclick="addElement('input');"><?php print __("Input", "pk");?></li>
					<li class="text" onclick="addElement('text');"><?php print __("Textfield", "pk");?></li>
					<li class="list" onclick="addElement('list');"><?php print __("List", "pk");?></li>
				</ul>
			</div>
			<div class="settings">
				<h2><?php print __("Settings", "pk");?></h2>
				<input type="hidden" id="settings_id" value="0" />
				<div class="entry">
					<strong><?php print __("Name", "pk");?>:</strong>
					<input type="text" id="settings_name" value="" />
				</div>
				
				<div class="entry">
					<strong><?php print __("Content", "pk");?>:</strong>
					<input type="text" id="settings_value" value="" />
				</div>
				
				<div class="entry">
					<strong><?php print __("Type", "pk");?>:</strong>
					<b id="settings_type"> <?php print __("Input", "pk");?></b> <i>(<?php print __("Can't change", "pk");?>)</i>
				</div>
				
				<div class="entry">
					<strong><?php print __("Required", "pk");?>?</strong>
					<select id="settings_required">
						<option value="no"><?php print __("No", "pk");?></option>
						<option value="yes"><?php print __("Yes", "pk");?></option>
					</select>
					<div id="settings_required_data">
						<div class="entry">
							- <?php print __("Not Empty", "pk");?>
							<select id="settings_required_null">
								<option value="no"><?php print __("No", "pk");?></option>
								<option value="yes"><?php print __("Yes", "pk");?></option>
							</select>
						</div>
						<div class="entry" id="settings_entry_min">
							- <?php print __("Min. Length", "pk");?>
							<input type="text" style="width: 50px;" id="settings_required_min" value="1" />
						</div>
						<div class="entry" id="settings_entry_max">
							- <?php print __("Max. Length", "pk");?>
							<input type="text" style="width: 50px;" id="settings_required_max" value="255" />
						</div>
						<div class="entry" id="settings_entry_email">
							- <?php print __("Check as E-Mail", "pk");?>
							<select id="settings_required_email">
								<option value="no"><?php print __("No", "pk");?></option>
								<option value="yes"><?php print __("Yes", "pk");?></option>
							</select>
						</div>
					</div>
					<div class="entry" id="settings_entry_subject">
						<strong><?php print __("Use as Subject", "pk");?>:</strong>
						<select id="settings_subject">
							<option value="no"><?php print __("No", "pk");?></option>
							<option value="yes"><?php print __("Yes", "pk");?></option>
						</select> <span class="data"><img src="../wp-content/plugins/plugin-kontakt/images/icon_data.png" /></span>
					</div>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="my_form">
				<form method="post" id="actionizer" action="options-general.php?page=plugin_kontakt&site=form_save">
				<?php
					$fields = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "kontakt_fields` ORDER BY `order` ASC");
					foreach($fields as $field) {
						print "<div class=\"field type_" . $field->type . "\" id=\"" . $field->id . "\">\n";
						print "<label>" . $field->name . ":" . ($field->required == 1 ? "<strong>*</strong>" : "") . "</label>\n";
						
						switch($field->type) {
							case "input":
								print "<div class=\"fake_input\">" . $field->value . "</div>\n";
							break;
							case "list":
								print "<div class=\"fake_list\">" . $field->value . "</div>\n";
							break;
							case "text":
								print "<div class=\"fake_text\">" . $field->value . "</div>\n";
							break;
						}
						
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_type\" value=\"" . $field->type . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_name\" value=\"" . $field->name . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_required\" value=\"" . $field->required . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_required_data\" value=\"" . $field->required_data . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_value\" value=\"" . $field->value . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_order\" value=\"" . $field->order . "\" />";
						print "<input type=\"hidden\" name=\"field_" . $field->id . "_subject_data\" value=\"" . $field->subject_data . "\" />";
						
						print "<div class=\"bar\">\n";
						print "<span class=\"up\" id=\"" . $field->id . "\"></span>";
						print "<span class=\"down\" id=\"" . $field->id . "\"></span>";
						print "<span class=\"delete\" id=\"" . $field->id . "\"></span>";
						print "<div class=\"clear\"></div>";
						print "</div>\n";
						print "</div>\n";
						$last_order = $field->order;
					}					
				?>
				<input type="hidden" name="order_data" id="order_data" value="" />
				<input type="hidden" name="delete_data" id="delete_data" value="" />
				<input type="hidden" name="last_order" id="last_order" value="<?php print $last_order; ?>" />
				</form>
			</div>
		</div>
	</div>
</div>