<?php
namespace Models;

use \Core\Model;

class PedItens extends Model
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
		$sql = "SELECT i.*, p.descricao as produto
		        FROM   peditens i
					INNER JOIN produtos p on p.id = i.idProduto
				WHERE  i.idPedido = :idPedido";
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

		$sql = "SELECT i.*, p.descricao as produto
		        FROM   peditens i
					INNER JOIN produtos p on p.id = i.idProduto
				WHERE  i.idPedido = :idPedido";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':idPedido', $id);
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
		$sql = "INSERT INTO peditens 
					   (idPedido, qtde, ambiente, idProduto, valorUnitario, valorDesconto, valorTotal, criadoPor, criadoEm)
				VALUES (:idPedido, :qtde, :ambiente, :idProduto, :valorUnitario, :valorDesconto, :valorTotal, :criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':idPedido', $this->id_pedido);
		$sql->bindValue(':qtde', $data->qtde);
		$sql->bindValue(':ambiente', empty($data->ambiente) ? "" : $data->ambiente);
		$sql->bindValue(':idProduto', $data->idProduto);
		$sql->bindValue(':valorUnitario', $data->valorUnitario);
		$sql->bindValue(':valorDesconto', empty($data->valorDesconto) ? "" : $data->valorDesconto);
		$sql->bindValue(':valorTotal', $data->valorTotal);
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();
	}

	public function update($id, $data)
	{
		if (empty($data)) {
			return;
		}

		if ($this->exist($id)) {
			$sql = "UPDATE peditens
			        SET    qtde = :qtde, 
			               ambiente = :ambiente, 
						   idProduto = :idProduto, 
						   valorUnitario = :valorUnitario, 
						   valorDesconto = :valorDesconto, 
						   valorTotal = :valorTotal, 
						   alteradoPor = :alteradoPor,
						   alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':qtde', $data->qtde);
			$sql->bindValue(':ambiente', empty($data->ambiente) ? "" : $data->ambiente);
			$sql->bindValue(':idProduto', $data->idProduto);
			$sql->bindValue(':valorUnitario', $data->valorUnitario);
			$sql->bindValue(':valorDesconto', empty($data->valorDesconto) ? "" : $data->valorDesconto);
			$sql->bindValue(':valorTotal', $data->valorTotal);
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->getById($this->id_pedido);
		} else {
			$this->error = 'Registro não encontrado';
		}
	}

	public function createOrUpdate($id, $data)
	{
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
			$sql = "DELETE FROM peditens WHERE id = :id";
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
		$sql = "SELECT id FROM peditens WHERE id = :id";
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
