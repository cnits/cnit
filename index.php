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
				$year => $m . '_Q' . ceil($n / 3)
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
	}
	
	//var_dump(_Class::getQuarterOfDate('12-Jun-09', 2010));
	//$s = date_parse_from_format('m/d/Y', '2/14/2014');
	$array = array();
	$bizObjectName = substr('CrmAccountEntity', 0, strlen('CrmAccountEntity') - 6);
	$bizObjectName = str_replace("Entity", "Biz", "CrmAccountEntity");
	var_dump($bizObjectName);
	
	
	
	
	
	
?>