<?php

include_once("../common.php");

function puzzle5_part1() {
	$vm = new intcodeEngine(readInput());
	$vm->provideInput("1");
	$vm->run();
	return $vm->outputs;
}

function puzzle5_part2() {
	$vm = new intcodeEngine(readInput());
	$vm->provideInput("5");
	$vm->run();
	return $vm->outputs;
}