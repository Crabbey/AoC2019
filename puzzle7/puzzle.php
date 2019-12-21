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
			$memory = explode(",", $input);
			$position = 0;
			$memory['inputs'] = array(
				"position" => 0,
				"data" => array(
					$phases[$amp],
					$chainvalue,
				),
			);
			ob_start();
			while ($position < count($memory)) {
				$position += p7_process_opcode($memory, $position);
			}
			$chainvalue = ob_get_clean();
		}
		$peak = max(array($peak, $chainvalue));
	}
	return $peak;
}

// function puzzle7_part2() {
// 	$input = readInput();
// 	$memory = explode(",", $input);
// 	$position = 0;
// 	$memory['inputs'] = array(
// 		"position" => 0,
// 		"data" => array(
// 			"5",
// 		),
// 	);
// 	while ($position < count($memory)) {
// 		$position += p7_process_opcode($memory, $position);
// 	}
// }