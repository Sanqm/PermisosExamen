<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Com\Daw2\Models;

/**
 * Description of AuxIdiomaModel
 *
 * @author Sandra Queimadelos 
 */
class AuxIdiomaModel extends \Com\Daw2\Core\BaseModel {
    function getAll(){
        $query = "select * from aux_idiomas";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }
    
    function getIdioma(int $id_idioma):?array{
        $query = "select * from aux_idiomas where id_idioma=?";
        $stmt =$this->pdo->prepare($query);
    $stmt->execute([$id_idioma]);
        if( $row= $stmt->fetch()){
            return $row;
        }else{
            return null;
        }
        
    }
}
