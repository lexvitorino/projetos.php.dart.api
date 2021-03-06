<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class ContasReceber extends Model
{

	private $id_logged;
	private $id_contas_receber;
	private $result;
	private $error;

	public function __construct($id_logged)
	{
		parent::__construct();
		$this->id_logged = $id_logged;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT r.*, c.nome as cliente
		        FROM   contasreceber r 
					INNER JOIN clientes c on c.id = r.idCliente";		
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT r.*, c.nome as cliente
		        FROM   contasreceber r 
					INNER JOIN clientes c on c.id = r.idCliente
				WHERE  r.id = :id";	
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

		$sql = "INSERT INTO contasreceber 
					   (data, numero, parcela, idCliente, status, valor, desconto, dataVencimento, 
					    dataLiquidacao, juros, valorPago, criadoPor, criadoEm) 
				VALUES (:data, :numero, :parcela, :idCliente, :status, :valor, :desconto, :dataVencimento, 
				        :dataLiquidacao, :juros, :valorPago, :criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':data', Check::IsNull($data, 'data'));
		$sql->bindValue(':numero', Check::IsNull($data, 'numero'));
		$sql->bindValue(':parcela', Check::IsNull($data, 'parcela'));
		$sql->bindValue(':idCliente', Check::IsNull($data, 'idCliente'));
		$sql->bindValue(':status', Check::IsNull($data, 'status'));
		$sql->bindValue(':valor', Check::IsNull($data, 'valor'));
		$sql->bindValue(':desconto', Check::IsNull($data, 'desconto'));
		$sql->bindValue(':dataVencimento', Check::IsNull($data, 'dataVencimento'));
		$sql->bindValue(':dataLiquidacao', Check::IsNull($data, 'dataLiquidacao'));
		$sql->bindValue(':juros', Check::IsNull($data, 'juros'));
		$sql->bindValue(':valorPago', Check::IsNull($data, 'valorPago'));
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_contas_receber = $this->db->lastInsertId();
		$this->getById($this->id_contas_receber);
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
			$sql = "UPDATE contasreceber 
				    SET    data = :data,
					       numero = :numero,
						   parcela = :parcela, 
						   idCliente = :idCliente, 
						   status = :status, 
						   valor = :valor, 
						   desconto = :desconto, 
						   dataVencimento = :dataVencimento, 
					       dataLiquidacao = :dataLiquidacao, 
						   juros = :juros, 
						   valorPago = :valorPago, 
						   alteradoPor = :alteradoPor,
						   alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':data', Check::IsNull($data, 'data'));
			$sql->bindValue(':numero', Check::IsNull($data, 'numero'));
			$sql->bindValue(':parcela', Check::IsNull($data, 'parcela'));
			$sql->bindValue(':idCliente', Check::IsNull($data, 'idCliente'));
			$sql->bindValue(':status', Check::IsNull($data, 'status'));
			$sql->bindValue(':valor', Check::IsNull($data, 'valor'));
			$sql->bindValue(':desconto', Check::IsNull($data, 'desconto'));
			$sql->bindValue(':dataVencimento', Check::IsNull($data, 'dataVencimento'));
			$sql->bindValue(':dataLiquidacao', Check::IsNull($data, 'dataLiquidacao'));
			$sql->bindValue(':juros', Check::IsNull($data, 'juros'));
			$sql->bindValue(':valorPago', Check::IsNull($data, 'valorPago'));
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_contas_receber = $id;
			$this->getById($this->id_contas_receber);
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
			$sql = "DELETE FROM contasreceber 
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
		$sql = "SELECT id FROM contasreceber WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function numeroEParcelaExists($numero, $parcela, $id)
	{
		$sql = "SELECT id FROM contasreceber WHERE numero = :numero and parcela = :parcela and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':numero', $numero);
		$sql->bindValue(':parcela', $parcela);
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

		if (Check::IsNull($data, 'data') == '') {
			$errors[] = 'Data não pode ser vazio';
		}

		if (Check::IsNull($data, 'numero') == '') {
			$errors[] = 'Número não pode ser vazio';
		} else if ($this->numeroEParcelaExists($data['numero'], Check::IsNull($data, 'parcela'), $id)) {
			$errors[] = 'Número e Parcela já cadastrado em nossa base';
		}

		if (Check::IsNull($data, 'idCliente') == '') {
			$errors[] = 'Cliente não pode ser vazio';
		}

		if (Check::IsNull($data, 'valor') == '') {
			$errors[] = 'Valor não pode ser vazio';
		} else if (floatval($data['valor']) == 0) { 
			$errors[] = 'Valor deve ser maior que zero';
		}

		if (Check::IsNull($data, 'dataVencimento') == '') {
			$errors[] = 'Data de Vencimento não pode ser vazio';
		}

		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
