<?php
namespace Controllers;

use \Core\Controller;
use \Models\Pedidos;

class PedidosController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$pedidos = new Pedidos($this->getLogged()->id);
				$pedidos->getAll();
				if ($pedidos->getResult()) {
					$array['data'] = $pedidos->getResult();
				} else {
					$array['error'] = $pedidos->getError();
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
				$pedidos = new Pedidos($this->getLogged()->id);
				$pedidos->getById($id);
				if ($pedidos->getResult()) {
					$array['data'] = $pedidos->getResult();
				} else {
					$array['error'] = $pedidos->getError();
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
				$pedidos = new Pedidos($this->getLogged()->id);
				$pedidos->create($data);
				if ($pedidos->getResult()) {
					$array['data'] = $pedidos->getResult();
				} else {
					$array['error'] = $pedidos->getError();
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
				$pedidos = new Pedidos($this->getLogged()->id);
				$pedidos->update($id, $data);
				if ($pedidos->getResult()) {
					$array['data'] = $pedidos->getResult();
				} else {
					$array['error'] = $pedidos->getError();
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
				$pedidos = new Pedidos($this->getLogged()->id);
				$pedidos->delete($id);
				if ($pedidos->getResult()) {
					$array['data'] = $pedidos->getResult();
				} else {
					$array['error'] = $pedidos->getError();
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
