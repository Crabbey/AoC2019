<?php

include_once("../common.php");

function puzzle8_part1() {
	$input = readInput();
	$resolution = array(
		"C" => 25,
		"R" => 6
	);
	$layers = processLayers($resolution, $input);
	$layerCounts = array();
	$highCount = 9999;
	$highCountArray = 0;
	foreach ($layers as $layer) {
		$count = array(0 => 0, 1 => 0, 2 => 0);
		foreach ($layer as $row) {
			for ($x = 0; $x < strlen($row); $x++) {
				$count[$row[$x]]++;
			}
		}
		if ($count[0] < $highCount) {
			$highCount = $count[0];
			$highCountArray = $count;
		}
		$layerCounts[] = $count;
	}
	return $highCountArray[1] * $highCountArray[2];
}

function puzzle8_part2() {
	$input = readInput();
	$resolution = array(
		"C" => 25,
		"R" => 6
	);
	$layers = processLayers($resolution, $input);
	$finishedImage = array();
	for ($x = 0; $x < $resolution['R']; $x++) {
		$finishedImage[$x] = array();
		for ($y = 0; $y < $resolution['C']; $y++) {
			$finishedImage[$x][$y] = '2';
		}
	}
	foreach ($layers as $layer) {
		foreach ($layer as $x => $row) {
			for ($y = 0; $y < strlen($row); $y++) {
				if ($finishedImage[$x][$y] == "2") {
					$finishedImage[$x][$y] = $row[$y];
				}
			}
		}
	}
	$return = "\n";
	foreach ($finishedImage as $x => $row) {
		foreach ($row as $y => $val) {
			if ($val == 1) {
				$return .= "█";
			} else {
				$return .= " ";
			}
		}
		$return .= "\n";
	}
	return $return;
}

function processLayers($resolution, $input) {
	$layers = array();
	$chunkLen = $resolution['C'] * $resolution['R'];
	$chunks = str_split($input, $chunkLen);
	foreach ($chunks as $chunk) {
		$layer = str_split($chunk, $resolution['C']);
		$layers[] = $layer;
	}
	return $layers;	
}