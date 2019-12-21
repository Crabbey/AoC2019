<?php

function intcode_getParameter(&$memory, $position, $mode = 0) {
	if ($mode == 0) {
		return $memory[$position];
	} else if ($mode == 1) {
		return $position;
	}
}

function intcode_opcode_01(&$memory, $position, $modes) {
	$input1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$input2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	$memory[intcode_getParameter($memory, $position+3, $modes[2])] = $input1 + $input2;
	return 4;
}

function intcode_opcode_02(&$memory, $position, $modes) {
	$input1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$input2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	$memory[intcode_getParameter($memory, $position+3, $modes[2])] = $input1 * $input2;
	return 4;
}

function intcode_opcode_03(&$memory, $position, $modes) {
	$memory[intcode_getParameter($memory, $position+1, $modes[0])] = $memory['inputs']['data'][$memory['inputs']['position']];
	$memory['inputs']['position']++;
	return 2;
}

function intcode_opcode_04(&$memory, $position, $modes) {
	echo $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	return 2;
}

function intcode_opcode_05(&$memory, $position, $modes) {
	$param1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	if ($param1 != 0) {
		return $param2 - $position;
	}
	return 3;
}

function intcode_opcode_06(&$memory, $position, $modes) {
	$param1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	if ($param1 == 0) {
		return $param2 - $position;
	}
	return 3;
}

function intcode_opcode_07(&$memory, $position, $modes) {
	$param1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	$output = 0;
	if ($param1 < $param2) {
		$output = 1;
	}
	$memory[intcode_getParameter($memory, $position+3, $modes[2])] = $output;
	return 4;
}

function intcode_opcode_08(&$memory, $position, $modes) {
	$param1 = $memory[intcode_getParameter($memory, $position+1, $modes[0])];
	$param2 = $memory[intcode_getParameter($memory, $position+2, $modes[1])];
	$output = 0;
	if ($param1 == $param2) {
		$output = 1;
	}
	$memory[intcode_getParameter($memory, $position+3, $modes[2])] = $output;
	return 4;
}

function intcode_opcode_99(&$memory, $position, $modes) {
	return count($memory) + 1;
}

function intcode_parse_opcode($opcode) {
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

function intcode_process_opcode(&$memory, $position) {
	$operation = intcode_parse_opcode($memory[$position]);
	if (function_exists("intcode_opcode_".$operation['opcode'])) {
		return call_user_func_array("intcode_opcode_".$operation['opcode'], array(&$memory, $position, $operation['modes']));
	} else {
		echo "Unknown opcode intcode_opcode_".$operation['opcode']." at position ".$position."\n";
		die();
	}
}
