<?php 
?>
<link rel=" stylesheet" type="text/css" href="tinytools.progressbar.min.css">
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script type="text/javascript"  src="tinytools.progressbar.min.js"></script>

<script type="application/javascript">
    function sortMailString(mail,valide){
      var mailString = $("#mail-input").val();
         var mailString = mailString.replace("/"+mail+"\n/g", '');
         $("#mail-input").val(mailString);
            var validStr =  $("#valid").val();
            validStr += mail+"\n";
           validStr =  $("#valid").val(validStr);
        mail = "tdinfo@setun.com";
      mailString = mailString.replace("/"+mail+"/g", '');
       $("#mail-input").val(mailString);

       if(valide == true){

       }
       else{
           var  inValideMail = $('#invalid').val();
           inValideMail += mail;
           $('#invalid').val(inValideMail);
       }

    }
    function stringToArr(areaId, $delimiter = "\n"){
        var str = $("#"+areaId).val();

    }//функция возвращает массив из значений textarea;
    function getQuery(mail){
        $.ajax({
            url: 'functions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'check_email' : mail,

            },
            success: function(data){
                //console.log("тип" + typeof(data));

                if(data == "true"){
                    sortMailString(mail,true);
                }
                else if(data == "false"){
                    sortMailString(mail,false);
                }
            },
            error: function(erData){

            }

        });
    }

    function getMails(areaId){
        var res = $("#"+areaId).val();
        res = res.trim();
        var arr = res.split('\n');
        $("#"+areaId).val("");
        var resString = "";
        for (var i = 0; i < arr.length; i++) {
            arr[i] = arr[i].trim();
            resString += arr[i]+"\n";
        }
        $("#"+areaId).val(resString);

        if(arr){
            return arr;
        }
        else{
            alert("Поле с мейлами пусто");
            return false;
        }
    }//функция форматирует текст в форме и возвращает масси значений поля
    function transferMail(areaId1, areaId2, mail){}//Функция удаляет значение из первого поля и добавляет его во второй

$(document).ready(function(){
    function showSpinner(){
      $('#push-spiner').css('visibility','visible');
      $('#push-spiner-wrapper').css('visibility','visible');
    }

    function hideSpiner(){
      $('#push-spiner').css('visibility','hidden');
      $('#push-spiner-wrapper').css('visibility','hidden');
    }

    ar = stringToArr("mail-input");



    $("#str-btn").click(function(){
    
        var mailsForCheck = $('#mail-input').val();
        var ignoreMails = $('#ignore-mails').val();

        var arrmails = $('#mail-input').val().split("\n");
   
        var count = arrmails.length;
         var num = 3;
         var len = Math.trunc(count / num);
         var i = 0;
         var len2 = len;
         var valide_mails = '';
        var invalide_mails = '';
        var x=50;
        var index = 0;
        var count2 = 0;
        var x2 = 0;
         while (i<=count) {
             index = i;
             count2 = count;
             x2 = x;
             var arr2 = arrmails.slice(i, len2).join('\n');
         //alert(1);
              $.ajax({
              url: "functions.php",
              dataType: "json",
              method: "post",
              data: {
                'mails': arr2,
                 'ignore': ignoreMails,
                 'index': index,
                 'count': count2,
                 'x': x2
               },
               success: function(data){
             
                 valide_mails += data['valide_mails'];
                 invalide_mails += data['invalide_mails'];
                  $('#valid').val(valide_mails);
                   $('#invalid').val(invalide_mails);

               
                    $("#progress").progressBar({
                     width: 500,
                     height: 20,
                     percent: ((data['index'] + 1 )/ data['count'] * 100),
                    //  percent: 33,
                      showPercent: true,
                        split: 1
                     });
                  setTimeout(() => { console.log("мир"); }, 5000);
                   hideSpiner();
                  console.log("Полученные данные ");
                  console.log(data);
               },
               error: function(){
                 hideSpiner();
                $('#valid').val('Ошибка');
                 $('#invalid').val('Ошибка');
                },
            });
               
              i+=len;
                len2+=len; 
         
       }
        
       });
          
  
});

</script>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Проверка на валидность email</title>
  </head>
  <body>
  <div id="push-spiner-wrapper"></div>
  <div id="push-spiner">
    <div>
          <section class="talign-center">
            <div class="spinner icon-spinner-3" aria-hidden="true"></div>
          </section>
          <footer class="talign-center">
            <p>Адреса email обрабатываются</p>
          </footer>
    </div>
  </div>

    <div  class="wrapper">
      <form action="function.php" method="POST">
        <div class="panel">
          <div class="exclude-mails">
              <h3>Исключения</h3>
              <textarea id="ignore-mails" title="адреса которые будут игнорироваться">
              </textarea>

          </div>
            <div class="get-res">
                <input type="button" value="Получить" id="str-btn">
            </div>
            <p class="clearboth"></p>
        </div>
        <div class="body-pr">
          <div class="input-area text-input">
              <h3>Адреса для проверки</h3>
              <textarea id="mail-input" title="можно вводить адреса через пробелы, перенос строки, и запятые">
              </textarea>
              <br>
              <br>
              <br>
              <div id="progress"></div>
          </div>
          <div class="valide-email text-input">
              <h3>Валидные адреса</h3>
            <textarea id="valid">
            </textarea>

              <br>
              <br>
               <br>
                <br>
                 <br>
                  <br>
                   <br>



              
          </div>
          <div class="invalide-email text-input">
              <h3>Невалидные адреса</h3>
            <textarea id="invalid"></textarea>

          </div>
          <p class="clearboth"></p>
        </div>

      </form>
    </div>

  </body>
</html>
