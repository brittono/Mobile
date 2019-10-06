<div id="comments_container">

	<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>

	<div class="viewport">

		<div class="overview">

			<div id="comments">

			</div>

			<div id="comment_post">

				<form id="comment_form">
					<table>
						<tr>
							<td><label for="comment_name">Name: </label></td>
							<td><input tabindex= "7" id="comment_name" name="comment_name" type="text"/></td>
							<td><input tabindex="11" id="comment_submit" type="button" value="Post"></td>
						</tr>
						<tr>
							<td><label for="comment_intro">Intro: </label></td>
							<td colspan="2"><input tabindex= "8" id="comment_intro" name="comment_intro" type="text"/></td>
						</tr>
						<tr>
							<td><label for="comment_text">Comment: </label></td>
							<td colspan="2"><textarea tabindex="10" id="comment_text" class="tinymce"></textarea></td>
						</tr>
					</table>
				</form>

			</div>

		</div>

	</div>

</div>

<script>

	$("#comment_submit").click(function() {
	
		var comment_name= $("#comment_name").val();
		var comment_intro= $("#comment_intro").val();
		var comment_text= $("#comment_text").val();
		var page_id= $("#logbooks").attr("page_id");
		
		AddComment(page_id, comment_name, comment_intro, comment_text, function(comment) {
			if(comment.posted== true)
			{
				$("#comment_post").html("Thank you, your comment has been posted.");
				LoadComments(page_id, function(comments) {
					$("#comments").html(comments.comment_data);
				});
			}
		});
	});	

	$(".comment_intro").live("click", function() {

		if($(this).siblings('.comment_text').css("display")== 'none')
		{
			$(this).siblings('.comment_text').css("display", "block");
		}
		else
		{
			$(this).siblings('.comment_text').css("display", "none");
		}
	});

</script>

<script type="text/javascript" src="/js/lib/tiny_mce/jquery.tinymce.js"></script>

<script type="text/javascript">
/*
	$().ready(function() {
		$('#comment_text.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '/js/lib/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
*/
</script>
