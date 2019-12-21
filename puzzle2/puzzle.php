<?php

include_once("../common.php");

function puzzle2_part1() {
	$input = readInput();
	$vm = new intcodeEngine($input);
	$vm->memory[1] = 12;
	$vm->memory[2] = 2;
	$vm->run();
	return $vm->memory[0];
}

function puzzle2_part2() {
	$input = readInput();

	for ($x=0; $x<99; $x++) {
		for ($y=0; $y<99; $y++) {
			$vm = new intcodeEngine($input);
			$vm->memory[1] = $x;
			$vm->memory[2] = $y;
			$vm->run();
			if ($vm->memory[0] == 19690720) {
				return 100 * $x + $y;
			}
		}
	}

}