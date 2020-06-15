<?php
namespace Controllers;

use \Core\Controller;
use \Models\Usuarios;

class AuthController extends Controller
{

    public function index()
    { }

    public function login()
    {
        $array = array('error' => '');

        $method = $this->getMethod();
        $data = $this->getRequestData();

        if ($method == 'POST') {
            if (!empty($data['usuario']) && !empty($data['senha'])) {
                $usuarios = new Usuarios(0);
                if ($usuarios->checkCredentials($data['usuario'], $data['senha'])) {
                    $array['jwt'] = $this->createJwt($usuarios->getResult());
                } else {
                    $array['error'] = 'Acesso negado:';
                }
            } else {
                $array['error'] = 'Usuário e/ou Senha não preenchido.';
            }
        } else {
            $array['error'] = 'Método de requisição incompatível';
        }

        $this->returnJson($array);
    }
}
