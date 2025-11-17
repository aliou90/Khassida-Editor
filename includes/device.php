<?php
function isMobileDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $mobileDevices = array(
        'Android', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone'
    );
    foreach ($mobileDevices as $device) {
        if (stripos($userAgent, $device) !== false) {
            return true;
        }
    }
    return false;
}

if (isMobileDevice()) {
    //echo "Cet utilisateur utilise un appareil mobile.";
} else {
    //echo "Cet utilisateur utilise un ordinateur de bureau ou portable.";
}

?>
