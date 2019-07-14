<?php
/**
 * 无向graph
 *
 *  A-B/C/E
 *  B-D/E
 */

$graph = [];
$nodes = ['A', 'B', 'C', 'D', 'E'];

foreach($nodes as $xNode) {
    foreach ($nodes as $yNode) {
        $graph[$xNode][$yNode] = 0;
    }
}

$graph['A']['B'] = 1;
$graph['B']['A'] = 1;
$graph['A']['C'] = 1;
$graph['C']['A'] = 1;
$graph['A']['E'] = 1;
$graph['E']['A'] = 1;
$graph['B']['E'] = 1;
$graph['E']['B'] = 1;
$graph['B']['D'] = 1;
$graph['D']['B'] = 1;

foreach ($graph as $index => $row) {
    foreach ($row as $index2 => $v) {
        print($v . "\t");
    }
    print("\n");
}

