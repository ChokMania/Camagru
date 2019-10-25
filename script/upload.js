function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

function onclickfilter(data) {
    let elem_preview_img = document.getElementById("img-preview-in-camera");
    let filter = document.getElementById(data);
    elem_preview_img.style.display = "block";
    elem_preview_img.src = filter.src;
    console.log(filter.src);
    src = filter.src;
}

function drop(ev) {
    ev.preventDefault();
    let elem_preview_img = document.getElementById("img-preview-in-camera");
    elem_preview_img.style.display = "block";
    var data = ev.dataTransfer.getData("text");
    console.log("filter " + data + " dropped");
    src = document.getElementById(data).src;
    elem_preview_img.src = src;
    console.log(src);
}


function onclickfilter_upload(data) {
    let elem_preview_img = document.getElementById("img-preview-in-upload");
    let filter = document.getElementById(data);
    elem_preview_img.style.display = "block";
    elem_preview_img.src = filter.src;
    console.log(filter.src);
    src = filter.src;
}

function drop_upload(ev) {
    ev.preventDefault();
    let elem_preview_img = document.getElementById("img-preview-in-upload");
    elem_preview_img.style.display = "block";
    var data = ev.dataTransfer.getData("text");
    console.log("filter " + data + " dropped");
    src = document.getElementById(data).src;
    elem_preview_img.src = src;
    console.log(src);
}

var video;

function startWebcam() {
    // [1] GET ALL THE HTML ELEMENTS
    video = document.getElementById("vid-show"),
        canvas = document.getElementById("vid-canvas"),
        take = document.getElementById("vid-take");
    // [2] ASK FOR USER PERMISSION TO ACCESS CAMERA
    // WILL FAIL IF NO CAMERA IS ATTACHED TO COMPUTER
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            // [3] SHOW VIDEO STREAM ON VIDEO TAG
            video.srcObject = stream;
            video.play();
            //allow button take picture
            document.getElementById('vid-take').disabled = false;

            // [4] WHEN WE CLICK ON "TAKE PHOTO" BUTTON
            take.addEventListener("click", function() {
                // Create snapshot from video
                var draw = document.createElement("canvas");
                draw.width = video.videoWidth;
                draw.height = video.videoHeight;
                var context2D = draw.getContext("2d");
                context2D.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
                // Upload to server
                draw.toBlob(function(blob) {
                    var data = new FormData();
                    data.append('upimage', blob);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', "upload.php", true);
                    xhr.send(data);
                    xhr.onload = function() {
                        if (xhr.status == 403 || xhr.status == 404) {
                            alert("ERROR LOADING UPLOAD.PHP");
                        } else {
                            hello = this.response;
                            var xhrq = new XMLHttpRequest();
                            if (typeof(src) === 'undefined') {
                                src = "none";
                                xhrq.open('GET', "./filtre.php?img=" + hello + "&filtre=" + src, true);
                            } else {
                                var words = src.split('/');
                                console.log("./filtre.php?img=" + hello + "&filtre=./ressources/filters/" + words[5]);
                                xhrq.open('GET', "./filtre.php?img=" + hello + "&filtre=./ressources/filters/" + words[5], true);
                            }
                            xhrq.send(null);
                            xhrq.onload = function() {
                                if (xhr.status == 403 || xhr.status == 404) {
                                    alert("ERROR LOADING UPLOAD.PHP");
                                } else {
                                    let elem = document.getElementById("img-screenshot");
                                    elem.src = hello;
                                    console.log(hello);
                                    console.log(this.response);
                                }
                            }
                        }
                    };
                });
            });
        })
        .catch(function(err) {
            document.getElementById("vid-controls").innerHTML = "Please enable access and attach a camera";
        });
}

function stopWebcam() {
    video.pause();
    video.currentTime = 0;
    document.getElementById('vid-take').disabled = true;
}