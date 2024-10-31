<?php
	@ob_start();
			
	class plugin_kontakt_backend {
		function init() {
			global $plugin_page;
			
			if(isset($plugin_page) && $plugin_page == plugin_kontakt::getClass()) {
				wp_enqueue_script('common');
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-color');
				wp_print_scripts('editor');
				
				if(function_exists('add_thickbox')) {
					add_thickbox();
					wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
				}
				
				wp_print_scripts('media-upload');
				
				if(function_exists('wp_tiny_mce')) {
					wp_tiny_mce();
				}
				
				#wp_admin_css();
				#wp_enqueue_script('utils');
				#do_action("admin_print_styles-post-php");
				#do_action('admin_print_styles');
				
				wp_enqueue_script('editor.js', '/wp-content/plugins/plugin-kontakt/backend/editor.js', null, '1.0');
				wp_enqueue_style("style-backend.css", "/wp-content/plugins/plugin-kontakt/backend/style.css");
			}
		}
		
		function navigation() {
			add_submenu_page("options-general.php", plugin_kontakt::getName(), plugin_kontakt::getName(), 0, plugin_kontakt::getClass(), array('plugin_kontakt_backend', 'page'));
		}
		
		function pages() {
			$pages = array(
				"form"			=> __("Formular", "pk"),
				"settings"		=> __("Settings", "pk"),
				"form_save"		=> "-"
			);
			
			return $pages;
		}
		
		function HTMLNavigation() {
			print "<div class=\"navigation\"><ul>";
				
			$pages = self::pages();
			foreach($pages AS $page => $name) {
				if($name != "-") {
					print "<li class=\"mail\"><a href=\"options-general.php?page=" . plugin_kontakt::getClass() . "&site=" . $page . "\">" . $name . "</a></li>";
				}
			}
				
			print "</ul><div class=\"clear\"></div></div>";
		}
		
		function page() {
			global $wpdb;
			
			$site = "";
			print "<div id=\"" . plugin_kontakt::getClass() . "\">";
			self::HTMLNavigation();
			$pages = self::pages();
			
			foreach($pages AS $page => $name) {
				if(!isset($_GET['site'])) {
					$site = $page;
					break;
				}
			}
			
			foreach($pages AS $page => $name) {
				if(isset($_GET['site']) && $_GET['site'] == $page) {
					$site = $page;
					break;
				}
			}
			include("page_" . $site . ".php");
			
			print "</div>";
		}
	}
?>