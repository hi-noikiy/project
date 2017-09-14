var submited="";
//动作提交
function submitCommonForm(formObject, buttonName) {
   
    if(!validator(formObject)){
        return (false);
    }
    if (submited == 1) {
        return (false);
    } 
    formObject.action.value = buttonName;
    formObject.submit();
    submited = 1;
    return (false);
}

//带一个隐藏值的提交

function submitCommonFormEx(formObject, buttonName, hiddenObject, value) {
    if(!validator(formObject)){
        return (false);
    }
    if (submited == 1) {
        return (false);
    }
    formObject.action.value = buttonName;
    hiddenObject.value = value;
    formObject.submit();
    submited = 1;
    return (false);
}
function p_del(msg) {
    if(!msg){
        msg = "您真的确定要删除吗？\n\n请确认！";
    }
    if (confirm(msg)==true){
        return true;
    }else{
        return false;
    }
}


