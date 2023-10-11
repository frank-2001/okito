<?php

class houses extends tables{        
    public function __construct(){
        $bdd=new tables();
        $this->table="houses";
    }
}
$houses=new houses();
if (isset($_GET[$houses->table."-all"])) {
    $output=$houses->All();
}
if (isset($_GET[$houses->table."-new"])) {
    $path="images/";
    // Conteneur image
    $image=new image($_FILES['file']['tmp_name']);
    // Nom de l'image
    $name="Laloca".time().".".explode(".",$_FILES['file']['name'])[1];
    // Verification si l'adresse de l'image existe si non cree la
    if (file_exists($path)==FALSE) {
        mkdir($path);
    }
    // Compression et deplacement de l'image vers l'adresse indiquee dans la variable path
    $image->compressImage($path.$name,40);
    $_POST["image"]=$name;
    $output=$houses->new($_POST);
}
if (isset($_GET[$houses->table."-byId"])) {
    $output=$houses->new($_GET[$houses->table."-byId"]);
}
if (isset($_GET[$houses->table."-update"])) {
    $output=$houses->update($_GET[$houses->table."-update"],$_POST);
}
if (isset($_GET[$houses->table."-delete"])) {
    $output=$houses->delete($_GET[$houses->table."-delete"]);
}
if (isset($_GET[$houses->table."-search"])) {
    $output=$houses->search($_POST);
}

            