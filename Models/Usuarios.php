<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class Usuarios extends Model
{

	private $id_logged;
	private $id_usuario;
	private $result;
	private $error;

	public function __construct($id_logged) {
		parent::__construct();
		$this->id_logged = $id_logged;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT id, nome, usuario, email, avatar FROM usuarios";
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT id, nome, usuario, email, avatar FROM usuarios WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
			$this->id_usuario = $array['id'];

			if (!empty($array['avatar'])) {
				$array['avatar'] = BASE_URL . 'media/avatar/' . $array['avatar'];
			} else {
				$array['avatar'] = BASE_URL . 'media/avatar/default.png';
			}
		}

		$this->result = $array;
	}

	public function create($data)
	{
		if (empty($data)) {
			return false;
		}

		if (!$this->checkData($data)) {
			return;
		}

		$hash = password_hash(Check::IsNull($data, 'senha'), PASSWORD_DEFAULT);

		$sql = "INSERT INTO usuarios 
			     	   (nome, email, usuario, senha, avatar, criadoPor, criadoEm) 
				VALUES (:nome, :email, :usuario, :senha, :avatar, :criadoPor, NOW())";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':nome', Check::IsNull($data, 'nome'));
		$sql->bindValue(':usuario',  Check::IsNull($data, 'usuario'));
		$sql->bindValue(':email',  Check::IsNull($data, 'email'));
		$sql->bindValue(':senha', $hash);
		$sql->bindValue(':avatar',  Check::IsNull($data, 'avatar'));
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_usuario = $this->db->lastInsertId();
		$this->getById($this->id_usuario);
	}

	public function update($id, $data)
	{
		if (empty($data)) {
			return false;
		}

		if (!$this->checkData($data, $id)) {
			return;
		}

		extract($data);
		if ($this->exist($id)) {
			$hash = password_hash(Check::IsNull($data, 'senha'), PASSWORD_DEFAULT);

			$sql = "UPDATE usuarios 
				    SET    nome = :nome, 
					       email = :email, 
						   usuario = :usuario, 
						   senha = :senha,
						   avatar = :avatar,
						   alteradoPor = :alteradoPor,
						   alteradoEm = NOW()
					WHERE  id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':nome', Check::IsNull($data, 'nome'));
			$sql->bindValue(':usuario', Check::IsNull($data, 'usuario'));
			$sql->bindValue(':email',  Check::IsNull($data, 'email'));
			$sql->bindValue(':senha', $hash);
			$sql->bindValue(':avatar',  Check::IsNull($data, 'avatar'));
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_usuario = $id;
			$this->getById($this->id_usuario);
		} else {
			$this->error = 'Registro não encontrado';
		}
	}

	public function delete($id)
	{
		if (empty($id)) {
			return false;
		}

		if ($this->exist($id)) {
			$sql = "DELETE FROM usuarios 
					WHERE  id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			$this->result = true;
		} else {
			$this->error = 'Registro não excluído';
		}
	}

	public function checkCredentials($usuario, $senha)
	{
		$sql = "SELECT id, senha FROM usuarios WHERE usuario = :usuario";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':usuario', $usuario);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$info = $sql->fetch();

			if (password_verify($senha, $info['senha'])) {
				$this->getById($info['id']);

				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getId()
	{
		return $this->id_usuario;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function getError()
	{
		return $this->error;
	}

	/* METODOS PRIVADOS */

	private function exist($id)
	{
		$sql = "SELECT id FROM usuarios WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function nomeExist($nome, $id = 0)
	{
		$sql = "SELECT id FROM usuarios WHERE nome = :nome and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':nome', $nome);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function emailExist($email, $id = 0)
	{
		$sql = "SELECT id FROM usuarios WHERE email = :email and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':email', $email);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function usuarioExist($usuario, $id = 0)
	{
		$sql = "SELECT id FROM usuarios WHERE usuario = :usuario and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':usuario', $usuario);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function checkData($data, $id = 0)
	{
		$errors = array();

		if (Check::IsNull($data, 'nome') == '') {
			$errors[] = 'Nome não pode ser vazio';
		} else if ($this->nomeExist($data['nome'], $id)) {
			$errors[] = 'Nome já cadastrado em nossa base';
		}

		if (Check::IsNull($data, 'email') == '') {
			$errors[] = 'E-mail não pode ser vazio';
		} else if ($this->emailExist($data['email'], $id)) {
			$errors[] = 'E-mail já cadastrado em nossa base';
		}

		if (Check::IsNull($data, 'usuario') == '') {
			$errors[] = 'Usuário não pode ser vazio';
		} else if ($this->usuarioExist($data['usuario'], $id)) {
			$errors[] = 'Usuario já cadastrado em nossa base';
		}

		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
