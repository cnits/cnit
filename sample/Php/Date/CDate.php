<?php
/*
 * Code Owner: CNIT
 * Modified Date: 11/25/2015
 * Modified By: Phong Lam
 */
namespace CNIT;

class CDate {

    public static function getDateTime($datetime, $timezone = false, $format = null)
    {
        if(empty($datetime)){
            $datetime = 'now';
        }else{
            $_fm = 'Y-m-d h:i:s';
            if(is_string($datetime)){
                if(strtotime($datetime) == false || strtotime($datetime) == -1){
                    $datetime = 'now';
                }
            }else{
                if(is_array($datetime)){
                    $datetime = isset($datetime['date'])? $datetime['date']: 'now';
                }
                else
                    if(is_object($datetime) && $datetime instanceof \DateTime){
                        $datetime = $datetime->format($_fm);
                    }else{
                        if(is_object($datetime) && $datetime instanceof \MongoDate){
                            $datetime = date($_fm, floatval($datetime->sec) + floatval($datetime->usec/1000000));
                        }else{
                            $datetime = 'now';
                        }
                    }
            }
        }
        $dd = new \DateTime($datetime, new \DateTimeZone('UTC'));
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
    public static function getQuarterOfDate($date, $year, $start_month = 1) {
        // Start Month:
        if(!is_numeric($start_month) || $start_month > 12 || $start_month < 0){
            $start_month = 1;
        }else{
            $start_month = intval($start_month);
        }
        // End Month:
        $end_month = 12;
        if($start_month > 1){
            $end_month = $start_month - 1;
        }
        // calculate quarter of date:
        $n = intval(date('n', strtotime($date)));
        $m = date('M', strtotime($date));
        $y = floatval(date('Y', strtotime($date)));
        $year = floatval($year);
        if ($y < $year || ($y == $year && $n < $start_month)
            || ($y > $year && $n > $end_month) || $y > $year + 1) {
            return null;
        } else {
            if ($n >= $start_month) {
                $const = $start_month - 1;
            } else {
                $const = $end_month - 12;
            }
            $n -= $const;
        }
        //Fc ~ Fiscal
        return array(
            "FcYear" => $year,
            "FcMonth" => $m,
            "FcQuarter" => ceil($n /3)
        );
    }

    public static function getFiscalYearOfDate($date = '', $start_month = 1){
        if(!is_numeric($start_month) || $start_month > 12 || $start_month < 0){
            $start_month = 1;
        }else{
            $start_month = intval($start_month);
        }
        if(strtotime($date) === false || strtotime($date) == -1){
            $date = date('d-M-Y h:i:s A');
        }
        $n = intval(date('n', strtotime($date)));
        $year = floatval(date('Y', strtotime($date)));
        if($n >= $start_month){
            return $year;
        }else{
            return --$year;
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
            if(is_array($rs) && count($rs) > 0){
                //Cd ~ Calendar
                $quarters[] = array(
                    'FcMonth' => $rs["FcMonth"],
                    'FcQuarter' => $rs["FcQuarter"],
                    'CdYear' => $d -> format('Y')
                );
            }
            $d = $date -> add(\DateInterval::createFromDateString('1 months'));
            $i++;
        } while ($i < 12);
        return $quarters;
    }

    public static function getDateInLinux() {
        $cmd = 'date +%z';
        return exec($cmd);
    }

    public static function getOffsetTZ($timezone = "UTC") {
        if(empty($timezone)) {
            $timezone = "UTC";
        }
        $date = new \DateTime('now', new \DateTimeZone($timezone));
        $os =  $date->getOffset();
        $os = $os / 3600;
        if($os > 0) {
            $offset = '+0'.$os.':00';
        } elseif($os < 0) {
            $os = $os*(-1);
            $offset = '-0'.$os.':00';
        } else {
            $offset = "00:00";
        }
        return $offset;
    }

    public static function add($date = 'now', $interval = "1 day") {
        $date = new \DateTime($date);
        $date ->add(\DateInterval::createFromDateString($interval));
        return $date;
    }

    public static function sub($date = 'now', $interval = "1 day") {
        $date = new \DateTime($date);
        $date ->sub(\DateInterval::createFromDateString($interval));
        return $date;
    }
}