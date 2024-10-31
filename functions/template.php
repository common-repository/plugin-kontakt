<?php
	class plugin_kontakt_template {
		function getTemplate($file) {
			$template = file_get_contents($file);
			preg_match_all("/\/\*(.*)\*\//Uis", $template, $array);
			$template = str_replace("\t", "", $array[0][0]);
			$template = str_replace("/*", "", $template);
			$template = str_replace("*/", "", $template);
			preg_match_all("/(Name|Version|Author|Type|Author Mail|Author WWW):(.*)([(\r|\n|\r\n)]+)/Uis", $template, $array2);
			$data = null;
			
			for($i = 0; $i < count($array2[1]); $i++) {
				// Name
				if(strtolower($array2[1][$i]) == "name") {
					$data->name = $array2[2][$i];
				}
				
				// Author
				if(strtolower($array2[1][$i]) == "author") {
					$data->author = $array2[2][$i];
				}
				
				// Author Mail
				if(strtolower($array2[1][$i]) == "author mail") {
					$data->author_mail = $array2[2][$i];
				}
				
				// Author WWW
				if(strtolower($array2[1][$i]) == "author www") {
					$data->author_web = $array2[2][$i];
				}
				
				// Version
				if(strtolower($array2[1][$i]) == "version") {
					$data->version = $array2[2][$i];
				}
				
				// Type
				if(strtolower($array2[1][$i]) == "type") {
					$data->type = $array2[2][$i];
				}
			}
			
			$data->id = MD5($data->name . "_" . $data->version);
			$data->file = $file;
			return $data;
		}
		
		function parseTemplate($file) {
			$html = file_get_contents($file);
			$html = preg_replace("/\/\*(.*)\*\//Uis", "", $html);			
			return $html;
		}
	}
?>