jQuery(function(){
  function zeroPad(i){return (i<10)?("0"+i):i;}
      var reservel = document.getElementById("reservels");
      var rex = /^(http|https):\/\/([\w-]+\.)+[\w-]+\/?/g;
      var domain = rex.exec(reservel.src);
      var root = document.createElement('div');
      root.innerHTML = '<div class="reservel_inner"><div class="reservel_title">受付状況</div><div class="reservel_status"><span id="reservel_date"></span><span class="reservel_txt">現在の待ち人数</span><span id="totalCnt">0</span><span class="reservel_unit">人</span></div><div class="reservel_btn"><a href="https://shinka-reservel.jp">診療受付</a></div></div><link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:700&display=swap" rel="stylesheet"><style type="text/css">.reservel_inner{margin-bottom:24px;padding:1rem 0;border-top:solid 1px #1bb8ce;border-bottom:solid 1px #1bb8ce;color:#565656}.reservel_title{padding-bottom:1rem;border-bottom:dotted 3px #a4e7f0;font-size:1.8rem;font-weight:bold;line-height:100%;text-align:center}#reservel_date{display:inline-block;padding-right:1.25rem;vertical-align:baseline;font-size:2rem;font-family:"Roboto Condensed",sans-serif;letter-spacing:-0.05rem;font-feature-settings:"palt";font-weight:normal;line-height:100%}#totalCnt{display:inline-block;font-size:3.5rem;font-family:"Roboto Condensed",sans-serif;letter-spacing:-0.05rem;font-feature-settings:"palt";font-weight:normal;line-height:100%}.reservel_status{padding:1rem 0;text-align:center;font-size:0}.reservel_txt{display:inline-block;padding-right:.5rem;vertical-align:baseline;font-size:1.6rem}.reservel_unit{display:inline-block;vertical-align:bottom;font-size:1.6rem;font-weight:bold}.reservel_btn{margin:0 calc((100% - 200px) / 2);text-align:center}.reservel_btn a{display:inline-block;width:200px;height:36px;border-radius:18px;background-color:#1bb8ce;color:#fff;line-height:36px;font-weight:bold}@media screen and (min-width :768px){.reservel_inner{display:flex}.reservel_title{display:flex;align-items:center;padding:.5rem 1.5rem 0 0;border-right:dotted 3px #a4ddfe;border-bottom:0}.reservel_status{padding:0 0 0 1.5rem;text-align:center;vertical-align:middle;font-size:0}.reservel_txt{padding-right:3rem;font-size:1.6rem;vertical-align:middle}#reservel_date{padding-right:2.5rem;font-size:2.4rem;vertical-align:middle}#totalCnt{font-size:5rem;vertical-align:middle}.reservel_btn{display:flex;align-items:center;margin:0 0 0 auto;text-align:center}.reservel_btn a{width:160px}}@media screen and (min-width :1200px){.reservel_inner{width:1140px;margin-right:auto;margin-left:auto;margin-bottom:40px}.reservel_title{border-right:dotted 4px #a4ddfe;font-size:2rem}.reservel_txt{padding-right:3rem;font-size:2rem;vertical-align:middle}#reservel_date{padding-right:2.5rem;font-size:3.6rem;vertical-align:middle}#totalCnt{font-size:6rem;vertical-align:middle}.reservel_unit{display:inline-block;padding-right:.5rem;padding-bottom:.5rem;font-size:2rem;vertical-align:bottom}.reservel_btn a{width:200px;height:48px;border-radius:24px;line-height:48px}}</style>';
      reservel.parentNode.insertBefore(root, reservel);
      reservel.parentNode.removeChild(reservel);
      reservel.removeAttribute("id");
      root.setAttribute("id","reservel");
      
      jQuery.ajax({type: "GET",
              url: domain[0] + "api/acceptanceCount",
             }).done(function( json ) {
               var obj = JSON.parse(json);
               if (obj.status.code==0){
                jQuery("#totalCnt").text(obj.result.total);
                jQuery("#reservel_date").text(obj.result.time);
               }else{
                jQuery("#totalCnt").text("-");
                 var d = new Date();
                 jQuery("#reservel_date").text(d.getFullYear()+"/"+d.getMonth()+1+"/"+d.getDate()+" "+zeroPad(d.getHours())+":"+zeroPad(d.getMinutes()));
               }
             }).fail(function(data) {
                   console.log(data);
             });
  });