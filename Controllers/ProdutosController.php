<?php
namespace Controllers;

use \Core\Controller;
use \Models\Produtos;

class ProdutosController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$produtos = new Produtos($this->getLogged()->id);
				$produtos->getAll();
				if ($produtos->getResult()) {
					$array['data'] = $produtos->getResult();
				} else {
					$array['error'] = $produtos->getError();
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
				$produtos = new Produtos($this->getLogged()->id);
				$produtos->getById($id);
				if ($produtos->getResult()) {
					$array['data'] = $produtos->getResult();
				} else {
					$array['error'] = $produtos->getError();
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
				$produtos = new Produtos($this->getLogged()->id);
				$produtos->create($data);
				if ($produtos->getResult()) {
					$array['data'] = $produtos->getResult();
				} else {
					$array['error'] = $produtos->getError();
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
				$produtos = new Produtos($this->getLogged()->id);
				$produtos->update($id, $data);
				if ($produtos->getResult()) {
					$array['data'] = $produtos->getResult();
				} else {
					$array['error'] = $produtos->getError();
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
				$produtos = new Produtos($this->getLogged()->id);
				$produtos->delete($id);
				if ($produtos->getResult()) {
					$array['data'] = $produtos->getResult();
				} else {
					$array['error'] = $produtos->getError();
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
