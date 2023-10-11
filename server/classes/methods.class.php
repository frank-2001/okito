<?php
// Methods json file
class json_file{
    function get_data($file){
        if(file_exists($file)){
            $jsonString = file_get_contents($file);
            $jsonData = json_decode($jsonString, true);
        }else{
            $jsonData=NULL;
        }
        
       
        // if($jsonData==NULL){
        //     $jsonData=[];
        // }
        return $jsonData;
    }
    function search($array,$_key,$value){
        foreach ($array as $key) {
            if ($key[$_key]==$value) {
                return $key;
            }
        }
        return NULL;
    }
    
    function write_json($filename,$data){
        $fp = fopen($filename, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    } 
}

// compress and move files
class image{
    var $source;
    public function __construct($url){
        $this->source=$url;
    }
    function compressImage($destination,$quality){
        #Get image info
        $imgInfo=getimagesize($this->source);
        $mime=$imgInfo['mime'];
        #Create a new image from file
        switch ($mime) {
            case 'image/jpeg':
                $image =imagecreatefromjpeg($this->source);
                break;
            case 'image/png':
                $image =imagecreatefrompng($this->source);
                break;
            case 'image/gif':
                $image =imagecreatefromgif($this->source);
                break;
            
            default:
            $image =imagecreatefromjpeg($this->source);
                break;
        }	
        #Save image
        imagejpeg($image,$destination,$quality);
        #return Compressed imag
        return $destination;
    }
}

class mytime{
    function timeDo($stamp){// Retour le temps qu'un article a fait depuis sa publication
        $tm=time();
        if (intval($stamp)>intval($tm)) {
            $trueTm=intval($stamp)-intval($tm);
            $text="Dans ";
        }else{
            $trueTm=intval($tm)-intval($stamp);
            $text="Il ya ";
        }
        if ($trueTm>=31104000) {
            $time=$trueTm/31104000;
            $time=intval($time).' ans';
        }
        elseif($trueTm>=2592000){
            $time=$trueTm/2592000;
            $time=intval($time).' mois';
        }
        elseif($trueTm>=604800){
            $time=$trueTm/604800;
            $time=intval($time).' semaines';
        }
        elseif($trueTm>=86400){
            $time=$trueTm/86400;
            $time=intval($time).' jours';
        }
        elseif($trueTm>=3600){
            $time=$trueTm/3600;
            $time=intval($time).' h';
        }
        elseif($trueTm>=60){
            $time=$trueTm/60;
            $time=intval($time).' min';
        }
        elseif($trueTm<60 && $trueTm>0){
            $time=intval($trueTm).' sec';
        }
        else{
            $time="Now";
        }
        return $text.' '.$time;
    }
}

class _string{
    function random($len){
        $cons="rtypsdfghjklzcvbnm";
        $voyel="aeuio";
        $name="";
        for ($i=0; $i < $len; $i++) { 
            if($i%2==0){
                $name=$name."".$cons[rand(0,17)];
            }else{
                $name=$name."".$voyel[rand(0,4)];
            }
        }
        return $name;
    }
}

// Importation images
if (isset($_GET["import_image"])) {
    // Repertoire images
    $path="images/".$_GET["dir"]."/";
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
    // Afficher le nom de l'image
    $output=$name;
}


