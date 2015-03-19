<!DOCTYPE html>
<script charset="UTF-8" type="text/javascript" language="JavaScript" src="lib/built-in/amlich-js/JavaScript/amlich-hnd.js"></script>
<?php
	//echo '<div onload="showVietCal();">t</div>';
	class _Class {
		/*
		 * Date: Jan 12nd, 2015
		 * Author: Phong Lam
		 * Functionality: Get quarter of date in concrete year
		 */
		public static function getQuarterOfDate($strdate, $year, $startmonth = 1) {
			//setup $startmonth value
			if(!is_numeric($startmonth) || $startmonth > 12 || $startmonth < 0){
				$startmonth = 1;
			}else{
				$startmonth = intval($startmonth);
			}
			//setup $endmonth value
			$endmonth = 12;
			if($startmonth > 1){
				$endmonth = $startmonth - 1;
			}
			//calculate quarter of date
			$n = intval(date('n', strtotime($strdate)));
			$m = date('M', strtotime($strdate));
			$y = floatval(date('Y', strtotime($strdate)));
			$year = floatval($year);
			if ($y < $year || ($y == $year && $n < $startmonth) || ($y > $year && $n > $endmonth) || $y > $year + 1) {
				return null;
			} else {
				$const = 0;
				if ($n >= $startmonth) {
					$const = $startmonth - 1;
				} else {
					$const = $endmonth - 12;
				}
				$n -= $const;
			}
			return array(
				strval($year) => $m . '_Q' . ceil($n / 3)
			);
		}
	
		public static function getFiscalYearOfDate($strdate = '', $startmonth = 1){
			if(!is_numeric($startmonth) || $startmonth > 12 || $startmonth < 0){
				$startmonth = 1;
			}else{
				$startmonth = intval($startmonth);
			}
			if(strtotime($strdate) === false || strtotime($strdate) == -1){
				$strdate = date('d-M-Y h:i:s A');
			}
			$n = intval(date('n', strtotime($strdate)));
			$y = floatval(date('Y', strtotime($strdate)));
			if($n >= $startmonth){
				return $y;
			}else{
				return --$y;
			}
		}
		
		public static function getWeekBeginSunday($stringDate=''){
			$dateProcess = new \DateTime($stringDate);
			//check if is sunday ++1
			if($dateProcess->format('w') == 0){
				$dateProcess->add(date_interval_create_from_date_string("1 day"));
			}
			return $dateProcess->format('W');
		}

		public static function generateQuartersOfYear($year, $month){
			$quarters = array();
			if(!is_numeric($year) || empty($year)){
				$year = date('Y');
			}
			if(!is_numeric($month) || $month > 12 || $month < 0){
				$month = 1;
			}else{
				$month = intval($month);
			}
			$date = new \DateTime();
			$d = $date -> setDate($year, $month, 1);
			$i = 0;
			do{
				$rs = self::getQuarterOfDate($d -> format('d-M-Y'), $year, $month);
				if(isset($rs[$year])){
					$quarters[] = array(
						'MQ' => $rs[$year],
						'CY' => $d -> format('Y'),
						'FM' => $d -> format('m')
					);
				}
				$d = $date -> add(\DateInterval::createFromDateString('1 months'));
				$i++;
			}while($i < 12);
			return $quarters;
		}
	}
	echo "<pre>";
	//var_dump(_Class::getQuarterOfDate('12-Jun-09', 2010));
	//$s = date_parse_from_format('m/d/Y', '2/14/2014');
	//var_dump(_Class::generateQuartersOfYear(2014, 6));

	$lastDate = new \DateTime('2014-09-22T08:30:00.610Z');
	$sDate = new \DateTime('2014-09-22T08:30:00.610Z');

	//$lastDate->sub(\DateInterval::createFromDateString('3 days'));
	$sDate -> setTimezone(new \DateTimeZone("America/Los_Angeles"));
	var_dump($lastDate->format('Y-m-d H:i:s'));
	var_dump($sDate->format('Y-m-d H:i:s'));
	
	
?>