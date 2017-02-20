<?php

namespace Application\Service;

use DateTime;
use Zend\ServiceManager\Config;

/**
 * Description of DateService
 *
 * @author jasonpalmer
 */
class DateService extends BaseService{
    
    protected $time_string;
    
    protected $year;
    
    protected $month;
    
    protected $date;
    
    //new DateTime('today -1 day 1:00PM')
    public function __construct(array $config) {
        $this->time_string = $this->exists("time_string", $config['daily_cutoff']) ? 
                $config['daily_cutoff']['time_string'] : NULL;
        $this->year = $this->exists("year", $config['daily_cutoff']) ? 
                $config['daily_cutoff']['year'] : 1970;
        $this->month = $this->exists("month", $config['daily_cutoff']) ? 
                $config['daily_cutoff']['month'] : 1;
        $this->date = $this->exists("date", $config['daily_cutoff']) ? 
                $config['daily_cutoff']['date'] : 1;
    }
    
    private function exists($key, $array) {
        return array_key_exists($key, $array);
    }
    
    public function getDailyCutoff() {
        if(!empty($this->time_string) && 
                (empty($this->year) && empty($this->month) && empty($this->date))){
            return new DateTime($this->time_string);
        }else{
            $date = new DateTime();
            $date->setDate($this->year, $this->month, $this->date);
            if(!empty($this->time_string)){
                $date->modify($this->time_string);
            }
            return $date;
        }
    }
    
}
