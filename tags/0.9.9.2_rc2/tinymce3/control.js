function gdsrChangeTrend(trend, el, index) {
    document.getElementById("gdsr-"+trend+"-txt["+index+"]").style.display = el == "txt" ? "block" : "none";
    document.getElementById("gdsr-"+trend+"-img["+index+"]").style.display = el == "img" ? "block" : "none";
}

function gdsrChangeStars(el, stars, index) {
    document.getElementById("gdsr-stars-"+stars+"["+index+"]").style.display = el == "stars" ? "block" : "none";
}

function gdsrChangeStyles(el, index) {
    document.getElementById("gdsr-styles-built["+index+"]").style.display = el == "built" ? "block" : "none";
    document.getElementById("gdsr-styles-external["+index+"]").style.display = el == "built" ? "none" : "block";
}

function gdsrChangeDate(el, index) {
    document.getElementById("gdsr-pd-lastd["+index+"]").style.display = el == "lastd" ? "block" : "none";
    document.getElementById("gdsr-pd-month["+index+"]").style.display = el == "month" ? "block" : "none";
    document.getElementById("gdsr-pd-range["+index+"]").style.display = el == "range" ? "block" : "none";
}
