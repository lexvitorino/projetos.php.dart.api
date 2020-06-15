<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class Clientes extends Model
{

	private $id_logged;
	private $id_cliente;
	private $result;
	private $error;

	public function __construct($id_logged) {
		parent::__construct();
		$this->id_logged = $id_logged;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT * FROM clientes";
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT * FROM clientes WHERE id = :id";
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

		$sql = "INSERT INTO clientes 
						(nome, cpfCnpj, documento, telefone, celular, email,
						fatCep, fatRua, fatNumero, fatComplemento, fatBairro, fatCidade, fatEstado, fatTelefone,
						entCep, entRua, entNumero, entComplemento, entBairro, entCidade, entEstado, entTelefone, entReferencia,
						criadoPor, criadoEm) 
				VALUES (:nome, :cpfCnpj, :documento, :telefone, :celular, :email,
						:fatCep, :fatRua, :fatNumero, :fatComplemento, :fatBairro, :fatCidade, :fatEstado, :fatTelefone,
						:entCep, :entRua, :entNumero, :entComplemento, :entBairro, :entCidade, :entEstado, :entTelefone, :entReferencia,
						:criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':nome', Check::IsNull($data, 'nome'));
		$sql->bindValue(':cpfCnpj', Check::IsNull($data, 'cpfCnpj'));
		$sql->bindValue(':documento', Check::IsNull($data, 'documento'));
		$sql->bindValue(':telefone', Check::IsNull($data, 'telefone'));
		$sql->bindValue(':celular', Check::IsNull($data, 'celular'));
		$sql->bindValue(':email', Check::IsNull($data, 'email'));
		$sql->bindValue(':fatCep', Check::IsNull($data, 'fatCep'));
		$sql->bindValue(':fatRua', Check::IsNull($data, 'fatRua'));
		$sql->bindValue(':fatNumero', Check::IsNull($data, 'fatNumero'));
		$sql->bindValue(':fatComplemento', Check::IsNull($data, 'fatComplemento'));
		$sql->bindValue(':fatBairro', Check::IsNull($data, 'fatBairro'));
		$sql->bindValue(':fatCidade', Check::IsNull($data, 'fatCidade'));
		$sql->bindValue(':fatEstado', Check::IsNull($data, 'fatEstado'));
		$sql->bindValue(':fatTelefone', Check::IsNull($data, 'fatTelefone'));
		$sql->bindValue(':entCep', Check::IsNull($data, 'entCep'));
		$sql->bindValue(':entRua', Check::IsNull($data, 'entRua'));
		$sql->bindValue(':entNumero', Check::IsNull($data, 'entNumero'));
		$sql->bindValue(':entComplemento', Check::IsNull($data, 'entComplemento'));
		$sql->bindValue(':entBairro', Check::IsNull($data, 'entBairro'));
		$sql->bindValue(':entCidade', Check::IsNull($data, 'entCidade'));
		$sql->bindValue(':entEstado', Check::IsNull($data, 'entEstado'));
		$sql->bindValue(':entTelefone', Check::IsNull($data, 'entTelefone'));
		$sql->bindValue(':entReferencia', Check::IsNull($data, 'entReferencia'));
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_cliente = $this->db->lastInsertId();
		$this->getById($this->id_cliente);
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
			$sql = "UPDATE clientes 
				    SET    nome = :nome, cpfCnpj = :cpfCnpj, documento = :documento, telefone = :telefone, celular = :celular, email = :email,
                           fatCep = :fatCep, fatRua = :fatRua, fatNumero = :fatNumero, fatComplemento = :fatComplemento, fatBairro = :fatBairro, fatCidade = :fatCidade, fatEstado = :fatEstado, fatTelefone = :fatTelefone,
                           entCep = :entCep, entRua = :entRua, entNumero = :entNumero, entComplemento = :entComplemento, entBairro = :entBairro, entCidade = :entCidade, entEstado = :entEstado, entTelefone = :entTelefone, entReferencia = :entReferencia,
						   alteradoPor = :alteradoPor, alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':nome', Check::IsNull($data, 'nome'));
			$sql->bindValue(':cpfCnpj', Check::IsNull($data, 'cpfCnpj'));
			$sql->bindValue(':documento', Check::IsNull($data, 'documento'));
			$sql->bindValue(':telefone', Check::IsNull($data, 'telefone'));
			$sql->bindValue(':celular', Check::IsNull($data, 'celular'));
			$sql->bindValue(':email', Check::IsNull($data, 'email'));
			$sql->bindValue(':fatCep', Check::IsNull($data, 'fatCep'));
			$sql->bindValue(':fatRua', Check::IsNull($data, 'fatRua'));
			$sql->bindValue(':fatNumero', Check::IsNull($data, 'fatNumero'));
			$sql->bindValue(':fatComplemento', Check::IsNull($data, 'fatComplemento'));
			$sql->bindValue(':fatBairro', Check::IsNull($data, 'fatBairro'));
			$sql->bindValue(':fatCidade', Check::IsNull($data, 'fatCidade'));
			$sql->bindValue(':fatEstado', Check::IsNull($data, 'fatEstado'));
			$sql->bindValue(':fatTelefone', Check::IsNull($data, 'fatTelefone'));
			$sql->bindValue(':entCep', Check::IsNull($data, 'entCep'));
			$sql->bindValue(':entRua', Check::IsNull($data, 'entRua'));
			$sql->bindValue(':entNumero', Check::IsNull($data, 'entNumero'));
			$sql->bindValue(':entComplemento', Check::IsNull($data, 'entComplemento'));
			$sql->bindValue(':entBairro', Check::IsNull($data, 'entBairro'));
			$sql->bindValue(':entCidade', Check::IsNull($data, 'entCidade'));
			$sql->bindValue(':entEstado', Check::IsNull($data, 'entEstado'));
			$sql->bindValue(':entTelefone', Check::IsNull($data, 'entTelefone'));
			$sql->bindValue(':entReferencia', Check::IsNull($data, 'entReferencia'));
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_cliente = $id;
			$this->getById($this->id_cliente);
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
			$sql = "DELETE FROM clientes 
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
		$sql = "SELECT id FROM clientes WHERE id = :id";
		$sql = $this->db->prepare($sql);
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
		$sql = "SELECT id FROM pedidos WHERE idCliente = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		}
		
		$sql = "SELECT id FROM contasreceber WHERE idCliente = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		}
		
		return false;
	}

	private function cpfCnpjExists($cpfCnpj, $id)
	{
		$sql = "SELECT id FROM clientes WHERE cpfCnpj = :cpfCnpj and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':cpfCnpj', $cpfCnpj);
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
		}
		
		$cpfCnpj = Check::IsNull($data, 'cpfCnpj');
		if (empty($cpfCnpj)) {
			$errors[] = 'CPF/CNPJ não pode ser vazio';
		} else if (strlen($cpfCnpj) != 11 && strlen($cpfCnpj) != 14) {
			$errors[] = 'Número de CPF/CNPJ inválido';
		} else if ($this->cpfCnpjExists($cpfCnpj, $id)) {
			$errors[] = 'CPF/CNPJ já cadastrado em nossa base';
		}

		$email = Check::IsNull($data, 'email');
		if ($email != '') {
			if (!Check::Email($email)) {
				$errors[] = 'E-mail informado não é válido';
			}
		}
		
		$this->error = array('dataError' => $errors);
		if (count($errors) > 0) {
			return false;
		} else {
			return true;
		}
	}
}
