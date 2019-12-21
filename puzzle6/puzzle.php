<?php

include_once("../common.php");

function puzzle6_part1() {
	$input = readInput();
	$planets = buildMap($input);
	return count_conns(0, $planets['COM']);
}

function buildMap($input) {
	$planets = array();	
	$planets['COM'] = new Planet("COM");
	$lines = explode("\n", $input);
	foreach ($lines as $line) {
		$parts = explode(")", $line);
		if (!isset($planets[$parts[1]])) {
			$planets[$parts[1]] = new Planet($parts[1]);
		}
		$planets[$parts[1]]->parent = $parts[0];
	}
	foreach ($planets as $planetname => $planet) {
		$planets[$planet->parent]->children[$planetname] = $planet;
	}
	foreach ($planets as $planetname => $planet) {
		if ($planetname != "COM") {
			unset($planets[$planetname]);
		}
	}
	return $planets;
}

function count_conns($depth, $planet) {
	$total = $depth;
	if (!$planet->children) {
		return $total;
	}
	foreach ($planet->children as $child) {
		$total += count_conns($depth + 1, $child);
	}
	return $total;
}

function hopsBetween($planets, $origin, $destination) {
	$originTree = array();
	$destinationTree = array();
	buildTree($planets['COM'], $originTree, $origin);
	buildTree($planets['COM'], $destinationTree, $destination);
	foreach ($originTree as $k => $val) {
		if (isset($destinationTree[$k]) && $destinationTree[$k] == $val) {
			unset($originTree[$k]);
			unset($destinationTree[$k]);
		}
	}
	// 2 refers to the top to/from the last common system
	return 2 + count($originTree) + count($destinationTree);
}

function buildTree($planet, &$tree, $target) {
	foreach ($planet->children as $childname => $child) {
		if ($childname == $target) {
			array_unshift($tree, $planet->name);
			return true;
		}
	}
	// Looked through children, can't find target.
	foreach ($planet->children as $childname => $child) {
		$found = buildTree($child, $tree, $target);
		if ($found) {
			array_unshift($tree, $planet->name);
			return true;
		}
	}
	return false;
}

function puzzle6_part2() {
	$input = readInput();
	$planets = buildMap($input);
	$answer = hopsBetween($planets, "YOU", "SAN");
	$answer -= 2; // Remove the extra hops for origin and dest parent
	return $answer;
}


class Planet {
	public $children;
	public $parent;
	public $name;

	public function __construct($name) {
		$this->children = array();
		$this->name = $name;
	}
}