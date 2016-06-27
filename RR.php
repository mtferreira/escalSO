<?php

function updateProcess(array $arrProcess, $timeMore, $current)
{
    foreach ($arrProcess as $pos => $process) {
        if ($process['lastTime']) {
            continue;
        }

        if ($current >= $process['timeInit']) {
            $timeMore = $timeMore - $process['timeInit'];
            $arrProcess[$pos]['time'] += $timeMore;
        }
    }

    return $arrProcess;
}

echo "----------- Sistema de escalonamento FIFO -----------" . PHP_EOL;
$quantum = 2;
$process = array(
    ['timeInit' => 0, 'cpu' => 4, 'time' => 0, 'lastTime' => ''],
    ['timeInit' => 1, 'cpu' => 6, 'time' => 0, 'lastTime' => ''],
    ['timeInit' => 5, 'cpu' => 2, 'time' => 0, 'lastTime' => ''],
    ['timeInit' => 2, 'cpu' => 3, 'time' => 0, 'lastTime' => ''],
    ['timeInit' => 0, 'cpu' => 2, 'time' => 0, 'lastTime' => ''],
);

// ordena em ordem de tempo de execução
usort(
    $process,
    function ($a, $b) {
        if ($a['timeInit'] == $b['timeInit']) {
            return 0;
        }

        return ($a['timeInit'] < $b['timeInit']) ? -1 : 1;
    }
);

$arrProcessFinal = $process;
echo "----------- ANTES DE PROCESSAR-----------" . PHP_EOL;
print_r($arrProcessFinal);
$i = 0;

foreach ($process as $p => $proc) {
    $arrProcessFinal                 = updateProcess($arrProcessFinal, $quantum, $i);
    $arrProcessFinal[$p]['lastTime'] = $arrProcessFinal[$p]['time'];
    $i++;
}

echo "----------- DEPOIS DE PROCESSAR-----------" . PHP_EOL;
print_r($arrProcessFinal);
