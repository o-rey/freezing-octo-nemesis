<?php

/**
 * Функция вычисления факториала, которой, как выяснилось, нет в стандартной поставке php (WAT?)
 * @param integer $value
 * @return integer факториал
 */
function fact($value)
{
    if ($value < 2) return 1;
    for ($f = 2; $value - 1 > 1; $f *= $value--);
    return $f;
};

/**
 * Генерирует число с `$bitcount` установленных младших бит
 * @param integer $bitcount кол-во бит
 * @return integer число, в котором установлено требуемое значение бит
 */
function get_min_element($bitcount)
{
	return (1 << (int)$bitcount) - 1;
}

/**
 * Генерирует число разрядностью `$capacity` с `$bitcount` установленных старших бит
 * @param integer $bitcount кол-во бит
 * @param integer $length разрядность
 * @return integer число, в котором установлено требуемое значение бит
 */
function get_max_element($bitcount, $capacity)
{
	return ((1 << (int)$bitcount) - 1) << ((int)$capacity - (int)$bitcount);
}

/**
 * Генерирует следующее по возрастанию сочетание с заданным кол-вом установленных битов по предыдущему сочетанию.
 * Суть алгоритма сводится к следующему:
 * 1. находим самый правый бит, слева от которого пусто ("голова червячка"),
 * 2. перемещаем голову влево на пустое место, а оставшееся тело сдвигаем до конца направо.
 * Алгоритм основан на т. н. Gosper's hack (Д. Кнут, "Bitwise tricks and techniques").
 * См. также:
 * http://graphics.stanford.edu/~seander/bithacks.html
 * http://www.catonmat.net/blog/low-level-bit-hacks-you-absolutely-must-know/
 * @return integer Следующее по возрастанию число в последовательности
 */
function get_next_permutation($x)
{
     if ($x == 0) return 0;
     $smallest     = ($x & -$x);
     $ripple       = $x + $smallest;
     $new_smallest = ($ripple & -$ripple); // cool hack
     $ones         = (($new_smallest / $smallest) >> 1) - 1;
     return $ripple | $ones;
}

while (!$fieldsCount = abs((int)readline('Fields count:'))) {
	echo "Please enter a non-zero number of fields\n";
};

while (!$chipsCount = abs((int)readline('Chips count:'))) {
	echo "Please enter a non-zero number of chips\n";
};

if ($chipsCount > $fieldsCount) {
	die("This ain't gonna fit\n");
}

echo "Placing $chipsCount chips on $fieldsCount fields\n";

// маска вывода
$mask = "%0${fieldsCount}b";

$value = get_min_element($chipsCount);
$max   = get_max_element($chipsCount, $fieldsCount);

// Кол-во сочетаний будет равно `$fieldsCount! / $chipsCount! * ($fieldsCount - $chipsCount)!`,
// но считать факториалы для произвольных значение - зло.
// TODO: запросить изменение функционала для переноса кол-ва вариантов в конец
$count = fact($fieldsCount) / (fact($chipsCount) * fact($fieldsCount - $chipsCount));

$f = fopen('./result.txt', 'w');

if ($count < 10) {
	fwrite($f, 'менее 10 вариантов');
} else {
	fwrite($f, $count . "\n");
	while ($value <= $max) {
		fwrite($f, sprintf($mask, $value) . "\n");
		$value = get_next_permutation($value);
		// $count++;
	}
}

fclose($f);

echo "Total number of permutations: $count (see result.txt)\n";
