var nFlag=unFlag=pFlag=cpFlag=false;
var msg=clr="";
var xmlHttp;
function GetXmlHttpObject() {
  xmlHttp=null;
  try
    {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
    }
  catch (e)
    {
    // Internet Explorer
    try
      { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); }
    catch (e)
      { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
    }
  return xmlHttp;
  }
function checkName(n){
  var nameExp=/^[a-z]{3,15}$/i;
  if (n.length==0){
    msg="";
    nFlag=false;
  }
  else if (!nameExp.test(n)){
      msg="Invalid Name";
      clr="red";
      nFlag=false;
    }
  else{
      msg="";
      nFlag=true;
    }
  document.getElementById('nameMsg').style.color=clr;
  document.getElementById('nameMsg').innerHTML=msg;
}
function checkName2(n){
  var nameExp=/^[a-z]{3,15}$/i;
  if (n.length==0){
    msg="";
    nFlag=false;
  }
  else if (!nameExp.test(n)){
      msg="Invalid Name";
      clr="red";
      nFlag=false;
    }
  else{
      msg="";
      nFlag=true;
    }
  document.getElementById('nameMsg2').style.color=clr;
  document.getElementById('nameMsg2').innerHTML=msg;
}
function checkUN(un){
  var unameExp = /^[a-z]{3,9}$/i;
  if (un.length==0){
    msg="";
    unFlag=false;
    document.getElementById('usernameMsg').innerHTML=msg;
  }
  else if (!unameExp.test(un)){
      msg="Invalid Username";
      clr="red";
      unFlag=false;
      document.getElementById('usernameMsg').style.color=clr;
      document.getElementById('usernameMsg').innerHTML=msg;
    }
  else{
      xmlHttp=GetXmlHttpObject();
      var url="CheckUsername.php";
      url=url+"?un="+un;
      url=url+"&sid="+Math.random();

      xmlHttp.onreadystatechange=function(){
        if (xmlHttp.readyState==4){
            if (xmlHttp.responseText=="valid")
            {
              msg="";
              unFlag=true;
            }
            else {
              msg="Username is taken";
              clr="red";
              unFlag=false;
            }
            document.getElementById('usernameMsg').style.color=clr;
            document.getElementById('usernameMsg').innerHTML=msg;
          }
      };
      xmlHttp.open("GET",url,true);
      xmlHttp.send(null);
    }
}
function checkPassword(psw){
  var lowercase = /[a-z]/;
  var uppercase = /[A-Z]/;
  var number = /[0-9]/;

  if(psw.length==0){
    msg="";
    pFlag=false;
  }
  else if (psw.length<8 || psw.length>25) {
    msg = "Your password should be in range of (8-25) charchters";
    clr="red";
    pFlag = false;
  }
   else if (!lowercase.test(psw)) {
    msg = "Your password should contain at least one lowercase charchter";
    clr="red";
    pFlag = false;
  }
  else if (!uppercase.test(psw)) {
    msg = "Your password should contain at least one uppercase charchter";
    clr="red";
    pFlag = false;
  }
   else if (!number.test(psw)) {
     msg = "Your password should contain at least one number";
     clr="red";
     pFlag = false;
  }
   else {
    msg = "";
    pFlag = true;
  }
   document.getElementById('passwordMsg').style.color=clr;
   document.getElementById('passwordMsg').innerHTML=msg;
}
function checkCpassword(cpsw){
var psw = document.getElementById('psw').value;
if(psw!=cpsw){
  document.getElementById('cpasswordMsg').style.color="red";
  document.getElementById('cpasswordMsg').innerHTML="Your passwords doesn't match";
  pFlag = false;
}
else{
  document.getElementById('cpasswordMsg').innerHTML="";
  cpFlag=true;
}

}
function checkUserInputs(formName){
  document.formName.JSEnabled.value="TRUE";
  return (nFlag && unFlag && pFlag && cpFlag);

}


function changeText(button,input)
{
var x = document.getElementById(button);
  if (x.innerHTML == "Edit") {
    x.innerHTML = "Save";
    x.value="Save";
    document.getElementById('isset1').value="true";
    document.getElementById(input).readOnly=false;
    document.getElementById(input).focus();
    document.getElementById(input).select();
  }
  else if (x.innerHTML == "Save")  {
    document.getElementById('isset1').value="false";
    x.value="Edit";
    x.innerHTML = "Edit";
    document.getElementById(input).readOnly=true;
  }
}
