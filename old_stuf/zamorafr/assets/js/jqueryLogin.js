function loginValidation(){
    if (loginFieldsValidation()===true){        
        $('#loginForm').submit();    
    }
    $(setFocus("#username"));
}
function setFocus(element){
    $(element).focus();
    //console.log("Focus in"+ element);
}
function loginFieldsValidation(){	
    //console.log("Llegue a login Fields Validation 2");
    if (document.loginForm.username.value ===""){ //IF 1
        document.getElementById("errorlbl").innerHTML = 'Username is empty';
        $(setFocus("#username"));
        return false;						
    }else{ // else 1
        if (document.loginForm.passwordTB.value ===""){ // IF 2
            document.getElementById("errorlbl").innerHTML = 'Password is empty';
            $(setFocus("#passwordTB"));
            return false;
        }else{
            return true;
        }
        return true;			
    }//else 1 of the username validation
    return true;	
}// end IF 1
$(document).ready(function(){
    $(document).keyup(function(objEvent){
        objEvent ? keycode = objEvent.keyCode : keycode = event.keyCode;        
        if(keycode ===13){          
            $(loginValidation);
        }
    });
});