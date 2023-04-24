//
// Depracated.
// Eduardo Herrera
// 07-03-2013
//
//function hideshow(){ 
//    var frm=document.addForm; 
//    if(frm.style.display=="block"){
//        frm.style.display="none"
//        } 
//    else if(frm.style.display=="none"){
//        frm.style.display="block"
//        } 
//}

function qMonitor(){//this function shows the available Qmonitor
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        AjaxQM=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        AjaxQM=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    AjaxQM.open("POST","include/getQmon.php",true);
    AjaxQM.send();
    AjaxQM.onreadystatechange=function() {
        if (AjaxQM.readyState===4 && AjaxQM.status===200){
            document.getElementById("QMName").innerHTML=AjaxQM.responseText;
        }
    };
};

function getenglst(){//this function creates a dropdown menu with all the available engineers for the add form.
    var objeto =document.getElementById("premier");
    if (objeto){
        objeto= document.getElementById("premier").value;		
    }	
    else{
        objeto=1;
    }
    var producto= document.getElementById("productID");
    if (producto){
        producto= document.getElementById("productID").value;		
    }
    else{
        producto=1;
    }
    if (objeto != "null"){
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp5=new XMLHttpRequest();
            ajaxPremier=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            xmlhttp5=new ActiveXObject("Microsoft.XMLHTTP");
            ajaxPremier=new ActiveXObject("Microsoft.XMLHTTP");
        }
        if( objeto =="No"){            
            xmlhttp5.open("GET","include/getEngineerList.php?producto="+producto,true);
            xmlhttp5.send();
            xmlhttp5.onreadystatechange=function(){
                if (xmlhttp5.readyState==4 && xmlhttp5.status===200){			
                    document.getElementById("engineerADD_dpb").innerHTML=xmlhttp5.responseText;
                }
            };
        }
        else{	            
            ajaxPremier.open("POST","include/getPremierList.php?producto="+producto,true);
            ajaxPremier.send();
            ajaxPremier.onreadystatechange=function(){
                if (ajaxPremier.readyState==4 && ajaxPremier.status==200){			
                    document.getElementById("engineerADD_dpb").innerHTML=ajaxPremier.responseText;	                
                }
            }
        }
    }
}
function getenglst2(){//this function creates a dropdown menu with all the available engineers for the add form.
    var objeto =document.getElementById("premier")
    var producto= document.getElementById("productID");
    if (producto){
        producto= document.getElementById("productID").value;		
    }
    else{
        producto=1;
    }
    if (objeto!="null"){	
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp0=new XMLHttpRequest();
            ajaxPremier2=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            xmlhttp0=new ActiveXObject("Microsoft.XMLHTTP");
            ajaxPremier2=new ActiveXObject("Microsoft.XMLHTTP");
        }
        if( document.getElementById("premier").value =="No"){	            
            xmlhttp0.open("POST","include/getEngineerList.php?producto="+producto,true);
            xmlhttp0.send();
            xmlhttp0.onreadystatechange=function(){
                if (xmlhttp0.readyState==4 && xmlhttp0.status==200){			
                    document.getElementById("engineerADD_dpb").innerHTML=xmlhttp0.responseText;	
                }
            }
        }
        else{	
            ajaxPremier2.open("POST","include/getPremierList.php?producto="+producto,true);
            ajaxPremier2.send();
            ajaxPremier2.onreadystatechange=function(){
                if (ajaxPremier2.readyState==4 && ajaxPremier2.status==200){			
                    document.getElementById("engineerADD_dpb").innerHTML=ajaxPremier2.responseText;	
                }
            }
        }    
    }
}
function getproducts(){//this function shows the people with status of not available, on vacations, or any other exception.	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ajaxProducts=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        ajaxProducts=new ActiveXObject("Microsoft.XMLHTTP");
    }
    ajaxProducts.open("POST","include/getProducts.php",true);
    ajaxProducts.send();
    ajaxProducts.onreadystatechange=function(){
        if (ajaxProducts.readyState==4 && ajaxProducts.status==200){
            document.getElementById("product").innerHTML=ajaxProducts.responseText;        
        }
    }
    getenglst();
}

function graphicAv(url){//
    var Formulario = document.forms['misForm'];
    startdate = Formulario.datepicker3.value	
    enddate = Formulario.datepicker4.value 
    reportType = Formulario.reportType.value
    if (document.getElementById("reportType").value=="7"){
        engineer = Formulario.engineerlst.value
    }
    else{
        engineer=0
        }	
    if (startdate > enddate){
        alert ("Please check the dates");
        return false;
    }
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xfecha=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        xfecha=new ActiveXObject("Microsoft.XMLHTTP");
    }  
    xfecha.open("POST",url+"?startdate="+startdate+"&enddate="+enddate+"&reportType="+reportType+"&engineer="+engineer,true);
    xfecha.send();
    xfecha.onreadystatechange=function(){
        if (xfecha.readyState==4 && xfecha.status==200){
            document.getElementById("grafico").innerHTML=xfecha.responseText;        
        }
    }
}
function newCaseSubmit(){//this function submits the new case
    if (document.addForm.casetxt.value < 999999999){
        alert ("The case number must have at least 10 digits")
    }
    else{	
        var Formulario = document.forms['addForm'];
        var longitudFormulario = Formulario.elements.length;
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i=0; i <= Formulario.elements.length-1;i++) {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
            sepCampos="&";
        }
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxAddCase=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            ajaxAddCase=new ActiveXObject("Microsoft.XMLHTTP");
        }	
        ajaxAddCase.open("POST","include/insertCase.php",true);
        ajaxAddCase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        			
        ajaxAddCase.onreadystatechange=function(){
            if (ajaxAddCase.readyState==4 && ajaxAddCase.status==200){
                document.getElementById("messages").innerHTML=ajaxAddCase.responseText;			
            }
        }        
        ajaxAddCase.send(cadenaFormulario);
        getUser();
        getlunch();
        getnoavail();
        addcase();
        getenglst();
        getenglst2();
    }
}
function addcase(){ //this function cleans and display the add case formulary
    document.addForm.reset();
    document.addForm.casetxt.focus();	
    $('#formulario').toggle();
    getUser();
    getproducts();
    getlunch();
    getnoavail();
}
function deleteCase(){//this function deletes the case from the DB	
      if (document.addForm.casetxt.value < 999999999){
        alert ("The case number must have at least 10 digits")		
    }
    else{		
        var Formulario = document.forms['addForm'];
        var longitudFormulario = Formulario.elements.length;
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i=0; i <= Formulario.elements.length-1;i++) {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
            sepCampos="&";
        }
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxdelCase=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            ajaxdelCase=new ActiveXObject("Microsoft.XMLHTTP");
        }	
        ajaxdelCase.open("POST","include/deleteCase.php",true);
        ajaxdelCase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajaxdelCase.onreadystatechange=function(){
            if (ajaxdelCase.readyState==4 && ajaxdelCase.status==200){                
                document.getElementById("messages").innerHTML=ajaxdelCase.responseText;
            }
        }        
        ajaxdelCase.send(cadenaFormulario);
        getUser();
        getlunch();        
        getnoavail();
        addcase();
    }
}
function deleteException (){
    var Formulario = document.forms['delExc'];
    var longitudFormulario = Formulario.elements.length;
    var cadenaFormulario = "";
    var sepCampos;
    sepCampos = "";
    for (var i=0; i <= Formulario.elements.length-1;i++) {
        cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
        sepCampos="&";	 
    }
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ajaxDelExc=new XMLHttpRequest();
    }else{// code for IE6, IE5
        ajaxDelExc=new ActiveXObject("Microsoft.XMLHTTP");
    }	
    ajaxDelExc.open("POST","include/delException.php",true);
    ajaxFindCase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');			
    ajaxDelExc.onreadystatechange=function(){
        if (ajaxFindCase.readyState==4 && ajaxDelExc.status==200){            
            document.getElementById("info").innerHTML=ajaxDelExc.responseText;			
        }
    }    
    ajaxFindCase.send(cadenaFormulario);		 
}
function searchCase(Flag){//this function searches if the case exists
    if (Flag==1){		
        var Formulario = document.forms['addForm'];
        var textField =document.addForm.casetxt.value;
    }
    if (Flag==2){
        var Formulario = document.forms['addForm'];
        var textField =document.addForm.casetxt.value;
    }
    if (textField < 999999999){
        alert ("The case number must have at least 10 digits");
    }
    else{		
        var longitudFormulario = Formulario.elements.length;
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i=0; i <= Formulario.elements.length-1;i++) {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
            sepCampos="&";
        }
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxFindCase=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            ajaxFindCase=new ActiveXObject("Microsoft.XMLHTTP");
        }	
        ajaxFindCase.open("POST","include/searchCase.php",true);
        ajaxFindCase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        ajaxFindCase.onreadystatechange=function(){
            if (ajaxFindCase.readyState==4 && ajaxFindCase.status==200){                
                document.getElementById("messages").innerHTML=ajaxFindCase.responseText;			
            }
        }
        ajaxFindCase.send(cadenaFormulario);
        getUser();
        getlunch();
        getnoavail();
    }
}
function modifyCase(){//this function modify the case with the new data	
    if (document.addForm.casetxt.value < 999999999){
        alert ("The case number must have at least 10 digits")		
    }
    else{		
        var Formulario = document.forms['addForm'];
        var longitudFormulario = Formulario.elements.length;
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i=0; i <= Formulario.elements.length-1;i++) {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
            sepCampos="&";
        }
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            ajaxmodCase=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            ajaxmodCase=new ActiveXObject("Microsoft.XMLHTTP");
        }
        ajaxmodCase.open("POST","include/modifyCase.php",true);
        ajaxmodCase.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
        
        ajaxmodCase.onreadystatechange=function(){
            if (ajaxmodCase.readyState==4 && ajaxmodCase.status==200){
                document.getElementById("messages").innerHTML=ajaxmodCase.responseText;
            }
        }
        ajaxmodCase.send(cadenaFormulario);
        getUser();
        getlunch();
        getnoavail();
        addcase();
    }
}
function createUser(){
    document.createUserF.style.display="block";
    document.modifyUserF.style.display="none";
}
function modifyUser(){
    getfulllst();
    document.modifyUserF.style.display="block";
    document.createUserF.style.display="none";
}
function getfulllst(){//this function creates a dropdown menu with all the available engineers for the add form.	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ActiveFull=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        ActiveFull=new ActiveXObject("Microsoft.XMLHTTP");
    }
    ActiveFull.open("POST","include/getActiveList.php",true);
    ActiveFull.send();
    ActiveFull.onreadystatechange=function(){
        if (ActiveFull.readyState===4 && ActiveFull.status===200){		  
            document.getElementById("usrdrp").innerHTML=ActiveFull.responseText;        
        }
    };
}
function fillModUsr(){
    user = document.modifyUserF.engineerlst.value;
    document.modifyUserF.txtUsrName.value = document.modifyUserF.engineerlst.value;
    var w = document.modifyUserF.engineerlst.selectedIndex;
    document.modifyUserF.txtDisplayName.value = document.modifyUserF.engineerlst.options[w].text;
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        rolesAjax=new XMLHttpRequest();
    }else{// code for IE6, IE5
        rolesAjax=new ActiveXObject("Microsoft.XMLHTTP");
    }
    rolesAjax.open("POST","include/getActiveListbyUsr.php"+"?user="+user,true);
    rolesAjax.send();
    rolesAjax.onreadystatechange=function(){
        if (rolesAjax.readyState===4 && rolesAjax.status===200){
            document.getElementById("rolesContentM").innerHTML=rolesAjax.responseText;
        }
    };
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ProductsAjax=new XMLHttpRequest();
    }else{// code for IE6, IE5
        ProductsAjax=new ActiveXObject("Microsoft.XMLHTTP");
    }
    ProductsAjax.open("POST","include/getProductsByUsr.php"+"?user="+user,true);
    ProductsAjax.send();
    ProductsAjax.onreadystatechange=function(){
        if (ProductsAjax.readyState===4 && ProductsAjax.status===200){
            document.getElementById("productsContentM").innerHTML=ProductsAjax.responseText;        
        }
    };
}