<?php

namespace Application\Utility;

use DateTime;
use DateTimeZone;

/**
 * Description of DateUtils
 *
 * @author jasonpalmer
 */
class DateUtils {
    
    public static function getDailyCutoff() {
        //Jeff asked for temporary override to return all rows and not limiting by cutoff
        //$pre1pm = false;
        //if (date('H') < 13) {
        //    $pre1pm = true;
        //}
        //if ($pre1pm) {
            //it is before 1:00PM UTC now - so set the query to retrieve all rows since yesterday at 1:00PM
        //    return DateUtils::fromOneToOne();
        //} else {
            //it is after 1:00PM UTC now - so set the query to retrieve all rows since today at 1:00PM
        //    return DateUtils::fromOne();
        //}
        $date = new DateTime();
        $date->setDate(2017, 1, 1);
        return $date;
    }

    public static function fromOne() {
        return DateUtils::getDateTime('today  1:00PM');
    }
    
    public static function fromOneToOne() {
        return DateUtils::getDateTime('today -1 day 1:00PM');
    }
    
    private static function getDateTime($start){
        $date = strtotime($start);
        $tz_string = "US/Samoa"; // Use one from list of TZ names http://php.net/manual/en/timezones.php UTC?
        $tz_object = new DateTimeZone($tz_string);
        $datetime = new DateTime();
        $datetime->setTimestamp($date);
        $datetime->setTimezone($tz_object);
        return $datetime;
    }

}
