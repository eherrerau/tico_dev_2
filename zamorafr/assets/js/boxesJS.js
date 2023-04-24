/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author ehu@hp.com
 * Date: 23-02-2013
 * @param {event} evt Receive the click event.
 * @param {int} user Receive the id of the user
  * This Functions display the description box when you click on any of the circle representing 
 * the cases on engineer boxes 
 * 
 **/
function caseDescription(evt, user){//this function shows the available people    
    xPos = evt.clientX;
    yPos = evt.clientY;	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        AjaxCaseDetail=new XMLHttpRequest();
    }else{// code for IE6, IE5
        AjaxCaseDetail=new ActiveXObject("Microsoft.XMLHTTP");
    }	  
    AjaxCaseDetail.open("POST","../include/caseDetails.php?engineer="+user,true);    
    AjaxCaseDetail.send();
    AjaxCaseDetail.onreadystatechange=function(){
        if (AjaxCaseDetail.readyState===4 && AjaxCaseDetail.status===200){                     
            var prevWin = document.getElementById("detail");
            prevWin.innerHTML=AjaxCaseDetail.responseText;
            prevWin.style.top = parseInt(yPos)+4 + "px";
            prevWin.style.left = parseInt(xPos)+4 + "px";
            prevWin.style.visibility = "visible";
            prevWin.onmouseout = function(){
                document.getElementById("detail").style.visibility ="hidden";
            };
        }		
    };
}

/**
 * Created by ehu@hp.com
 * Created* Date: 06-03-2013
 * Updated by:
 * Updated date:
 * This function displays and hide the Leyend for the icons, with a click.
*/
$('#leyendDescriptionLink').click(function() {
  $('#leyendDescription').toggle('slow', function() {
    // Animation complete.
  });
});

function getUser(){//this function shows the available people
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xmlhttp.open("POST","include/getAvailable.php",true);
    xmlhttp.send();
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState===4 && xmlhttp.status===200){
            document.getElementById("box1").innerHTML=xmlhttp.responseText;
        }
    };
}

/**
 * Created by MArcos Ramirez
 * Created* Date: 06-03-2012
 * Updated by:
 * Updated date:
 * Description: Displays the date picker for the date fields on the reports sections of the index. 
*/
$( "#datepicker" ).datepicker();
$( "#datepicker2" ).datepicker();
$( "#datepicker3" ).datepicker();
$( "#datepicker4" ).datepicker();
/*
 * 
 * @deprecated: Function getlunch should be no longer use.
 * Date: 07-03-2013
 * 
function getlunch(){//this function shows the people in lunch
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp2=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xmlhttp2.open("POST","include/getlunch.php",true);
    xmlhttp2.send();
    xmlhttp2.onreadystatechange=function(){
        if (xmlhttp2.readyState===4 && xmlhttp2.status===200){
            document.getElementById("box2").innerHTML=xmlhttp2.responseText;	
        }
    };
}	

/*
 * 
 * @deprecated: Function getexpert should be no longer use.
 * Date: 07-03-2013
 * 
function getexpert(){//this function shows the people with expert role or technical leaders
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpE=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttpE=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttpE.open("POST","include/getexpert.php",true);
    xmlhttpE.send();
    xmlhttpE.onreadystatechange=function(){
        if (xmlhttpE.readyState===4 && xmlhttpE.status===200){		
            document.getElementById("box3").innerHTML=xmlhttpE.responseText;	
        }
    };
}	

/*
 * 
 * @deprecated: Function getnoavail should be no longer use.
 * Date: 07-03-2013
 
function getnoavail(){//this function shows the people with status of not available, on vacations, or any other exception.	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp4=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp4.open("POST","include/getnoavail.php",true);
    xmlhttp4.send();
    xmlhttp4.onreadystatechange=function(){
        if (xmlhttp4.readyState===4 && xmlhttp4.status===200){
            document.getElementById("box4").innerHTML=xmlhttp4.responseText;        
        }
    };
}*/

		
