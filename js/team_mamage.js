import * as lib from './lib.js';
var tag_cookie_userId = "userId";

var formURL = "action.php";
var POST = "POST"
var GET = "GET"


// refresh page, called after update news, make a new search and create a new News.
function refreshPage()
{
    var keywords = $("#id_search_team_keywords").val();
    var url = window.location.href;
    var newUrl =  url.split("?")[0] + '?keywords=' + keywords;
    console.log("Refresh team manage page: " + url);
    window.location.href = newUrl;
}

/* must apply only after HTML has loaded */
$(document).ready(function () {

    //  make a news search
    $("#id_search_team").on('click', function() {
        refreshPage();
    });

    //  delete a news
    $(".delete_team").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var id =  t[t.length - 1];
        var title = $("#team_name_"+ id).text();
        if (!confirm("Are you sure you want to delete this team!\n\n" + title)) {
            return;
        }
        var data = {"teamId" : Number(id),
            "action" : "httpDeleteTeam"};
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
    $(".edit_team").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var teamId =  t[t.length - 1];
        var teamName = $("#inputTeamName_" + teamId).val();
        var teamLocation = $("#inputTeamLocation_" + teamId).val();
        var teamEstablishTime = $("#inputTeamTime_" + teamId).val();
        var teamCaptionName = $("#inputTeamCaptionName_" + teamId).val();
        var teamIntroduction = $("#inputTeamIntro_" + teamId).val();
        $("#modal_teamId").val(teamId);
        $("#edit_dialog_title").html("Edit Team Info");
        $("#modal_teamName").val(teamName);
        $("#modal_teamLocation").val(teamLocation);
        $("#modal_teamEstablishTime").val(teamEstablishTime);
        $("#modal_teamCaptionName").val(teamCaptionName);
        $("#modal_teamIntroduction").html(teamIntroduction);
        $("#update_editTeam").show();
        $("#create_editTeam").hide();
        $("#editTeamModal").modal('show');
    });

    // Update team
    $("#update_editTeam").on('click', function() {
        var teamId = $("#modal_teamId").val();
        var teamName = $("#modal_teamName").val();
        var teamLocation = $("#modal_teamLocation").val();
        var teamTime = $("#modal_teamEstablishTime").val();
        var teamCaptionName = $("#modal_teamCaptionName").val();
        var teamIntroduction = $("#modal_teamIntroduction").val();

        if (teamName == "")
        {
            alert("Team name can not be empty!");
            return;
        }
        var data = {
            "teamId" : Number(teamId),
            "teamName" : teamName,
            "teamLocation" : teamLocation,
            "teamEstablishTime" : teamTime,
            "teamCaptionName" : teamCaptionName,
            "teamIntroduction" : teamIntroduction,
            "action" : "httpUpdateTeam"};
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


    $("#createNewTeam").on('click', function() {
        $("#edit_dialog_title").html("Create a new team");
        $("#modal_teamName").val("");
        $("#modal_teamLocation").val("");
        $("#modal_teamEstablishTime").val("");
        $("#modal_teamCaptionName").val("");
        $("#modal_teamIntroduction").html("");
        $("#update_editTeam").hide();
        $("#create_editTeam").show();
        $("#editTeamModal").modal('show');
    });


    // Update news
    $("#create_editTeam").on('click', function() {

        var teamName = $("#modal_teamName").val();
        var teamLocation = $("#modal_teamLocation").val();
        var teamTime = $("#modal_teamEstablishTime").val();
        var teamCaptionName = $("#modal_teamCaptionName").val();
        var teamIntroduction = $("#modal_teamIntroduction").val();

        if (teamName == "")
        {
            alert("Team name can not be empty!");
            return;
        }


        var data;

            data = {
                "teamName" : teamName,
                "teamLocation" : teamLocation,
                "teamEstablishTime" : teamTime,
                "teamCaptionName" : teamCaptionName,
                "teamIntroduction" : teamIntroduction,
                "action" : "httpCreateTeam"};


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