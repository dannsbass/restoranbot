<?php
require __DIR__ . '/callback_query.php';

function tombolBatal($data = ''){
  return "\n[❌ BATALKAN|batalkan_$data]";
}

function tombolUlang($data = ''){
  return "\n[🗒️ ULANG|ulang_$data]";
}

function tombolKembali($data = ''){
  return "\n[🔙 KEMBALI|kembali_$data]";
}

function tombolCheckout($data = ''){
  return "\n[✅ CHECK OUT|checkout_$data]";
}

function mintaTombol($menu_restoran = []){
  $menu_restoran = empty($menu_restoran) ? daftarMenuRestoran() : $menu_restoran;
  $item = '';
  $no = 1;
  foreach($menu_restoran as $key => $value){
    $data = $key;
    
    if (is_numeric($value)){
      // $data .= " ($value)";
    }

    $item .= "[$data]";
    $item .= ($no % 2 == 0) 
    ? "\n"
    : '';
    
    $no++;
  }
  return $item;
}

function daftarMenuRestoran(){  
  return [
    
    '🍔 MAKANAN' => [
                      '🍛 NASI GORENG' => 5000, 
                      '🍝 MIE GORENG' => 3000, 
                      '🧆 BAKSO' => 8000, 
                      '🍜 SOTO' => 4500,
                    ],
    
    '☕️ MINUMAN' => [
                      '🥤 ES TEH' => 5000,
                      '🥤 ES JERUK' => 5000, 
                      '☕️ TEH PANAS' => 4000,
                      '☕️ KOPI PANAS' => 6500,
                      '🍹 ES BUAH' => 8500,
                      '🥛 SUSU' => 9000,
                    ], 
    
    '🍎 BUAH'    => [
                      '🍏 APEL' => 10000,
                      '🍉 SEMANGKA' => 4500,
                      '🍊 JERUK' => 5000,
                    ],
    
    '🍦 ES KRIM' => [
                      '🍦 ES KRIM COKLAT' => 15000,
                      '🍦 ES KRIM VANILLA' => 14000,
                      '🍦 ES KRIM STROWBERRY' => 16000,
                    ],
    
    '🍩 DONAT' => [
                    '🍩 DONAT COKLAT' => 10000,
                    '🍩 DONAT KACANG' => 9500,
                    '🍩 DONAT STROWBERRY' => 8000,
                  ],
    
  ];
}

