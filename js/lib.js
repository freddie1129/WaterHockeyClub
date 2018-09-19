// This file includes functions for operating cookies, ajax interface, etc

// Get a cookie
export function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// Set a cookie
export function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// Delete a cookie
export function deleteCookie(cname) {
    var d = new Date();
    d.setTime(d.getTime() + 100);
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + "" + ";" + expires + ";path=/";
}


// validate the userToken in the cookie
//var data = {"userToken" : userToken,
//    "action" : "httpLoginByToken"};
//var postData = JSON.stringify(data);
//var postData = $.param(data);

export function http(url,method,data,callbackSuccess, callbackFailed)
{
    $.ajax({
        url: url,
        type: method,
        data: data,
        dataType: "json",
        success: callbackSuccess,
        error: callbackFailed
    });
}





