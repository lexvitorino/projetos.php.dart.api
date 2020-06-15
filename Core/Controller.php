<?php
namespace Core;

use \Models\Jwt;

class Controller
{

	public function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public function getRequestData()
	{

		switch ($this->getMethod()) {
			case 'GET':
				return $_GET;
				break;
			case 'DELETE':
				parse_str(file_get_contents('php://input'), $data);
				return (array)$data;
				break;
			case 'PUT':
			case 'POST':
				$data = json_decode(file_get_contents('php://input'));

				if (is_null($data)) {
					$data = $_POST;
				}

				return (array)$data;
				break;
		}
	}

	public function returnJson($array)
	{
		header("Content-Type: application/json");
		echo json_encode($array);
		exit;
	}

	public function getRequest()
	{
		return array(
			"method" => $this->getMethod(),
			"data" => $this->getRequestData()
		);
	}

	public function createJwt($data)
	{
		$jwt = new Jwt();
		return $jwt->create(array(
			'id' => $data["id"],
			'nome' => $data["nome"],
			'usuario' => $data["usuario"],
			'avatar' => $data["avatar"],
			'email' => $data["email"],
			'exp' => date("Y-m-d H:i:s", strtotime('+3 hours'))
		));
	}

	public function isLogged()
	{
		$info = $this->getLogged();

		if (isset($info->id)) {
			return true;
		} else {
			return false;
		}
	}

	public function getLogged() 
	{
		$token = null;
		$headers = apache_request_headers();
		if (isset($headers['Authorization'])) {
			$matches = array();
			preg_match('/Bearer\s((.*)\.(.*)\.(.*))/', $headers['Authorization'], $matches);
			if (isset($matches[1])) {
				$token = $matches[1];
			}
		}

		if (empty($token)) {
			return false;
		}

		$jwt = new Jwt();
		return $jwt->validate($token);
	}
}
