/*slideshow script part
 **
 **
 **
 */


var slideindex = 0; //witch picture to show off
var slideduration = 10000; //time in milisec before autochange slide
var directselect; //in case of direct select (with the bullet) change slideindex to the corresponding one
var autoslide = 1; //permit to stop the autoslide when set to zero, and allow it again when set to 1
var firstin = 0; //avoiding multiple recursive autoslide in case of multiple clicking
var changefirstin = 0; //same as firstin, use to set firstin to 0 after the settimeout



//image sources for the slideshow
var imgArray = [
    './ressources\\img\\menu-des-roulants-preview.jpg',
    './ressources\\img\\image trop grosse.jpeg',
    'http://qnimate.com/wp-content/uploads/2014/03/images2.jpg'
];


//updating the slide number HTML display to current slide
document.getElementById("current-slide-number").innerHTML = "image " + (slideindex + 1) + " / " +
    imgArray.length;

var element = document.getElementById('images-slideshow'); //element get the id of the img
element.src = imgArray[0];

function slideShow(way, onclick, directselect, changefirstin) {

    if (changefirstin == 1)
        firstin = 0;
    if (onclick == 1 || autoslide == 1) {
        console.log("%cInside SlideShow", "text-decoration: underline; color: green;");
        if (onclick == 1) {
            console.log("Change Onclick");
        }
        slideindex += way; //way is -1 with the left arrow and +1 for the right arrow. 0 otherwise



        if (directselect > 0) {
            slideindex = directselect - 1;
        } //set the corresponding slideindex

        if (slideindex == imgArray.length) {
            slideindex = 0;
        } else if (slideindex < 0) {
            slideindex = imgArray.length - 1;
        } //When slideindex is bigger than or smaller than the imgsources array

        console.log("slideindex = " + slideindex);

        document.getElementById("current-slide-number").innerHTML = "image " + (slideindex + 1) + " / " +
            imgArray.length; //updating the slide number HTML display to current slide
        element.className += "fadeOut"; //fadding out current image
        setTimeout(function() {
            element.src = imgArray[slideindex];
            setTimeout(function() {
                element.className = ""; //fadding in new image
            }, 300)
        }, 1000);
        if (onclick == 0 && autoslide == 1 && firstin == 0) {
            console.log("Change auto");
            setTimeout(slideShow, slideduration, 1, 0, 0, 0); //autoslide recursiv
        } else if (onclick == 1 && firstin == 0) {
            firstin = 1; //avoiding multiple recursive
            autoslide = 0; //stopping autoslide
            setTimeout(function() {
                console.log("Relaunching Auto slide");
                autoslide = 1; //relaunching auto slide
                slideShow(1, 0, 0, 1) //relaunching auto slide
            }, slideduration + 10000)
        }
    }
}
setTimeout(slideShow, slideduration, 1, 0, 0, 0);


/*End of slideshow
 **
 **
 */