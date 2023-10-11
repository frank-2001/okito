<?php
    print_r("\nCration of project file by - FrankM Architecture project\n");
    
    // Complete by your db information
    $host='localhost';
    $dbname='okito';
    $user='root';
    $pass='';
    echo !file_exists("classes");
    // From here Don't change nothing
    if (!is_dir("classes")) {
        mkdir("classes");
        $code='
class bdd{
    var $host="'.$host.'";
    var $dbname="'.$dbname.'";
    var $user="'.$user.'";
    var $pass="'.$pass.'";
    function connect(){
        try { 
            $bdd = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->user, $this->pass);
            return $bdd;
        }
        catch   (PDOException $pe){
            die ("I cannot connect to the database " . $pe->getMessage());
            return null;
        }
    }
    function listTable(){
        $sql="SHOW TABLES";
        $requete= $this->connect()->prepare($sql);
        $requete->execute();
        return $requete->fetchAll();
    }
}
$bdd=new bdd();

class tables extends bdd{
    var $table;
    function all(){
        $sql="SELECT * FROM ".$this->table;
        $requete= $this->connect()->prepare($sql);
        $requete->execute();
        return $requete->fetchAll();
    }

    function new($data){
        $keys=implode(",",array_keys($data));
        $values=array_values($data);
        $sign="";
        for ($i=0; $i < count($data)-1 ; $i++) { 
            $sign=$sign."?,";
        }
        $sign=$sign."?";
        $sql = "INSERT INTO ".$this->table." (".$keys.") VALUES (".$sign.")";
        $exec=$this->connect()->prepare($sql)->execute($values);
        return ["message"=>$this->table." Enregistre avec succes"];
    }
    function byId($id){
        $sql="SELECT * FROM ".$this->table." where id=:id";
        $requete= $this->connect()->prepare($sql);
        $requete->bindParam(":id",$id);
        $requete->execute();
        return $requete->fetchAll();
    }
    function update($id,$data){
        $keys=array_keys($data);
        $values=array_values($data);
        $struc="";
        foreach ($keys as $key) {
            $struc=$struc."".$key."=:".$key.",";
        }
        
        $struc=substr($struc,0,-1);
        if (count($this->byId($id))>0) {
            $sql="UPDATE ".$this->table." set ".$struc." where id=:id";
            $requete= $this->connect()->prepare($sql);
            $requete->bindParam(":id",$id);
            foreach ($data as $key=>$value) {
                $requete->bindParam(":".$key,$data[$key]);
            }
            $requete->execute();
            return $id." Mis en jour avec succes";
        } else {
            return $id." Non valide";
        }      
    }
    function delete($id){
        if (count($this->byId($id))>0) {
            $sql="DELETE FROM ".$this->table." where id=:id";
            $requete=$this->connect()->prepare($sql);
            $requete->bindParam(":id",$id);
            $requete->execute();
            return "ID : ".$id." deleted successfully";
        } else {
            return $id." Non valide";
        }      
    }
    function search($data){
        $key=array_keys($data)[0];
        $value=$data[$key];
        $sql="SELECT * FROM ".$this->table." where ".$key." LIKE :element";
        $requete= $this->connect()->prepare($sql);
        $requete->bindParam(":element",$value);
        $requete->execute();
        return $requete->fetchAll();
    }
}
        '
        ;
        file_put_contents("classes/bdd.class.php",'<?php'.$code.'?>');
        echo "classes/bdd.php : connexion to your database \n";
        echo "classes.php : collect all classes from classes directory\n";
        file_put_contents("classes.php","<?php\nrequire 'classes/bdd.class.php';\n");
    }

    require "classes/bdd.class.php";

    // Creates classes by the bdd
    echo "We found ".count($bdd->listTable())." tables in your database\n";
    foreach ($bdd->listTable() as $key) {
        $key=$key["0"];
        if (!file_exists("classes/".$key.".class.php")) {
            
            $code='
class '.$key.' extends tables{        
    public function __construct(){
        $bdd=new tables();
        $this->table="'.$key.'";
    }
}
$'.$key.'=new '.$key.'();
if (isset($_GET[$'.$key.'->table."-all"])) {
    $output=$'.$key.'->All();
}
if (isset($_GET[$'.$key.'->table."-new"])) {
    $output=$'.$key.'->new($_POST);
}
if (isset($_GET[$'.$key.'->table."-byId"])) {
    $output=$'.$key.'->new($_GET[$'.$key.'->table."-byId"]);
}
if (isset($_GET[$'.$key.'->table."-update"])) {
    $output=$'.$key.'->update($_GET[$'.$key.'->table."-update"],$_POST);
}
if (isset($_GET[$'.$key.'->table."-delete"])) {
    $output=$'.$key.'->delete($_GET[$'.$key.'->table."-delete"]);
}
if (isset($_GET[$'.$key.'->table."-search"])) {
    $output=$'.$key.'->search($_POST);
}

            ';
            file_put_contents("classes/".$key.".class.php","<?php\n".$code);
            file_put_contents("classes.php",file_get_contents("classes.php")."\trequire 'classes/".$key.".class.php';\n");
            echo "classes/".$key.".class.php : Generated\n";
        }
        
    }
    if (!file_exists("index.php")) {
        $code='
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: application/json");
            $output=array("message"=>"Aucune requete");
            require "classes.php";
            echo json_encode($output);
        ';
        file_put_contents("index.php","<?php\n\t".$code);
        echo "index.php : generated";
    }
    
    // header()

    
    
    