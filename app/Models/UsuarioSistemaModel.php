<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class UsuarioSistemaModel extends \Com\Daw2\Core\BaseModel {

    private const SELECT_FROM = "SELECT us.*, ar.nombre_rol, ai.nombre_idioma FROM usuario_sistema us LEFT JOIN aux_rol ar ON ar.id_rol = us.id_rol LEFT JOIN aux_idiomas ai ON ai.id_idioma = us.id_idioma ORDER BY us.nombre";

    function getAll(): array {
        return $this->pdo->query(self::SELECT_FROM)->fetchAll();
    }

    function getEmail(string $email): ?array {
        $query = "select * from usuario_sistema where email=?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return $stmt->fetch();
        } else {
            return null;
        }
    }

    function usuarioAdd(array $datos): bool {
        $query = "insert into usuario_sistema (id_rol, email, pass, nombre, id_idioma) values (:id_rol, :email, "
                . ":pass, :nombre, :id_idioma)";
        $vars = [
            'id_rol' => $datos['id_rol'],
            'email' => $datos['email'],
            'pass' => password_hash($datos['pass'], PASSWORD_DEFAULT),
            'nombre' => $datos['nombre'],
            'id_idioma' => $datos['idioma']
        ];
        $stmt = $this->pdo->prepare($query);
        if($stmt->execute($vars)){
            return true;
        }else{
            return false;
        }
        
    }

}
