function gdsrShowHidePreview(gdid) {
    var preview = document.getElementById(gdid+'-on');
    var message = document.getElementById(gdid+'-off');
    var hidden = document.getElementById(gdid);

    if (preview.style.display == "block") {
        preview.style.display = "none";
        message.style.display = "block";
        hidden.value = "0";
    }
    else {
        preview.style.display = "block";
        message.style.display = "none";
        hidden.value = "1";
    }
}

