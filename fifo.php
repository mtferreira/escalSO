<?php
echo "----------- Sistema de escalonamento FIFO -----------" . PHP_EOL;

$i       = 1;
$more    = 'S';
$process = [];


while ($more=='S') 
{
    echo "------- Entre com informações do processo $i -------" . PHP_EOL;
    
    $time = readline("Tempo de chegada na fila: ");
    $cpu  = readline("Tempo de CPU necessário: ");
    
    $process[] = ['time' => $time, 'cpu' => $cpu, 'timeLate' => ''];

    $more = readline("Deseja acrescentar outro processo?" . PHP_EOL . "[S] - Sim". PHP_EOL . "[N] - N". PHP_EOL);
    
    while ($more!="S" && $more != "N") 
    {
        echo "Não entende o que quis dizer, Vamos tentar novamente" . PHP_EOL;
        $more = readline("Deseja acrescentar outro processo? [S] - Sim [N] - N ");
    }

    $i++;
}

print_r($process);
