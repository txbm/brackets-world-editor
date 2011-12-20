<?php

require_once 'class.floor.php';
require_once 'class.bracket.php';
require_once 'class.ball.php';
require_once 'class.homebracket.php';

class Cell {
	protected $number;
	protected $column;
	protected $floor;
	protected $bracketA;
	protected $bracketB;
	protected $ball;
	protected $homeBracket;

	public function __construct($xml = null, $number = null, $column = null) {
		if(isset($xml)) {
			$this -> number = $xml -> getAttribute("number");
			$this -> column = $xml -> getAttribute("column");
			$this -> floor = new Floor();
			$this -> floor -> setType($xml -> getAttribute("floorType"));
			$this -> bracketA = new Bracket($xml -> getAttribute("bracketA"));
			$this -> bracketB = new Bracket($xml -> getAttribute("bracketB"));
			$this -> ball = new Ball($xml -> getAttribute("ballType"), $xml -> getAttribute("ballDirection"));
			$this -> homeBracket = new HomeBracket($xml -> getAttribute("homeBracket"));
			return $this;
		} else if (isset($number) && isset($column)) {
			$this -> number = $number;
			$this -> column = $column;
			$this -> floor = new Floor();
			$this -> bracketA = new Bracket();
			$this -> bracketB = new Bracket();
			$this -> ball = new Ball();
			$this -> homeBracket = new HomeBracket();
			return $this;	
		}
		return false;
	}

	// Getters
	public function getNumber() {
		return $this -> number;
	}
	public function getColumn() {
		return $this -> column;
	}
	public function getFloor() {
		return $this -> floor;
	}
	public function getBracketA() {
		return $this -> bracketA;
	}
	public function getBracketB() {
		return $this -> bracketB;
	}
	public function getBall() {
		return $this -> ball;
	}
	public function getHomeBracket() {
		return $this -> homeBracket;
	}

	// Builds the html for the cell editing menu
	public function getOpts() {
		$html = "";

		// Floor options
		$html .= "<p class=\"floorType\"><label for=\"floor\">Floor Type: </label><select id=\"floor\" name=\"floorType\">";
		foreach(Floor::Types() as $type) {
			if(($floor = $this -> floor) && $floor -> getType() == $type) {
				$html .= "<option selected=\"true\" value=\"$type\">$type</option>";
			}
			else {
				$html .= "<option value=\"$type\">$type</option>";
			}
		}
		$html .= "</select></p>";

		// Bracket A
		$html .= "<p class=\"bracketA\"><label for=\"bracketAtype\">Bracket A: </label><select id=\"bracketAtype\" name=\"bracketA\">";
		foreach(Bracket::Types() as $type) {
			if(($bracket = $this -> bracketA) && $bracket -> getType() == $type) {
				$html .= "<option selected=\"true\" value=\"$type\">$type</option>";
			}
			else {
				$html .= "<option value=\"$type\">$type</option>";
			}
		}
		$html .= "</select></p>";

		// Bracket B
		$html .= "<p class=\"bracketB\"><label for=\"bracketBtype\">Bracket B: </label><select id=\"bracketBtype\" name=\"bracketB\">";
		foreach(Bracket::Types() as $type) {
			if(($bracket = $this -> bracketB) && $bracket -> getType() == $type) {
				$html .= "<option selected=\"true\" value=\"$type\">$type</option>";
			}
			else {
				$html .= "<option value=\"$type\">$type</option>";
			}
		}
		$html .= "</select></p>";

		// Ball Type
		$html .= "<p class=\"ballType\"><label for=\"balltype\">Ball Type: </label><select id=\"balltype\" name=\"ballType\">";
		foreach(Ball::Types() as $type) {
			$html .= "<option value=\"$type\">$type</option>";
		}
		$html .= "</select></p>";
		
		// Ball Direction
		$html .= "<p class=\"ballDirection\"><label for=\"balldirection\">Ball Direction:</label><select id=\"balldirection\" name=\"ballDirection\">";
		foreach(Ball::Directions() as $direction) {
			$html .= "<option value=\"$direction\">$direction</option>";
		}
		$html .= "</select></p>";

		// Home Bracket
		$html .= "<p id=\"homedirection\"><label for=\"homedirection\">Home Bracket: </label><select name=\"homeBracket\">";
		foreach(HomeBracket::Directions() as $dir) {
			if($this -> homeBracket -> getDirection() == $dir) {
				$html .= "<option selected=\"true\" value=\"$dir\">$dir</option>";
			}
			else {
				$html .= "<option value=\"$dir\">$dir</option>";
			}
		}
		return $html;
	}

	// Generic update function, checks options and validates values before applying
	public function update($values = array()) {
		$this -> floor -> setType($values["floorType"]);
		$this -> bracketA -> setType($values["bracketA"]);
		$this -> bracketB -> setType($values["bracketB"]);
		$this -> ball -> setType($values["ballType"]);
		$this -> ball -> setDirection($values["ballDirection"]);
		$this -> homeBracket -> setDirection($values["homeBracket"]);
		return true;
	}
}

?>