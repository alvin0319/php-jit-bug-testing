<?php

declare(strict_types=1);

function fixedArrayRead(SplFixedArray $array, int $index) : mixed{
    return $array->offsetGet($index);
}

function makeDate(string $value) : DateTimeImmutable{
    return new DateTimeImmutable($value);
}

function iteratorSeek(ArrayIterator $iterator, int $position) : void{
    $iterator->seek($position);
}

$caught = 0;
$array = new SplFixedArray(1);
$iterator = new ArrayIterator([1]);
for($i = 0; $i < 10; $i++){
    try{
        fixedArrayRead($array, 5);
    }catch(RuntimeException $e){
        $caught++;
    }
    try{
        makeDate('not a date');
    }catch(Exception $e){
        $caught++;
    }
    try{
        iteratorSeek($iterator, 5);
    }catch(OutOfBoundsException $e){
        $caught++;
    }
}
echo "caught $caught\n";
