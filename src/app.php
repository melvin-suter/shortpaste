<?php

require(__DIR__.'/helper.php');

// Get Variables
define('SITE_TITLE', getenv('SITE_TITLE') ? getenv('SITE_TITLE') : 'ShortPaste');
define('PASTE_EXPIRATION', getenv('PASTE_EXPIRATION') ? getenv('PASTE_EXPIRATION') : 1440);
define('URL_PRETTY', getenv('URL_PRETTY') ? strtolower(getenv('URL_PRETTY')) == "true" : true);
define('CRYPT_CIPHER', getenv('CRYPT_CIPHER') ? strtolower(getenv('CRYPT_CIPHER')) : "aes128");

// Start SQLite
$db = new SQLite3(__DIR__.'/data/data.db');

// create DB
Helper::createDB($db);
Helper::cleanupDB($db,PASTE_EXPIRATION);




// Handle Forms
if(isset($_POST['add-paste'])) {

    $pasteKey = Helper::generateKey($db);

    Helper::addPaste($db, $pasteKey, $_POST['content']);
    if(URL_PRETTY){
        header("Location: /l/".$pasteKey);
    } else {
        header("Location: /?link=".$pasteKey);
    }
}
