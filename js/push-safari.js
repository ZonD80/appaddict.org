$(document).ready(function() {


    var checkRemotePermission = function(permissionData) {
        
        if (permissionData.permission === 'default') {
            // This is a new web service URL and its validity is unknown.
            window.safari.pushNotification.requestPermission(
                    'https://www.appaddict.org/push-safari/?', // The web service URL.
                    'web.appaddict.org', // The Website Push ID.
                    {}, // Data that you choose to send to your server to help you identify the user.
                    checkRemotePermission         // The callback function.
                    );
        }
        else if (permissionData.permission === 'denied') {
            //$.get('/push-safari/',{'action':'remove'});
        }
        else if (permissionData.permission === 'granted') {
            $.get('/push-safari/',{'action':'update','token':permissionData.deviceToken});
        }
    };
    // Ensure that the user can receive Safari Push Notifications.
    if ('safari' in window && 'pushNotification' in window.safari) {
        var permissionData = window.safari.pushNotification.permission('web.appaddict.org');
        checkRemotePermission(permissionData);
    }
});