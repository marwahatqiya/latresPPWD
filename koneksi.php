<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'lab';

$konek = new mysqli($hostname, $username, $password, $database);
    if($konek->connect_error){
        die('Gagal menyambungkan:' . $konek->connect_error);
}
?>