<?php

include_once("../common.php");

function mapWire(&$grid, $moves, $wirename) {
	$x=0;
	$y=0;
	$wirelen = 1;
	foreach ($moves as $move) {
		$xop = 0;
		$yop = 0;
		$direction = $move[0];
		$length = ltrim($move, $move[0]);
		if ($direction == "D") {
			$xop = 1;
		} else if ($direction == "L") {
			$yop = -1;
		} else if ($direction == "U") {
			$xop = -1;
		} else if ($direction == "R") {
			$yop = 1;
		}
		for ($offset=1; $offset <= $length; $offset++) {
			$wirelen++;
			$x += $xop;
			$y += $yop;
			if (!isset($grid[$x])) {
				$grid[$x] = array();
			}
			if (!isset($grid[$x][$y])) {
				$grid[$x][$y] = array(
					"wire1" => 0,
					"wire2" => 0,
				);
			}
			if ($grid[$x][$y][$wirename] == 0) {
				$grid[$x][$y][$wirename] = $wirelen;
			} else { 
				// $wirelen = $grid[$x][$y]['wire1'];
			}
		}
	}
}


function puzzle3_part1() {
	$input = readInput();
	$grid = array();
	
	$wires = explode("\n", $input);
	$wire1moves = explode(",", $wires[0]);
	$wire2moves = explode(",", $wires[1]);

	mapWire($grid, $wire1moves, "wire1");
	mapWire($grid, $wire2moves, "wire2");

	$count = 0;
	$crossovers = array();
	$answer = 1000000000000000000000;
	$answer2 = 1000000000000000000000;
	foreach ($grid as $x => $gridx) {
		foreach ($gridx as $y => $gridy) {
			if ($gridy['wire1'] != 0 && $gridy['wire2'] != 0) {
				$count++;
				$distance = abs($x) + abs($y);
				$totlen = $gridy['wire1'] + $gridy['wire2'];
				if ($distance < $answer) {
					$answer = $distance;
				}
				if ($totlen < $answer2) {
					$answer2 = $totlen;
				}
				// echo "\n";
			}
		}
	}

	return $answer;
}


function puzzle3_part2() {
	$input = readInput();
	$grid = array();
	
	$wires = explode("\n", $input);
	$wire1moves = explode(",", $wires[0]);
	$wire2moves = explode(",", $wires[1]);

	mapWire($grid, $wire1moves, "wire1");
	mapWire($grid, $wire2moves, "wire2");

	$count = 0;
	$crossovers = array();
	$answer = 1000000000000000000000;
	$answer2 = 1000000000000000000000;
	foreach ($grid as $x => $gridx) {
		foreach ($gridx as $y => $gridy) {
			if ($gridy['wire1'] != 0 && $gridy['wire2'] != 0) {
				$count++;
				$distance = abs($x) + abs($y);
				$totlen = $gridy['wire1'] + $gridy['wire2'];
				if ($distance < $answer) {
					$answer = $distance;
				}
				if ($totlen < $answer2) {
					$answer2 = $totlen;
				}
				// echo "\n";
			}
		}
	}

	return $answer2;
}