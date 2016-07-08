<?php

error_reporting(E_ERROR);
/**
 * Esta função é responsável por verificar se existe
 * algum processo com tempo de execução diferente que o tempo de CPU
 * @param $proccess: array de processos ou um array com informaçoes de 1 processo
 * @return bool
 */
function testExec($proccess)
{
    // verifica se é um array de array de processos
    if (count($proccess) != count($proccess, COUNT_RECURSIVE)) {
        foreach ($proccess as $r) {
            if ($r['timeExec'] != $r['cpu']) {
                return true;
            }
        }
    } elseif ($proccess['timeExec'] != $proccess['cpu']) {
        return true;
    }

    return false;
}

function updateProccess($process, $procAtual, $quantum)
{
    $arrUpdated = $process;
    foreach ($process as $pos => $proc) {
        if ($procAtual == $pos) {
            continue;
        }
        if (testExec($process) && ($process['timeInit'] <= $process[$procAtual]['time'])) {
            $arrUpdated[$pos]['time'] += $quantum;
            $arrUpdated[$pos]['lastTime'] += $quantum;
        }
    }

    return $arrUpdated;
}

function printProcess(array $arrProces)
{
    echo "Processo\t T.E.\t CPU\t T.W.\t T.F." . PHP_EOL;
    foreach ($arrProces as $pos => $proc) {
        echo "{$proc['number']}\t\t {$proc['timeInit']}\t {$proc['cpu']}\t {$proc['lastTime']}\t {$proc['time']}" . PHP_EOL;
    }
}

echo "----------- Sistema de escalonamento RR(Circular) -----------" . PHP_EOL;
$process = array();

echo "Deseja inserir os valores ou o sistema prefere que o sistema gere aleatoriamente?" . PHP_EOL;
echo "[1] - Gerar aleatoriamente" . PHP_EOL;
echo "[2] - Entrar com os dados" . PHP_EOL;

$option  = readline();
$count   = readline("Quantidade de processos a gerar: ");
$quantum = readline("Informe o valor do Quantum: ");

if ($option == 1) {
    while ($count > 0) {
        $timeIn = rand(0, 60);
        $cpu    = rand(0, 60);

        $process[] = [
            'number' => count($process) + 1,
            'timeInit' => $timeIn,
            'cpu'      => $cpu,
            'time'     => $timeIn,
            'lastTime' => 0,
            'timeExec' => 0
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
            'time'     => $timeIn,
            'lastTime' => 0,
            'timeExec' => 0,
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

while (testExec($process)) {

    while (list($p, $proc) = each($process)) {
        $countQuantum = 0;
        while (testExec($process[$p]) && $countQuantum < $quantum) {
            $process[$p]['time']++;
            $process[$p]['timeExec']++;
            $countQuantum++;
        }

        $process = updateProccess($process, $p, $countQuantum);
    }
}

echo "----------- DEPOIS DE PROCESSAR-----------" . PHP_EOL;
printProcess($process);