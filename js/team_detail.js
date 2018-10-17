import * as lib from './lib.js';
var tag_cookie_userId = "userId";

var formURL = "action.php";
var POST = "POST"
var GET = "GET"


// refresh page, called after update news, make a new search and create a new News.
function refreshPage()
{
    //var keywords = $("#id_search_team_keywords").val();
    //var url = window.location.href;
    //var newUrl =  url.split("?")[0] + '?teamId=' + keywords;
    //console.log("Refresh team manage page: " + url);
    //window.location.href = newUrl;
    location.reload();
}



/* must apply only after HTML has loaded */
$(document).ready(function () {

    //  make a news search
    $("#id_search_team").on('click', function() {
        refreshPage();
    });

    //  delete a news
    $(".delete_member").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var id =  t[t.length - 1];
        var name = $("#inputMemberFristName_" + id).val();
        if (!confirm("Are you sure you want to delete this member!\n\n" + name)) {
            return;
        }
        var data = {"id" : Number(id),
            "action" : "httpDeleteMember"};
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
    $(".edit_member").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var teamId =  t[t.length - 1];
        var memberId = $("#inputMemberId_" + teamId).val();
        var memberFirstName = $("#inputMemberFristName_" + teamId).val();
        var memberLastName = $("#inputMemberLastName_" + teamId).val();
        var memberNickName = $("#inputMemberNickName_" + teamId).val();
        var memberGender = $("#inputMemberGender_" + teamId).val();
        var birthday = $("#inputMemberBirthday_" + teamId).val();
        $("#edit_dialog_title").html("Update Member");
        $("#modal_first_name").val(memberFirstName);
        $("#modal_last_name").val(memberLastName);
        $("#modal_nick_name").val(memberNickName);
        if (memberGender == "male")
        {
            $( "#modal_gender_male" ).prop( "checked", true );
        }
        else if (memberGender == "female")
        {
            $( "#modal_gender_female" ).prop( "checked", true );
        }
        else
        {
            $( "#modal_gender_other" ).prop( "checked", true );
        }




        $("#modal_birthday").val(birthday);
        $("#modal_member_id").val(memberId);
        $("#modal_update"). show();
        $("#modal_create").hide();
        $("#editMemberModal").modal('show');
    });

    // Update team
    $("#modal_update").on('click', function() {

        var firstName = $("#modal_first_name").val();
        var lastName = $("#modal_last_name").val();
        var nickName = $("#modal_nick_name").val();
        var gender =  $("input:checked" ).val();
        var birthday = $("#modal_birthday").val();
        var teamId = $("#input_team_id").val();
        var teamName = $("#input_team_name").html();
        var memberId = $("#modal_member_id").val();


        if (firstName == "")
        {
            alert("First name can not be empty!");
            return;
        }

        var data;

        data = {
            "id" : Number(memberId),
            "firstName" : firstName,
            "lastName" : lastName,
            "nickName" : nickName,
            "gender" : gender,
            "birthday" : birthday,
            "teamId" : Number(teamId),
            "teamName" : teamName,

            "action" : "httpUpdateMember"};


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


    $("#createNewMember").on('click', function() {
        $("#edit_dialog_title").html("Create a new Member");
        $("#modal_first_name").val("");
        $("#modal_last_name").val("");
        $("#modal_nick_name").val("");
        $( "#modal_gender_male" ).prop( "checked", true );
        $( "#modal_gender_female" ).prop( "checked", false );
        $( "#modal_gender_other" ).prop( "checked", false );
        $( "#modal_birthday" ).val( new Date().toISOString().split("T")[0]);
        $("#modal_update"). hide();
        $("#modal_create").show();
        $("#editMemberModal").modal('show');
    });


    // Update news
    $("#modal_create").on('click', function() {

        var firstName = $("#modal_first_name").val();
        var lastName = $("#modal_last_name").val();
        var nickName = $("#modal_nick_name").val();
        var gender =  $("input:checked" ).val();
        var birthday = $("#modal_birthday").val();
        var teamId = $("#input_team_id").val();
        var teamName = $("#input_team_name").html();


        if (firstName == "")
        {
            alert("First name can not be empty!");
            return;
        }

        var data;

            data = {
                "firstName" : firstName,
                "lastName" : lastName,
                "nickName" : nickName,
                "gender" : gender,
                "birthday" : birthday,
                "teamId" : Number(teamId),
                "teamName" : teamName,

                "action" : "httpCreateMember"};


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