<?php

require_once 'class.board.php';

if(isset($_POST['number']) && isset($_POST['name'])) {
	if($board = new Board($_POST['number'])) {
		$board -> setName($_POST['name']);
		if($board -> save()) {
			echo "true";
		} else {
			echo "false";
		}
	}
}


if(isset($_POST['number']) && isset($_POST['rowNumber']) && isset($_POST['columnNumber'])) {
	if(($board = new Board($_POST['number'])) && ($cell = $board -> getCell($_POST['rowNumber'], $_POST['columnNumber']))) {
		if(isset($_POST['cellops'])) {
			$values = array();
			foreach($_POST['cellops'] as $pair) {
				$values[$pair["name"]] = $pair["value"];
			}
			if($cell -> update($values)) {
				if($board -> save()) {
					echo "true";
				} else {
					echo "false";
				}
			}
		}
		else {
			echo $cell -> getOpts();
		}
	}
}

?>