<?php
	/*
		Plugin Name: Plugin: Kontakt
		Plugin URI: http://hovida-design.de
		Description: Kontaktformular mit frei definierbaren Feldern
		Author: Adrian Preuß
		Version: 1.3.2
		Author URI: mailto:a.preuss@hovida-design.de
	*/
	
	if(!isset($_SESSION)) {
		@session_start();
	}
	
	load_plugin_textdomain("pk", false, dirname(plugin_basename(__FILE__)) . '/lang/');
	
	@ob_start();
	include("functions/install.php");
	include("functions/functions.php");
	include("functions/template.php");
	include("frontend/frontend.php");
	include("frontend/formular.php");
	include("backend/backend.php");
	
	class plugin_kontakt {
		private static $name		= '';
		private static $class		= "plugin_kontakt";
		
		function getName() {
			if(empty(self::$name)) {
				self::$name			= __("Contact", "pk");
			}
			
			return self::$name;
		}
		
		function getClass() {
			return self::$class;
		}
		
		function getOption($name) {
			global $wpdb;
			
			$option = $wpdb->get_results("SELECT `value` FROM `" . $wpdb->prefix . "kontakt_options` WHERE `name`='" . $name . "' LIMIT 1");
			
			return $option[0]->value;
		}
		
		function setOption($name, $value) {
			global $wpdb;
			
			$wpdb->query("UPDATE `" . $wpdb->prefix . "kontakt_options` SET `value`='" . $value . "' WHERE `name`='" . $name . "' LIMIT 1");
		}
	}
	
	register_activation_hook(__FILE__, array('plugin_kontakt_install', 'install'));
	register_deactivation_hook(__FILE__, array('plugin_kontakt_install', 'uninstall'));
	
	add_action("init", array('plugin_kontakt_frontend', 'init'), 1);
	add_action("admin_init", array('plugin_kontakt_backend', 'init'), 1);
	add_action('admin_menu', array('plugin_kontakt_backend', 'navigation'));
	add_filter('the_content', array('plugin_kontakt_formular', 'the_content'));
?>