<?


    //-------------------------------------------------------
    // Рекурсивно-возвратный алгоритм генерации лабиринта
    //-------------------------------------------------------
     
    // Начальные параметры для генерации лабиринта
    $width  = 10;
    $height = 10;
     
    // Инициализация
    $grid=array(array(),array());
    for($y=0; $y<$height; $y++) {
        for($x=0; $x<=$width; $x++) {
            $grid[$y][$x]=0;
        }
    }
    $N=1; $S=2; $E=4; $W=8;
     
    // Рекурсивная функция создания проходов в лабиринте
    function passage($cx,$cy) {
        global $grid,$width,$height,$N,$S,$E,$W;
     
        // Клетки для направления движения
        $DX=array($E=>1, $W=>-1, $N=>0, $S=>0);
        $DY=array($E=>0, $W=>0, $N=>-1, $S=>1);
        $OP=array($E=>$W, $W=>$E, $N=>$S, $S=>$N);
     
        $directions=array($N, $S, $E, $W);
        shuffle($directions);
     
        foreach($directions as $direction) {
            $nx=$cx+$DX[$direction];
            $ny=$cy+$DY[$direction];
     
            if ($ny>=0 && $ny<$height && $nx>=0 && $nx<$width && $grid[$ny][$nx]==0) {
                $grid[$cy][$cx] |= $direction;
                $grid[$ny][$nx] |= $OP[$direction];
                passage($nx, $ny);
            }
        }
    }
    passage(0,0);
     
    // Вывести лабиринт в консоль
    echo "<br>\n";
    echo ' '.str_repeat('_', ($width*2-1))."<br>\n";
    for($y=0; $y<$height; $y++) {
        echo '|';
        for($x=0; $x<$width; $x++) {
            echo (($grid[$y][$x] & $S) != 0) ? " " : "_";
            if (($grid[$y][$x] & $E) != 0) {
                echo ((($grid[$y][$x] | $grid[$y][$x+1]) & $S) != 0) ? " " : "_";
            }
            else {
                echo '|';
            }
        }
        echo "<br>\n";
    }
	
    echo "<hr>";
    echo "<br>\n";
	var_export($grid);

?>