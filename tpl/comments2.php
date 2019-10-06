<div id='page_comments'>
	
	<div class='cb_comments'>

		<div id="comment_post">
			<form id="comment_form">
				<textarea tabindex="9" id="comment_text" class="tinymce"></textarea>
				<label id="comment_email_label" for="comment_email">Email: </label>
					<div id="comment_email_container"><input tabindex= "7" id="comment_email" name="comment_name" type="text"/></div>
					<div id="comment_msg">Enter gravatar email</div>
				<label for="comment_intro" id="comment_intro_label">Intro: </label>
					<input tabindex= "8" id="comment_intro" name="comment_intro" type="text"/>
				<label id="comment_text_label" for="comment_text">Comment: </label>
					
				<!--<label for="comment_image">Image: </label>
					<input tabindex= "10" id="comment_image" name="comment_image" type="text"/> -->
				<div id="gravatar_pic"><img src="/img/gravatar_default.jpg" style="width: 16px; height: 16px;"/></div>		
				<!-- <div id="edit_pic"><img src="/img/edit.png" style="width: 16px; height: 16px;"/></div> -->
				<input tabindex="11" id="comment_submit" type="button" value="Post">				
			</form>
	</div>
		
		<div id="cb_feed">
		
			<img src='../img/loading2.gif'/>
			<!-- <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>

			<div class="viewport">

				<div class="overview">
		
				</div>
				
			</div> -->
				
		</div>
			
		<div class='fb-comments' data-href= 'http://cb_chapter_name_cb.cb_logbook_name_cb.cerebrit.com/cb_article_name_cb' data-width='910'></div>
			
	</div>

	
</div>
	
	
	<!-- <div class='fb-comments' data-href= 'http://cb_chapter_name_cb.cb_logbook_name_cb.cerebrit.com' data-width='400'></div> -->
	
</div>

<script>

	LoggedIn(function(user) {
		if(user.logged_in== true)
		{
			$("#comment_email").val(response.email);
			if(user.authorized== false)
			{

			}
			
		}

		else
		{
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
				
					FB.api('/me', function(response) {
						$("#comment_email").val(response.email);
					});
				} else if (response.status === 'not_authorized') {
					//alert('not authorized');
				} else {

				}
			});		
		}
	});

	var article_id = $("#logbooks").attr("article_id");
	
	LoadComments(article_id, "json", function(comments) {
	
		$("#cb_feed").html(comments.comment_data);
		var $container = $('#comment_list');
		$container.imagesLoaded(function(){
			$container.masonry({
				itemSelector : '.comment_link',
				columnWidth : 40,
				isAnimated: true,
				containerStyle: { position: 'absolute', height: '700px' },
				isFitWidth : true
			});
		});

		//$('#cb_feed').tinyscrollbar();
	});

</script>