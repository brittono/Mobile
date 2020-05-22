//var server = "https://brittono.herokuapp.com";
var server = "http://http://127.0.0.1";

function RandomColor() {
  var color = "#" + Math.floor(Math.random() * 16777215).toString(16);
  return color;
}

/*
function AddComment(comment_text, handleData)
{
	var style= "border: 4px solid " + RandomColor();
	var article_id= $("#logbooks").attr("article_id");
	var page_id= $("#logbooks").attr("page_id");
	var user_id= $("#logbooks").attr("user_id");

    $.ajax({
		type: 'POST',
		url: '/JSON/tile.php',
		dataType: 'json',
		data: {
			action: 'Comment',
			style: style,
			comment: comment_text,
			article_id: article_id,
			page_id: page_id,
			user_id: user_id
		},
		success: function(data)
		{
			handleData(data);
		}
    });
}
*/

function AppendComment(comment_id, comment_text, style) {
  var $comment =
    "<div class='comment_wrapper'><div class='comment' comment_id='" +
    comment_id +
    "' style='" +
    style +
    "' ><div class='comment_tile'>" +
    comment_text +
    "</div></div></div>";
  $("#page").append($comment);
}

function AddTile(brand, tile_content, handleData) {
  var style = "border: 1px solid " + RandomColor();
  var page_id = $("#logbooks").attr("page_id");

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "DisplayAddTile",
    data: {
      action: "Create",
      brand: brand,
      style: style,
      tile_content: tile_content,
      page_id: page_id,
      enc_type: "callback"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function AppendTile(tile_id, style) {
  $("#page").append(
    "<div class='tile_wrapper'><div tile_id= '" +
      tile_id +
      "' class='tile' style='" +
      style +
      "' ><div class='editable'></div></div></div>"
  );

  $(".editable").editable(
    function(content, settings) {
      var article_id = $("#logbooks").attr("article_id");
      var page_id = $("#logbooks").attr("page_id");
      SaveTile(content, function(tile) {
        LoadPage(article_id, page_id, "", function(page) {
          $("#page").html(page.page_data);
          $.getScript("/website/js/user.js");
        });
      });
    },
    {
      type: "textarea",
      cancel: "Cancel",
      submit: "Save",
      indicator: "<img src='indicator.gif'>",
      tooltip: "Editable",
      height: "200px",
      event: "dblclick"
    }
  );
  $(".tile").resizable();
  $(".tile_wrapper").draggable({
    containment: "#page",
    snap: true,
    snapMode: "outer",
    snapTolerance: 3
  });
  //	$(document).on("mouseup", '.tile_wrapper', LoadTileValues);
  SetControlValues(tile_id, style, "", "");

  return tile_id;
}

function AppendPublicTile(tile_id, style, content) {
  $("#page").append(
    "<div class='tile_wrapper'><div tile_id= '" +
      tile_id +
      "' class='tile' style='" +
      style +
      "' ><div class='editable'>" +
      content +
      "</div></div></div>"
  );

  $(".tile").resizable();
  $(".tile_wrapper").draggable({
    containment: "parent",
    snap: $(".tile_wrapper, #page"),
    snapMode: "outer",
    snapTolerance: 8
  });
  $(".comment_wrapper").draggable({
    containment: "parent",
    snap: $(".tile_wrapper"),
    snapMode: "outer",
    snapTolerance: 5
  });

  return tile_id;
}

function MoveToTop(tile_id, page_id, enc_type, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Top",
      tile_id: tile_id,
      page_id: page_id,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SaveTile(tile_content, handleData) {
  var brand = $(".tile_brand_hidden").val();
  var user_id = $("#logbooks").attr("user_id");
  var tile_id = $(".tile_id_hidden").val();
  var style = $(".tile_style_hidden").val();
  var position = $(".tile_position_hidden").val();

  if (brand == "paint" || brand == "paint_flippable") {
    tile_content = $("#wPaint" + tile_id).wPaint("image");
    $("#_wPaint_menu").css("display", "none");
  }

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Save",
      tile_id: tile_id,
      tile_content: tile_content,
      style: style,
      position: position,
      user_id: user_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SaveBack(back_content, handleData) {
  if (back_content !== "") {
    var tile_id = $(".tile_id_hidden").val();

    $.ajax({
      type: server + "POST",
      url: "/json/account.php",
      dataType: "json",
      data: {
        action: "Back",
        tile_id: tile_id,
        back_content: back_content
      },
      success: function(data) {
        handleData(data);
      }
    });
  }
}

function LoadBack(tile_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Flip",
      tile_id: tile_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SavePaint() {
  $("._wPaint_menu").css("display", "none");
  var tile_content = $("#wPaint").wPaint("image");

  var tile_id = $(".tile_id_hidden").val();
  var style = $(".tile_style_hidden").val();
  var position = $(".tile_position_hidden").val();

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Paint",
      tile_id: tile_id,
      tile_content: tile_content,
      style: style,
      position: position
    },
    success: function(data) {
      var article_id = $("#logbooks").attr("article_id");
      var page_id = $("#logbooks").attr("page_id");
      LoadPage(article_id, page_id, "", function(page) {
        $("#page").html(page.page_data);
        $.getScript("/website/js/user.js");
      });
    }
  });
}

function SaveMobilePaint() {
  var article_id = $("#logbooks").attr("article_id");

  $("._wPaint_menu").css("display", "none");
  var tile_content = $("#wPaint").wPaint("image");
  var tile_id = $(".tile_id_hidden").val();
  var style = $(".tile_style_hidden").val();

  var tile_brand = $(".tile_brand_hidden").val();

  if (tile_brand > 4) {
    //var tile_id= $(".tile_id_hidden").val();
    var link_id = $(".link_id_hidden").val();
    LinkTiles(link_id, tile_id, function(link) {});
    UpdateTile(tile_id, "brand_id", tile_brand, function() {});
  }

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Paint",
      tile_id: tile_id,
      tile_content: tile_content,
      style: style,
      position: "",
      enc_type: "json"
    },
    success: function(data) {
      $(".navigation8").hide();
      $("#mobile_parse").show();
      $("#mobile_page").show();
      $("#mobile_paint_area").html(
        "<div class='msg_success'>Paint Saved</div>"
      );
      NavCheck(3);
      LoadMobileArticle(3);
    }
  });
}

function ResetMobileTileValues() {
  $(".tile_id_hidden").val("");
  $(".link_id_hidden").val("");
  $(".tile_position_hidden").val("");
  $(".tile_style_hidden").val("");
  $(".tile_brand_hidden").val("0");
}

function CancelMobilePaint() {
  var tile_id = $(".tile_id_hidden").val();
  $(".navigation8").hide();
  $("#mobile_parse").show();
  $("#mobile_page").show();
  $("#mobile_paint_area").html("<div class='msg_error'>Paint Cancelled</div>");
  NavCheck(3);
  $("#wPaint").wPaint("clear");
  $("._wPaint_menu").remove();
  //$("#mobile_page").html("<img src='../img/loading.gif'/>");

  DeleteTile(tile_id, function() {
    var offset = $("#load_more_link").attr("offset");
    offset = parseInt(offset) - 20;
    if (offset < 0) {
      offset = 0;
    }
    LoadFeed(26, 20, 0, "mobile", function(feed) {
      ResetMobileTileValues();
      SetPage(feed);
      //$("#mobile_page").html(feed.feed_data);
      $("#mobile_page").append(
        "<div id='back_to_top_link_container'><a id='back_to_top_link' href='javascript:window:scrollTo(0,0)'>Back to Top</a></div>"
      );
      $("#mobile_page").append(
        "<div id='load_more_link_container'><a id='load_more_link' offset='20' href='javascript:LoadMore()'>Load More</a>"
      );
      DisableMobileFooter();
    });
  });
}

function AppendPaint(tile_id, style) {
  $("#page").append(
    "<div class='tile_wrapper paint'><div tile_id= '" +
      tile_id +
      "' class='tile' style='" +
      style +
      "' ><div id= 'wPaint' style='width: 100%; height: 100%;'></div></div><a href=javascript:SavePaint(); style='position: relative; bottom: 0px;'>Save Paint</a><a href='javascript:' style='margin-left: 40px;' class='drag'>Move</a>"
  );

  $("#wPaint").wPaint({
    image: "data:image/png;base64",
    lineWidthMin: "1", // line width min for select drop down
    lineWidthMax: "60", // line widh max for select drop down
    lineWidth: "6", // starting line width
    fillStyle: "#55B74E", // starting fill sfdsdsdssdf
    strokeStyle: "#5B5B5B", // start stroke style
    fontSizeMin: "8", // min font size in px
    fontSizeMax: "60", // max font size in px
    fontSize: "12" // current font size for text input
  });
  $(".tile").resizable();
  $(".tile_wrapper").draggable({
    handle: ".drag",
    containment: "#page",
    snap: true,
    snapMode: "outer",
    snapTolerance: 3
  });
  //$(document).on("mouseup", '.tile_wrapper', LoadTileValues);
  SetControlValues(tile_id, style, "", "");

  return tile_id;
}

function AppendMobilePaint(tile_id, style) {
  var fillstyle = RandomColor();
  var strokestyle = RandomColor();

  $("#image_preview").append(
    "<div class='tile_wrapper paint'><div tile_id= '" +
      tile_id +
      "' class='tile' style='' ><div id= 'wPaint' style='width: 100%; height: 100%;'></div></div><input type='button' onClick='SaveMobilePaint()' id='save_mobile_paint_button' value='Save'/></div>"
  );
  $("#wPaint").wPaint({
    image: "data:image/png;base64",
    mode: "pencil",
    menuOrientation: "vertical",
    lineWidthMin: "4", // line width min for select drop down
    lineWidthMax: "100", // line widh max for select drop down
    lineWidth: "8", // starting line width
    fillStyle: fillstyle, // starting fill
    strokeStyle: strokestyle, // start stroke style
    fontSizeMin: "24", // min font size in px
    fontSizeMax: "100", // max font size in px
    fontSize: "14" // current font size for text input
  });
  //SetControlValues(tile_id, style, "",	 "", "2", "");

  return tile_id;
}

function LoadTileValues() {
  var style = $(this).children(".tile").attr("style");
  var tile_id = $(this).find(".tile").attr("tile_id");
  var content = $(this).find(".editable").html();
  var position = $(this).attr("style");
  var brand = "editable";
  var back = "";
  var type = $(this).attr("type");

  if ($(this).hasClass("paintable")) {
    brand = "paint";
  }
  if ($(this).hasClass("flippable")) {
    if (brand == "paint") {
      brand = "flippable_paint";
    } else {
      brand = "flippable";
    }
    LoadBack(tile_id, function(back) {
      back = back.content;
      SetControlValues(tile_id, style, content, position, brand, back, type);
    });
  } else {
    //style= style.replace(/; /g, ";\r\n");
    SetControlValues(tile_id, style, content, position, brand, back, type);
  }
}

function LoadCommentValues(comment_id) {
  comment_id = typeof comment_id !== "undefined" ? comment_id : $(this).find(".tile").attr("comment_id");

  var style = $(this).children(".comment").attr("style");
  comment_id = $(this).find(".tile").attr("comment_id");
  var content = $(this).find(".editable").html();
  var position = $(this).attr("style");

  SetControlValues(comment_id, style, content, position, back);
}

function SetControlValues(
  tile_id,
  style,
  content,
  position,
  brand,
  back,
  type
) {
  if (content) {
    if (content.search("<form><textarea ") != -1) {
      content = $(".tile_content_hidden").val();
    }
  }

  $(".tile_id_hidden").val(tile_id);
  $(".tile_style_hidden").val(style);
  $(".tile_content_hidden").val(content);
  $(".tile_position_hidden").val(position);
  $(".tile_brand_hidden").val(brand);
  $(".tile_back_hidden").val(back);
  $(".tile_type_hidden").val(type);

  LoadControlValues();
}

function LoadControlValues() {
  var controls_page = $("#controls").attr("page");
  var template = $("#account").attr("template");

  if (template == "public_controls") {
    var content = $(".tile_content_hidden").val();
    $("#tile_content").val(content);
  }

  if (controls_page == "1") {
    content = $(".tile_content_hidden").val();
    $("#tile_content").val(content);
  }
  if (controls_page == "2") {
    var style = $(".tile_style_hidden").val();
    $("#tile_style").val(RemoveSize(style));

    var position = $(".tile_position_hidden").val();
    var xcoord = GetXCoord(position);
    var ycoord = GetYCoord(position);
    $("#xcoord").val(xcoord);
    $("#ycoord").val(ycoord);

    style = $(".tile_style_hidden").val();
    var wcoord = GetWidth(style);
    var hcoord = GetHeight(style);
    $("#wcoord").val(wcoord);
    $("#hcoord").val(hcoord);

    var tile_id = $(".tile_id_hidden").val();
    var link_id = $("#1coord").val();
    $("#1coord").val(tile_id);
    $("#2coord").val(link_id);
  }
  if (controls_page == "3") {
    var back = $(".tile_back_hidden").val();
    $("#tile_back").val(back);

    if ($(".tile_brand_hidden").val() == "flippable") {
      $("#flip_button").val("Flippable");
    } else {
      $("#flip_button").val("No Flip");
    }

    if ($(".tile_type_hidden").val() == "free") {
      $("#front_button").val("Free Front");
      $("#back_button").val("Free Back");
    }
    if ($(".tile_type_hidden").val() == "user") {
      $("#front_button").val("User Front");
      $("#back_button").val("User Back");
    }
    if ($(".tile_type_hidden").val() == "member") {
      $("#front_button").val("Member Front");
      $("#back_button").val("Member Back");
    }
    if ($(".tile_type_hidden").val() == "user_front") {
      $("#front_button").val("User Front");
      $("#back_button").val("Free Back");
    }
    if ($(".tile_type_hidden").val() == "user_back") {
      $("#front_button").val("Free Front");
      $("#back_button").val("User Back");
    }
    if ($(".tile_type_hidden").val() == "member_front") {
      $("#front_button").val("Member Front");
      $("#back_button").val("Free Back");
    }
    if ($(".tile_type_hidden").val() == "member_back") {
      $("#front_button").val("Free Front");
      $("#back_button").val("Member Back");
    }
    if ($(".tile_type_hidden").val() == "user_member") {
      $("#front_button").val("User Front");
      $("#back_button").val("Member Back");
    }
    if ($(".tile_type_hidden").val() == "member_user") {
      $("#front_button").val("Member Front");
      $("#back_button").val("User Back");
    }
  }
}

function LoadArticle(
  logbook_id,
  article_id,
  page_index,
  direction,
  handleData
) {
  //var logbook_id= $("#logbooks").attr("logbook_id");
  var chapter_id = $("#header").attr("chapter_id");

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Load",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      article_id: article_id,
      page_index: page_index,
      direction: direction
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadLogbook(chapter_id, logbook_id, offset, limit, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Load",
      offset: offset,
      limit: limit,
      logbook_id: logbook_id,
      chapter_id: chapter_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadPage(article_id, page_id, direction, handleData) {
  var chapter_name = $("#header").attr("chapter_name");
  var logbook_name = $("#logbooks").attr("logbook_name");

  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Load",
      article_id: article_id,
      page_id: page_id,
      direction: direction,
      chapter_name: chapter_name,
      logbook_name: logbook_name
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadTile(tile_id, stack_id, enc_type, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Load",
      tile_id: tile_id,
      stack_id: stack_id,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function GetRandomUser(handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Random"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function GetRandomArticle(user_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Random",
      user_id: user_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadArticleList(chapter_id, logbook_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "LoadArticleList",
    data: {
      action: "ArticleList",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      enc_type: "mobile"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadMobileArticleList(chapter_id, logbook_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "ArticleList",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      enc_type: "mobile"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadPageList(article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "PageList",
      article_id: article_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadChapterList(article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "ChapterList",
      article_id: article_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadLogbookList(article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "LogbookList",
      article_id: article_id,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadMobileLogbookList(chapter_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "LogbookList",
      chapter_id: chapter_id,
      enc_type: "mobile"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadUserList(logbook_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "UserList",
      logbook_id: logbook_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SaveArticle(article_id, page_id, title, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Save",
      article_id: article_id,
      page_id: page_id,
      title: title
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SaveArticleOrder(article_order, page_order) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "SaveOrder",
      article_order: article_order,
      page_order: page_order
    },
    success: function(data) {}
  });
}

function SaveChapter(article_id, chapter_arr, logbook_id) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Save",
      article_id: article_id,
      chapter_arr: chapter_arr,
      logbook_id: logbook_id
    },
    success: function(data) {}
  });
}

function CreateArticle(chapter_id, logbook_id, title, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "AppendArticle",
    data: {
      action: "Create",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      title: title,
      enc_type: "callback"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function CreatePage(article_id, title, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Create",
      article_id: article_id,
      title: title
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function DeleteArticle(article_id, title) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Delete",
      article_id: article_id,
      title: title
    },
    success: function(data) {}
  });
}

function DeletePage(page_id, title) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Delete",
      page_id: page_id,
      title: title
    },
    success: function(data) {}
  });
}

function NextArticle(logbook_id, article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Nextarticle",
      logbook_id: logbook_id,
      article_id: article_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function PrevArticle(logbook_id, article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Prevarticle",
      logbook_id: logbook_id,
      article_id: article_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadUpdates(logbook_id, page_id, direction, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Updates",
      logbook_id: logbook_id,
      page_id: page_id,
      direction: direction
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function NextPage(page_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "PrevPage",
      page_id: page_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function PrevPage(page_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "PrevPage",
      page_id: page_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function Verify(UID, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Verify",
      UID: UID
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function ClearTile(tile_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Clear",
      tile_id: tile_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function DeleteTile(tile_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "DisplayDeleteTile",
    data: {
      action: "Delete",
      tile_id: tile_id,
      enc_type: "mobile"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function Login(user_name, password, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Login",
      user_name: user_name,
      password: password,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function ShowMessages(messages, index) {
  style = "color:blue";

  if (index < messages.length) {
    if (messages[index].style) {
      style = messages[index].style;
    }

    $("#message")
      .attr("style", style)
      .html(messages[index].text)
      .fadeOut(2000, function() {
        $("#message").show();
        ShowMessages(messages, parseInt(index + 1));
      });
  } else {
    $("#message").hide();
  }
}

function ShowError(error) {
  $("#error").text(error).show().fadeOut(3000, function() {
    $("#error").text("");
  });
}

function CheckUserName(user_name, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "UserName",
      user_name: user_name,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function CheckUserID(user_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "UserID",
      user_id: user_id,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function CheckEmail(user_name, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Email",
      user_name: user_name
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function CreateAccount(user_name, password, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Create",
      user_name: user_name,
      password: password
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function CreateEmailAccount(user_name, email, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "CreateEmail",
      user_name: user_name,
      email: email
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function SendEmail(email, subject, body, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Email",
      email: email,
      subject: subject,
      body: body
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function UpdateUser(user_id, field, data, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Update",
      user_id: user_id,
      field: field,
      data: data
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoggedIn(handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "LoggedIn",
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function Logout(handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Logout",
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadTemplate(name, vars_obj, handleData) {
  if (vars_obj.length === 0) {
    vars_obj = { none: "none" };
  }
  vars_obj = JSON.stringify(vars_obj);

  $.ajax({
    type: "GET",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "LoadTemplate",
    data: {
      action: "Template",
      name: name,
      vars_obj: vars_obj,
      enc_type: "callback"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function ResetPassword(user_id, email, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Reset",
      user_id: user_id,
      email: email
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function UserInfo(email, password, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Info",
      email: email,
      password: password
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function UserPassword(user_id, password, PID, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Password",
      user_id: user_id,
      password: password,
      PID: PID
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function ChapterSelect() {
  var article_id = $("#logbooks").attr("article_id");
  //var chapter_id= $("#header").attr("chapter_id");
  //var logbook_id= $("#logbooks").attr("logbook_id");

  LoadChapterList(article_id, function(list) {
    $("#chapter_select").html(list.list_data);
    //$("#chapter_list li[chapter_id= '" +chapter_id+ "']").addClass("selected");

    LoadLogbookList(article_id, function(list) {
      $("#logbook_select").html(list.list_data);
      //$("#logbook_list li[logbook_id= '" +logbook_id+ "']").addClass("selected");
    });
  });
}

function LogbookSelect(article_id, page_id) {
  var chapter_id = $("#header").attr("chapter_id");
  var logbook_id = $("#logbooks").attr("logbook_id");

  if (article_id === 0) {
    article_id = $("#logbooks").attr("article_id");
  }
  if (page_id === 0) {
    page_id = $("#logbooks").attr("page_id");
  }

  LoadArticleList(chapter_id, logbook_id, function(list) {
    $("#article_select").html(list.list_data);
    $("#article_list li[article_id= '" + article_id + "']").addClass(
      "selected"
    );
    $("#article_list").sortable();
    $(".article_link").droppable({
      accept: ".page_link",
      drop: function() {
        var article_id = $(this).attr("article_id");
        var page_id = $("#logbooks").attr("page_id");
        ChangeArticle(page_id, article_id);
        LogbookSelect(article_id, page_id);
      }
    });

    LoadPageList(article_id, function(list) {
      $("#page_select").html(list.list_data);
      $("#page_list li[page_id= '" + page_id + "']").addClass("selected");
      $("#page_list").sortable();
      $(".page_link").droppable({
        tolerance: "pointer",
        accept: ".tile_wrapper",
        drop: function() {
          var tile_id = $(".tile_id_hidden").val();
          var page_id = $(this).attr("page_id");
          ChangePage(tile_id, page_id, function(change) {
            LoadPage(article_id, page_id, "", function(page) {
              $("#page").html(page.page_data);
              $("#logbooks").attr("page_id", page_id);
              LogbookSelect(article_id, page_id);
            });
          });
        }
      });
    });
  });
}

function AttachChapterLinkEvents() {
  if ($("#logbooks").data("chapter_link_events") != true) {
    $(document).on("click", ".chapter_link", function(event) {
      var chapter_id = $(this).attr("chapter_id");

      if ($(this).hasClass("selected")) {
        $(this).removeClass("selected");
      } else {
        $(this).addClass("selected");
      }
    });

    $("#logbooks").data("chapter_link_events", true);
  }
}

function AttachLogbookLinkEvents() {
  if ($("#logbooks").data("logbook_link_events") !== true) {
    $(document).on("click", ".logbook_link", function(event) {
      var logbook_id = $(this).attr("logbook_id");

      $(".logbook_link").each(function() {
        $(this).removeClass("selected");
      });
      $(this).addClass("selected");

      $("#logbooks").data("logbook_link_events", true);
    });
  }
}

function AttachArticleLinkEvents() {
  if ($("#logbooks").data("article_link_events") !== true) {
    $(document).on("click", ".article_link", function(event) {
      $("#page").css("background", "#fff");
      $("#page").html("<img src='/website/img/loading3.gif'/>");
      $("#page_select").html("loading...");
      $("#controls").attr("select", "article");

      var article_id = $(this).attr("article_id");

      $(".article_link").each(function() {
        $(this).removeClass("selected");
      });
      $(this).addClass("selected");

      $("#title").val($(this).text());
      $("#logbooks").attr("article_id", article_id);
      $("#logbooks").attr("page_id", 0);

      LoadPageList(article_id, function(list) {
        $("#page_select").html(list.list_data);
        $("#page_list li:first").addClass("selected");

        LoadPage(article_id, list.first, "", function(page) {
          $("#logbooks").attr("page_id", page.page_id);
          $("#page").html(page.page_data);
          $.getScript("/website/js/user.js");
          $("#page_list").sortable();
          $(".page_link").droppable({
            tolerance: "pointer",
            accept: ".tile_wrapper",
            drop: function() {
              var tile_id = $(".tile_id_hidden").val();
              var page_id = $(this).attr("page_id");
              ChangePage(tile_id, page_id, function() {
                LoadPage(article_id, page_id, "", function(page) {
                  $("#page").html(page.page_data);
                  $("#logbooks").attr("page_id", page_id);
                  LogbookSelect();
                });
              });
            }
          });
        });
      });

      $("#logbooks").data("article_link_events", true);
    });
  }
}

function AttachPageLinkEvents() {
  if ($("#logbooks").data("page_link_events") !== true) {
    $(document).on("click", ".page_link", function(event) {
      $("#controls").attr("select", "page");
      $("#page").css("background", "#fff");
      $("#page").html("<img src='/website/img/loading3.gif'/>");

      var article_id = $("#logbooks").attr("article_id");
      var page_id = $(this).attr("page_id");

      $(".page_link").each(function() {
        $(this).removeClass("selected");
      });
      $(this).addClass("selected");

      $("#title").val($(this).text());
      $("#logbooks").attr("page_id", page_id);

      LoadPage(article_id, page_id, "", function(page) {
        $("#page").html(page.page_data);
        $.getScript("/website/js/user.js");
      });
    });

    $("#logbooks").data("page_link_events", true);
  }
}

function ChangeArticle(page_id, article_id) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Change",
      page_id: page_id,
      article_id: article_id
    },
    success: function(data) {
      //alert(data);
    }
  });
}

function ChangePage(tile_id, page_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    data: {
      action: "Change",
      tile_id: tile_id,
      page_id: page_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadComments(article_id, enc_type, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Load",
      article_id: article_id,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function AddComment(
  article_id,
  comment_email,
  comment_intro,
  comment_text,
  comment_image,
  handleData
) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Add",
      article_id: article_id,
      comment_email: comment_email,
      comment_intro: comment_intro,
      comment_text: comment_text,
      comment_image: comment_image
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadCommentCount(page_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Count",
      page_id: page_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

/*
function CommentsOn()
{
	var page_id= $("#logbooks").attr("page_id");
	LoadTemplate('comments', function(template) {
		$("#page").html(template.template_data);
		LoadComments(page_id, function(comments) {

			$("#comments").html(comments.comment_data);
			$('#comments_container').tinyscrollbar();
		});
	});
}

function CommentsOff()
{
	var page_id= $("#logbooks").attr("article_id");
	var page_id= $("#logbooks").attr("page_id");
	LoadPage(article_id, page_id, "", function(page) {

		$("#page").html(page.page_data);
	});
}
*/

function LoadStats(chapter_id, logbook_id, article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "DisplayStats",
    data: {
      action: "Stats",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      article_id: article_id,
      enc_type: "callback"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadMetric(metric, chapter_id, logbook_id, article_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Metric",
      metric: metric,
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      article_id: article_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function GenerateLinks(first) {
  /*
	var logbook_link= "<a id='" + $("#top_nav_article").attr("id") + "_link' href='http://" + $("#header").attr("chapter_name") + "." $("#top_nav_element1").text() + ".cerebrit.com/'>" + $("#top_nav_element1").text() + "</a>";
	var article_link= "<a id='" + $("#top_nav_article").attr("id") + "_link' href='/" + EscapeChars($("#top_nav_element1").text()) + "/'>" + $("#top_nav_element1").text() + "</a>";
	var page_link= "<a id='" + $("#top_nav_page").attr("id") + "_link' href='/" + EscapeChars($("#top_nav_article").text()) + "/" + EscapeChars($("#top_nav_page").text()) + "/'>" + $("#top_nav_page").text() + "</a>";
	var user_link= "<a id='" + $("#top_nav_user").attr("id") + "_link' href='/" + EscapeChars($("#top_nav_user").text()) + "/'>" + $("#top_nav_user").text() + "</a>";
	*/

  switch (first) {
    case "logbook":
      element1 =
        "<a id='logbook_link' href='http://" +
        $("#header").attr("chapter_name") +
        "." +
        $("#top_nav_element1").text() +
        ".cerebrit.com/'>" +
        $("#top_nav_element1").text() +
        "</a>";
      element2 =
        "<a id='article_link' href='/" +
        EscapeChars($("#top_nav_element2").text()) +
        "/'>" +
        $("#top_nav_element2").text() +
        "</a>";
      break;
    case "article":
      element1 =
        "<a id='article_link' href='/" +
        EscapeChars($("#top_nav_element1").text()) +
        "/'>" +
        $("#top_nav_element1").text() +
        "</a>";
      element2 =
        "<a id='page_link' href='/" +
        EscapeChars($("#top_nav_element1").text()) +
        "/" +
        EscapeChars($("#top_nav_element2").text()) +
        "/'>" +
        $("#top_nav_element2").text() +
        "</a>";
      break;
    case "page":
      element1 =
        "<a id='page_link' href='/" +
        EscapeChars($("#top_nav_element1").text()) +
        "/" +
        EscapeChars($("#top_nav_element1").text()) +
        "/'>" +
        $("#top_nav_element1").text() +
        "</a>";
      element2 =
        "<a id='user_link' href='/" +
        EscapeChars($("#top_nav_element2").text()) +
        "/'>" +
        $("#top_nav_element2").text() +
        "</a>";
      break;
    default:
      element1 =
        "<a id='logbook_link' href='http://" +
        $("#header").attr("chapter_name") +
        "." +
        $("#top_nav_element1").text() +
        ".cerebrit.com/'>" +
        $("#top_nav_element1").text() +
        "</a>";
      element2 =
        "<a id='article_link' href='/" +
        EscapeChars($("#top_nav_element2").text()) +
        "/'>" +
        $("#top_nav_element2").text() +
        "</a>";
      break;
  }

  $("#top_nav_element1").html(element1);
  $("#top_nav_element2").html(element2);
}

function EscapeChars(text_string) {
  text_string = text_string.replace("'", "&#039;");
  text_string = text_string.replace('"', "&quot;");
  return text_string;
}

function FullPagePaint(tile_id, style) {
  $("#page").append(
    "<div class='tile_wrapper paint' style='width: 100%; height: 100%;'><div tile_id= '" +
      tile_id +
      "' class='tile' style='" +
      style +
      "; width: 100%; height: 100%;' ><div id= 'wPaint' style='width: 100%; height: 100%;'></div></div><a href=javascript:SavePaint(); style='position: relative; bottom: 0px;'>Save Paint</a><a href='javascript:' style='margin-left: 40px;' class='drag'>Move</a>"
  );

  $("#wPaint").wPaint({
    image: "data:image/png;base64",
    lineWidthMin: "1", // line width min for select drop down
    lineWidthMax: "60", // line widh max for select drop down
    lineWidth: "6", // starting line width
    fillStyle: "#55B74E", // starting fill sfdsdsdssdf
    strokeStyle: "#5B5B5B", // start stroke style
    fontSizeMin: "8", // min font size in px
    fontSizeMax: "60", // max font size in px
    fontSize: "12" // current font size for text input
  });
  $(".tile").resizable();
  $(".tile_wrapper").draggable({
    handle: ".drag",
    containment: "#page",
    snap: true,
    snapMode: "outer",
    snapTolerance: 3
  });
  //$(document).on("mouseup", '.tile_wrapper', LoadTileValues);
  SetControlValues(tile_id, style, "", "");

  return tile_id;
}

function FullPageEdit(tile_id, style) {
  $("#page").append(
    "<div class='tile_wrapper' style='width: 100%; height: 100%;'><div tile_id= '" +
      tile_id +
      "' class='tile' style='" +
      style +
      "; width: 100%; height: 100%;' ><div class='editable fullscreen_" +
      tile_id +
      "'></div></div></div>"
  );

  var selector = ".fullscreen_" + tile_id;

  $(selector).tinymce({
    // Location of TinyMCE script
    script_url: "/website/js/lib/tiny_mce/tiny_mce.js",

    // General options
    theme: "advanced",
    plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

    // Theme options
    theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
    theme_advanced_toolbar_location: "top",
    theme_advanced_toolbar_align: "left",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: false,
    theme_advanced_font_sizes: "8pt, 10pt, 12pt, 14pt 18pt, 24pt, 36pt, 54pt, 72pt, 96pt, 112pt, 134pt, 164pt, 198pt",
    font_size_style_values: "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt, 54pt, 72pt, 96pt, 112pt, 134pt, 164pt, 198pt",
    height: "100%",

    // Example content CSS (should be your site CSS)
    //content_css : "css/content.css",

    // Drop lists for link/image/media/template dialogs
    //template_external_list_url : "lists/template_list.js",
    //external_link_list_url : "lists/link_list.js",
    //external_image_list_url : "lists/image_list.js",
    //media_external_list_url : "lists/media_list.js",
    save_enablewhendirty: false,
    media_use_script: false,
    save_onsavecallback: "SaveFullPageEdit"

    // Replace values for the template plugin
    //			template_replace_values : {
    //				username : "Some User",
    //				staffid : "991234"
    //			}
  });
}

function SaveFullPageEdit() {
  var tile_content = tinyMCE.activeEditor.getContent({ format: "raw" });
  $("#full_page_edit_text.tinymce").remove();
  $(".tile_content_hidden").val(tile_content);
  SaveTile(tile_content, function(tile) {
    var article_id = $("#logbooks").attr("article_id");
    var page_id = $("#logbooks").attr("page_id");

    $("#page").html("<img src='../img/loading2.gif'/>");

    LoadPage(article_id, page_id, "", function(page) {
      $("#page").html(page.page_data);
      $.getScript("/website/js/user.js");
    });
  });
}

function GetXCoord(coord) {
  var leftpos = coord.indexOf("left: ");
  var leftpx = coord.indexOf("px;");
  var leftdf = leftpx - leftpos;

  var xcoord = coord.slice(leftpos + 6, leftdf);

  return xcoord;
}

function GetYCoord(coord) {
  var firstpx = coord.indexOf("px;");
  coord = coord.substr(firstpx + 3);
  var toppos = coord.indexOf("top: ");
  var toppx = coord.indexOf("px;");
  var topdf = toppx - toppos;

  var ycoord = coord.slice(toppos + 5, topdf + 1);

  return ycoord;
}

function RemoveSize(style) {
  var begin = style.split(";");
  var len = begin.length;
  while (len--) {
    if (begin[len].indexOf("width:") != -1) {
      begin.splice(len, 1);
    }
    if (begin[len].indexOf("height:") != -1) {
      begin.splice(len, 1);
    }
  }
  begin = begin.join("; ");

  return begin;
}

function GetWidth(coord) {
  var firstpx = coord.indexOf("width:");
  coord = coord.substr(firstpx);
  var leftpx = coord.indexOf("px;");
  //var leftdf= leftpx - leftpos;

  var wcoord = coord.slice(7, leftpx);

  return wcoord;
}

function GetHeight(coord) {
  var firstpx = coord.indexOf("height:");
  coord = coord.substr(firstpx);
  var toppx = coord.indexOf("px;");
  //var topdf= toppx - toppos;

  var ycoord = coord.slice(8, toppx);

  return ycoord;
}

function LoadRecentUpdates(
  chapter_id,
  logbook_id,
  user_id,
  limit,
  type,
  handleData
) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Recent",
      chapter_id: chapter_id,
      logbook_id: logbook_id,
      user_id: user_id,
      limit: limit,
      type: type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadPopularPages(user_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Popular",
      user_id: user_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadPageViews(page_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Views",
      page_id: page_id
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function UpdateTile(tile_id, property, value, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Update",
      tile_id: tile_id,
      property: property,
      value: value,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadGravatar(hash, handleData) {
  $.ajax({
    type: "GET",
    url: "http://www.gravatar.com/" + hash + ".json",
    dataType: "jsonp",
    data: {},
    success: function(data) {
      handleData(data);
    }
  });
}

function UploadImage(image_name, image_path, full_size, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Upload",
      full_size: full_size,
      name: image_name,
      path: image_path
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LinkTiles(tile_id, link_id, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Link",
      tile_id: tile_id,
      link_id: link_id,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadArticleInfo(article_id, page_id, enc_type, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Info",
      article_id: article_id,
      page_id: page_id,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function FacebookLogin() {
  FB.login(
    function(response) {
      if (response.authResponse) {
        FB.api("/me", function(response) {
          LoadTemplate(
            "user_logged_in",
            { user_name: response.first_name },
            function(template) {
              $("#home_title").html(template.template_data);
              $("#home_title").attr("template", "user_logged_in");

              LoadTemplate("article_info", { "": "" }, function(template2) {
                $("#account").html(template2.template_data);
                $("#account").attr("template", "article_info");
              });
            }
          );
        });
      } else {
        // cancelled
      }
    },
    { scope: "email" }
  );
}

function FacebookLoginMobile() {
  FB.login(
    function(response) {
      if (response.authResponse) {
        FB.api("/me", function(response) {
          //$("#article_list_container").html("loading...");
          LoadTemplate(
            "user_logged_in",
            { user_name: response.first_name },
            function(template) {
              $("#home_title").html(template.template_data);
              $("#home_title").attr("template", "user_logged_in");
              var article_id = $("#logbooks").attr("article_id");
              NavCheck(article_id);
              LoadMobileArticle(article_id);

              LoadTemplate(
                "user_info",
                { user_name: response.first_name },
                function(template2) {
                  $("#account").html(template2.template_data);
                  $("#account").attr("template", "article_info");
                  LoadArticleList("26", "4", function(list) {
                    $("#article_list_container").html(list.list_data);
                    var hash = hex_md5(response.email);
                    $("#new_post_gravatar img").attr(
                      "src",
                      "http://www.gravatar.com/avatar/" + hash + "?r=pg"
                    );
                  });
                }
              );
            }
          );
        });
      } else {
        // cancelled
      }
    },
    { scope: "email" }
  );
}

function ParseUrl(
  url,
  res_text,
  res_images,
  res_links,
  res_path,
  search,
  enc_type,
  handleData
) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "DisplayParseUrl",
    data: {
      action: "Parse",
      url: url,
      res_text: res_text,
      res_images: res_images,
      res_links: res_links,
      res_path: res_path,
      search: search,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function LoadFeed(chapter_id, limit, offset, enc_type, handleData) {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "jsonp",
    jsonpCallback: "DisplayLoadFeed",
    data: {
      action: "Feed",
      chapter_id: chapter_id,
      limit: limit,
      offset: offset,
      enc_type: enc_type
    },
    success: function(data) {
      handleData(data);
    }
  });
}

function Flip() {
  $("#page").css({
    transformOrigin: "50% 50% -100px",
    transformStyle: "preserve-3d"
  });
  $("#page").rotate3Di("720", 6000);
}

function BMP() {
  $(".editable").editable("destroy");
  $(".tile").resizable("destroy");
  $(".tile_wrapper").draggable("destroy");
  $(".paint").off("dblclick");
  $(document).off("mouseup", ".tile_wrapper");

  var html = $("#page").html();
  html = "<div>" + html + "</div>";
  $("#page").html(html);
  html2canvas($("#page"), {
    onrendered: function(canvas) {
      $("#page").html(
        "<img style='position: relative; width: 25px; height: 25px; left: 0px; top: 0px;' src='/website/img/loading2.gif'/>"
      );
      //$("#image_preview").html(canvas);
      var oImgPNG = Canvas2Image.saveAsPNG(canvas, true);
      $("#page").html(oImgPNG);
      html = $("#page").html();
      html = "<div id='page_img'>" + html + "</div>";
      $("#page").html(html);
    }
    //width: 300,
    //height: 300
  });
}

function LoadZipfile() {
  $.ajax({
    type: "POST",
    url: server + "/json/account.php",
    dataType: "json",
    data: {
      action: "Zipfile",
      page_id: page_id,
      enc_type: "json"
    },
    success: function(data) {
      handleData(data);
    }
  });
}