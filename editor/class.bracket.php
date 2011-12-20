<?php

class Bracket
{
	protected $type;

	public function __construct($type = "None") {
		$this -> type = $type;
	}

	public static function Types() {
		return array(
			"None",
			"Standard",
			"Stasis",
			"Ether",
			);
	}

	// Getters
	public function getType() {
		return $this -> type;
	}

	// Setters
	public function setType($type) {
		$this -> type = $type;
	}
}

?>