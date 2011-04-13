<?php

$ausgabe .= "
    <script type=\"text/javascript\">
    StartZ = new Date();
    function ankzcalc(s1){
     var ZeitServer = new Date(\"". date("M, d Y H:i:s") ."\");
     var Zeit = new Date();
     var offset = ZeitServer.getTime() - StartZ.getTime();
     var AbsolutJetzt = Zeit.getTime();
     var AbsolutDann = AbsolutJetzt + (s1 * 1000);
     Zeit.setTime(AbsolutDann + offset);
     var Jahr = Zeit.getFullYear();
     var Monat = Zeit.getMonth() + 1;
     var Tag = Zeit.getDate();
     var hh = Zeit.getHours();
     var mm = Zeit.getMinutes();
     var ss = Zeit.getSeconds();
     if(ss<10){ss=\"0\"+ss}
     if(mm<10){mm=\"0\"+mm}
     if(hh<10){hh=\"0\"+hh}
     if(Tag<10){Tag=\"0\"+Tag}
     if(Monat<10){Monat=\"0\"+Monat}
     document.getElementById(\"z\").innerHTML=hh+\":\"+mm+\":\"+ss+\" (\"+Tag+\".\"+Monat+\".\"+Jahr+\")\";
    }
    function endzcalc(s2){
     var ZeitServer = new Date(\"". date("M, d Y H:i:s") ."\");
     var Zeit = new Date();
     var offset = ZeitServer.getTime() - StartZ.getTime();
     var AbsolutJetzt = Zeit.getTime();
     var AbsolutDann = AbsolutJetzt + (2 * s1 * 1000);
     Zeit.setTime(AbsolutDann + offset);
     var Jahr = Zeit.getFullYear();
     var Monat = Zeit.getMonth() + 1;
     var Tag = Zeit.getDate();
     var hh = Zeit.getHours();
     var mm = Zeit.getMinutes();
     var ss = Zeit.getSeconds();
     if(ss<10){ss=\"0\"+ss}
     if(mm<10){mm=\"0\"+mm}
     if(hh<10){hh=\"0\"+hh}
     if(Tag<10){Tag=\"0\"+Tag}
     if(Monat<10){Monat=\"0\"+Monat}
     document.getElementById(\"y\").innerHTML=hh+\":\"+mm+\":\"+ss+\" (\"+Tag+\".\"+Monat+\".\"+Jahr+\")\";
    }
    function daucalc(){
     a=document.getElementsByName(\"mkx\")[0].value;
     b=document.getElementsByName(\"mky\")[0].value;
     c=document.getElementsByName(\"mkz\")[0].value;
     p=document.getElementsByName(\"mg\")[0].value;
     m=0;
     h=0;
     d=\"-\";
     if((a!=$x || b!=$y || c!=$z) && a<7 && b<7 && c<7 && a>0 && b>0 && c>0){
     d=500;
     l=500;
     j=700;
     d=Math.round(Math.sqrt(Math.pow(Math.abs($z-c)*100,2)+Math.pow(Math.abs($y-b)*100,2)+Math.pow(Math.abs($x-a)*100,2))*100000)/100000;
     l=Math.round(Math.sqrt(Math.abs($z-c)+Math.abs($y-b)+Math.abs($x-a))/10*100000)/100000+1;
     j=Math.round(((d+300)/l)*100000)/100000;
     s=Math.round((100/p)*j/$ges*4000);
     d=Math.round(d*100)/100;
     s1=s;
     s2=s;
     ankzcalc(s1);
     endzcalc(s2);
     if(s>59){
      m=Math.floor(s/60);
      s=s-m*60}
     if(m>59){
      h=Math.floor(m/60);
      m=m-h*60}
     if(s<10){s=\"0\"+s}
     if(m<10){m=\"0\"+m}
         if(h<10){h=\"0\"+h}
     }
     if(d==\"-\"){
     document.getElementById(\"w\").innerHTML=d;
     document.getElementById(\"x\").innerHTML=d;
     document.getElementById(\"y\").innerHTML=d;
     document.getElementById(\"z\").innerHTML=d;
     }else{
     document.getElementById(\"w\").innerHTML=d;
     document.getElementById(\"x\").innerHTML=h+\":\"+m+\":\"+s;
     }
     }
   </script>\n";

?>