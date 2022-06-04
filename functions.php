<?php 

 $valideMails = "";
  $inValideMails = "";
  //require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
  function makeArFromStr($str){
  $res = preg_replace("#\s+#",",",$str);
  $arMails = explode(",",$res);
  foreach ($arMails as $key => $item){
    if($item === "" || $item === " " || is_null($item) || $item === false)
      unset($arMails[$key]);
  }
  return $arMails;
}//Преобразует строку с мылом в массив
  /* Credit: https://github.com/hbattat/verifyEmail */
  function verifyEmail($toemail, $fromemail, $getdetails = false)
{
  // Get the domain of the email recipient
 // sleep(1);
  $details = "";
  $email_arr = explode('@', $toemail);
  $domain = array_slice($email_arr, -1);
  $domain = $domain[0];

  // Trim [ and ] from beginning and end of domain string, respectively
  $domain = ltrim($domain, '[');
  $domain = rtrim($domain, ']');

  if ('IPv6:' == substr($domain, 0, strlen('IPv6:'))) {
    $domain = substr($domain, strlen('IPv6') + 1);
  }

  $mxhosts = array();
  // Check if the domain has an IP address assigned to it
  if (filter_var($domain, FILTER_VALIDATE_IP)) {
    $mx_ip = $domain;
  } else {
    // If no IP assigned, get the MX records for the host name
    getmxrr($domain, $mxhosts, $mxweight);
  }

  if (!empty($mxhosts)) {
    $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
  } else {
    // If MX records not found, get the A DNS records for the host
    if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
      $record_a = dns_get_record($domain, DNS_A);
      // else get the AAAA IPv6 address record
    } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
      $record_a = dns_get_record($domain, DNS_AAAA);
    }

    if (!empty($record_a)) {
      $mx_ip = $record_a[0]['ip'];
    } else {
      // Exit the program if no MX records are found for the domain host
      $result = 'invalid';
      $details .= 'No suitable MX records found.';

      return ((true == $getdetails) ? array($result, $details) : $result);
    }
  }

  // Open a socket connection with the hostname, smtp port 25
  $connect = @fsockopen($mx_ip, 25);

  if ($connect) {

    // Initiate the Mail Sending SMTP transaction
    if (preg_match('/^220/i', $out = fgets($connect, 1024))) {

      // Send the HELO command to the SMTP server
      fputs($connect, "HELO $mx_ip\r\n");
      $out = fgets($connect, 1024);
      $details .= $out."\n";

      // Send an SMTP Mail command from the sender's email address
      fputs($connect, "MAIL FROM: <$fromemail>\r\n");
      $from = fgets($connect, 1024);
      $details .= $from."\n";

      // Send the SCPT command with the recepient's email address
      fputs($connect, "RCPT TO: <$toemail>\r\n");
      $to = fgets($connect, 1024);
      $details .= $to."\n";

      // Close the socket connection with QUIT command to the SMTP server
      fputs($connect, 'QUIT');
      fclose($connect);

      // The expected response is 250 if the email is valid
      if (!preg_match('/^250/i', $from) || !preg_match('/^250/i', $to)) {
        $result = 'invalid';
      } else {
        $result = 'valid';
      }
    }
  } else {
    $result = 'invalid';
    $details .= 'Could not connect to server';
  }
  if ($getdetails) {
    return array($result, $details);
  } else {
    return $result;
  }
}
  $checkMails = htmlspecialchars($_POST["mails"]);
  $ignoreMails = htmlspecialchars($_POST["ignore"]);
  $index = htmlspecialchars($_POST["index"]);
   $count2 = htmlspecialchars($_POST["count"]);
      $x = htmlspecialchars($_POST["x"]);
  file_put_contents("log.log",print_r($_REQUEST,true),FILE_APPEND);
  $arMailsForCheck = makeArFromStr($checkMails);
  $arMailsForIgnore = makeArFromStr($ignoreMails);
  $arCheck = array();

  foreach ($arMailsForCheck as $key => $mail){
    foreach ($arMailsForIgnore as $ignore){
       $res = "";
       $res = preg_match("#".$ignore."#", $mail,$m);
        if($res === 1){
         unset($arMailsForCheck[$key]);
       }
    }
  }

 

  $fromemail = "andyanderflow@gmail.com";
  foreach ($arMailsForCheck as $key => $mail){
  $verRes =  verifyEmail($mail, $fromemail, $getdetails = false);

    if($verRes === "valid"){
      $valideMails .= $mail."\n";
    }
    else{
      $inValideMails .= $mail."\n";
    }

  }

  $arResult = array(
    "valide_mails" => $valideMails,
    "invalide_mails" => $inValideMails,
    "index" =>   $index,
    "count" => $count2,
     "x" => $x
  );


  echo json_encode($arResult);






















?>


