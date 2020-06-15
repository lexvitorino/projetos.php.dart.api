<?php
namespace Controllers;

use \Core\Controller;
use \Models\ContasPagar;

class ContasPagarController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$contasPagar = new ContasPagar($this->getLogged()->id);
				$contasPagar->getAll();
				if ($contasPagar->getResult()) {
					$array['data'] = $contasPagar->getResult();
				} else {
					$array['error'] = $contasPagar->getError();
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
				$contasPagar = new ContasPagar($this->getLogged()->id);
				$contasPagar->getById($id);
				if ($contasPagar->getResult()) {
					$array['data'] = $contasPagar->getResult();
				} else {
					$array['error'] = $contasPagar->getError();
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
				$contasPagar = new ContasPagar($this->getLogged()->id);
				$contasPagar->create($data);
				if ($contasPagar->getResult()) {
					$array['data'] = $contasPagar->getResult();
				} else {
					$array['error'] = $contasPagar->getError();
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
				$contasPagar = new ContasPagar($this->getLogged()->id);
				$contasPagar->update($id, $data);
				if ($contasPagar->getResult()) {
					$array['data'] = $contasPagar->getResult();
				} else {
					$array['error'] = $contasPagar->getError();
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
				$contasPagar = new ContasPagar($this->getLogged()->id);
				$contasPagar->delete($id);
				if ($contasPagar->getResult()) {
					$array['data'] = $contasPagar->getResult();
				} else {
					$array['error'] = $contasPagar->getError();
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
