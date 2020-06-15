<?php
namespace Controllers;

use \Core\Controller;
use \Models\Fornecedores;

class FornecedoresController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$fornecedores = new Fornecedores($this->getLogged()->id);
				$fornecedores->getAll();
				if ($fornecedores->getResult()) {
					$array['data'] = $fornecedores->getResult();
				} else {
					$array['error'] = $fornecedores->getError();
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
				$fornecedores = new Fornecedores($this->getLogged()->id);
				$fornecedores->getById($id);
				if ($fornecedores->getResult()) {
					$array['data'] = $fornecedores->getResult();
				} else {
					$array['error'] = $fornecedores->getError();
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
				$fornecedores = new Fornecedores($this->getLogged()->id);
				$fornecedores->create($data);
				if ($fornecedores->getResult()) {
					$array['data'] = $fornecedores->getResult();
				} else {
					$array['error'] = $fornecedores->getError();
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
				$fornecedores = new Fornecedores($this->getLogged()->id);
				$fornecedores->update($id, $data);
				if ($fornecedores->getResult()) {
					$array['data'] = $fornecedores->getResult();
				} else {
					$array['error'] = $fornecedores->getError();
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
				$fornecedores = new Fornecedores($this->getLogged()->id);
				$fornecedores->delete($id);
				if ($fornecedores->getResult()) {
					$array['data'] = $fornecedores->getResult();
				} else {
					$array['error'] = $fornecedores->getError();
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
