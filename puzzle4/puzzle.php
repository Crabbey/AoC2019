<?php

include_once("../common.php");

function criteria_decrease($number) {
	$ints = str_split($number);
	foreach ($ints as $k => $int) {
		if (isset($ints[$k+1]) && $ints[$k+1] < $int) {
			return false;
		}
	}
	return true;
}

function criteria_adjacency($number) {
	$ints = str_split($number);
	foreach ($ints as $k => $int) {
		if (isset($ints[$k+1]) && $ints[$k+1] == $int) {
			return true;
		}
	}
	return false;
}

function criteria_adjacency2($number) {
	$ints = str_split($number);
	$return = false;
	for ($x = 0; $x < count($ints); $x++) {
		$count = 1;
		$pos = $x;
		while ($pos < count($ints)) {
			if (!isset($ints[$x+1])) {
				break;
			}
			if ($ints[$x] == $ints[$x+1]) {
				$count++;
				$x++;
			}
			$pos++;
		}
		if ($count == 2) {
			return true;
		}
	}
	return $return;
}

function puzzle4_part1() {
	$input = explode("-", readInput());
	$min = $input[0];
	$max = $input[1];
	$answers = array();
	for ($x = $min; $x <= $max; $x++) {
		if (criteria_decrease($x) && criteria_adjacency($x)) {
			$answers[] = $x;
		}
	}
	return count($answers);
}

function puzzle4_part2() {
	$input = explode("-", readInput());
	$min = $input[0];
	$max = $input[1];
	$answers = array();
	for ($x = $min; $x <= $max; $x++) {
		if (criteria_decrease($x) && criteria_adjacency2($x)) {
			$answers[] = $x;
		}
	}
	return count($answers);
}

// $answers = array();

// for ($x = $min; $x < $max; $x++) {
// 	$test1 = 0;
// 	$test2 = 1;
// 	$ints = str_split($x);
// 	foreach ($ints as $k => $v) {
// 		if (isset($ints[$k+1]) && $ints[$k+1] == $v) {
// 			$test1 = 1;
// 		}
// 		if (isset($ints[$k+1]) && intval($ints[$k+1]) < intval($v)) {
// 			// echo intval($ints[$k+1])." <= ".intval($v)."\n";
// 			$test2 = 0;
// 		}
// 	}
// 	if ($test1 && $test2) {
// 		$answers[] = $x;
// 	}
// }
// print_r($answers);
// echo count($answers);
// echo "\n";