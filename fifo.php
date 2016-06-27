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

function printProcess(array $arrProces)
{
    echo "Processo\t Time In\t CPU\t Waiting Time".PHP_EOL;
    foreach ($arrProces as $pos => $proc) {
        echo "$pos\t\t {$proc['timeInit']}\t\t {$proc['cpu']}\t\t {$proc['lastTime']}" . PHP_EOL;
    }
}

echo "----------- Sistema de escalonamento FIFO -----------" . PHP_EOL;
$more    = 'S';
$process = array();

echo "Deseja inserir os valores ou o sistema prefere que o sistema gere aleatoriamente?" . PHP_EOL;
echo "[1] - Gerar aleatoriamente" . PHP_EOL;
echo "[2] - Entrar com os dados" . PHP_EOL;

$option = readline();
$count  = readline("Quantidade de processos a gerar: ");

if ($option == 1) {
    while ($count > 0) {
        $timeIn = rand(0, 60);
        $cpu    = rand(0, 60);

        $process[] = [
            'timeInit' => $timeIn,
            'cpu'      => $timeIn,
            'time'     => 0,
            'lastTime' => ''
        ];

        $count--;
    }
} elseif($option == 2) {
    while ($count > 0) {
        $timeIn = readline("Informe o tempo de entrada: ");
        $cpu    = readline("Informe o tempo de CPU necessário: ");

        $process[] = [
            'timeInit' => $timeIn,
            'cpu'      => $timeIn,
            'time'     => 0,
            'lastTime' => ''
        ];
        $count--;
    }
}

// ordena em ordem de tempo de chegada
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
printProcess($arrProcessFinal);
$i = 0;
foreach ($process as $p => $proc) {
    $arrProcessFinal                 = updateProcess($arrProcessFinal, $proc['cpu'], $i);
    $arrProcessFinal[$p]['lastTime'] = $arrProcessFinal[$p]['time'];
    $i++;
}

echo "----------- DEPOIS DE PROCESSAR-----------" . PHP_EOL;
printProcess($arrProcessFinal);