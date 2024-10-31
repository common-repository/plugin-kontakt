<?php
	class plugin_kontakt_install {
		function install() {
			global $wpdb;
			
			$wpdb->query("CREATE TABLE `" . $wpdb->prefix . "kontakt_fields` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`type` char(255) DEFAULT NULL,
				`name` char(255) DEFAULT NULL,
				`value` text,
				`required` enum('1','0') DEFAULT NULL,
				`required_data` text,
				`order` int(11) DEFAULT NULL,
				`subject_data` text,
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1");

			$wpdb->query("CREATE TABLE `" . $wpdb->prefix . "kontakt_options` (`name` char(255) DEFAULT NULL, `value` text) ENGINE=MyISAM DEFAULT CHARSET=latin1");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('BUTTON_RESET_SHOW', '0')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('BUTTON_RESET_NAME', '" . __("Reset", "pk") . "')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('BUTTON_SEND_NAME', '" . __("Send", "pk") . "')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('HTML_SHORT_TAG', '1')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('SPAM_PROTECTION_SHOW', '0')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('HTML_TEXT', '" . __("Welcome", "pk") . "')");
			$wpdb->query("INSERT INTO `" . $wpdb->prefix . "kontakt_options` (`name`, `value`) VALUES ('HTML_POSITION', 'left')");
		}
		
		function uninstall() {
			global $wpdb;
			
			$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "kontakt_options`;");
			$wpdb->query("DROP TABLE IF EXISTS `" . $wpdb->prefix . "kontakt_fields`;");
		}
	}
?>