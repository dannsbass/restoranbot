<?php

require __DIR__ . '/config.php';

// echo Bot::setWebhook('https://testing.bintulabun.repl.co'); die;

##########################################
Bot::start(function () {

  $photo1 = 'AgACAgUAAxkBAAMTZQJ_dCp41QuNoVyNBH0t5Bqx-IQAAm-4MRvCsBFUize84fSdSHoBAAMCAANtAAMwBA';

  $caption = "Selamat datang di <b>Restoran Kita</b>.";

  $options = [
    'reply' => false,
    'caption' => $caption,
    'reply_markup' => Bot::inline_keyboard("[MENU 2]\n[ORDER]"),
    'parse_mode' => 'html'
  ];

  $res = Bot::sendPhoto($photo1, $options);

  Bot::deleteMessage(Bot::message_id());

  Bot::debug($res);
});

##########################################
Bot::chat('/video', function () {

  $video1 = 'BAACAgUAAxkBAAMEZQIwu8og67gUxINnUnUx9NAdvP0AApkMAALCsBFUkHpqijG9jA4wBA';
  $res1 = Bot::sendVideo($video1, ['reply_markup' => Bot::inline_keyboard("[next2][next3]")]);
  Bot::debug($res1);
});

##########################################
Bot::photo(function () {
  $res = Bot::sendMessage(json_encode(Bot::message(), JSON_PRETTY_PRINT));
  $o = json_decode($res);
  if (!$o || !$o->ok) return Bot::sendMessage($res);
});

##########################################
Bot::callback_query(function ($data) {
  return (require 'callback_query.php')($data);
});

##########################################
Bot::chat('unduh', function ($file) {
  if (!file_exists($file)) return Bot::sendMessage("file $file gak ada");
  return Bot::sendDocument($file);
});

##########################################
Bot::text(function ($input) {
  return Bot::deleteMessage(Bot::message_id());
});

##########################################
Bot::new_chat_members(function () {
  return Bot::deleteMessage(Bot::message_id());
});

##########################################
Bot::all(function () {
  return Bot::debug();
  return Bot::deleteMessage(Bot::message_id());
});

##########################################
Bot::run();
