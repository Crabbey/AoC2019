<?php

include_once("../common.php");

function p5_getParameter(&$memory, $position, $mode = 0) {
	if ($mode == 0) {
		return $memory[$position];
	} else if ($mode == 1) {
		return $position;
	}
}

function p5_opcode_01(&$memory, $position, $modes) {
	$input1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$input2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	$memory[p5_getParameter($memory, $position+3, $modes[2])] = $input1 + $input2;
	return 4;
}

function p5_opcode_02(&$memory, $position, $modes) {
	$input1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$input2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	$memory[p5_getParameter($memory, $position+3, $modes[2])] = $input1 * $input2;
	return 4;
}

function p5_opcode_03(&$memory, $position, $modes) {
	$memory[p5_getParameter($memory, $position+1, $modes[0])] = $memory['inputs']['data'][$memory['inputs']['position']];
	$memory['inputs']['position']++;
	return 2;
}

function p5_opcode_04(&$memory, $position, $modes) {
	echo $memory[p5_getParameter($memory, $position+1, $modes[0])]."\n";
	return 2;
}

function p5_opcode_05(&$memory, $position, $modes) {
	$param1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	if ($param1 != 0) {
		return $param2 - $position;
	}
	return 3;
}

function p5_opcode_06(&$memory, $position, $modes) {
	$param1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	if ($param1 == 0) {
		return $param2 - $position;
	}
	return 3;
}

function p5_opcode_07(&$memory, $position, $modes) {
	$param1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	$output = 0;
	if ($param1 < $param2) {
		$output = 1;
	}
	$memory[p5_getParameter($memory, $position+3, $modes[2])] = $output;
	return 4;
}

function p5_opcode_08(&$memory, $position, $modes) {
	$param1 = $memory[p5_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[p5_getParameter($memory, $position+2, $modes[1])];
	$output = 0;
	if ($param1 == $param2) {
		$output = 1;
	}
	$memory[p5_getParameter($memory, $position+3, $modes[2])] = $output;
	return 4;
}

function p5_opcode_99(&$memory, $position, $modes) {
	return count($memory) + 1;
}

function p5_parse_opcode($opcode) {
	$return = array(
		"opcode" => "01",
		"modes" => array(
			0 => 0,
			1 => 0,
			2 => 0,
		),
	);
	$code = strrev($opcode);
	$return['opcode'] = strrev(substr($code, 0, 2));
	if (strlen($return['opcode']) == 1) {
		$return['opcode'] = "0".$return['opcode'];
	} 
	$code = substr($code, 2);
	$pos = 0;
	while (strlen($code) > 0) {
		$return['modes'][$pos] = substr($code, 0, 1);
		$pos++;
		$code = substr($code, 1);
	}
	return $return;
}

function p5_process_opcode(&$memory, $position) {
	$operation = p5_parse_opcode($memory[$position]);
	if (function_exists("p5_opcode_".$operation['opcode'])) {
		return call_user_func_array("p5_opcode_".$operation['opcode'], array(&$memory, $position, $operation['modes']));
	} else {
		echo "Unknown opcode p5_opcode_".$operation['opcode']." at position ".$position."\n";
		die();
	}
}

function puzzle5_part1() {
	$input = readInput();
	$memory = explode(",", $input);
	$position = 0;
	$memory['inputs'] = array(
		"position" => 0,
		"data" => array(
			"1",
		),
	);
	while ($position < count($memory)) {
		$position += p5_process_opcode($memory, $position);
	}
}

function puzzle5_part2() {
	$input = readInput();
	$memory = explode(",", $input);
	$position = 0;
	$memory['inputs'] = array(
		"position" => 0,
		"data" => array(
			"5",
		),
	);
	while ($position < count($memory)) {
		$position += p5_process_opcode($memory, $position);
	}
}