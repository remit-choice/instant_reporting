// $(document).ready(function () {
//     $('.nav a').click(function (e) {

//         $('.nav a').removeClass('active');

//         var $this = $(this);
//         if (!$this.hasClass('active')) {
//             $this.addClass('active');
//         }
//         //e.preventDefault();
//     });
// });
$(function () {
    var current_page_URL = location.href;
    $("a").each(function () {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                return false;
            }
        }
    });
});
var header = document.getElementById("myDIV");
var btns = header.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
        var current = document.getElementsByClassName("active");
        current[0].className = current[0].className.replace(" active", "");
        this.className += " active";
    });
}