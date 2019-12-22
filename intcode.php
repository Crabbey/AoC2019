<?php

class intcodeEngine {
	public $memory;
	public $position;
	public $outputs;
	public $inputs;
	public $relativeBase;

	function __construct($input) {
		$this->memory = explode(",", $input);
		$this->position = 0;
		$this->outputs = array();
		$this->inputs = array(
			"position" => 0,
			"data" => array(),
		);
		$this->relativeBase = 0;
	}

	function provideInput($input) {
		$this->inputs['data'][] = $input;
	}

	function getParameter($position, $mode = 0) {
		if ($mode == 0) {
			return $this->memory[$position];
		} else if ($mode == 1) {
			return $position;
		} else if ($mode == 2) {
			$ref = $this->memory[$position] + $this->relativeBase;
			return $ref;
		}
	}

	function getFromMemory($position, $mode = 0) {
		if (!isset($this->memory[$this->getParameter($position, $mode)])) {
			return 0;
		}
		return $this->memory[$this->getParameter($position, $mode)];
	}

	function opcode_01($modes) {
		$input1 = $this->getFromMemory($this->position+1, $modes[0]);
		$input2 = $this->getFromMemory($this->position+2, $modes[1]);
		$this->memory[$this->getParameter($this->position+3, $modes[2])] = $input1 + $input2;
		return 4;
	}

	function opcode_02($modes) {
		$input1 = $this->getFromMemory($this->position+1, $modes[0]);
		$input2 = $this->getFromMemory($this->position+2, $modes[1]);
		$this->memory[$this->getParameter($this->position+3, $modes[2])] = $input1 * $input2;
		return 4;
	}

	function opcode_03($modes) {
		if (!isset($this->inputs['data'][$this->inputs['position']])) {
			return false;
		}
		$this->memory[$this->getParameter($this->position+1, $modes[0])] = $this->inputs['data'][$this->inputs['position']];
		$this->inputs['position']++;
		return 2;
	}

	function opcode_04($modes) {
		$this->outputs[] = $this->getFromMemory($this->position+1, $modes[0]);
		return 2;
	}

	function opcode_05($modes) {
		$param1 = $this->getFromMemory($this->position+1, $modes[0]);
		$param2 = $this->getFromMemory($this->position+2, $modes[1]);
		if ($param1 != 0) {
			return $param2 - $this->position;
		}
		return 3;
	}

	function opcode_06($modes) {
		$param1 = $this->getFromMemory($this->position+1, $modes[0]);
		$param2 = $this->getFromMemory($this->position+2, $modes[1]);
		if ($param1 == 0) {
			return $param2 - $this->position;
		}
		return 3;
	}

	function opcode_07($modes) {
		$param1 = $this->getFromMemory($this->position+1, $modes[0]);
		$param2 = $this->getFromMemory($this->position+2, $modes[1]);
		$output = 0;
		if ($param1 < $param2) {
			$output = 1;
		}
		$this->memory[$this->getParameter($this->position+3, $modes[2])] = $output;
		return 4;
	}

	function opcode_08($modes) {
		$param1 = $this->getFromMemory($this->position+1, $modes[0]);
		$param2 = $this->getFromMemory($this->position+2, $modes[1]);
		$output = 0;
		if ($param1 == $param2) {
			$output = 1;
		}
		$this->memory[$this->getParameter($this->position+3, $modes[2])] = $output;
		return 4;
	}

	function opcode_09($modes) {
		$this->relativeBase = $this->getFromMemory($this->position+1, $modes[0]) + $this->relativeBase;
		return 2;
	}

	function opcode_99($modes) {
		return count($this->memory) + 1;
	}

	function parse_opcode($opcode) {
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

	function process_opcode() {
		$operation = $this->parse_opcode($this->memory[$this->position]);
		if (method_exists($this, "opcode_".$operation['opcode'])) {
			return $this->{"opcode_".$operation['opcode']}($operation['modes']);
		} else {
			echo "Unknown opcode opcode_".$operation['opcode']." at position ".$this->position."\n";
			die();
		}
	}

	function run() {
		$increment = $this->process_opcode();
		if ($increment) {
			$this->position += $increment;
			if ($this->position > count($this->memory)) {
				return 0;
			}
			return $this->run();
		}
		return 1;
	}
}