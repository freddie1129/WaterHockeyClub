import * as lib from './lib.js';
var tag_cookie_userId = "userId";

var formURL = "action.php";
var POST = "POST"
var GET = "GET"


// refresh page, called after update news, make a new search and create a new News.
function refreshPage()
{
    var keywords = $("#id_search_news_keywords").val();
    var startTime = $("#search_news_start_date").val();
    var endTime = $("#search_news_end_date").val();
    var userId = lib.getCookie(tag_cookie_userId);
    var url = window.location.href;
    var newUrl =  url.split("?")[0] + '?keywords=' + keywords + "&userId=" + userId + "&startTime=" + startTime + "&endTime=" + endTime;
    console.log(url);
    window.location.href = newUrl;
}

/* must apply only after HTML has loaded */
$(document).ready(function () {

    //  make a news search
    $("#id_search_news").on('click', function() {
        var keywords = $("#id_search_news_keywords").val();
        var startTime = $("#search_news_start_date").val();
        var endTime = $("#search_news_end_date").val();
        var userId = lib.getCookie(tag_cookie_userId);
        var url = window.location.href;
        var newUrl =  url.split("?")[0] + '?keywords=' + keywords + "&userId=" + userId + "&startTime=" + startTime + "&endTime=" + endTime;
        console.log(url);
        window.location.href = newUrl;
    });

    //  delete a news
    $(".delete_news").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var news_id =  t[t.length - 1];
        var title = $("#news_title_"+ news_id).text();
        if (!confirm("Are you sure you want to delete this news!\n\n" + title)) {
            return;
        }
        var data = {"newsId" : Number(news_id),
            "action" : "httpDeleteNews"};
        console.log(data);
        var postData = $.param(data);
        lib.http(formURL,POST,postData,
            function(data, textStatus, jqXHR) {
                console.log(data);
                if(data["status"] == "success")
                {
                    refreshPage();
                }
                else
                {
                }
            },
            function(jqXHR, status, error) {
                console.log(status + ": " + error);
            });
    });

    // Pop up update news dialogue
    $(".edit_news").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var newsId =  t[t.length - 1];
        var title = $("#inputNewsTitle_" + newsId).val();
        var content = $("#inputNewsContent_" + newsId).val();
        $("#edit_dialog_title").html("Edit News");
        $("#modal_newsTitle").val(title);
        $("#modal_newsContent").html(content);
        $("#modal_newsId").val(newsId);
        $("#update_editNews").show();
        $("#create_editNews").hide();
        $("#editNewsModal").modal('show');


    });

    // Update news
    $("#update_editNews").on('click', function() {
        var newsId = Number($("#modal_newsId").val());
        var newsTitle = $("#modal_newsTitle").val();
        var newsContent = $("#modal_newsContent").val();
        console.log("Update News:")
        console.log(newsId,newsTitle,newsContent);
        var data = {"newsId" : newsId,
                    "newsTitle" : newsTitle,
                    "newsContent" : newsContent,
                    "action" : "httpUpdateNews"};
        var postData = $.param(data);
        lib.http(formURL,POST,postData,
            function(data, textStatus, jqXHR) {
                console.log(data);
                if(data["status"] == "success")
                {
                    refreshPage();
                }
                else
                {
                    console.log("Failed to update news!")
                }
            },
            function(jqXHR, status, error) {
                console.log("Failed to update news!" + status + ": " + error);
            });
    });


    $("#createNewNews").on('click', function() {
        $("#edit_dialog_title").html("Post a News");
        $("#update_editNews").hide();
        $("#create_editNews").show();



        $("#modal_newsTitle").val("");
        $("#modal_newsContent").html("");
        $("#modal_newsId").val("");
        $("#editNewsModal").modal('show');
    });


    // Update news
    $("#create_editNews").on('click', function() {
        var newsTitle = $("#modal_newsTitle").val();
        var newsContent = $("#modal_newsContent").val();
        var userId = lib.getCookie(tag_cookie_userId);
        var data;


            data = {
                "userId" : userId,
                "newsTitle" : newsTitle,
                "newsContent" : newsContent,
                "action" : "httpCreateNews"};


        console.log(data);
        var postData = $.param(data);
        console.log(postData)
        lib.http(formURL,POST,postData,
            function(data, textStatus, jqXHR) {
                console.log(data);
                if(data["status"] == "success")
                {
                    refreshPage();
                }
                else
                {
                    // controlUserButtons(true,usrType);
                    console.log(data);
                }
            },
            function(jqXHR, status, error) {
                console.log(status + ": " + error);
            });
    });

});