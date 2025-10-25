//profile
$("document").ready(function() {
	$("li a:contains(My Profile)").addClass("current");		
});	

$( "#datepickerFrom" ).datepicker();
$("#datepickerFrom").datepicker('option', {dateFormat: 'yy-mm-dd'});		
$( "#datepickerTo" ).datepicker();
$("#datepickerTo").datepicker('option', {dateFormat: 'yy-mm-dd'});


function MM_openBrWindow(theURL,winName,features) { //v2.0
    window.open(theURL,winName,features);
}
function onLoadProfile(){
    qMonitor();
}
function showClock() {
    x=setTimeout("showClock()",1000);
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ajaxClock=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        ajaxClock=new ActiveXObject("Microsoft.XMLHTTP");
    }
    ajaxClock.open("GET","include/clock.php",true);
    ajaxClock.send();
    ajaxClock.onreadystatechange=function(){
        if (ajaxClock.readyState==4 && ajaxClock.status==200){
            document.getElementById("TimeClock").innerHTML=ajaxClock.responseText;
        }
    }
}
function getSchedule(url, schId){//this function shows the people with expert role or technical leaders
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp3=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xmlhttp3.open("POST",url+"?schtype="+schId,true);
    xmlhttp3.send();
    xmlhttp3.onreadystatechange=function(){
        if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
            document.getElementById("scheduleDays").innerHTML=xmlhttp3.responseText;        
        }
    }
}	
function updateMyProfile(){//update info on my profile	
    if (document.MyProfileForm.Name.value ==""){
        alert ("The name to display cannot be empty")				
    }
    if (document.MyProfileForm.Mail.value ==""){
        alert ("The email field cannot be empty")				
    }
    if (document.MyProfileForm.Phone.value ==""){
        alert ("The phone ext field cannot be empty")				
    }
    if (document.MyProfileForm.Bday.value ==""){
        alert ("The name to display cannot be empty")				
    }	
    else{		
        var Formulario = document.forms['MyProfileForm'];
        var longitudFormulario = Formulario.elements.length;
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i=0; i <= Formulario.elements.length-1;i++) {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
            sepCampos="&";
        }
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxUpMyProfile=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            ajaxUpMyProfile=new ActiveXObject("Microsoft.XMLHTTP");
        }	
        ajaxUpMyProfile.open("POST","include/updateMyProfile.php",true);
        ajaxUpMyProfile.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');			
        ajaxUpMyProfile.onreadystatechange=function(){
            if (ajaxdelCase.readyState==4 && ajaxdelCase.status==200){
                document.getElementById("message").innerHTML=ajaxUpMyProfile.responseText;
            }
        }
        ajaxUpMyProfile.send(cadenaFormulario);
    }
}