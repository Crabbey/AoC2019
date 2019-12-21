<?php

function readInput($section = "") {
	return file_get_contents("input".$section.".txt");
}

function runPuzzle($puzzle) {
	runPuzzlePart($puzzle, 1);
	runPuzzlePart($puzzle, 2);
}

function runPuzzlePart($puzzle, $part) {
	if (!function_exists("puzzle".$puzzle."_part".$part)) {
		return;
	}
	echo "Puzzle ".$puzzle." (".$part."): ";
	$response = call_user_func("puzzle".$puzzle."_part".$part);
	if (is_array($response)) {
		print_r($response);
	} else {
		echo $response;
	}
	echo "\n";
}

include_once('intcode.php');
