<?php

class Helper {
    public static function createDB($db){
        $db-> exec("CREATE TABLE IF NOT EXISTS pastes(
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            pasteKey TEXT NOT NULL ,
            content TEXT NOT NULL,
            createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

    }

    public static function cleanupDB($db, $expirationInMin) {
        $query = $db->prepare("DELETE FROM pastes WHERE createdAt <= datetime('now', '-$expirationInMin minute')");
        $query->execute();
    }

    public static function pullWord($file) {
        $f_contents = file($file); 
        $line = $f_contents[rand(0, count($f_contents) - 1)];    
        return trim($line);
    }

    public static function checkKey($db, $pasteKey) {
        $query = $db->prepare("SELECT * FROM pastes WHERE pasteKey = :pasteKey");
        $query->bindValue(":pasteKey", implode("-",array_slice(explode("-",$pasteKey),1)));
        $result = $query->execute();
        return $result->fetchArray() > 0;
    }

    public static function generateKey($db) {
        do {
            $pasteKey = Helper::pullWord(__DIR__.'/data/word_1.txt') . "-" .Helper::pullWord(__DIR__.'/data/word_1.txt') . "-" . Helper::pullWord(__DIR__.'/data/word_2.txt');
        } while(Self::checkKey($db, $pasteKey));

        return $pasteKey;
    }

    public static function getPaste($db, $pasteKey) {

        // Get
        $query = $db->prepare("SELECT * FROM pastes WHERE pasteKey = :pasteKey");
        $query->bindValue(":pasteKey", implode("-",array_slice(explode("-",$pasteKey),1)));
        $result = $query->execute();
        $paste = $result->fetchArray();
        $paste['content'] = Self::decrypt($pasteKey,$paste['content']);

        // Delete
        $query = $db->prepare("DELETE FROM pastes WHERE pasteKey = :pasteKey");
        $query->bindValue(":pasteKey", implode("-",array_slice(explode("-",$pasteKey),1)));
        $query->execute();

        return $paste;
    }


    public static function encrypt($key, $content) {
        $password = substr(hash('sha256', $key, true), 0, 32);
        $ivlen = openssl_cipher_iv_length(CRYPT_CIPHER);
        $iv = openssl_random_pseudo_bytes($ivlen);
        return base64_encode($iv) . ":" . base64_encode(openssl_encrypt($content, CRYPT_CIPHER, $password, OPENSSL_RAW_DATA, $iv));
        
    }

    public static function decrypt($key, $content) {
        $iv = base64_decode(explode(":",$content)[0]);
        $contentReal = implode(":", array_slice(explode(":",$content),1));
        $password = substr(hash('sha256', $key, true), 0, 32);
        return openssl_decrypt(base64_decode($contentReal), CRYPT_CIPHER, $password, OPENSSL_RAW_DATA, $iv);
    }

    public static function addPaste($db, $pasteKey, $content) {
        $query = $db->prepare("INSERT INTO pastes (pasteKey, content) VALUES (:pasteKey, :content)");
        $query->bindValue(":pasteKey", implode("-",array_slice(explode("-",$pasteKey),1)));
        $query->bindValue(":content", Self::encrypt($pasteKey, $content));
        $query->execute();
    }
}