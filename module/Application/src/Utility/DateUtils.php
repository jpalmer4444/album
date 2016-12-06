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
        $pre1pm = false;
        if (date('H') < 13) {
            $pre1pm = true;
        }
        if ($pre1pm) {
            //it is before 1:00PM UTC now - so set the query to retrieve all rows since yesterday at 1:00PM
            return DateUtils::fromOneToOne();
        } else {
            //it is after 1:00PM UTC now - so set the query to retrieve all rows since today at 1:00PM
            return DateUtils::fromOne();
        }
    }

    public static function fromOne() {
        $date = strtotime('today');
        $time = "13:00:00"; //overwrite time to 1:00 if it is after 1:00.
        $tz_string = "US/Samoa"; // Use one from list of TZ names http://php.net/manual/en/timezones.php UTC?
        $tz_object = new DateTimeZone($tz_string);
        $datetime = new DateTime();
        $datetime->setTimestamp($date);
        $datetime->setTimezone($tz_object);
        return $datetime;
    }
    
    public static function fromOneToOne() {
        $date = strtotime('today -1 day');
        $time = "13:00:00"; //overwrite time to 1:00 if it is after 1:00.
        $tz_string = "US/Samoa"; // Use one from list of TZ names http://php.net/manual/en/timezones.php UTC?
        $tz_object = new DateTimeZone($tz_string);
        $datetime = new DateTime();
        $datetime->setTimestamp($date);
        $datetime->setTimezone($tz_object);
        return $datetime;
    }

}
