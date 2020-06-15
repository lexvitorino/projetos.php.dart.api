<?php
namespace Controllers;

use \Core\Controller;
use \Models\ContasReceber;

class ContasReceberController extends Controller
{

	public function index()
	{
		$array = array('error' => '');

		$request = $this->getRequest();
		extract($request);

		if ($method == 'GET') {
			if ($this->isLogged()) {
				$contasReceber = new ContasReceber($this->getLogged()->id);
				$contasReceber->getAll();
				if ($contasReceber->getResult()) {
					$array['data'] = $contasReceber->getResult();
				} else {
					$array['error'] = $contasReceber->getError();
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
				$contasReceber = new ContasReceber($this->getLogged()->id);
				$contasReceber->getById($id);
				if ($contasReceber->getResult()) {
					$array['data'] = $contasReceber->getResult();
				} else {
					$array['error'] = $contasReceber->getError();
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
				$contasReceber = new ContasReceber($this->getLogged()->id);
				$contasReceber->create($data);
				if ($contasReceber->getResult()) {
					$array['data'] = $contasReceber->getResult();
				} else {
					$array['error'] = $contasReceber->getError();
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
				$contasReceber = new ContasReceber($this->getLogged()->id);
				$contasReceber->update($id, $data);
				if ($contasReceber->getResult()) {
					$array['data'] = $contasReceber->getResult();
				} else {
					$array['error'] = $contasReceber->getError();
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
				$contasReceber = new ContasReceber($this->getLogged()->id);
				$contasReceber->delete($id);
				if ($contasReceber->getResult()) {
					$array['data'] = $contasReceber->getResult();
				} else {
					$array['error'] = $contasReceber->getError();
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
