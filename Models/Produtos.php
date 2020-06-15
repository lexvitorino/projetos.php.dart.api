<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class Produtos extends Model
{

	private $id_logged;
	private $id_produto;
	private $result;
	private $error;

	public function __construct($id_logged) {
		parent::__construct();
		$this->id_logged = $id_logged;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT * FROM produtos";
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT * FROM produtos WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
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

		$sql = "INSERT INTO produtos 
					   (descricao, tipo, valor, criadoPor, criadoEm) 
				VALUES (:descricao, :tipo, :valor, :criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':descricao', Check::IsNull($data, 'descricao'));
		$sql->bindValue(':tipo', Check::IsNull($data, 'tipo'));
		$sql->bindValue(':valor', Check::IsNull($data, 'valor'));
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_produto = $this->db->lastInsertId();
		$this->getById($this->id_produto);
	}

	public function update($id, $data)
	{
		if (empty($data)) {
			return;
		}

		if (!$this->checkData($data, $id)) {
			return;
		}

		if ($this->exist($id)) {
			$sql = "UPDATE produtos 
				    SET    descricao = :descricao, 
					       tipo = :tipo, 
						   valor = :valor,
						   alteradoPor = :alteradoPor,
						   alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':descricao', Check::IsNull($data, 'descricao'));
			$sql->bindValue(':tipo', Check::IsNull($data, 'tipo'));
			$sql->bindValue(':valor', Check::IsNull($data, 'valor'));
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_produto = $id;
			$this->getById($this->id_produto);
		} else {
			$this->error = 'Registro não encontrado';
		}
	}

	public function delete($id)
	{
		if (empty($id)) {
			return false;
		}
		
		if ($this->dependencias($id)) {
			$this->error = 'Existem dependências para esse registro';
			return;
		}

		if ($this->exist($id)) {
			$sql = "DELETE FROM produtos 
					WHERE  id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->execute();

			$this->result = true;
		} else {
			$this->error = 'Registro não excluído';
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
		$sql = "SELECT id FROM produtos WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function descrExists($descricao, $id)
	{
		$sql = "SELECT id FROM produtos WHERE descricao = :descricao and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':descricao', $descricao);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function dependencias($id)
	{
		$sql = "SELECT id FROM pedItens WHERE idProduto = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		}
		
		return false;
	}

	private function checkData($data, $id = 0)
	{
		$errors = array();

		if (Check::IsNull($data, 'descricao') == '') {
			$errors[] = 'Descrição não pode ser vazio';
		} else if ($this->descrExists($data['descricao'], $id)) {
			$errors[] = 'Descrição já cadastrado em nossa base';
		}
		
		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
