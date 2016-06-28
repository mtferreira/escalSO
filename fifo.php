<?php

function testeArray($arr)
{
    $count = 0;
    foreach ($arr as $r) {
        if (!$r['lastTime']) {
            $count++;
        }
    }

    return $count;
}

function printProcess(array $arrProces)
{
    echo "Processo\t T.E.\t CPU\t T.W.\t T.F." . PHP_EOL;
    foreach ($arrProces as $pos => $proc) {
        echo "{$proc['number']}\t\t {$proc['timeInit']}\t {$proc['cpu']}\t {$proc['lastTime']}\t {$proc['time']}" . PHP_EOL;
    }
}

echo "----------- Sistema de escalonamento FIFO -----------" . PHP_EOL;
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
            'number' => count($process) + 1,
            'timeInit' => $timeIn,
            'cpu'      => $cpu,
            'time'     => 0,
            'lastTime' => ''
        ];

        $count--;
    }
} elseif ($option == 2) {
    while ($count > 0) {
        $timeIn = readline("Informe o tempo de entrada: ");
        $cpu    = readline("Informe o tempo de CPU necessário: ");

        $process[] = [
            'number'   => count($process) + 1,
            'timeInit' => $timeIn,
            'cpu'      => $cpu,
            'time'     => 0,
            'lastTime' => ''
        ];
        $count--;
    }
}

echo "----------- ANTES DE PROCESSAR-----------" . PHP_EOL;
printProcess($process);

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
foreach ($process as $p => $proc) {
    if ($proc['number'] == 1) {
        $arrProcessFinal[$p]['lastTime'] = 0;
        $arrProcessFinal[$p]['time'] = $arrProcessFinal[$p]['timeInit'] + $proc['cpu'];
    } else {
        $timeAnt = $arrProcessFinal[$p - 1]['time'];

        $arrProcessFinal[$p]['lastTime'] = $timeAnt - $arrProcessFinal[$p]['timeInit'];
        if ($arrProcessFinal[$p]['lastTime'] < 0) {
            $arrProcessFinal[$p]['lastTime'] = 0;
        }
        $arrProcessFinal[$p]['time']    = $timeAnt + $proc['cpu'];
    }

}

echo "----------- DEPOIS DE PROCESSAR-----------" . PHP_EOL;
printProcess($arrProcessFinal);