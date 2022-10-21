var Passing = false;
function ForPasswer() {
    if (Passing) {
        document.getElementById("password").setAttribute("type", "password");
        document.getElementById("Show").style.display = "none";
        document.getElementById("Hide").style.display = "block";
        Passing = false;
    }
    else {
        document.getElementById("password").setAttribute("type", "text");
        document.getElementById("Show").style.display = "block";
        document.getElementById("Hide").style.display = "none";
        Passing = true;
    }
}
