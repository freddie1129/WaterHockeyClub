import * as lib from './lib.js';
var tag_cookie_userId = "userId";

var formURL = "action.php";
var POST = "POST"
var GET = "GET"

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
                    var keywords = $("#id_search_news_keywords").val();
                    var startTime = $("#search_news_start_date").val();
                    var endTime = $("#search_news_end_date").val();
                    var userId = lib.getCookie(tag_cookie_userId);
                    var url = window.location.href;
                    var newUrl =  url.split("?")[0] + '?keywords=' + keywords + "&userId=" + userId + "&startTime=" + startTime + "&endTime=" + endTime;
                    console.log(url);
                    window.location.href = newUrl;
                }
                else
                {
                }
            },
            function(jqXHR, status, error) {
                console.log(status + ": " + error);
            });
    });


    // edit_news
    $(".edit_news").on('click', function() {
        var keywords = $("#id_search_news_keywords").val();
        var startTime = $("#search_news_start_date").val();
        var endTime = $("#search_news_end_date").val();
        var userId = lib.getCookie(tag_cookie_userId);
        var url = window.location.href;
        var newUrl =  url.split("?")[0] + '?keywords=' + keywords + "&userId=" + userId + "&startTime=" + startTime + "&endTime=" + endTime;
        console.log(url);
        window.location.href = newUrl;
    });

});