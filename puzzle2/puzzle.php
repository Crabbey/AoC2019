<?php

include_once("../common.php");

function p2_opcode_1(&$memory, $position) {
	$input1 = $memory[$memory[$position + 1]];
	$input2 = $memory[$memory[$position + 2]];
	$memory[$memory[$position+3]] = $input1 + $input2;
	return 4;
}

function p2_opcode_2(&$memory, $position) {
	$input1 = $memory[$memory[$position + 1]];
	$input2 = $memory[$memory[$position + 2]];
	$memory[$memory[$position+3]] = $input1 * $input2;
	return 4;
}

function p2_opcode_99(&$memory, $position) {
	return count($memory) + 1;
}

function p2_process_opcode(&$memory, $position) {
	$opcode = $memory[$position];
	if (function_exists("p2_opcode_".$opcode)) {
		return call_user_func_array("p2_opcode_".$opcode, array(&$memory, $position));
	}
}

function puzzle2_part1() {
	$input = readInput();
	$memory = explode(",", $input);
	$memory[1] = 12;
	$memory[2] = 2;
	$position = 0;
	while ($position < count($memory)) {
		$position += p2_process_opcode($memory, $position);
	}
	return $memory[0];
}

function puzzle2_part2() {
	$input = readInput();

	for ($x=0; $x<99; $x++) {
		for ($y=0; $y<99; $y++) {
			$memory = explode(",", $input);
			$memory[1] = $x;
			$memory[2] = $y;
			$position = 0;
			while ($position < count($memory)) {
				$position += [2_process_opcode($memory, $position);
			}
			if ($memory[0] == 19690720) {
				return 100 * $x + $y;
			}
		}
	}

}