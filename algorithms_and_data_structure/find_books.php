<?php

/**
 * 从指定书目中找到订购的书籍
 */

function findABook(Array $booklist, String $bookName)
{
	$found = false;
	foreach ($booklist as $index => $book) {
		if ($book == $bookName) {
			$found = $index;
			break;
		}
	}

	return $found;
}

function placeAllBooks(Array $orderedBooks, Array &$bookList)
{
	foreach ($orderedBooks as $book) {
		$bookFound = findABook($bookList, $book);
		if ($bookFound !== false) {
			array_splice($bookList, $bookFound, 1);
		}
	}
}

$bookList = ['PHP', 'MySQL', 'PGSQL', 'Oracle', 'Java'];
$orderedBooks = ['Mysql', 'PGSQL', 'Java'];

$res = placeAllBooks($orderedBooks, $bookList);

print_r($bookList);