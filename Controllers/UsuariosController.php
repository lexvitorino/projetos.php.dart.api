<?php
namespace Controllers;

use \Core\Controller;
use \Models\Usuarios;

class UsuariosController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$usuarios = new Usuarios($this->getLogged()->id);
				$usuarios->getAll();
				if ($usuarios->getResult()) {
					$array['data'] = $usuarios->getResult();
				} else {
					$array['error'] = $usuarios->getError();
				}
			} else {
				$array['error'] = 'Acesso negado';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function view($id)
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$usuarios = new Usuarios($this->getLogged()->id);
				$usuarios->getById($id);
				if ($usuarios->getResult()) {
					$array['data'] = $usuarios->getResult();
				} else {
					$array['error'] = $usuarios->getError();
				}
			} else {
				$array['error'] = 'Acesso negado';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function create()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'POST') {
			if ($this->isLogged()) {
				$usuarios = new Usuarios($this->getLogged()->id);
				$usuarios->create($data);
				if ($usuarios->getResult()) {
					$array['data'] = $usuarios->getResult();
				} else {
					$array['error'] = $usuarios->getError();
				}
			} else {
				$array['error'] = 'Acesso negado';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function update($id)
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'PUT') {
			if ($this->isLogged()) {
				$usuarios = new Usuarios($this->getLogged()->id);
				$usuarios->update($id, $data);
				if ($usuarios->getResult()) {
					$array['data'] = $usuarios->getResult();
				} else {
					$array['error'] = $usuarios->getError();
				}
			} else {
				$array['error'] = 'Acesso negado';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}

	public function delete($id)
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'DELETE') {
			if ($this->isLogged()) {
				$usuarios = new Usuarios($this->getLogged()->id);
				$usuarios->delete($id);
				if ($usuarios->getResult()) {
					$array['data'] = $usuarios->getResult();
				} else {
					$array['error'] = $usuarios->getError();
				}
			} else {
				$array['error'] = 'Acesso negado';
			}
		} else {
			$array['error'] = 'Método de requisição incompatível';
		}

		$this->returnJson($array);
	}
}
