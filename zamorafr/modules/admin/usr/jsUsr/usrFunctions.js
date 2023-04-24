function newAjax(addUrl, paramForm){//this function submits the new case

if ((document.createUserF.txtUsrName.value === "") || (document.createUserF.txtDisplayName.value === "") || (document.createUserF.txtMail.value === "")){
alert ("Please complete the information");}
else{
        var Formulario = document.forms[paramForm];
        var cadenaFormulario = "";
        var sepCampos;
        sepCampos = "";
        for (var i = 0; i <= Formulario.elements.length - 1; i++) {
            
if (Formulario.elements[i].type==="checkbox"){
                    if(Formulario.elements[i].checked){
                        cadenaFormulario += sepCampos + Formulario.elements[i].name + '=' + encodeURI(Formulario.elements[i].value);
        sepCampos = "&";}
            }
            else{
cadenaFormulario += sepCampos + Formulario.elements[i].name + '=' + encodeURI(Formulario.elements[i].value);
        sepCampos = "&";}
}
$.ajax({
        data:  cadenaFormulario,
        url:   addUrl,
        type:  'post',
        beforeSend: function () {
        $("#responseMessage").html("In Progress, Please wait...");
},
        success:  function (response) {
        $("#responseMessage").html(response);

}
});
}
}

function newUser(){
    //document.createUserF.style.display="block";
   // document.modifyUserF.style.display="none";
}
function modifyUser(){
    
    
    document.createUserF.txtUsrName.style.visibility ='hidden';
    document.createUserF.txtDisplayName.style.visibility ='hidden';
    ajaxCall("../../include/getActiveList.php", "nameTxt");
    //document.modifyUserF.style.display="block";
    //document.createUserF.style.display="none";
}
function ajaxCall(url,element){//this function creates a dropdown menu with all the available engineers for the add form.	
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        myAjax=new XMLHttpRequest();
    }
    else{// code for IE6, IE5
        myAjax=new ActiveXObject("Microsoft.XMLHTTP");
    }
    myAjax.open("POST",url,true);
    myAjax.send();
    myAjax.onreadystatechange=function(){
        if (myAjax.readyState==4 && myAjax.status==200){		  
            document.getElementById(element).innerHTML=myAjax.responseText;        
        }
    }
}
function fillModUsr(){
    user = document.createUserF.engineerlst.value;
    document.createUserF.txtUsrName.value = document.createUserF.engineerlst.value;
    var w = document.createUserF.engineerlst.selectedIndex;
    document.createUserF.txtDisplayName.value = document.createUserF.engineerlst.options[w].text;
        //alert(document.createUserF.txtDisplayName.value);
        alert("../../include/getActiveListbyUsr.php"+"?user="+user);
    ajaxCall("../include/getActiveListbyUsr.php"+"?user="+user, "rolesContent");
    ajaxCall("../../include/getProductsByUsr.php"+"?user="+user, "productsContentM");
   /* if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        rolesAjax=new XMLHttpRequest();
    }else{// code for IE6, IE5
        rolesAjax=new ActiveXObject("Microsoft.XMLHTTP");
    }
    rolesAjax.open("POST","include/getActiveListbyUsr.php"+"?user="+user,true);
    rolesAjax.send();
    rolesAjax.onreadystatechange=function(){
        if (rolesAjax.readyState==4 && rolesAjax.status==200){
            document.getElementById("rolesContentM").innerHTML=rolesAjax.responseText;
        }
    }	*/
    /*if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        ProductsAjax=new XMLHttpRequest();
    }else{// code for IE6, IE5
        ProductsAjax=new ActiveXObject("Microsoft.XMLHTTP");
    }
    ProductsAjax.open("POST","include/getProductsByUsr.php"+"?user="+user,true);
    ProductsAjax.send();
    ProductsAjax.onreadystatechange=function(){
        if (ProductsAjax.readyState==4 && ProductsAjax.status==200){
            document.getElementById("productsContentM").innerHTML=ProductsAjax.responseText;        
        }
    }*/	
}