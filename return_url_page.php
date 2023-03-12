<?php

use Shopier\Models\ShopierResponse;

require_once __DIR__ . '/bootstrap.php';
require 'template/connect.php';
require 'template/settings.php';

$shopierResponse = ShopierResponse::fromPostData();

if (!$shopierResponse->hasValidSignature(getenv('SHOPIER_API_SECRET'))) {

    //print_r($shopierResponse->toArray());

    $guncelle = $db->prepare('UPDATE `accounts` SET `postbalance`=:balance WHERE `Character`=:username');
    $guncelle->execute([
        'balance' => '0',
        'username' => $_COOKIE['usernamee']
    ]);
    die('<!DOCTYPE html>
    <html lang="tr">
    
    <head>
        <title>Arzea.online - Pay Failed</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <!-- Favicon -->
        <link rel="shortcut icon" href="' . $favcion . '">
    
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        
        <style>
        .vh-100{
            height: 100vh;
            min-height:100vh;
        }
        </style>
    </head>
    
    <body>
        <div class="container">
            <div class="row vh-100 align-items-center">
                <div class="col text-center">
                    <div class="content">
                        <h1>Pay Could Not Be Received</h1>
                        <br>
                        <a class="btn btn-outline-primary mt-4" href=""> Go back to the pay page</a>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    </body>
    
    </html>');
} else {

    $select = $db->prepare('SELECT * FROM `accounts` WHERE `Username`=:username');
    $select->execute([
        'username' => $_COOKIE['usernamee']
    ]);

    $fetch = $select->fetch(PDO::FETCH_ASSOC);

    $islem = $fetch['Bakiye'] + $fetch['postbalance'];

    $guncelle = $db->prepare('UPDATE `accounts` SET `Balance`=:miktar, `postbalance`=:balance WHERE `Username`=:username');
    $guncelle->execute([
        'miktar' => $islem,
        'balance' => '0',
        'username' => $_COOKIE['usernamee']
    ]);

    if ($guncelle) {
        echo '<!DOCTYPE html>
     <html lang="tr">
 
     <head>
         <title>Arzea.online - Payment Successful</title>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
     
         <!-- Favicon -->
         <link rel="shortcut icon" href="' . $favicon . '">
     
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
         
         <style>
         .vh-100{
             height: 100vh;
             min-height:100vh;
         }
         </style>
     </head>
     
     <body>
         <div class="container">
             <div class="row vh-100 align-items-center">
                 <div class="col text-center">
                     <div class="content">
                         <h1>Payment Successful</h1>
                         <a class="btn btn-outline-primary mt-4" href=""> Go back to the pay page</a>
                     </div>
                 </div>
             </div>
         </div>
     
         <!-- JS -->
         <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>
     
     </html>';
        setcookie('usernamee', null, time() + 3600);
    } else {
        setcookie('usernamee', null, time() + 3600);
        die('<!DOCTYPE html>
     <html lang="tr">
 
     <head>
         <title>Liveable Roleplay - Ödeme Başarısız</title>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1">
     
         <!-- Favicon -->
         <link rel="shortcut icon" href="' . $favicon . '">
     
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
         
         <style>
         .vh-100{
             height: 100vh;
             min-height:100vh;
         }
         </style>
     </head>
     
     <body>
         <div class="container">
             <div class="row vh-100 align-items-center">
                 <div class="col text-center">
                     <div class="content">
                         <h1>Database error!</h1>
                         <small>Your balance could not be updated due to database error! Contact the administrator!</small><br>
                         <a class="btn btn-outline-primary mt-4" href=""> Go back to the pay page</a>
                     </div>
                 </div>
             </div>
         </div>
     
         <!-- JS -->
         <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>
     
     </html>');
    }
}
