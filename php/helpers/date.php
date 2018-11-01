<?php

    namespace Helpers;

    Class Date {

        public static $days = ["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"];
        public static $months = ["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];

        public static function beautify($date){
            $time = time();
            //SECONDS
            if($time - 60 < $date){ return "Gerade eben"; }
            //MINUTE
            if($time - 60*60 < $date){ return "vor ".round(($time-$date)/60,0)."min"; }
            //HOUR
            if($time - 60*60*12 < $date){ return date("H:i",$date); }
            //DAYS
            if($time - 60*60*24*7 < $date){ return Self::$days[date("w",$date)]; }
            //WEEKS
            if($time - 60*60*24*14 < $date){ $w = round(($time-$date)/(60*60*24*7),0); return ($w == 1 ? "vor einer Woche" : "vor ".$w." Wochen"); }
            //SHORT DATE
            if($time - 60*60*24*365 < $date){ return date("d",$date).". ".Self::$months[intval(date("m",$date))-1]; }
            return date("d.m.Y",$date);
        }

    }

?>