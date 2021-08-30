function windowLocation(url){
    window.location = url;
}

function blockMessage(element, message, color) {
    $(element).block({
        message:
            '<span class="text-semibold"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin position-left"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg></i>&nbsp; ' +
            message +
            "</span>",
        overlayCSS: {
            backgroundColor: color,
            opacity: 0.8,
            cursor: "wait",
        },
        css: {
            border: 0,
            padding: "10px 15px",
            color: "#fff",
            width: "auto",
            "-webkit-border-radius": 2,
            "-moz-border-radius": 2,
            backgroundColor: "#0e1726",
        },
    });
}

// function notifError($message) {
//     $.jGrowl($message, {
//         header: "Oh! Snap",
//         position: "top-center",
//         theme: "alert-styled-left bg-danger",
//     });
// }

// function notifSuccess($message) {
//     $.jGrowl($message, {
//         header: "Success!",
//         position: "top-center",
//         theme: "alert-styled-left bg-success",
//     });
// }