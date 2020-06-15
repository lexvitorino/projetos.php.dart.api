<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class Fornecedores extends Model
{

	private $id_logged;
	private $id_fornecedor;
	private $result;
	private $error;

	public function __construct($id_logged) {
		parent::__construct();
		$this->id_logged = $id_logged;
	}

	/* METODOS PUBLiC */

	public function getAll()
	{
		$sql = "SELECT * FROM fornecedores";
		$sql = $this->db->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$this->result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		}
	}

	public function getById($id)
	{
		$array = array();

		$sql = "SELECT * FROM fornecedores WHERE id = :id";
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

		$sql = "INSERT INTO fornecedores 
					   (razaoSocial, cnpj, ie, telefone, celular, email,
						cep, rua, numero, complemento, bairro, cidade, estado,
						criadoPor, criadoEm) 
				VALUES (:razaoSocial, :cnpj, :ie, :telefone, :celular, :email,
						:cep, :rua, :numero, :complemento, :bairro, :cidade, :estado,
						:criadoPor, NOW())";

		$sql = $this->db->prepare($sql);
		$sql->bindValue(':razaoSocial', Check::IsNull($data, 'razaoSocial'));
		$sql->bindValue(':cnpj', Check::IsNull($data, 'cnpj'));
		$sql->bindValue(':ie', Check::IsNull($data, 'ie'));
		$sql->bindValue(':telefone', Check::IsNull($data, 'telefone'));
		$sql->bindValue(':celular', Check::IsNull($data, 'celular'));
		$sql->bindValue(':email', Check::IsNull($data, 'email'));
		$sql->bindValue(':cep', Check::IsNull($data, 'cep'));
		$sql->bindValue(':rua', Check::IsNull($data, 'rua'));
		$sql->bindValue(':numero', Check::IsNull($data, 'numero'));
		$sql->bindValue(':complemento', Check::IsNull($data, 'complemento'));
		$sql->bindValue(':bairro', Check::IsNull($data, 'bairro'));
		$sql->bindValue(':cidade', Check::IsNull($data, 'cidade'));
		$sql->bindValue(':estado', Check::IsNull($data, 'estado'));
		$sql->bindValue(':criadoPor', $this->id_logged);
		$sql->execute();

		$this->id_fornecedor = $this->db->lastInsertId();
		$this->getById($this->id_fornecedor);
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
			$sql = "UPDATE fornecedores 
				    SET    razaoSocial = :razaoSocial, cnpj = :cnpj, ie = :ie, telefone = :telefone, celular = :celular, email = :email,
                           cep = :cep, rua = :rua, numero = :numero, complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado,
						   alteradoPor = :alteradoPor, alteradoEm = NOW()
					WHERE  id = :id";

			$sql = $this->db->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':razaoSocial', Check::IsNull($data, 'razaoSocial'));
			$sql->bindValue(':cnpj', Check::IsNull($data, 'cnpj'));
			$sql->bindValue(':ie', Check::IsNull($data, 'ie'));
			$sql->bindValue(':telefone', Check::IsNull($data, 'telefone'));
			$sql->bindValue(':celular', Check::IsNull($data, 'celular'));
			$sql->bindValue(':email', Check::IsNull($data, 'email'));
			$sql->bindValue(':cep', Check::IsNull($data, 'cep'));
			$sql->bindValue(':rua', Check::IsNull($data, 'rua'));
			$sql->bindValue(':numero', Check::IsNull($data, 'numero'));
			$sql->bindValue(':complemento', Check::IsNull($data, 'complemento'));
			$sql->bindValue(':bairro', Check::IsNull($data, 'bairro'));
			$sql->bindValue(':cidade', Check::IsNull($data, 'cidade'));
			$sql->bindValue(':estado', Check::IsNull($data, 'estado'));
			$sql->bindValue(':alteradoPor', $this->id_logged);
			$sql->execute();

			$this->id_fornecedor = $id;
			$this->getById($this->id_fornecedor);
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
			$sql = "DELETE FROM fornecedores 
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
		$sql = "SELECT id FROM fornecedores WHERE id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function cnpjExists($cnpj, $id)
	{
		$sql = "SELECT id FROM fornecedores WHERE cnpj = :cnpj and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':cnpj', $cnpj);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function rsocialExists($razaoSocial, $id)
	{
		$sql = "SELECT id FROM fornecedores WHERE razaoSocial = :razaoSocial and id <> :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':razaoSocial', $razaoSocial);
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
		$sql = "SELECT id FROM contaspagar WHERE idFornecedor = :id";
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

		if (Check::IsNull($data, 'razaoSocial') == '') {
			$errors[] = 'Razao Social não pode ser vazio';
		} else if ($this->rsocialExists($data['razaoSocial'], $id)) {
			$errors[] = 'Razao Social já cadastrado em nossa base';
		}
		
		$cnpj = Check::IsNull($data, 'cnpj');
		if (empty($cnpj)) {
			$errors[] = 'CNPJ não pode ser vazio';
		} else if (strlen($cnpj) != 14) {
			$errors[] = 'Número de CNPJ inválido';
		} else if ($this->cnpjExists($cnpj, $id)) {
			$errors[] = 'CNPJ já cadastrado em nossa base';
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
