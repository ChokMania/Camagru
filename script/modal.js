var openModalIds = [];

function modal_onclick(id) {
    if (Number(id) < 10)
        var elem = document.getElementById("modal0" + id);
    else
        var elem = document.getElementById("modal" + id);
    elem.style.display = "block";
    openModalIds = openModalIds.concat(Number(id) < 10 ? '0' + id : id);
}

function hide_modal(id) {
    if (Number(id) < 10)
        var elem = document.getElementById("modal0" + id);
    else
        var elem = document.getElementById("modal" + id);
    elem.style.display = "none";
}

var modal01 = document.getElementById("modal01");
var modal02 = document.getElementById("modal02");
var modal03 = document.getElementById("modal03");

window.onclick = function(event) {
    openModalIds.forEach((item, index) => {
        elem = document.getElementById("modal" + item);
        if (event.target.id == "modal" + item) {
            elem.style.display = "none";
            openModalIds.splice(index, 1);
        }
    })
}