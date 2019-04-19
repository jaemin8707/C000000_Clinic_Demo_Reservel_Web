$(function(){
function zeroPad(i){return (i<10)?("0"+i):i;}
    var reservel = document.getElementById("reservel");
    var rex = /^(http|https):\/\/([\w-]+\.)+[\w-]+\/?/g;
    var domain = rex.exec(reservel.src);

    var root = document.createElement('div');
    root.innerHTML ='<div id="reservel_date" style="margin-bottom:8px;font-size:16px;"></div><div>現在の待ち人数</div><div id="totalCnt" style="margin:4px 0 8px;font-weight:bold;"><span>0</span>人</div><div><a href="' + domain[0] + 'index" target="_blank">受付状況</a></div><link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet"><style type="text/css">#reservel{box-sizing: border-box; width:234px;background:#FFF;border-radius:6px;margin-bottom:20px;padding:24px;font-size:16px;}#reservel div{text-align:center;}#totalCnt span{ font-size:3.5rem;font-family: "Roboto Condensed", sans-serif;letter-spacing:-0.05rem;font-feature-settings:"palt";font-weight:normal;line-height:100%;}#reservel div a{display: inline-block;box-shadow:0px 2px 3px rgba(0,0,0,0.3);padding:15px 15px;color:#fff;background:red;border-radius:8px;font-size:0.9rem;text-decoration:none;}</style>';
    reservel.parentNode.insertBefore(root, reservel);
    reservel.parentNode.removeChild(reservel);
    reservel.removeAttribute("id");
    root.setAttribute("id","reservel");

    $.ajax({type: "GET",
            url: domain[0] + "api/acceptanceCount",
           }).done(function( json ) {
             var obj = JSON.parse(json);
             if (obj.status.code==0){
               $("#totalCnt span").text(obj.result.total);
               $("#reservel_date").text(obj.result.time);
             }else{
               $("#totalCnt span").text("-");
               var d = new Date();
               $("#reservel_date").text(d.getFullYear()+"/"+d.getMonth()+1+"/"+d.getDate()+" "+zeroPad(d.getHours())+":"+zeroPad(d.getMinutes()));
             }
           }).fail(function(data) {
                 console.log(data);
           });
});