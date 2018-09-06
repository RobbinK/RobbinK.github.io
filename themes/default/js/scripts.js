function trim(s)
{
    return rtrim(ltrim(s));
}

function ltrim(s)
{
    var l=0;
    while(l < s.length && s[l] == ' ')
    {
        l++;
    }
    return s.substring(l, s.length);
}

function rtrim(s)
{
    var r=s.length -1;
    while(r > 0 && s[r] == ' ')
    {
        r-=1;
    }
    return s.substring(0, r+1);
}


function urlencode(text)
{
    text=trim(text);
    return text.replace(/[^a-zA-Z0-9_]/g,'_');
}



function selectOptionByValue(objid, val){
    var selObj=document.getElementById(objid);
    var A= selObj.options, L= A.length;
    while(L){
        if (A[--L].value== val){
            selObj.selectedIndex= L;
            L= 0;
        }
    }
}

//################### Validators ####################//
function is_valid_url(url)
{
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

