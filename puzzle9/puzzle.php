<?php

include_once("../common.php");

function puzzle9_part1() {
	$input = readInput();
	$engine = new intcodeEngine($input);
	$engine->provideInput("1");
	$engine->run();
	return $engine->outputs[0];
}

function puzzle9_part2() {
	$input = readInput();
	$engine = new intcodeEngine($input);
	$engine->provideInput("2");
	$engine->run();
	return $engine->outputs[0];
}
