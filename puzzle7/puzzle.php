<?php

include_once("../common.php");



function genCombination($chars, $size, $combinations = array()) {
    if (empty($combinations)) {
        $combinations = $chars;
    }
    if ($size == 1) {
        return $combinations;
    }
    $new_combinations = array();
    foreach ($combinations as $combination) {
        foreach ($chars as $char) {
        	if (strpos((string) $combination, (string) $char) === FALSE) {
	            $new_combinations[] = $combination . $char;
        	}
        }
    }
    return genCombination($chars, $size - 1, $new_combinations);
}


function puzzle7_part1() {
    $combinations = genCombination(array(0,1,2,3,4), 5); 
	$input = readInput();
	$peak = 0;
	foreach ($combinations as $combination) {
		$phases = str_split($combination);
		$chainvalue = 0;
		for ($amp = 0; $amp < 5; $amp++) {
			$vm = new intcodeEngine($input);
			$vm->provideInput($phases[$amp]);
			$vm->provideInput($chainvalue);
			$vm->run();
			$chainvalue = $vm->outputs[0];
		}
		$peak = max(array($peak, $chainvalue));
	}
	return $peak;
}

function puzzle7_part2() {
	$input = readInput();
    $combinations = genCombination(array(5,6,7,8,9), 5); 
	$peak = 0;
	foreach ($combinations as $combination) {
		$chainvalue = 0;
		$phases = str_split($combination);
		$engine = array(
			"0" => new intcodeEngine($input),
			"1" => new intcodeEngine($input),
			"2" => new intcodeEngine($input),
			"3" => new intcodeEngine($input),
			"4" => new intcodeEngine($input),
		);
		for ($amp = 0; $amp < 5; $amp++) {
			$engine[$amp]->provideInput($phases[$amp]);
		}
		$stop = false;
		$iterator = 0;
		while (!$stop) {
			$engine[$iterator]->provideInput($chainvalue);
			$rc = $engine[$iterator]->run();
			$output = array_pop($engine[$iterator]->outputs);
			if ($rc == 0 && $iterator == 4) {
				$peak = max($peak, $output);
				$stop = true;
			}
			$chainvalue = $output;
			$iterator++;
			if ($iterator == 5) {
				$iterator = 0;
			}
		}
	}
	return $peak;
}
