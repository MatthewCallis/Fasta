<?php
if(!defined('EXT')){
	exit('Invalid file request');
}

class fasta{
	var $name = 'Fasta';
	var $classname = 'fasta';
	var $version = '1.0';
	var $description = 'Update templates faster.';
	var $docs_url = '';

	function activate_extension(){
		global $DB;
		$DB->query($DB->insert_string('exp_extensions',
			array(
				'extension_id' => '',
				'class' => $this->classname,
				'method' => 'fasta_hook',
				'hook' => 'show_full_control_panel_end',
				'settings' => '',
				'priority' => 10,
				'version' => $this->version,
				'enabled' => 'y'
			)
		));
	}

	function update_extension($current=''){
		global $DB;
		if($current == '' || $current == $this->version){
			return FALSE;
		}
		$DB->query("UPDATE `exp_extensions` SET `version` = '".$this->version."' WHERE `class`='".$this->classname."'");
		return TRUE;
	}

	function disable_extension(){
		global $DB;
		$DB->query("DELETE FROM `exp_extensions` WHERE `class`='".$this->classname."'");
	}

	function fasta_hook($out=''){
		global $EXT, $DB, $SESS, $IN;
		$group_id = $IN->GBL('tgpref');
		$out = ($EXT->last_call !== FALSE) ? $EXT->last_call : $out;
		if(!$group_id || (strlen(strstr($_SERVER['REQUEST_URI'],'update_template'))<0) || (strlen(strstr($_SERVER['REQUEST_URI'],'edit_template'))<0)){
			return $out;
		}
		$js = '<script type="text/javascript">$(document).ready(function(){$("form.fasta input[value=\'Update\']").before("<input type=\'submit\' class=\'submit\' value=\'Quick Update\' id=\'fasta_update\'/>&nbsp;<em id=\'working\'></em>&nbsp;");$("#fasta_update").click(function(){$("#working").html("&nbsp;Working...");$.post("index.php?S='.$SESS->userdata['session_id'].'&C=templates&M=update_template&tgpref='.$group_id.'",{XID:$("form.fasta input[name=\'XID\']").val(),template_id:$("form.fasta input[name=\'template_id\']").val(),template_data:$("#template_data").val(),template_notes:$("#template_notes").val(),save_history:$("form.fasta input[name=\'save_history\']").val(),columns:$("#columns").val()},function(){$("#working").html("&nbsp;Done!");});return false;});});</script>';
		if(preg_match('/M=update_template&amp;tgpref=([0-9]+)\'/', $out, $found)){
			$out = str_replace($found[0], 'M=update_template&amp;tgpref='.$found[1].'\' class="fasta"', $out);
			$out = str_replace('</body>', $js.'</body>', $out);
		}
		return $out;
	}
}