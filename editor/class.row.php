<?php

require_once 'class.cell.php';

class Row {
	protected $number;
	protected $length;
	protected $cells = array();

	public function __construct($xml = null, $number = null, $length = null) {
		if(isset($xml)) {
			$this -> number = $xml -> getAttribute("number");
			$this -> length = $xml -> getAttribute("length");
			$cells = $xml -> getElementsByTagName("cell");
			foreach($cells as $cell) {
				$this -> cells[] = new Cell($cell);
			}
			return $this;
		} else if(isset($number) && isset($length)) {
			$this -> length = $length;
			$this -> number = $number;
			for($c = 0; $c < $this -> length; $c++) {
				$cell = new Cell(null, ($this -> number * $this -> length) + $c, $c);
				$this -> cells[] = $cell;
			}
			return $this;
		}
		return false;
	}

	// Getters
	public function getCells() {
		return $this -> cells;
	}
	public function getNumber() {
		return $this -> number;
	}
	public function getLength() {
		return $this -> length;
	}
}

?>