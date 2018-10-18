import * as lib from './lib.js';
var tag_cookie_userId = "userId";

var formURL = "action.php";
var POST = "POST"
var GET = "GET"


// refresh page, called after update news, make a new search and create a new News.
function refreshPage()
{
    location.reload();
}

var monthNames = [
    "January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"
];
var dayOfWeekNames = [
    "Sunday", "Monday", "Tuesday",
    "Wednesday", "Thursday", "Friday", "Saturday"
];
function formatDate(date, patternStr){
    if (!patternStr) {
        patternStr = 'dd/mm/yyyy';
    }
    var day = date.getDate(),
        month = date.getMonth(),
        year = date.getFullYear(),
        hour = date.getHours(),
        minute = date.getMinutes(),
        second = date.getSeconds(),
        miliseconds = date.getMilliseconds(),
        h = hour % 12,
        hh = twoDigitPad(h),
        HH = twoDigitPad(hour),
        mm = twoDigitPad(minute),
        ss = twoDigitPad(second),
        aaa = hour < 12 ? 'AM' : 'PM',
        EEEE = dayOfWeekNames[date.getDay()],
        EEE = EEEE.substr(0, 3),
        dd = twoDigitPad(day),
        M = month + 1,
        MM = twoDigitPad(M),
        MMMM = monthNames[month],
        MMM = MMMM.substr(0, 3),
        yyyy = year + "",
        yy = yyyy.substr(2, 2)
    ;
    return patternStr
        .replace('hh', hh).replace('h', h)
        .replace('HH', HH).replace('H', hour)
        .replace('mm', mm).replace('m', minute)
        .replace('ss', ss).replace('s', second)
        .replace('S', miliseconds)
        .replace('dd', dd).replace('d', day)
        .replace('MMMM', MMMM).replace('MMM', MMM).replace('MM', MM).replace('M', M)
        .replace('EEEE', EEEE).replace('EEE', EEE)
        .replace('yyyy', yyyy)
        .replace('yy', yy)
        .replace('aaa', aaa)
        ;
}
function twoDigitPad(num) {
    return num < 10 ? "0" + num : num;
}


/* must apply only after HTML has loaded */
$(document).ready(function () {

    //  make a news search
    $("#id_search_team").on('click', function() {
        refreshPage();
    });

    //  delete a news
    $(".delete_match").on('click', function() {
        var t =  $(this).attr("id").split("_");
        var id =  t[t.length - 1];
        if (!confirm("Are you sure you want to delete this Math!\n\n")) {
            return;
        }
        var data = {"matchId" : Number(id),
            "action" : "httpDeleteMatch"};
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
    $(".edit_match").on('click', function() {
        var t =  $(this).attr("id").split("_");



        var matchId =  t[t.length - 1];
        var matchTime = $("#input_match_time_" + matchId).val();
        var matchLocation = $("#input_match_location_" + matchId).val();
        var matchTeamA = $("#input_match_teamA_" + matchId).val();
        var matchTeamB = $("#input_match_teamB_" + matchId).val();
        var matchStatus = $("#input_match_status_" + matchId).val();
        var matchScoreA = $("#input_match_scoreA_" + matchId).val();
        var matchScoreB = $("#input_match_scoreB_" + matchId).val();
        var matchTeamAName = $("#input_match_teamA_name_" + matchId).val();
        var matchTeamBName = $("#input_match_teamB_name_" + matchId).val();


        matchTime = matchTime.slice(0,matchTime.length - 3 );
        var t = matchTime.split(" ");
        matchTime = t[0] + "T" + t[1];
        var teamA = "(" + matchTeamA + "). " + matchTeamAName;
        var teamB = "(" + matchTeamB + "). " + matchTeamBName;

        $("#modal_match_id").val(matchId);
        $("#modal_time").val(matchTime);
        $("#modal_location").val(matchLocation);
        $('#sel_teamA').val(teamA).change();
        $('#sel_teamB').val(teamB).change();
        $("#modal_scoreA").val(matchScoreA);
        $("#modal_scoreB").val(matchScoreB);
        $("#modal_status").val(matchStatus);
        $("#modal_update").show();
        $("#modal_create").hide();
        $("#editMemberModal").modal('show');
    });

    // Update team
    $("#modal_update").on('click', function() {
        var matchId = $("#modal_match_id").val();
        var matchtime = $("#modal_time").val();
        matchtime = matchtime.replace("T"," ") + ":00";
        var matchLocation = $("#modal_location").val();
        var matchTeamA = $("#sel_teamA :selected").val().split("(")[1].split(")")[0];
        var matchTeamB = $("#sel_teamB :selected").val().split("(")[1].split(")")[0];
        var matchStatus =  $("#modal_status").val();
        var matchScoreA =  $("#modal_scoreA").val();
        var matchScoreB =  $("#modal_scoreB").val();

        var  data = {
            "matchId" : Number(matchId),
            "matchTime" : matchtime,
            "matchLocation" : matchLocation,
            "matchTeamA" : matchTeamA,
            "matchTeamB" : matchTeamB,
            "matchStatus" : matchStatus,
            "matchScoreA" : matchScoreA,
            "matchScoreB" : matchScoreB,

            "action" : "httpUpdateMatch"};


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


    $("#createNewMatch").on('click', function() {
        $("#edit_dialog_title").html("Create a new Match");

        $("#modal_time").val(formatDate(new Date(), 'yyyy-MM-ddTHH:mm'));
        $("#modal_scoreA").val(0);
        $("#modal_scoreB").val(0);
        $("#modal_status").val("In coming");

        $("#modal_update").hide();
        $("#modal_create").show();
        $("#editMemberModal").modal('show');
    });


    // Update news
    $("#modal_create").on('click', function() {
        var matchId = 0;
        var matchtime = $("#modal_time").val();
        matchtime = matchtime.replace("T"," ") + ":00";
        var matchLocation = $("#modal_location").val();
        var matchTeamA = $("#sel_teamA :selected").val().split("(")[1].split(")")[0];
        var matchTeamB = $("#sel_teamB :selected").val().split("(")[1].split(")")[0];
        var matchStatus =  $("#modal_status").val();
        var matchScoreA =  $("#modal_scoreA").val();
        var matchScoreB =  $("#modal_scoreB").val();

        var  data = {
                "matchId" : matchId,
                "matchTime" : matchtime,
                "matchLocation" : matchLocation,
                "matchTeamA" : matchTeamA,
                "matchTeamB" : matchTeamB,
                "matchStatus" : matchStatus,
                "matchScoreA" : matchScoreA,
                "matchScoreB" : matchScoreB,

                "action" : "httpCreateMatch"};


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