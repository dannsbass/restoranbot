<?php
return function($data){
  
  $video1 = 'BAACAgUAAxkBAAMEZQIwu8og67gUxINnUnUx9NAdvP0AApkMAALCsBFUkHpqijG9jA4wBA';
  $video2 = 'BAACAgUAAxkBAAMGZQIxDJzMdQMDFhzNjlzvqvyikroAApoMAALCsBFU_52z2otz1HQwBA';
  $photo1 = 'AgACAgUAAxkBAAMTZQJ_dCp41QuNoVyNBH0t5Bqx-IQAAm-4MRvCsBFUize84fSdSHoBAAMCAANtAAMwBA';

  switch($data){
    case 'next1':
      $res1 = Bot::editMessageMedia($video1,[
        'reply_markup' => Bot::inline_keyboard("[next2][next3]"),
      ]);
      return Bot::debug($res1);

      case 'next2':
      $res2 = Bot::editMessageMedia($video2, [
        'reply_markup' => Bot::inline_keyboard("[next3][next1]"),
      ]);
      return Bot::debug($res2);
      
    case 'next3':
      $res3 = Bot::editMessageMedia($photo1, [
        'reply_markup' => Bot::inline_keyboard("[next1][next2]"),
      ]);
      return Bot::debug($res3);
    
  }
  
  $n = 1;
  $harga = 0;
  $text1 = "Tekan tombol di bawah ini untuk memilih:";
  $text2 = "Daftar pesanan Anda:";

  $msg = Bot::message();  
  $message_caption = $msg['message']['caption'];
  $message_caption = preg_replace('/' . PHP_EOL .'+/', PHP_EOL, $message_caption);
  
  if(preg_match("/$data (\d+)/", $message_caption, $cocok)){
    $n = $cocok[1] + 1;
    $message_caption = str_replace(PHP_EOL . $cocok[0], '', $message_caption);
    $message_caption = str_replace($text2, '', $message_caption);  
  }else{
    $message_caption = str_replace($text2, '', $message_caption);
  }

  $daftarMenuRestoran = daftarMenuRestoran();
  
  ########################################################
  if (strpos($data, 'batalkan') === 0)
  {
    
    $res = Bot::deleteMessage(Bot::message_id());
    $o = json_decode($res);
    if (!$o || !$o->ok) return Bot::sendMessage($res);
    else return $res;
  
  }

  ########################################################
  elseif (strpos($data, 'MENU') === 0){

    $ke = str_replace('MENU', '', $data);

    switch (trim($ke)){
      case '1':
        $media = $photo1;
        $caption = 'DAFTAR MENU 1';
        $ke = 2;
      break;

      case '2':
        $media = $photo1;
        $caption = 'DAFTAR MENU 2';
        $ke = 1;
      break;
    }

    $media = [
      'type' => 'photo',
      'media' => $media,
      'caption' => $caption,
      'parse_mode' => 'html',
    ];
    
    $data = [
      'message_id' => Bot::message_id(),
      'chat_id' => Bot::from_id(),
      'media' => json_encode($media),
      'reply_markup' => Bot::inline_keyboard("[MENU $ke]\n[ORDER]"),
      // 'reply_markup' => Bot::inline_keyboard("[ORDER]"),
    ];
    
    $res = Bot::editMessageMedia($data);
    return Bot::debug($res);
  }

  ########################################################
  elseif (strpos($data, 'ORDER') === 0){
    
    $tombol = '' 
      .mintaTombol()
      //.tombolKembali()
      .tombolBatal()
      .tombolCheckout();
    
    $data = [
      'message_id' => Bot::message_id(),
      'chat_id' => Bot::from_id(),
      'reply_markup' => Bot::inline_keyboard($tombol),
    ];

    $res = Bot::editMessageReplyMarkup('', $data);
    return Bot::debug($res);
  }

  ########################################################
  elseif(in_array($data, array_keys($daftarMenuRestoran)))
  {


    $tombol = ''
      .mintaTombol($daftarMenuRestoran[$data])
      .tombolUlang()
      .tombolKembali()
      .tombolBatal()
      .tombolCheckout();
    
    $text = strpos($message_caption, 'TOTAL HARGA') !== false ? $text2 . PHP_EOL . $message_caption : $text1;

    $options = [
      'chat_id' => Bot::from_id(),
      'message_id' => Bot::message_id(),
      'caption' => $text,
      'parse_mode' => 'html',
      'reply_markup' => Bot::inline_keyboard($tombol),
    ];

    return Bot::editMessageCaption('', $options);
  
  } 
  
  ########################################################
  elseif (strpos($data, 'kembali') === 0){
    
    $tombol = ''
      .mintaTombol()
      .tombolUlang()
      .tombolBatal()
      .tombolCheckout();
    
    $text = strpos($message_caption, 'TOTAL HARGA') !== false ? $text2 . PHP_EOL . $message_caption : $text1;
  
  }

########################################################
  elseif (strpos($data, 'ulang') === 0){
    
    $tombol = ''
      .mintaTombol()
      .tombolBatal()
      .tombolCheckout();
    
    $text = $text1;
  
  }
  
  ########################################################
  elseif (strpos($data, 'checkout') === 0){

    if (strpos($message_caption, 'TOTAL HARGA') !== false){
      
      $tombol = ''
        .tombolUlang()
        .tombolBatal()
        ."[💳 BAYAR]";
      
      $text = $message_caption;
      
    }else{

      Bot::answerCallbackQuery("Anda belum mengorder. Silahkan order terlebih dahulu.");
      
      $text =  $text1;
      
      $tombol = ''
        .mintaTombol()
        //.tombolKembali()
        .tombolBatal()
        .tombolCheckout();
    
    }
    
  }

  ########################################################
  elseif (strpos($data, '💳 BAYAR') === 0){
    return Bot::answerCallbackQuery("Terima kasih😊");
  }
    
  ########################################################
  else 
  {
    $tombol = '';
    
    $message_caption = str_replace($text1, '', $message_caption);
    
    $text = $text2;
    
    $jenis = array_values(daftarMenuRestoran());
    
    foreach($jenis as $no => $items){
      foreach($items as $item => $harga){
        if($data == $item){
          preg_match("/(TOTAL HARGA: Rp )(\d+)/", $message_caption, $cocok);
          $harga += $cocok[2];
          $message_caption = str_replace($cocok[0], '', $message_caption);
          $text .= PHP_EOL . trim($message_caption);
          $text .= PHP_EOL . "$item $n";
          $text .= PHP_EOL . "TOTAL HARGA: Rp <b>$harga</b>";
        }
      }
    }
    
    foreach($jenis as $items){
      foreach($items as $item => $harga){
        if($data == $item){
          $tombol .= ''
            .mintaTombol($items)
            .tombolUlang()
            .tombolKembali()
            .tombolBatal()
            .tombolCheckout();
          break;
        }
      }
    }
  }

  ########################################################
  $options = [
    'chat_id' => Bot::from_id(),
    'message_id' => Bot::message_id(),
    'caption' => $text,
    'parse_mode' => 'html',
    'reply_markup' => Bot::inline_keyboard($tombol),
  ];
  
  $res = Bot::editMessageCaption('', $options);
};