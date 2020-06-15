<?php
namespace Models;

use \Core\Model;
use \Core\Check;

class Report extends Model
{

	private $result;
	private $error;

	/* METODOS PUBLiC */

	public function getImage($table, $field, $id)
	{
		$array = array();

		$sql = "SELECT " . $field . " as Image FROM " . $table ." WHERE id = :id";
		
		$sql = $this->db->prepare($sql);
		$sql->bindValue(':id', $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			$array = $sql->fetch(\PDO::FETCH_ASSOC);
		}

		$this->result = $array;
	}

	public function getResult()
	{
		return $this->result;
	}

	public function getError()
	{
		return $this->error;
	}
}
