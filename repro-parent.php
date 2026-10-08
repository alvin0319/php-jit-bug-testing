<?php

declare(strict_types=1);

$suppress = ($argv[1] ?? 'false') === 'true';
$runs = (int) ($argv[2] ?? 10);
const ACCESS_VIOLATION = -1073741819;
$crashes = 0;
for($i = 1; $i <= $runs; $i++){
    $cmd = 'start "" /b /wait ' . PHP_BINARY . ' -n -c ' . __DIR__ . '\child.ini ' . __DIR__ . '\repro-child.php & exit';
    $proc = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, __DIR__, getenv(), ['suppress_errors' => $suppress]);
    fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($proc);
    if($code === ACCESS_VIOLATION){
        $crashes++;
    }
    echo "run $i: exit $code, output: " . trim($out) . "\n";
}
echo "suppress_errors=" . var_export($suppress, true) . ": $crashes / $runs runs crashed\n";
exit($crashes > 0 ? 1 : 0);
