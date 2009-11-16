$(document).ready(function(){			
	$("form.fasta input[value=\'Update\']").before("<input type=\'submit\' class=\'submit\' value=\'Quick Update\' id=\'fasta_update\'/>&nbsp;<em id=\'working\'></em>&nbsp;");
	$("#fasta_update").click(function(){
		$("#working").html("&nbsp;Working...");
		$.post("index.php?S='.$SESS->userdata['session_id'].'&C=templates&M=update_template&tgpref='.$group_id.'",
		{
			XID: $("form.fasta input[name=\'XID\']").val(),
			template_id: $("form.fasta input[name=\'template_id\']").val(),
			template_data: $("#template_data").val(),
			template_notes: $("#template_notes").val(),
			save_history: $("form.fasta input[name=\'save_history\']").val(),
			columns: $("#columns").val()
		},
			function(){
				$("#working").html("&nbsp;Done!");
			}
		);
		return false;
	});
});