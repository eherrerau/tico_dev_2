function caseDescription(evt, user){//this function shows the available people
    xPos = evt.clientX;
    yPos = evt.clientY;	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        AjaxCaseDetail=new XMLHttpRequest();
    }else{// code for IE6, IE5
        AjaxCaseDetail=new ActiveXObject("Microsoft.XMLHTTP");
    }	  
    AjaxCaseDetail.open("POST","include/caseDetails.php?engineer="+user,true);
    AjaxCaseDetail.send();
    AjaxCaseDetail.onreadystatechange=function(){
        if (AjaxCaseDetail.readyState==4 && AjaxCaseDetail.status==200){            
            var prevWin = document.getElementById("detail");
            prevWin.innerHTML=AjaxCaseDetail.responseText;
            prevWin.style.top = parseInt(yPos)+4 + "px";
            prevWin.style.left = parseInt(xPos)+4 + "px";
            prevWin.style.visibility = "visible";
            prevWin.onmouseout = function(){
                document.getElementById("detail").style.visibility ="hidden";
            }
        }		
    }
}
