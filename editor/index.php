<?php
require_once 'class.board.php';
require_once 'class.config.php';
?>

<html>
<head>
<title>Brackets Worldbuilder</title>
<style type="text/css">
@import "css/master.css";
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/master.js"></script>
<script type="text/javascript" src="js/json2.js"></script>
</head>
<body>
	<div class="wrapper">
		<div class="intro">
			Brackets Worldbuilder v1.0
		</div>
	
		<?php
		if(isset($_POST['new'])) {
			if(($new_board = new Board(null, $_POST['rowNumber'], $_POST['columnNumber'])) && $new_board -> save()) {
				$board = $new_board;
			}
		} else if(isset($_POST['load'])) {
			if(isset($_POST['loadNum'])) {
				$board = new Board($_POST['loadNum']);
			}
		} else if(isset($_POST['reset'])) {
			if(isset($_POST['number'])) {
				if($old_board = new Board($_POST['number'])) {
					$board = $old_board -> erase();
				}
			}
		}
		?>
		<div class="content">
			<form name="boardinfo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<fieldset>
					<legend>Board Controls</legend>
					<div class="left">
						<p><input type="submit" name="new" value="New Board" /><label for="rowNumber">Board Rows:</label><select id="rowNumber" name="rowNumber"><?php for($i = 5; $i < 14; $i++) { if(isset($board) && $board -> getRowNumber() == $i) {echo "<option selected value=\"$i\">$i</option>";} else {echo "<option value=\"$i\">$i</option>";}} ?></select> <label for="columnNumber">Board Columns:</label><select id="columnNumber" name="columnNumber"><?php for($i = 5; $i < 14; $i++) { if(isset($board) && $board -> getColumnNumber() == $i) {echo "<option selected value=\"$i\">$i</option>";} else {echo "<option value=\"$i\">$i</option>";}} ?></select></p>
						
						<?php $levels = Board::GetLevels(); if(!empty($levels)) {?>
						<p><input type="submit" name="load" value="Load Board" /><label for="loadNum">Load Level #:</label><select id="loadNum" name="loadNum"><?php foreach($levels as $level) { if(isset($_POST['loadNum']) && $_POST['loadNum'] == $level) { echo "<option selected value=\"{$level}\">$level</option>";} else { echo "<option value=\"{$level}\">$level</option>";}}?></select><?php } else { echo "<strong>No saved level data found.</strong>"; }?></p>
					</div>
					<div class="right">
						<?php if(isset($board)) {?><p>Board #: <strong><?php echo $board -> getNumber(); ?> </strong> <input id="number" type="hidden" name="number" value="<?php echo $board -> getNumber(); ?>" /><label for="name">Name:</label><input id="boardname" type="text" name="boardname" value="<?php echo $board -> getName();?>" /> <input type="submit" name="reset" value="Erase Board" /></p><?php }?>	
					</div>
				</fieldset>
			</form>
			<div class="secondrow">
				<div class="board">
					<table>
					<?php
					if(isset($board)) {
						foreach($board -> getRows() as $row) {
							?>
						<tr>
						<?php
						foreach($row -> getCells() as $cell) {
							if($cell -> getNumber() % 2 != 0) {
								?>
							<td class="rotated"
								id="<?php echo "r{$row -> getNumber()}c{$cell -> getColumn()}n{$cell -> getNumber()}"; ?>">
								<?php
							}
							else {
								?>
							
							<td class="standard"
								id="<?php echo "r{$row -> getNumber()}c{$cell -> getColumn()}n{$cell -> getNumber()}"; ?>">
								<?php
							}?>
								<div class="inserts">
									<div name="floorType"
									<?php if($cell -> getFloor() -> getType() != "None") { echo "class=\"{$cell -> getFloor() -> getType()}\""; }?>></div>
									<div name="bracketA"
									<?php if($cell -> getBracketA() -> getType() != "None") { echo "class=\"{$cell -> getBracketA() -> getType()}\""; }?>></div>
									<div name="bracketB"
									<?php if($cell -> getBracketB() -> getType() != "None") { echo "class=\"{$cell -> getBracketB() -> getType()}\""; }?>></div>
									<div name="ballType"
									<?php if($cell -> getBall() -> getType() != "None") { echo "class=\"{$cell -> getBall() -> getType()}\""; }?>></div>
									<div name="ballDirection"
									<?php if($cell -> getBall() -> getDirection() != "None") { echo "class=\"{$cell -> getBall() -> getDirection()}\""; }?>></div>
									<div name="homeBracket"
									<?php if($cell -> getHomeBracket() -> getDirection() != "None") { echo "class=\"{$cell -> getHomeBracket() -> getDirection()}\""; }?>></div>
								</div> <?php }
								?>
							</td>
						</tr>
						<?php
						}
					}
					?>
					</table>
				</div>
				<div class="toolbox">
					<form name="celledit">
						<fieldset>
							<legend>Editing Cell #:</legend>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
