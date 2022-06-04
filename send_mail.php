<?
$to      = "a.shukov@solo-it.ru";
$mail = htmlspecialchars($_POST['MAIL']);
$name = htmlspecialchars($_POST['NAME']);
$phone = htmlspecialchars($_POST['PHONE']);
$type = intval($_POST['TYPE']);
//file_put_contents("log.log", print_r($_POST, 1),FILE_APPEND );

if($mail && $name && $phone){
  if($type == 1){
    $subject = "Получить прайс";
    $message = "Получить прайс:\n
  Имя: $name\n
  Емейл: $mail\n
  Телефон: $phone\n  
";
  }
  elseif($type == 2){
    $subject = "Сообщение с сайта";
    $message = "Сообщение с сайта:\n
  Имя: $name\n
  Емейл: $mail\n
  Телефон: $phone\n  
";

  }

  $statusSend = mail($to, $subject, $message);
  if($statusSend){
    echo json_encode("true");
  }
  else{
    echo json_encode("false");
  }
}
else{
  echo json_encode("false");
}




?>