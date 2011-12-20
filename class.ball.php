<?php

// Dr. Ball M.D.

class Ball
{
	protected $type;
	protected $direction;

	public function __construct($type = "None", $direction = "None") {
		$this -> type = $type;
		$this -> direction = $direction;
	}

	public static function Types() {
		return array(
			"None",
			"Standard",
			"Asplode",
			"Wrecker"
			);
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

	// Getters
	public function getType() {
		return $this -> type;
	}
	public function getDirection() {
		return $this -> direction;
	}

	// Setters
	public function setType($type) {
		$this -> type = $type;
	}
	public function setDirection($direction) {
		$this -> direction = $direction;
	}
}