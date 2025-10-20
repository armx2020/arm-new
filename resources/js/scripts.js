$(document).ready(function () {
    $("#locationButton1").click(function () {
        $("#selectCity").toggle();
    });
    $("#locationButton2").click(function () {
        $("#selectCity").toggle();
    });
    $("#openMenu").click(function () {
        $("#menu").toggle();
        document.body.style.position = 'fixed';
    });
    $("#closeMenu").click(function () {
        $("#menu").toggle();
        document.body.style.position = '';
    });
    $("#CategoryButton").click(function () {
        $("#selectCategory").toggle();
    });

    $("#dropdown_button").click(function() {
        $("#dropdown_ul").toggle();
        document.body.style.position = '';
    });
})
