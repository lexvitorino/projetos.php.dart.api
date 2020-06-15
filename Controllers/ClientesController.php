<?php
namespace Controllers;

use \Core\Controller;
use \Models\Clientes;

class ClientesController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$clientes = new Clientes($this->getLogged()->id);
				$clientes->getAll();
				if ($clientes->getResult()) {
					$array['data'] = $clientes->getResult();
				} else {
					$array['error'] = $clientes->getError();
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
				$clientes = new Clientes($this->getLogged()->id);
				$clientes->getById($id);
				if ($clientes->getResult()) {
					$array['data'] = $clientes->getResult();
				} else {
					$array['error'] = $clientes->getError();
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
				$clientes = new Clientes($this->getLogged()->id);
				$clientes->create($data);
				if ($clientes->getResult()) {
					$array['data'] = $clientes->getResult();
				} else {
					$array['error'] = $clientes->getError();
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
				$clientes = new Clientes($this->getLogged()->id);
				$clientes->update($id, $data);
				if ($clientes->getResult()) {
					$array['data'] = $clientes->getResult();
				} else {
					$array['error'] = $clientes->getError();
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
				$clientes = new Clientes($this->getLogged()->id);
				$clientes->delete($id);
				if ($clientes->getResult()) {
					$array['data'] = $clientes->getResult();
				} else {
					$array['error'] = $clientes->getError();
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
