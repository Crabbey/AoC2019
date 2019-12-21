<?php

include_once("../common.php");

function calculateFuel($mass) {
	return floor(($mass / 3) - 2);
}

function puzzle1_part1() {
	$input = readInput();
	$moduleMasses = explode("\n", $input);
	$totalWeight = 0;
	foreach ($moduleMasses as $module) {
		$totalWeight += calculateFuel($module);
	}
	return $totalWeight;
}

function puzzle1_part2() {
	$input = readInput();
	$moduleMasses = explode("\n", $input);
	$totalWeight = 0;
	foreach ($moduleMasses as $module) {
		$moduleWeight = 0;
		$runWeight = 1;
		while ($runWeight > 0) {
			$runWeight = max(array(calculateFuel($module), 0));
			$module = $runWeight;
			$moduleWeight += $runWeight;
		}
		$totalWeight += $moduleWeight;
	}
	return $totalWeight;
}