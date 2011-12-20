<?php

class Floor {
	
	protected $type;
	
	public function __construct($type = "None") {
		$this -> type = $type;
	}
	
	public static function Types() {
		return array(
			"None",
			"Block",
			"Lava"
		);
	}
	
	public function getType() {
		return $this -> type;
	}
	
	public function setType($type) {
		return $this -> type = $type;
	}
}


?>