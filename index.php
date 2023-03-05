<?

require "test_vk_head.php" ; 	// шапка страницы

    
    // Начальные параметры для генерации лабиринта
    $width  = 10;
    $height = 10;
     
    // Инициализация
    $gr=[];
    $gr[]= array ( 0,0,0,0,0,0,0,0,0,0,0 );
    $gr[]= array ( 0,1,1,1,1,1,1,1,1,1,0 );
    $gr[]= array ( 0,1,0,0,0,0,1,0,0,1,0 );
    $gr[]= array ( 0,1,1,0,1,1,1,0,0,1,0 );
    $gr[]= array ( 0,1,0,1,1,0,0,1,0,1,0 );
    $gr[]= array ( 0,1,0,1,0,0,0,1,0,1,0 );
    $gr[]= array ( 0,1,0,1,0,1,0,1,1,1,0 );
    $gr[]= array ( 0,1,0,1,1,1,0,1,0,0,0 );
    $gr[]= array ( 0,1,0,0,0,1,0,1,0,1,0 );
    $gr[]= array ( 0,1,1,1,1,1,0,1,1,1,0 );
    $gr[]= array ( 0,0,0,0,0,0,0,0,0,0,0 );

     
	class Grid{
		protected $grid;		// лабиринт
		protected $start;		// точка старта
		protected $finish;		// точка финиша
		protected $way;			// путь 
		protected $way_length;	// длинна пути
		
	
//		инициализация лабиринта
		public function setGrid( $grid ){
			$this->grid = $grid;
		}

		public function setStart( $x,$y ){
			$this->grid[$x][$y] = 'S';
			$this->start['x'] = $x;
			$this->start['y'] = $y;
		}
		public function setFinish( $x,$y ){
			$this->grid[$x][$y] = 'F';
			$this->finish['x'] = $x;
			$this->finish['y'] = $y;		}

//		вывод "плана" лабиринта
		public function outGrid(){
			echo "<hr>\n";
			foreach( $this->grid as $line ){
				foreach( $line as $point ){
					if( $point === 0 ){
						echo '&#9632;'; 
					} elseif ( $point === 'F' ){
						echo 'F';
					} elseif ( $point === 'S' ){
						echo 'S';
					} else {
						echo $point;
					}
				}
				echo '<br>';
			}
		}

//		считаем соседей
		public function iCount( $x,$y ){
			$i = 0 ; 	// счетчик выходов/не стены/
			if( $this->grid[$x-1][$y] !== 0 ){ $i++; }
			if( $this->grid[$x+1][$y] !== 0 ){ $i++; }
			if( $this->grid[$x][$y-1] !== 0 ){ $i++; }
			if( $this->grid[$x][$y+1] !== 0 ){ $i++; }
			// echo "$x * $y = $i ; <br>";
			return $i;
		}
		
//		просеиваем сетку на предмет тупиков		
		public function sitoGrid(){
			$n = 0; // считаю количество закрытых тупиков
			$x = 0;	// текущая строка
			foreach( $this->grid as $line ){
				$y = 0;	// текущая столбец
				foreach( $line as $point ){
					// если это не точка старта или финиша и не стена
					if( $point !== 'S' && $point !== 'F' && $point !== 0 ){
						// если из точки несколько путей - это не тупик
						if( $this->iCount( $x,$y ) >1 ){ 
							$this->grid[$x][$y] = $this->iCount( $x,$y );
						} else {  // иначе признает точку стеной!
							$this->grid[$x][$y] = 0;
							$n++;
						}
					}
					$y++;	// следущий столбец
				}
				$x++;		// следующая строка
			}
			return $n; 
		}


		public function whoIs( $is ){
			if( ( $is > 0 && $is < 5 ) || ( $is === 'F' ) ){
				return true;
			}	
		}
		
		public function findWay(){
			$i = 0 ; 	// счетчик выходов/не стены/
			$way = [];
			$point = $this->start;
			echo "<br> findWay()";
			#var_export( $point );
			$x = $point['x'];
			$y = $point['y'];
			do {
				$i++;
				if(     $this->whoIs( $this->grid[$x-1][$y] )){ $x--;  }
				elseif( $this->whoIs( $this->grid[$x+1][$y] )){ $x++;  }
				elseif( $this->whoIs( $this->grid[$x][$y-1] )){ $y--;  }
				elseif( $this->whoIs( $this->grid[$x][$y+1] )){ $y++;  }

				echo "<br> [ {$this->grid[$x][$y]} ] $x : $y = $i ; ";
				if( $this->grid[$x][$y] === 'F'  ){
					echo '<h3>Finish!</h3>';
					break;
				} elseif ( $this->grid[$x][$y] > 2  ){
					$this->grid[$x][$y] = 7 ;
				} else {
					$this->grid[$x][$y] = 5 ;
				};
				$way[] = [$x][$y];
			} while ( $i < 50 || $this->grid[$x][$y] === 'F' );
			$this->way = $way;
			$this->way_length = count( $way );
			echo "<br>";
			return $i;
		}

	}	// class Grid

	$grid = new Grid;
	$grid->setGrid( $gr );
	$grid->setStart( 1,1 );
	$grid->setFinish( 9,9 );
	$grid->outGrid();
	echo "<br> закрываю тупики: ";
	do { 
		$n = $grid->sitoGrid();
		echo " - ".$n;
	} while ( $n > 0 );
	$grid->outGrid();
	$grid->findWay();
	$grid->outGrid();

	
    echo "<br>";
    echo "<br>\n";
	
require "test_vk_end.php" ; 	// низ страницы
?>