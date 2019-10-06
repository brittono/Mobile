<div id="about_container">

	<div id="about" style="position: relative; top: 0px; padding: 40px 0;">

		<div id="updates" style="margin: 0 20px; font-size: 16px; color: #fff;">
			<div>Some info:  
				<ul style="list-style-type: none; margin: 0; padding: 0;">
					<li><span style="color: red;">cb_chapter_name_cb.cb_logbook_name_cb</span></li>
					<li><span style="color: blue;">cb_user_count_cb users</li>
					<li><span style="color: aqua;">Last modified: cb_last_modified_cb</span></li>
				</ul>
				<span style="color: brown;">Become a member!</span> - contact britton.otoole@gmail.com.
			</div>
		</div>
	</div>
</div>

<script>
	$("#logbooks").data("controls", "about");
	
	
	$(document).on("click", "#submit_link_button", function() {

		var link_text= $("#submit_link_text").val();
		var user_id= $("#logbooks").attr("user_id");
		var parsed_link_text = link_text.replace(/(<([^>]+)>)/ig,"");

		if(parsed_link_text!= '')
		{
			AddComment(parsed_link_text, function(comment) {

				AppendComment(comment.comment_id, comment.comment, comment.style);
				$("#submit_link_container").html("<div id='comment_thank_you'>Thank you, your link has been submitted.</div>");
				setTimeout(function() {
					$("#comment_thank_you").fadeOut("1000", function() {

						LoadTemplate("submit_link", function(template) {
						
							$("#submit_link_container").html(template.template_data);
						});
					});
				}, 3000);		
			});
		}
	});


	function Scroll() {

		if($("#logbooks").data("controls")== "about")
		{	
			var height= $("#about").height();
			var position= $("#about").position();

			if(height + position.top > -40) 
			{
				var top= position.top - 1;
				var top= top + "px";
				$("#about").css("top", top);
			} 
			else
			{
				var top= 0;
				var top= top + "px";
				$("#about").css("top", top);			
			}
		}
		else
		{
			clearInterval(about_time);
		}
	}
	
	var about_time= setInterval(Scroll, 100);

</script>
