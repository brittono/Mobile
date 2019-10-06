<div id="nav">

	<div id="nav_user" user_id="<?php echo $logbook->user_id; ?>">
		<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport">
			<div id="users_overview" class="overview">
				<?php echo $logbook->article_name;?>
			</div> 
		</div>
	</div>

	<div id="nav_article" article_id="<?php echo $logbook->article_id; ?>">
		<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport">
			<div  id="articles_overview" class="overview">
				<?php echo $logbook->article_name;?>
			</div>
		</div>
	</div>

	<div id="nav_page" page_id="<?php echo $logbook->page_id; ?>">
		<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
		<div class="viewport">
			<div id="pages_overview" class="overview">
				<?php echo $logbook->article_name;?>
			</div> 
		</div> 
	</div>

</div>


/*
	LoadUserList($("#logbooks").attr("logbook_id"), function(list) {
		$("#users_overview").html(list.list_data);
		var user_id= $("#logbooks").attr("user_id");
		var user_index= 0;
		var offset= 0;
		$("#user_list li").each(function(index) {
			if($(this).attr("user_id")== user_id)
			{
				user_index= index;
			}
		});
		offset= (user_index / 3) * 140;
		$("#user_" + user_id).addClass("selected");
		$('#nav_user').tinyscrollbar();
		$('#nav_user').tinyscrollbar_update(offset);
	});

	LoadarticleList($("#logbooks").attr("user_id"), function(list) {
		$("#articles_overview").html(list.list_data);
		var article_id= $("#logbooks").attr("article_id");
		var article_index= 0;
		var offset= 0;
		$("#article_list li").each(function(index) {
			if($(this).attr("article_id")== article_id)
			{
				article_index= index;
			}
		});
		offset= (article_index / 3) * 140;
		$("#article_" + article_id).addClass("selected");
		$('#nav_article').tinyscrollbar();
		$('#nav_article').tinyscrollbar_update(offset);
	});

	LoadPageList($("#logbooks").attr("article_id"), function(list) {
		$("#pages_overview").html(list.list_data);
		var page_id= $("#logbooks").attr("page_id");
		var page_index= 0;
		var offset= 0;
		$("#page_list li").each(function(index) {
			if($(this).attr("page_id")== page_id)
			{
				page_index= index;
			}
		});
		offset= (page_index / 3) * 140;
		$("#page_" + page_id).addClass("selected");
		$('#nav_page').tinyscrollbar();
		$('#nav_page').tinyscrollbar_update(offset);
	});

});

$(".user_link").live("click", function() {

	var user_id= $(this).attr("user_id");

	$(".user_link").each(function() {
		$(this).removeClass("selected");
	});
	$(this).addClass("selected");

	$("#logbooks").attr("user_id", user_id);

	LoadarticleList(user_id, function(list) {

		$("#articles_overview").html(list.list_data);
		$("#article_list li:first").addClass("selected");
		$('#nav_article').tinyscrollbar();

		LoadPageList(list.first, function(list) {

			$("#pages_overview").html(list.list_data);
			$("#page_list li:first").addClass("selected");
			$('#nav_page').tinyscrollbar();

			LoadPage(list.first, function(page) {

				$("#logbooks").attr("page_id", page.page_id);
				$("#page").html(page.page_data);
			});
		});
	});
});

$(".article_link").live("click", function() {
	
	var article_id= $(this).attr("article_id");

	$(".article_link").each(function() {
		$(this).removeClass("selected");
	});
	$(this).addClass("selected");

	$("#logbooks").attr("article_id", article_id);
	$("#logbooks").attr("page_id", 0);
	
	LoadPageList(article_id, function(list) {

		$("#pages_overview").html(list.list_data);
		$("#page_list li:first").addClass("selected");
		$('#nav_page').tinyscrollbar();

		LoadPage(list.first, function(page) {

			$("#logbooks").attr("page_id", page.page_id);
			$("#page").html(page.page_data);
		});
	});
});

$(".page_link").live("click", function() {

	var page_id= $(this).attr("page_id");

	$(".page_link").each(function() {
		$(this).removeClass("selected");
	});
	$(this).addClass("selected");

	LoadPage($(this).attr("page_id"), function(page){
		$("#logbooks").attr("page_id",page.page_id);		
		$("#page").html(page.page_data);
	});
});
*/

