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
var create_password = false;
function Create_Password() {
    if (create_password) {
        document.getElementById("create_password").setAttribute("type", "password");
        document.getElementById("create_Show").style.display = "none";
        document.getElementById("create_Hide").style.display = "block";
        create_password = false;
    }
    else {
        document.getElementById("create_password").setAttribute("type", "text");
        document.getElementById("create_Show").style.display = "block";
        document.getElementById("create_Hide").style.display = "none";
        create_password = true;
    }
}
var create_retype_password = false;
function Create_Retype_Password() {
    if (create_retype_password) {
        document.getElementById("create_retype_password").setAttribute("type", "password");
        document.getElementById("create_retype_Show").style.display = "none";
        document.getElementById("create_retype_Hide").style.display = "block";
        create_retype_password = false;
    }
    else {
        document.getElementById("create_retype_password").setAttribute("type", "text");
        document.getElementById("create_retype_Show").style.display = "block";
        document.getElementById("create_retype_Hide").style.display = "none";
        create_retype_password = true;
    }
}
var password = false;
function Password() {
    if (password) {
        document.getElementById("password").setAttribute("type", "password");
        document.getElementById("Show").style.display = "none";
        document.getElementById("Hide").style.display = "block";
        password = false;
    }
    else {
        document.getElementById("password").setAttribute("type", "text");
        document.getElementById("Show").style.display = "block";
        document.getElementById("Hide").style.display = "none";
        password = true;
    }
}
var retype_password = false;
function retype_Password() {
    if (retype_password) {
        document.getElementById("retype_password").setAttribute("type", "password");
        document.getElementById("retype_Show").style.display = "none";
        document.getElementById("retype_Hide").style.display = "block";
        retype_password = false;
    }
    else {
        document.getElementById("retype_password").setAttribute("type", "text");
        document.getElementById("retype_Show").style.display = "block";
        document.getElementById("retype_Hide").style.display = "none";
        retype_password = true;
    }
}