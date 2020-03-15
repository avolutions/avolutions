<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @author		Alexander Vogt <alexander.vogt@avolutions.de>
 * @copyright	2019 avolutions (http://avolutions.de)
 * @license		MIT License (https://opensource.org/licenses/MIT)
 * @link		https://github.com/avolutions/avolutions
 */

namespace Avolutions\Database;

/**
 * Column class
 *
 * The Column class represents the schema of a database table column. 
 *
 * @package		Database
 * @author		Alexander Vogt <alexander.vogt@avolutions.de>
 */
class Column
{
	/**
	 * @var string $name The name of the column.
	 */
	private $name;
	
	/**
	 * @var string $type The data type of the column.
	 */
	private $type;
	
	/**
	 * @var string $length The length of the column.
	 */
	private $length;
	
	/**
	 * @var string $default The default value of the column.
	 */
	private $default;
	
	/**
	 * @var string $null A flag if the column can be null or not.
	 */
	private $null;
	
	/**
	 * @var bool $primaryKey A flag if the column is a primary key or not.
	 */
	private $primaryKey;
	
	/**
	 * @var bool $autoIncrement A flag if the column is a auto increment column or not.
	 */
	private $autoIncrement;
	
	const TINYINT = "TINYINT";
	const SMALLINT = "SMALLINT";
	const MEDIUMINT = "MEDIUMINT";
	const INT = "INT";
	const BIGINT = "BIGINT";
	const DECIMAL = "DECIMAL";
	const FLOAT = "FLOAT";
	const DOUBLE = "DOUBLE";
	const BIT = "BIT";
	const BOOLEAN = "BOOLEAN";
	
	const DATE = "DATE";
	const DATETIME = "DATETIME";
	const TIMESTAMP = "TIMESTAMP";
	const TIME = "TIME";
	const YEAR = "YEAR";
	
	const CHAR = "CHAR";
	const VARCHAR = "VARCHAR";
	
	const TINYTEXT = "TINYTEXT";
	const TEXT = "TEXT";
	const MEDIUMTEXT = "MEDIUMTEXT";
	const LONGTEXT = "LONGTEXT";
	
	const NULL = "NULL";
	const NOTNULL = "NOT NULL";
	
	const CURRENT_TIMESTAMP = "CURRENT_TIMESTAMP";
	
	/**
	 * __construct
	 * 
	 * Creates a new Column object.
	 *
	 * @param string $name The name of the column.
	 * @param string $type The data type of the column.
	 * @param string $length The length of the column.
	 * @param string $default The default value of the column.
	 * @param string $null A flag if the column can be null or not.
	 * @param bool $primaryKey A flag if the column is a primary key or not.
	 * @param bool $autoIncrement A flag if the column is a auto increment column or not.
	 *
	 */
	public function __construct($name, $type, $length = null, $default = null, $null = Column::NOTNULL, $primaryKey = false, $autoIncrement = false) {
		$this->name = $name;
		$this->type = $type;
		$this->length = $length;
		$this->default = $default;
		$this->null = $null;
		$this->primaryKey = $primaryKey;
		$this->autoIncrement = $autoIncrement;
	}
	
	/**
	 * getPattern
	 * 
	 * Returns the SQL pattern for the column.
	 *
	 * @return string The SQL pattern for the column.
	 */
	public function getPattern() {
		$pattern = "";			
		$pattern .= $this->getNamePattern();
		$pattern .= $this->getTypePattern();
		$pattern .= $this->getNullPattern();
		$pattern .= $this->getDefaultPattern();
		$pattern .= $this->getPrimaryKeyPattern();
		$pattern .= $this->getAutoIncrementPattern();
		
		return $pattern;
	}
	
	/**
	 * getNamePattern
	 * 
	 * Returns the SQL pattern for the name attribute.
	 *
	 * @return string The SQL pattern for the name attribute.
	 */
	private function getNamePattern() {
		return "`".$this->name."` ";
	}
		
	/**
	 * getTypePattern
	 * 
	 * Returns the SQL pattern for the type attribute.
	 *
	 * @return string The SQL pattern for the type attribute.
	 */
	private function getTypePattern() {
		$typePattern = $this->type;
		
		if($this->length != null) {
			$typePattern .= "(".$this->length.")";
		}
			
		return $typePattern." ";
	}
		
		
	/**
	 * getNullPattern
	 * 
	 * Returns the SQL pattern for the null attribute.
	 *
	 * @return string The SQL pattern for the null attribute.
	 */
	private function getNullPattern() {			
		return $this->null;
	}
		
	/**
	 * getDefaultPattern
	 * 
	 * Returns the SQL pattern for the default attribute.
	 *
	 * @return string The SQL pattern for the default attribute.
	 */
	private function getDefaultPattern() {
		if($this->type == self::BOOLEAN) {
			$this->default = $this->default ? 'TRUE' : 'FALSE';
		}
		
		if($this->default != null) {			
			return " DEFAULT ".$this->default;
		}
	
		return "";
	}

	/**
	 * getPrimaryKeyPattern
	 * 
	 * Returns the SQL pattern for the primary key attribute.
	 *
	 * @return string The SQL pattern for the primary key attribute.
	 */
	private function getPrimaryKeyPattern()	{
		if($this->primaryKey) {
			return " PRIMARY KEY";
		}

		return "";
	}

	/**
	 * getAutoIncrementPattern
	 * 
	 * Returns the SQL pattern for the auto increment attribute.
	 *
	 * @return string The SQL pattern for the auto increment attribute.
	 */
	private function getAutoIncrementPattern() {
		if($this->autoIncrement) {
			return " AUTO_INCREMENT";
		}

		return "";
	}
}
?>