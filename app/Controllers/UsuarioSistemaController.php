<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

class UsuarioSistemaController extends \Com\Daw2\Core\BaseController {

    function mostrarTodos() {
        $data = [];
        $data['titulo'] = 'Todos los usuarios';
        $data['seccion'] = '/usuarios-sistema';

        $modelo = new \Com\Daw2\Models\UsuarioSistemaModel();
        $data['usuarios'] = $modelo->getAll();

        $this->view->showViews(array('templates/header.view.php', 'usuario_sistema.view.php', 'templates/footer.view.php'), $data);
    }

    /* para mostrar el formulario de inserción */

    function mostrarAdd() {
        $data = [];
        $data['titulo'] = 'Insertar Usuarios';
        $data['seccion'] = '/usuarios-sistema/add';
        $data['tituloDiv'] = 'Añadir Usuario';

        $model = new \Com\Daw2\Models\AuxRolModel();
        $data['roles'] = $model->getAll();
        $modelIdioma = new \Com\Daw2\Models\AuxIdiomaModel();
        $data['idiomas'] = $modelIdioma->getAll();
        $this->view->showViews(array('templates/header.view.php', 'edit.usuario_sistema.view.php', 'templates/footer.view.php'), $data);
    }

    function procesarAdd() {

        $errores = $this->chekForm($_POST);
        if (count($errores) > 0) {
            $data = [];
            $data['titulo'] = 'Insertar Usuarios';
            $data['seccion'] = '/usuarios-sistema/add';
            $data['tituloDiv'] = 'Añadir Usuario';
            $modelRol = new \Com\Daw2\Models\AuxRolModel();
            $data['roles'] = $modelRol->getAll();
            $modelIdioma = new \Com\Daw2\Models\AuxIdiomaModel();
            $data['idiomas'] = $modelIdioma->getAll();
            $data['errores'] = $errores;
            $this->view->showViews(array('templates/header.view.php', 'edit.usuario_sistema.view.php', 'templates/footer.view.php'), $data);
        } else {
            $model = new \Com\Daw2\Models\UsuarioSistemaModel();
            if ($model->usuarioAdd($_POST)) {
                header('location:/usuarios-sistema');
            } else {
                $data = [];
                $data['titulo'] = 'Insertar Usuarios';
                $data['seccion'] = '/usuarios-sistema/add';
                $data['tituloDiv'] = 'Añadir Usuario';
                $modelRol = new \Com\Daw2\Models\AuxRolModel();
                $data['roles'] = $modelRol->getAll();
                $modelIdioma = new \Com\Daw2\Models\AuxIdiomaModel();
                $data['idiomas'] = $modelIdioma->getAll();
                $data['errores'] = "No se ha podido insertar usuario";
                $this->view->showViews(array('templates/header.view.php', 'edit.usuario_sistema.view.php', 'templates/footer.view.php'), $data);
            }
            
        }
    }

    private function chekForm(array $datos): array {
        $errores = [];
        if (empty($datos['nombre'])) {
            $errores['nombre'] = "Campo nombre no puede estar vacio";
        } else if (!preg_match('/^[a-zA-Z\s_]{4,}$/', $datos['nombre'])) {
            $errores['nombre'] = "El nombre no cumple con formato válido";
        }

        if (empty($datos['email'])) {
            $errores['email'] = "El campo email es obligatorio";
        } else if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El email no cumple con el formato correcto";
        } else {
            $model = new \Com\Daw2\Models\UsuarioSistemaModel();
            if (!is_null($model->getEmail($datos['email']))) {
                $errores['email'] = "El email introducido ya existe en el base de datos";
            }
        }

        if (empty($datos['pass'])) {
            $errores['pass'] = "Campo contraseña no puede estar vacio";
        } else if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $datos['pass'])) {
            $errores['pass'] = "Formato de la contraseña incorrecto";
        } else if ($datos['pass'] != $datos['pass2']) {
            $errores['pass'] = "Las contraseñas deben ser iguales";
        }

        if (empty($datos['id_rol'])) {
            $errores['id_rol'] = "El rol no puedo estar vacio";
        } else {
            $modelRol = new \Com\Daw2\Models\AuxRolModel();
            if (is_null($modelRol->loadRol( (int) $datos['id_rol'])) || !filter_var( (int) $datos['id_rol'], FILTER_VALIDATE_INT)) {
                $errores['id_rol'] = "El rol introducido no es válido";
            }
        }

        if (empty($datos['idioma'])) {
            $errores['idioma'] = "El campo idioma es obligatorio";
        } else {
            $modelIdiomas = new \Com\Daw2\Models\AuxIdiomaModel();
            if (is_null($modelIdiomas->getIdioma( (int) $datos['idioma']))|| !filter_var((int) $datos['idioma'], FILTER_VALIDATE_INT)) {
                $errores['idioma'] = "El idioma introducido no es válido";
            }
        }
        
        return $errores;
    }

}
