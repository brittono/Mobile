window.addEventListener("load", function() {
  // Set a timeout...
  setTimeout(function() {
    // Hide the address bar!
    window.scrollTo(0, 1);
  }, 0);
});

//var server = "https://brittono.herokuapp.com";
var server = "http://127.0.0.1:8888";

$(document).ready(function() {
  OpenNav();
});

$(".mobile_footer").draggable({
  revert: true,
  revertDuration: 10,
  delay: 400,
  axis: "y",
  drag: function(event, ui) {
    $(".page_load").css("height", ui.offset.top);
  },
  start: function(event, ui) {
    $("#navigation_container").html("");
  },
  stop: function(event, ui) {
    LoadMobileArticle(3);
  }
});

GenerateLinks("article");

LoadTemplate("stats", { "": "" }, function(template) {
  $("#account").html(template.template_data);
});

LoadArticleList("26", "4", function(list) {
  $("#article_list_container").html(list.list_data);
});

function ActivateMobileFooter() {
  $("#footer_msg").html("Pull for .feed");
  $(".mobile_footer").draggable("enable");
}

function DisableMobileFooter() {
  $(".mobile_footer").draggable("disable");
  $("#footer_msg").html(
    "Questions, Comments, Feedback? <a href='www.twitter.com/cerebrit'>@cerebrit</a>"
  );
}

function SetPage(feed) {
  var link_id = "";
  var composite_content = "";

  $.each(feed.composite_data, function() {
    link_id = this.link_id;
    composite_content = this.tile_content;

    $(".mobile_wrapper").each(function(index, val) {
      //alert($(this).children(".mobile").attr("tile_id"));
      if (
        $(this)
          .children(".mobile")
          .attr("tile_id") == link_id
      ) {
        $(this).append(composite_content);
      }
    });
  });
}

function LoadMobileArticle(article_id) {
  if (article_id == "") {
    var article_id = $("#article_list option:selected").attr("article_id");
  }

  if (article_id == 2) {
    $("#article_list_container").html("loading...");
    LoadArticleList("26", "20", function(list) {
      $("#article_list_container").html(list.list_data);
      $("#logbooks").attr("article_id", "2");
    });
    $("#mobile_page").html("");
    $("#mobile_parse").html("");
    ActivateMobileFooter();
    $("#logbooks").attr("article_id", article_id);
    $("#logbooks").attr("page_id", 0);
  } else if (article_id == 3) {
    $("#mobile_page").html("<img src='/assets/images/loading.gif'/>");
    LoadFeed(26, 20, 0, "mobile", function(feed) {
      $("#mobile_page").html(feed.feed_data);
      SetPage(feed);

      $("#mobile_page").append(
        "<div id='back_to_top_link_container'><a id='back_to_top_link' href='javascript:window:scrollTo(0,0)'>Back to Top</a></div>"
      );
      $("#mobile_page").append(
        "<div id='load_more_link_container'><a id='load_more_link' offset='20' href='javascript:LoadMore()'>Load More</a>"
      );
      $("#logbooks").attr("article_id", "3");
      $("#logbooks").attr("page_id", 0);
      DisableMobileFooter();
    });
  } else if (article_id == 1) {
    $("#article_list_container").html(
      "<input id='add_article_title' style='width: 100px;font-size: 9px;' type='text' placeholder='Article Name'/><input id= 'add_article' style='font-size: 9px; float: right;' type='button' value='Add'/>"
    );
  } else if (article_id > 0) {
    //$("#mobile_page").html("<img src='/assets/images/loading.gif'/>");

    $.ajax({
      type: "POST",
      url: server + "/json/logbook.php",
      dataType: "json",
      data: {
        action: "Load",
        chapter_id: 26,
        logbook_id: 20,
        article_id: article_id,
        page_index: 0,
        direction: "",
        enc_type: "mobile"
      },
      success: function(data) {
        DisableMobileFooter();
        $("#logbooks").attr("article_id", article_id);
        $("#logbooks").attr("page_id", data.page_id);
        if (data.tile_count > 1) {
          $("#mobile_page").css("height", "auto");
          $("#mobile_container").css("height", "auto");
        }
        $("#mobile_page").html(data.content);
        SetPage(data);
        $("#mobile_page").append(
          "<div id='back_to_top_link_container'><a id='back_to_top_link' href='javascript:window:scrollTo(0,0)'>Back to Top</a></div>"
        );
        //LoadMobileComments();
      }
    });
  } else {
    $("#mobile_page").css("height", "460px;");
    $("#mobile_container").css("height", "500px;");
  }
}

function OpenNav() {
  var nav = $("#home_title").attr("nav");

  if (nav == "close") {
    $("#home_title").attr("nav", "open");
    /*
    $("#navigation_container").html(
      "<img style='position: relative; width: 25px; height: 25px; left: 0px; top: 0px;' src='./img/loading3.gif'/>"
    );
    */

    LoadTemplate("navigation_mobile5", { "": "" }, function(template) {
      $("#navigation_container").html(template.template_data);

      LoadTemplate("navigation_mobile", { "": "" }, function(template) {
        $("#navigation_container").append(template.template_data);

        LoadTemplate("navigation_mobile2", { "": "" }, function(template2) {
          $("#navigation_container").append(template2.template_data);

          LoadTemplate("navigation_mobile3", { "": "" }, function(template3) {
            $("#navigation_container").append(template3.template_data);
          });
        });
      });
    });
    //ActivateTileControls();
  } else {
    CloseNav();
  }
}

function CloseNav() {
  $("#home_title").attr("nav", "close");
  $("#navigation_container").html("");
  $("#navigation_container2").html("");
  $("#navigation_container3").html("");
  $(".social_media_selected").removeClass("social_media_selected");
  $(".navigation_container_selected").removeClass(
    "navigation_container_selected"
  );
  
  //DisableTileControls();

  var chapter_id = $("#mobile_header").attr("chapter_id");

  /*
  LoadStats(chapter_id, 0, 0, function(stats) {
    $("#tile_count").text(stats.edit_count);
    $("#edit_chart").sparkline(stats.tile_timeline, { width: "80px" });
  });
  */
  //$(document).off("touchstart", ".mobile_wrapper");
  //$(document).off("click", ".mobile_wrapper");
  //$(document).off("click", ".mobile_wrapper_selected");
}
