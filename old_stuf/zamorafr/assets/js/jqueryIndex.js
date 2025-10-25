//index page
$("document").ready(function() {
//    $("li a:contains(Dashboard)").css("background-color","#D7410B");
    $("li a:contains(Dashboard)").addClass("current");
});	


/* @author: ehu@hp.com
 * @createDate: 07-03-2013
 * @Description: Loads the automatic functions to refresh the boxes automatically.
 */

$(document).ready(function() {
    getUser();        
    setInterval(getenglst, 900000);
    setInterval(getenglst2, 900000);
    setInterval(qMonitor, 3000000);
    getenglst();
    getenglst2();
});
$('#CRUDCases').click(function()
{
     return ($(formSubmitAdd).attr('disabled')) ? false : true;
});
/*
 * Function showUser Deprecated, replace with jquery function ready.

function showUser() {//this function is loaded in the index onload() to fill out the information
    getUser();
    getlunch();
    getnoavail();
    qMonitor();
    setInterval(getUser, 150000);
    setInterval(getlunch, 900000);
    setInterval(getnoavail, 1200000);
    setInterval(getenglst, 900000);
    setInterval(getenglst2, 900000);
    setInterval(qMonitor, 3000000);
    getenglst();
    getenglst2();
} */