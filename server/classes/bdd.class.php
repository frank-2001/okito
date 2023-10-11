<?php
class bdd{
    var $host="localhost";
    var $dbname="u898308728_okito";
    var $user="u898308728_okito";
    var $pass="Code@2001";
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
        ?>