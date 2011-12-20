<?php

class HomeBracket {

	protected $direction;

	public function __construct($direction = "None") {
		$this -> direction = $direction;
	}

	public static function Directions() {
		return array(
			"None",
			"North",
			"South",
			"East",
			"West"
			);
	}
	
	public function getDirection() {
		return $this -> direction;
	}
	
	public function setDirection($direction) {
		$this -> direction = $direction;
	}
}