

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

  $(document).ready(function() {


      function sendMail(arFieldsId, type = 1) {

          var name = $("#" + arFieldsId[0]).val();
          var mail = $("#" + arFieldsId[1]).val();
          var phone = $("#" + arFieldsId[2]).val();

          $.ajax({
              url: 'send_mail.php',
              type: 'post',
              dataType: 'json',
              data: {
                  'NAME': name,
                  'MAIL': mail,
                  'PHONE': phone,
                  'TYPE': type,
              },
              success: function (data) {

                  if(data == "true"){
                      clearFields(arFieldsId);
                      alert("Спасибо! Ваше сообщение отправлено");

                  }
                  else{
                      alert("Отправка не удалась");

                  }

              },
              error: function () {
                  alert("Ошибка при отправке сообщения! Попробуйте еще раз");

              }

          });

      }
      function checkFields(arFieldsId){
          result = true;
          for(var i =0; i<arFieldsId.length; i++){
            var field = $("#"+arFieldsId[i]).val();
            if(field == "")
                return result = false;

          }
          return true;
      }
      function clearFields(arFieldsId){
             for(var i = 0; i < arFieldsId.length; i++){
                  $("#"+arFieldsId[i]).val("");
              }

      }


      $("#send-mail").click(function (event) {
          arFieldsId = [
              "u-name",
              "mail",
              "phone",
          ];

          if(checkFields(arFieldsId)){

            sendMail(arFieldsId, 2);

          }
          else{
              alert("Пожалуйста заполните все поля");
          }


      });

  });

</script>


<form action="send_mail_form.php">
  <input type="text" id="u-name">
  <input type="mail" id="mail">
  <input type="text" id="phone">

  <input type="button" value="Отправить" id="send-mail">

</form>