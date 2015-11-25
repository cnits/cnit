<?php
/*
 * Code Owner: Cnit
 * Modified Date: 11/25/2015
 * Modified By: Phong Lam
 */

class CDate {

    public static function getDateTime($string, $timezone = false, $format = null)
    {
        if(empty($string)){
            return '';
        }else{
            $_fm = 'Y-m-d h:i:s';
            if(is_string($string)){
                if(strtotime($string) == false || strtotime($string) == -1){
                    return 'Invalid Date';
                }
            }else{
                if(is_array($string)){
                    $string = isset($string['date'])?$string['date']:'';
                }
                else
                    if(is_object($string) && $string instanceof \DateTime){
                        $string = $string->format($_fm);
                    }else{
                        if(is_object($string) && $string instanceof \MongoDate){
                            $string = date($_fm, floatval($string->sec) + floatval($string->usec/1000000));
                        }else{
                            return 'Invalid Date';
                        }
                    }
            }
        }
        $dd = new \DateTime($string, new \DateTimeZone('UTC'));
        if($timezone === false){
            $timezone = date_default_timezone_get();
        }
        $dd->setTimezone(new \DateTimeZone($timezone));
        if($format != null){
            return $dd->format($format);
        }
        return $dd->format('m/d/Y h:i:s A');
    }

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