<?php

require_once 'class.row.php';
require_once 'class.cell.php';

class Board {
	protected $number;
	protected $name;
	protected $rowNumber;
	protected $columnNumber;
	protected $normalBrackets;
	protected $stasisBrackets;
	protected $etherBrackets;

	protected $rows = array();

	public function __construct($number = null, $rowNumber = null, $columnNumber = null) {
		// Load board from file
		if(isset($number)) {
			$this -> number = $number;
			if($this -> load()) {
				return $this;
			}
		} else if (isset($rowNumber) && isset($columnNumber)) {
			// Construct template for writing
			$this -> rowNumber = $rowNumber;
			$this -> columnNumber = $columnNumber;
			for($r = 0; $r < $this -> rowNumber; $r++) {
				if(($row = new Row(null, $r, $this -> columnNumber)) && isset($row)) {
					$this -> rows[] = $row;
				} else {
					return false;
				}
			}
			return $this;
		}
		return false;
	}

	public static function GetLevels() {
		$directory = opendir("boards");
		$numbers = array();
		while($file = readdir($directory)) {
			$matches = array();
			preg_match("/level([0-9]+)\.xml/", $file, $matches);
			if(isset($matches[1]) && !empty($matches[1])) {
				$numbers[] = $matches[1];
			}
		}
		if(sort($numbers)) {
			return $numbers;
		}
		return false;
	}

	// Getters
	public function getNumber() {
		return $this -> number;
	}
	public function getName() {
		return $this -> name;
	}
	public function getRowNumber() {
		return $this -> rowNumber;
	}
	public function getColumnNumber() {
		return $this -> columnNumber;
	}
	public function getRows() {
		return $this -> rows;
	}
	public function getNormalBrackets() {
		return $this -> normalBrackets;
	}
	public function getStasisBrackets() {
		return $this -> stasisBrackets;
	}
	public function getEtherBrackets() {
		return $this -> etherBrackets;
	}
	public function getCell($row, $column) {
		$rows = $this -> getRows();
		$cells = $rows[$row] -> getCells();
		$cell = $cells[$column];
		return $cell;
	}

	// Setters
	public function setNumber($value = 0) {
		$this -> number = $value;
	}
	public function setName($name) {
		$this -> name = $name;
	}
	public function setNormalBrackets($value = 0) {
		$this -> normalBrackets = $value;
	}
	public function setStasisBrackets($value = 0) {
		$this -> stasisBrackets = $value;
	}
	public function setEtherBrackets($value = 0) {
		$this -> etherBrackets = $value;
	}

	// Erase board
	public function erase() {
		$board = new Board(null, $this -> rowNumber, $this -> columnNumber);
		$board -> setNumber($this -> number);
		if($board -> save()) {
			return $board;
		}
	}

	// Load Board
	public function load() {
		return $this -> readXML();
	}

	// Save Board
	public function save() {
		if(isset($this -> number)) {
			error_log("saving existing board");
			return $this -> writeXML();
		} else {
			error_log("writing new board");
			$directory = opendir("boards");
			$numbers = array();
			while($file = readdir($directory)) {
				$matches = array();
				preg_match("/level([0-9]+)\.xml/", $file, $matches);
				if(isset($matches[1]) && !empty($matches[1])) {
					$numbers[] = $matches[1];
				}
			}
			if(!empty($numbers)) {
				$last_level = max($numbers);
				$this -> number = $last_level + 1;
			} else {
				$this -> number = 1;
			}
			return $this -> writeXML();
		}
		return false;
	}

	public function writeXML() {
		// Create XML Document
		$xmldoc = new DOMDocument("1.0", "utf-8");
		$xmldoc -> formatOutput = true;

		// Root node
		$root = $xmldoc -> createElement("level");
		$xmldoc -> appendChild($root);
		$root -> setAttribute("xml:id", "level");
		$root -> setAttribute("number", $this -> number);
		$root -> setAttribute("name", $this -> name);
		$root -> setAttribute("rowNumber", $this -> rowNumber);
		$root -> setAttribute("columnNumber", $this -> columnNumber);
		$root -> setAttribute("normalBrackets", $this -> normalBrackets);
		$root -> setAttribute("stasisBrackets", $this -> stasisBrackets);
		$root -> setAttribute("etherBrackets", $this -> etherBrackets);

		// Rows
		foreach($this -> rows as $row) {
			$rowXML = $xmldoc -> createElement("row");
			$root -> appendChild($rowXML);
			$rowXML -> setAttribute("xml:id", "row{$row -> getNumber()}");
			$rowXML -> setAttribute("number", $row -> getNumber());

			// Cells
			foreach($row -> getCells() as $cell) {
				// Create the cell
				$cellXML = $xmldoc -> createElement("cell");
				$cellXML -> setAttribute("xml:id", "cell{$cell -> getNumber()}");
				$cellXML -> setAttribute("number", $cell -> getNumber());
				$cellXML -> setAttribute("column", $cell -> getColumn());
				$cellXML -> setAttribute("floorType", $cell -> getFloor() -> getType());
				$cellXML -> setAttribute("bracketA", $cell -> getBracketA() -> getType());
				$cellXML -> setAttribute("bracketB", $cell -> getBracketB() -> getType());
				$cellXML -> setAttribute("ballType", $cell -> getBall() -> getType());
				$cellXML -> setAttribute("ballDirection", $cell -> getBall() -> getDirection());
				$cellXML -> setAttribute("homeBracket", $cell -> getHomeBracket() -> getDirection());
				$rowXML -> appendChild($cellXML);
			}
		}

		// Save path
		$path = "boards/level{$this -> number}.xml";

		// Overwrite if existing
		if(file_exists($path)) {
			unlink($path);
		}

		// Save
		$xmldoc -> save($path);

		// Set permissions so users can access
		return chmod($path, 0777);
	}

	public function readXML() {
		error_log("loading xml from file");
		// New DOMDocument Holder
		$xmldoc = new DOMDocument();
		$xmldoc -> formatOutput = true;

		// Load existing file
		if(isset($this -> number)) {
			$path = "boards/level{$this -> number}.xml";
			if(file_exists($path)) {
				$xmldoc -> load($path);
				$root = $xmldoc -> getElementById("level");
				$this -> number = $root -> getAttribute("number");
				$this -> name = $root -> getAttribute("name");
				$this -> rowNumber = $root -> getAttribute("rowNumber");
				$this -> columnNumber = $root -> getAttribute("columnNumber");
				$this -> normalBrackets = $root -> getAttribute("normalBrackets");
				$this -> stasisBrackets = $root -> getAttribute("stasisBrackets");
				$this -> etherBrackets = $root -> getAttribute("etherBrackets");

				// Read Rows
				$rowsXML = $xmldoc -> getElementsByTagName("row");
				$this -> rows = array();
				foreach($rowsXML as $rowXML) {
					$row = new Row($rowXML);
					$this -> rows[] = $row;
				}
				return true;
			}
		}
		return false;
	}
}

?>