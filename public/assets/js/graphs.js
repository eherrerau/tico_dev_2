function graphic(url){//this function shows the people with expert role or technical leaders
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp3=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xmlhttp3.open("POST",url,true);
    xmlhttp3.send();
    xmlhttp3.onreadystatechange=function(){
        if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
            document.getElementById("grafico").innerHTML=xmlhttp3.responseText;        
        }
    }
}	
function history(url,caseNum){//this function shows the people with expert role or technical leaders	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp3=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp3.open("POST",url+"?caseNumber="+caseNum,true);
    xmlhttp3.send();
    xmlhttp3.onreadystatechange=function(){
        if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
            document.getElementById("grafico").innerHTML=xmlhttp3.responseText;        
        }
    }
}	
function todaycases(url){	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp3=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xmlhttp3.open("POST",url,true);
    xmlhttp3.send();
    xmlhttp3.onreadystatechange=function(){
        if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
            document.getElementById("grafico").innerHTML=xmlhttp3.responseText;        
        }
    }
}
function getenglst3(){//this function creates a dropdown menu with all the available engineers for the add form.
    if (document.getElementById("reportType").value=="7"){	
        document.getElementById("addForm44").style.display="block";
    }else{
        document.getElementById("addForm44").style.display="none";
    }
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp0=new XMLHttpRequest();	 
    }else{// code for IE6, IE5
        xmlhttp0=new ActiveXObject("Microsoft.XMLHTTP");	 
    }
    xmlhttp0.open("POST","include/getFullEngineerList.php",true);
    xmlhttp0.send();
    xmlhttp0.onreadystatechange=function(){
        if (xmlhttp0.readyState==4 && xmlhttp0.status==200){
            document.getElementById("addForm44").innerHTML=xmlhttp0.responseText;        
        }
    } 
}