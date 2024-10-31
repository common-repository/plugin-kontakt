<?php
	class plugin_kontakt_formular {
		protected static $notify		= "";
		protected static $notify_state	= "";
		
		function the_content($content) {
			if(preg_match("[plugin_kontakt]", $content)) {
				$content = str_replace("[plugin_kontakt]", self::formular(), $content);
				$content = self::replaceValidation($content);
			}
			
			return $content;
		}
		
		function formular() {
			$html  = "<!-- Plugin: Kontakt -->\n";
			$html .= "<form method=\"post\" action=\"\">\n";
			$html .= "<div id=\"" . plugin_kontakt::getClass() . "\">\n";
			
			self::checkFields();
			
			if(plugin_kontakt::getOption("HTML_POSITION") == "left") {
				$html .= "<div class=\"left\">\n";
				$html .= self::generateForm();
				$html .= "</div>\n";
				$html .= "<div class=\"right\">\n";
				$html .= nl2br(plugin_kontakt::getOption("HTML_TEXT"));
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
			} else if(plugin_kontakt::getOption("HTML_POSITION") == "right") {
				$html .= "<div class=\"left\">\n";
				$html .= nl2br(plugin_kontakt::getOption("HTML_TEXT"));
				$html .= "</div>\n";
				$html .= "<div class=\"right\">\n";
				$html .= self::generateForm();
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
			} else if(plugin_kontakt::getOption("HTML_POSITION") == "top") {
				$html .= "<div class=\"top\">\n";
				$html .= self::generateForm();
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
				$html .= "<div class=\"bottom\">\n";
				$html .= nl2br(plugin_kontakt::getOption("HTML_TEXT"));
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
			} else {
				$html .= "<div class=\"top\">\n";
				$html .= nl2br(plugin_kontakt::getOption("HTML_TEXT"));
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
				$html .= "<div class=\"bottom\">\n";
				$html .= self::generateForm();
				$html .= "</div>\n";
				$html .= "<div class=\"clear\"></div>\n";
			}
			
			$html .= "</div>\n";
			$html .= "</form>\n";
			
			return $html;
		}
		
		function htmlShort() {
			$html = "";
			if(plugin_kontakt::getOption("HTML_SHORT_TAG") == 0) {
				$html .= ">\n";
			} else {
				$html .= " />\n";
			}
			return $html;
		}
		
		function generateForm() {
			$html  = "";
			$html .= self::getNotify();
			$html .= self::generateFields();
			
			if(plugin_kontakt::getOption("SPAM_PROTECTION_SHOW") == 1) {
				$html .= self::generateSpam();
			}
			
			$html .= "<div class=\"buttons\">\n";
			$html .= "<input type=\"submit\" name=\"kontakt_send\" value=\"" . plugin_kontakt::getOption("BUTTON_SEND_NAME") . "\"" . self::htmlShort();
			$html .= (plugin_kontakt::getOption("BUTTON_RESET_SHOW") == 1 ? "<input type=\"reset\" name=\"kontakt_reset\" value=\"" . plugin_kontakt::getOption("BUTTON_RESET_NAME") . "\"" . self::htmlShort() : "");
			$html .= "</div>\n";
			
			return $html;
		}
		
		function generateFields() {
			global $wpdb;
			
			$html  = "";
			$fields = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "kontakt_fields` ORDER BY `order` ASC");
			foreach($fields as $field) {
				$html .= "<div class=\"field type_" . $field->type . "\">\n";
				$html .= "<label>" . $field->name . ":" . ($field->required == 1 ? "<strong>*</strong>" : "") . "</label>\n";
				
				switch($field->type) {
					case "input":
						$html .= self::getInput($field);
					break;
					case "list":
						$html .= self::getList($field);
					break;
					case "text":
						$html .= self::getText($field);
					break;
				}
				
				$html .= "</div>\n";
			}
			
			return $html;
		}
		
		function generateSpam() {
			$html  = "";
			$html .= "<div class=\"field type_spam\">\n";
			$html .= "<label>" . __("Spam Check", "pk") . ":<strong>*</strong></label>\n";
			$html .= "<input type=\"text\" name=\"kontakt_spam\" value=\"\"" . self::htmlShort();
			$html .= "<img src=\"" . get_bloginfo("url") . "/wp-content/plugins/plugin-kontakt/functions/captcha.php\" alt=\"" . __("Spam Check", "pk") . "\"" . self::htmlShort();
			$html .= "</div>";
			return $html;
		}
		
		function clearNotify() {
			self::$notify = "";
		}
		
		function addNotify($text) {
			self::$notify .= $text;
		}
		
		function changeNotify($state) {
			self::$notify_state = $state;
		}
		
		function getNotify() {
			if(self::$notify != "") {
				$html  = "";
				$html .= "<div class=\"notify " . self::$notify_state . "\">\n";
				$html .= self::$notify;
				$html .= "</div>\n";
				return $html;
			}
		}
		
		function getInput($field) {
			if(isset($_POST['kontakt_' . $field->id])) {
				$value = $_POST['kontakt_' . $field->id];
			} else {
				$value = $field->value;
			}
			$html = "<input type=\"text\" name=\"kontakt_" . $field->id . "\" value=\"" . $value . "\"" . self::htmlShort();
			
			return $html;
		}
		
		function getList($field) {
			if(isset($_POST['kontakt_' . $field->id])) {
				$value = $_POST['kontakt_' . $field->id];
			}
			
			$html  = "";
			$html .= "<select name=\"kontakt_" . $field->id . "\">\n";
			$html .= "<option value=\"none\">" . $field->value . "</option>\n";
			
			$settings	= explode(";", $field->required_data);
			$data		= explode(";", $field->subject_data);
			
			foreach($data AS $entry) {
				if($entry != "") {
					// Subject
					if($settings[4] != 0) {
						$subject = explode(":", $entry);
						$html .= "<option value=\"" . $subject[0] . "\"" . ($subject[0] == $value ? " SELECTED" : "") . ">" . $subject[0] . "</option>\n";
					} else {
						$html .= "<option value=\"" . $entry . "\"" . ($entry == $value ? " SELECTED" : "") . ">" . $entry . "</option>\n";
					}
				}
			}
			
			$html .= "</select>\n";
			
			return $html;
		}
		
		function getText($field) {
			if(isset($_POST['kontakt_' . $field->id])) {
				$value = $_POST['kontakt_' . $field->id];
			} else {
				$value = $field->value;
			}
			$html = "<textarea name=\"kontakt_" . $field->id . "\" cols=\"1\" rows=\"1\">" . $value . "</textarea>\n";
			
			return $html;
		}
		
		function checkFields() {
			global $wpdb;
			if(isset($_POST['kontakt_send']) && $_POST['kontakt_send'] != "") {
				$mail			= __("It has entered a new contact request", "pk") . ":\n\n";
				$fields 		= $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "kontakt_fields` ORDER BY `order` ASC");
				$errors 		= 0;
				$error	 		= array();
				$subject_mail	= "";
				
				foreach($fields as $field) {
					$mail	.= $field->name . ":" . ($field->required == 1 ? "*" : "") . "\n" . $_POST['kontakt_' . $field->id] . "\n\n";
					$settings = explode(";", $field->required_data);
					
					if($field->required == 1) {
						// NULL
						if($settings[0] != 1) {
							if(isset($_POST['kontakt_' . $field->id]) && $_POST['kontakt_' . $field->id] != "") {
								/* Do Nothing */
							} else {
								$errors++;
								if(!in_array("- " . $field->name, $error)) {
									$error[] = "- " . $field->name;
								}
							}
						}
						
						// MIN
						if($settings[1] != 0) {
							if(strlen($_POST['kontakt_' . $field->id]) > $settings[1]) {
								/* Do Nothing */
							} else {
								$errors++;
								if(!in_array("- " . $field->name, $error)) {
									$error[] = "- " . $field->name;
								}
							}
						}
						
						// MAX
						if($settings[2] != 0) {
							if(strlen($_POST['kontakt_' . $field->id]) < $settings[2]) {
								/* Do Nothing */
							} else {
								$errors++;
								if(!in_array("- " . $field->name, $error)) {
									$error[] = "- " . $field->name;
								}
							}
						}
						
						// MAIL
						if($settings[3] != 0) {
							if(plugin_kontakt_functions::check_email_address($_POST['kontakt_' . $field->id])) {
								/* Do Nothing */
							} else {
								$errors++;
								if(!in_array("- " . $field->name, $error)) {
									$error[] = "- " . $field->name;
								}
							}
						}
						
						// SUBJECT
						if($settings[4] != 0) {
							if($_POST['kontakt_' . $field->id] != "none") {
								$data = explode(";", $field->subject_data);
								
								foreach($data AS $entry) {
									if($entry != "") {
										$subject = explode(":", $entry);
										if($subject[0] == $_POST['kontakt_' . $field->id]) {
											$subject_mail = $subject[1];
											break;
										}
									}
								}
							} else {
								$errors++;
								if(!in_array("- " . $field->name, $error)) {
									$error[] = "- " . $field->name;
								}
							}
						}
					}
				}
				
				// Spam CHeck
				if(plugin_kontakt::getOption("SPAM_PROTECTION_SHOW") == 1) {
					if($_POST['kontakt_spam'] == $_SESSION['kontakt_captcha']) {
						/* Do Nothing */
					} else {
						$errors++;
						if(!in_array("- " . __("Spam protection", "pk"), $error)) {
							$error[] = "- " . __("Spam protection", "pk");
						}
					}
				}
				
				print "<div class=\"notify\">";
				
				if($errors == 0) {
					self::changeNotify("");
					self::clearNotify();
					self::addNotify(__("Your contact request has been sent successfully", "pk") . ".");
					if(!plugin_kontakt_functions::check_email_address($subject_mail)) {
						$subject_mail = get_bloginfo("admin_email");
					}
					plugin_kontakt_functions::sendMail($subject_mail, "Kontaktanfrage", $mail);
				} else {
					self::changeNotify("red");
					self::clearNotify();
					self::addNotify(__("Please correct your entrys", "pk") . ":<br />");
					
					if(is_array($error) && count($error) > 0) {
						foreach($error AS $err) {
							self::addNotify("<br />" . $err);
						}
					}	
				}
				
				print "</div>";
			}
		}
		
		function replaceValidation($html) {
			$html = str_replace("</form>\n</p>", "</form>\n", $html);
			$html = str_replace("<p><!-- Plugin: Kontakt -->", "<!-- Plugin: Kontakt -->", $html);
			
			return $html;
		}
	}
?>