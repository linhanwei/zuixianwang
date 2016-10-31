function doDownload(url)
{

	var u = navigator.userAgent;
	if(u.indexOf("MicroMessenger")>-1)
	{
		if(u.indexOf('Android') > -1)
		{
      showTip(1);
		}
		else
		{
      showTip(2);
		}
	}
	else
	{
		window.location.href=url;
  }
}

function showTip(type){
  document.getElementById("show").className+="ly_shadow";
  document.getElementById("show1").style.display="";
  document.getElementById("clear").style.display="";
  if(type == 1)
  {
    document.getElementById("a").className="two2";
  }
  else
  {
    document.getElementById("a").className="two";
  }
}

function hideTip(){
  document.getElementById("show").removeAttribute("class");
	document.getElementById("show1").style.display="none";
	document.getElementById("clear").style.display="none";
	document.getElementById("a").setAttribute("class","");
}
