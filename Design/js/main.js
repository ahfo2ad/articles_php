
$(function () {

    // show and hide focus function

    $("[placeholder]").focus(function () {

        $(this).attr("data-text", $(this).attr("placeholder"));

        $(this).attr("placeholder", "");
    }).blur(function () {

        $(this).attr("placeholder", $(this).attr("data-text"));
    });

    // required attribure function

    $("input").each(function () {

        if ($(this).attr("required") == "required") {

            $(this).after('<span class="astix">*</span>');
        }
    });


    // connfirmation message function

    $(".confirm").click(function () {

        return confirm("Are You Sure?");
    });


    // convert date format 
    $("#datepicker").datepicker({
        dateFormat: 'yy/mm/dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });


});