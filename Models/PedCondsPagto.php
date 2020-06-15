<?php
namespace Models;

use \Core\Model;

class PedCondsPagto extends Model
{

	private $id_logged;
	private $id_pedido;
	private $result;
	private $error;

	public function __construct($id_logged, $id_pedido)
	{
		parent::__construct();
		$this->id_logged = $id_logged;
		$this->id_pedido = $id_pedido;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT * FROM pedcondspagto where idPedido = :idPedido";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':idPedido', $this->id_pedido);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT * FROM pedcondspagto WHERE id = :id";
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

		$data = (object)$data;
		$sql = "INSERT INTO pedcondspagto 
					   (idPedido, tipoDocto, dataVencimento, numCheque, banco, agencia, conta, valor, criadoPor, criadoEm)
				VALUES (:idPedido, :tipoDocto, :dataVencimento, :numCheque, :banco, :agencia, :conta, :valor, :criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':idPedido', $this->id_pedido);
		$sql->bindValue(':tipoDocto', $data->tipoDocto);
		$sql->bindValue(':dataVencimento', $data->dataVencimento);
		$sql->bindValue(':numCheque', empty($data->numCheque) ? "" : $data->numCheque);
		$sql->bindValue(':banco', empty($data->banco) ? "" : $data->banco);
		$sql->bindValue(':agencia', empty($data->agencia) ? "" : $data->agencia);
		$sql->bindValue(':conta', empty($data->conta) ? "" : $data->conta);
		$sql->bindValue(':valor', $data->valor);
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();
	}

	public function update($id, $data)
	{
		if (empty($data)) {
			return;
		}

		if ($this->exist($id)) {
			$sql = "UPDATE pedcondspagto
			        SET    tipoDocto = :tipoDocto, 
			               dataVencimento = :dataVencimento, 
						   numCheque = :numCheque, 
						   banco = :banco, 
						   agencia = :agencia, 
						   conta = :conta, 
						   valor = :valor,
						   alteradoPor = :alteradoPor,
						   alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':tipoDocto', $data->tipoDocto);
			$sql->bindValue(':dataVencimento', $data->dataVencimento);
			$sql->bindValue(':numCheque', empty($data->numCheque) ? "" : $data->numCheque);
			$sql->bindValue(':banco', empty($data->banco) ? "" : $data->banco);
			$sql->bindValue(':agencia', empty($data->agencia) ? "" : $data->agencia);
			$sql->bindValue(':conta', empty($data->conta) ? "" : $data->conta);
			$sql->bindValue(':valor', $data->valor);
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->getById($this->id_pedido);
		} else {
			$this->error = 'Registro não encontrado';
		}
	}

	public function createOrUpdate($id, $data) {
		if ($this->exist($id)) {
			$this->update($id, $data);
		} else {
			$this->create($data);
		}
	}

	public function delete($id)
	{
		if (empty($id)) {
			return false;
		}

		if ($this->exist($id)) {
			$sql = "DELETE FROM pedcondspagto WHERE id = :id";
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
		$sql = "SELECT id FROM pedcondspagto WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}
}
