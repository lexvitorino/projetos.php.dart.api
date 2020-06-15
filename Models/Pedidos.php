<?php
namespace Models;

use \Core\Model;
use \Models\Clientes;
use \Models\PedItens;
use \Models\PedCondsPagto;
use \Models\Produtos;
use \Core\Check;

class Pedidos extends Model
{

	private $id_logged;
	private $id_pedido;
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
		$sql = "SELECT p.*, c.nome as cliente
		        FROM   pedidos p 
					INNER JOIN clientes c on c.id = p.idCliente";
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT p.*, c.nome as cliente
		        FROM   pedidos p 
					INNER JOIN clientes c on c.id = p.idCliente
				WHERE  p.id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);

			$pedItens = new PedItens($this->id_logged, $id);
			$pedItens->getAll($id);
			if ($pedItens->getResult()) {
				$array['pedItens'] = $pedItens->getResult();
			}

			$pedCondsPagto = new PedCondsPagto($this->id_logged, $id);
			$pedCondsPagto->getAll();
			if ($pedCondsPagto->getResult()) {
				$array['pedCondsPagto'] = $pedCondsPagto->getResult();
			}
		}

		$this->result = $array;
	}

	public function create($data)
	{
		if (empty($data)) {
			return false;
		}

		$data = (object)$data;
		if (!$this->checkData($data)) {
			return;
		}

		if (!empty($data->pedCondsPagto)) {
			foreach ($data->pedItens as $item) {
				if (!$this->checkDataItens($item)) {
					return;
				}
			}
		}

		if (!empty($data->pedCondsPagto) && count($data->pedItens) == 0) {
			foreach ($data->pedCondsPagto as $item) {
				if ($this->checkDataCPag($item)) {
					return;
				}
			}
		}

		$sql = "INSERT INTO pedidos 
					   (data, numero, idCliente, prazoEntrega, prazoColocacao, tipoFrete, desconto, vendedor, valorTotal,
						fatCep, fatRua, fatNumero, fatComplemento, fatBairro, fatCidade, fatEstado, fatTelefone,
						entCep, entRua, entNumero, entComplemento, entBairro, entCidade, entEstado, entTelefone, entReferencia,
                        valorAdiant, tipoAdiant, bancoAdiant, agenciaAdiant, contaAdiant, assAdiant,
                        assinatura, criadoPor, criadoEm)
				VALUES (:data, :numero, :idCliente, :prazoEntrega, :prazoColocacao, :tipoFrete, :desconto, :vendedor, :valorTotal,
						:fatCep, :fatRua, :fatNumero, :fatComplemento, :fatBairro, :fatCidade, :fatEstado, :fatTelefone,
						:entCep, :entRua, :entNumero, :entComplemento, :entBairro, :entCidade, :entEstado, :entTelefone, :entReferencia,
                        :valorAdiant, :tipoAdiant, :bancoAdiant, :agenciaAdiant, :contaAdiant, :assAdiant,
                        :assinatura, :criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':data', $data->data);
		$sql->bindValue(':numero', $data->numero);
		$sql->bindValue(':idCliente', $data->idCliente);
		$sql->bindValue(':prazoEntrega', empty($data->prazoEntrega) ? "" : $data->prazoEntrega);
		$sql->bindValue(':prazoColocacao', empty($data->prazoColocacao) ? "" : $data->prazoColocacao);
		$sql->bindValue(':tipoFrete', empty($data->tipoFrete) ? "" : $data->tipoFrete);
		$sql->bindValue(':desconto', empty($data->desconto) ? "" : $data->desconto);
		$sql->bindValue(':vendedor', empty($data->vendedor) ? "" : $data->vendedor);
		$sql->bindValue(':fatCep', empty($data->fatCep) ? "" : $data->fatCep);
		$sql->bindValue(':fatRua', empty($data->fatRua) ? "" : $data->fatRua);
		$sql->bindValue(':fatNumero', empty($data->fatNumero) ? "" : $data->fatNumero);
		$sql->bindValue(':fatComplemento', empty($data->fatComplemento) ? "" : $data->fatComplemento);
		$sql->bindValue(':fatBairro', empty($data->fatBairro) ? "" : $data->fatBairro);
		$sql->bindValue(':fatCidade', empty($data->fatCidade) ? "" : $data->fatCidade);
		$sql->bindValue(':fatEstado', empty($data->fatEstado) ? "" : $data->fatEstado);
		$sql->bindValue(':fatTelefone', empty($data->fatTelefone) ? "" : $data->fatTelefone);
		$sql->bindValue(':entCep', empty($data->entCep) ? "" : $data->entCep);
		$sql->bindValue(':entRua', empty($data->entRua) ? "" : $data->entRua);
		$sql->bindValue(':entNumero', empty($data->entNumero) ? "" : $data->entNumero);
		$sql->bindValue(':entComplemento', empty($data->entComplemento) ? "" : $data->entComplemento);
		$sql->bindValue(':entBairro', empty($data->entBairro) ? "" : $data->entBairro);
		$sql->bindValue(':entCidade', empty($data->entCidade) ? "" : $data->entCidade);
		$sql->bindValue(':entEstado', empty($data->entEstado) ? "" : $data->entEstado);
		$sql->bindValue(':entTelefone', empty($data->entTelefone) ? "" : $data->entTelefone);
		$sql->bindValue(':entReferencia', empty($data->entReferencia) ? "" : $data->entReferencia);
		$sql->bindValue(':valorAdiant', empty($data->valorAdiant) ? "" : $data->valorAdiant);
		$sql->bindValue(':tipoAdiant', empty($data->tipoAdiant) ? "" : $data->tipoAdiant);
		$sql->bindValue(':bancoAdiant', empty($data->bancoAdiant) ? "" : $data->bancoAdiant);
		$sql->bindValue(':agenciaAdiant', empty($data->agenciaAdiant) ? "" : $data->agenciaAdiant);
		$sql->bindValue(':contaAdiant', empty($data->contaAdiant) ? "" : $data->contaAdiant);
		$sql->bindValue(':assAdiant', empty($data->assAdiant) ? "" : $data->assAdiant);
		$sql->bindValue(':assinatura', empty($data->assinatura) ? "" : $data->assinatura);
			$sql->bindValue(':valorTotal', empty($data->valorTotal) ? "" : $data->valorTotal);
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_pedido = $this->db->lastInsertId();

		$pedItens = new PedItens($this->id_logged, $this->id_pedido);
		foreach ($data->pedItens as $item) {
			$pedItens->create($item);
		}

		if (!empty($data->pedCondsPagto)) {
			$pedCondsPagto = new PedCondsPagto($this->id_logged, $this->id_pedido);
			foreach ($data->pedCondsPagto as $item) {
				$pedCondsPagto->create($item);
			}
		}

		$this->getById($this->id_pedido);
	}

	public function update($id, $data)
	{
		if (empty($data)) {
			return;
		}

		$data = (object)$data;
		if (!$this->checkData($data, $id)) {
			return;
		}

		if ($this->exist($id)) {
			$sql = "UPDATE pedidos
			        SET    data = :data, numero = :numero, idCliente = :idCliente, prazoEntrega = :prazoEntrega, prazoColocacao = :prazoColocacao, 
						   tipoFrete = :tipoFrete, desconto = :desconto, vendedor = :vendedor, valorTotal = :valorTotal,
                           fatCep = :fatCep, fatRua = :fatRua, fatNumero = :fatNumero, fatComplemento = :fatComplemento, fatBairro = :fatBairro, fatCidade = :fatCidade, fatEstado = :fatEstado, fatTelefone = :fatTelefone,
                           entCep = :entCep, entRua = :entRua, entNumero = :entNumero, entComplemento = :entComplemento, entBairro = :entBairro, entCidade = :entCidade, entEstado = :entEstado, entTelefone = :entTelefone, entReferencia = :entReferencia,
			               valorAdiant = :valorAdiant, tipoAdiant = :tipoAdiant, bancoAdiant = :bancoAdiant, agenciaAdiant = :agenciaAdiant, contaAdiant = :contaAdiant, 
						   assAdiant = :assAdiant, assinatura = :assinatura, alteradoPor = :alteradoPor, alteradoEm = NOW()	
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':data', $data->data);
			$sql->bindValue(':numero', $data->numero);
			$sql->bindValue(':idCliente', $data->idCliente);
			$sql->bindValue(':prazoEntrega', empty($data->prazoEntrega) ? "" : $data->prazoEntrega);
			$sql->bindValue(':prazoColocacao', empty($data->prazoColocacao) ? "" : $data->prazoColocacao);
			$sql->bindValue(':tipoFrete', empty($data->tipoFrete) ? "" : $data->tipoFrete);
			$sql->bindValue(':desconto', empty($data->desconto) ? "" : $data->desconto);
			$sql->bindValue(':vendedor', empty($data->vendedor) ? "" : $data->vendedor);
			$sql->bindValue(':fatCep', empty($data->fatCep) ? "" : $data->fatCep);
			$sql->bindValue(':fatRua', empty($data->fatRua) ? "" : $data->fatRua);
			$sql->bindValue(':fatNumero', empty($data->fatNumero) ? "" : $data->fatNumero);
			$sql->bindValue(':fatComplemento', empty($data->fatComplemento) ? "" : $data->fatComplemento);
			$sql->bindValue(':fatBairro', empty($data->fatBairro) ? "" : $data->fatBairro);
			$sql->bindValue(':fatCidade', empty($data->fatCidade) ? "" : $data->fatCidade);
			$sql->bindValue(':fatEstado', empty($data->fatEstado) ? "" : $data->fatEstado);
			$sql->bindValue(':fatTelefone', empty($data->fatTelefone) ? "" : $data->fatTelefone);
			$sql->bindValue(':entCep', empty($data->entCep) ? "" : $data->entCep);
			$sql->bindValue(':entRua', empty($data->entRua) ? "" : $data->entRua);
			$sql->bindValue(':entNumero', empty($data->entNumero) ? "" : $data->entNumero);
			$sql->bindValue(':entComplemento', empty($data->entComplemento) ? "" : $data->entComplemento);
			$sql->bindValue(':entBairro', empty($data->entBairro) ? "" : $data->entBairro);
			$sql->bindValue(':entCidade', empty($data->entCidade) ? "" : $data->entCidade);
			$sql->bindValue(':entEstado', empty($data->entEstado) ? "" : $data->entEstado);
			$sql->bindValue(':entTelefone', empty($data->entTelefone) ? "" : $data->entTelefone);
			$sql->bindValue(':entReferencia', empty($data->entReferencia) ? "" : $data->entReferencia);
			$sql->bindValue(':valorAdiant', empty($data->valorAdiant) ? "" : $data->valorAdiant);
			$sql->bindValue(':tipoAdiant', empty($data->tipoAdiant) ? "" : $data->tipoAdiant);
			$sql->bindValue(':bancoAdiant', empty($data->bancoAdiant) ? "" : $data->bancoAdiant);
			$sql->bindValue(':agenciaAdiant', empty($data->agenciaAdiant) ? "" : $data->agenciaAdiant);
			$sql->bindValue(':contaAdiant', empty($data->contaAdiant) ? "" : $data->contaAdiant);
			$sql->bindValue(':assAdiant', empty($data->assAdiant) ? "" : $data->assAdiant);
			$sql->bindValue(':assinatura', empty($data->assinatura) ? "" : $data->assinatura);
			$sql->bindValue(':valorTotal', empty($data->valorTotal) ? "" : $data->valorTotal);
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_pedido = $id;

			if (!empty($data->pedItens)) {
				$pedItens = new PedItens($this->id_logged, $this->id_pedido);
				foreach ($data->pedItens as $item) {
					$pedItens->createOrUpdate(empty($item->id) ? 0 : $item->id, $item);
				}
			}

			if (!empty($data->pedCondsPagto)) {
				$pedCondsPagto = new PedCondsPagto($this->id_logged, $this->id_pedido);
				foreach ($data->pedCondsPagto as $item) {
					$pedCondsPagto->createOrUpdate(empty($item->id) ? 0 : $item->id, $item);
				}
			}

			$this->getById($this->id_pedido);
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
			$sql = "DELETE FROM pedidos WHERE id = :id";
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

	public function getEndFat()
	{
		if (empty($this->result)) {
			return '';
		}

		if (empty($this->result['fatRua'])) {
			return '';
		}
		
		$endereco = $this->result['fatRua'];
		if ($this->result['fatNumero']) {
			$endereco .= ", " . $this->result['fatNumero'];
		}

		if ($this->result['fatBairro']) {
			$endereco .= ", " . $this->result['fatBairro'];
		}

		if ($this->result['fatEstado']) {
			$endereco .= ", " . $this->result['fatEstado'];
		}
		
		return $endereco;
	}

	public function getEndEnt()
	{
		if (empty($this->result)) {
			return '';
		}

		if (empty($this->result['entRua'])) {
			return '';
		}
		
		$endereco = $this->result['entRua'];
		if ($this->result['entNumero']) {
			$endereco .= ", " . $this->result['entNumero'];
		}

		if ($this->result['entBairro']) {
			$endereco .= ", " . $this->result['entBairro'];
		}

		if ($this->result['entEstado']) {
			$endereco .= ", " . $this->result['entEstado'];
		}
		
		return $endereco;
	}

	/* METODOS PRIVADOS */

	private function exist($id)
	{
		$sql = "SELECT id FROM pedidos WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function clieExists($idCliente)
	{
		$clientes = new Clientes($this->id_logged);
		$clientes->getById($idCliente);

		if ($clientes->getResult()) {
			return true;
		} else {
			return false;
		}
	}

	private function checkData($data)
	{
		$errors = array();

		if (empty($data->data)) {
			$errors[] = 'Data não pode ser vazio';
		}

		if (empty($data->numero)) {
			$errors[] = 'Número não pode ser vazio';
		} else if ($data->numero == 0) {
			$errors[] = 'Número deve ser maior que zero';
		}

		if (empty($data->idCliente)) {
			$errors[] = 'Cliente não pode ser vazio';
		} else if (!$this->clieExists($data->idCliente)) {
			$errors[] = 'Cliente não existe em nossa base';
		}

		if (empty($data->valorTotal)) {
			$errors[] = 'Valor Total não pode ser vazio';
		} else if ($data->valorTotal == 0) {
			$errors[] = 'Valor Total deve ser maior que zero';
		}

		if (empty($data->pedItens)) {
			$errors[] = 'É necessário informar os itens do produto';
		} else if (count($data->pedItens) == 0) {
			$errors[] = 'É necessário informar os itens do produto';
		}

		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}

	private function prodExists($idProduto)
	{
		$produtos = new Produtos($this->id_logged);
		$produtos->getById($idProduto);

		if ($produtos->getResult()) {
			return true;
		} else {
			return false;
		}
	}

	private function checkDataItens($data)
	{
		$errors = array();

		if (empty($data->qtde)) {
			$errors[] = 'Qtde não pode ser vazio';
		} else if ($data->qtde == 0) {
			$errors[] = 'Qtde deve ser maior que zero';
		}

		if (empty($data->idProduto)) {
			$errors[] = 'Produto não pode ser vazio';
		} else if (!$this->prodExists($data->idProduto)) {
			$errors[] = 'Produto não existe em nossa base';
		}

		if (empty($data->valorUnitario)) {
			$errors[] = 'Valor Unitário não pode ser vazio';
		} else if ($data->valorUnitario == 0) {
			$errors[] = 'Valor Unitário deve ser maior que zero';
		}

		if (empty($data->valorTotal)) {
			$errors[] = 'Valor Total não pode ser vazio';
		} else if ($data->valorTotal == 0) {
			$errors[] = 'Valor Total deve ser maior que zero';
		}

		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}

	private function checkDataCPag($data)
	{
		$errors = array();

		if (empty($data->tipoDocto)) {
			$errors[] = 'Qtde não pode ser vazio';
		}

		if (empty($data->dataVencimento)) {
			$errors[] = 'Data Vencimento não pode ser vazio';
		}

		if (empty($data->valor)) {
			$errors[] = 'Valor não pode ser vazio';
		} else if ($data->valorTotal == 0) {
			$errors[] = 'Valor deve ser maior que zero';
		}

		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
