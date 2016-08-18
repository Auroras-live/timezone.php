<?php
  namespace AurorasLive;
  class timezone extends DateTime {

    public $offset, $offsetFormat;

    function __construct($date = "now", $offset = null) {
      $this->offset = $offset;
      if(date_create($date) === false) {
        return false;
      } else {
        // If we haven't explicitly set an offset
        if(is_null($this->offset)) {
          // If we have set a timezone via $_REQUEST
          if(isset($_REQUEST["tz"])) {
            // Set our offset, to whatever we $_REQUESTed
            $this->offset = $_REQUEST["tz"];
            // Return the difference between UTC and the offset, as a + or - value (e.g. -600 = +10:00)
            $this->offsetFormat = (new \DateTime($date, new \DateTimeZone("UTC")))->diff((new \DateTime($date, new \DateTimeZone("UTC")))->sub(\DateInterval::createFromDateString($this->offset . " minutes")))->format("%R%H:%I");
          // If we haven't set a timezone, and timezone is null,
          } else {
            // Set offset to 0 and be done with it
            $this->offsetFormat = "+00:00";
            $this->offset = 0;
          }
        // If we have set an offset via $offset,
        } else {
          // Return the difference between UTC and the offset, same as above.
          $this->offsetFormat = (new \DateTime($date, new \DateTimeZone("UTC")))->diff((new \DateTime($date, new \DateTimeZone("UTC")))->sub(\DateInterval::createFromDateString($this->offset . " minutes")));
          $this->offsetFormat = $this->offsetFormat->format("%R%H:%I");
        }
        // If the offset in $date does NOT match the offset we're passing
        if(((new \DateTime($date))->getOffset() / 60) !== $this->offset) {
          // Assume offset hasn't been added / subtracted, so modify the date
          $date = (new \DateTime($date, new \DateTimeZone("UTC")))->sub(\DateInterval::createFromDateString($this->offset . " minutes"));
        // If the offset DOES match
        } else {
          // Then assume the offset has been applied
          $date = (new \DateTime($date, new \DateTimeZone("UTC")));
        }

        // Finally, construct the date, append our timezone offset to it, then pass it up to our parent class
        parent::__construct($date->format("Y-m-d\TH:i:s") . $this->offsetFormat, null);
      }
    }

    function fromNow($suffix = true) {
      return $this->ago($suffix);
    }

    function ago($suffix = true, $abbr = false) {

      $current = (new \timezone("now", $this->offset));
      $diff = $this->diff($current);

      if($abbr = false) {
        $prefixes = array(
          "year" => " year" . ($diff->y != 1 ? "s" : ""),
          "month" => " month" . ($diff->m != 1 ? "s" : ""),
          "day" => " day" . ($diff->d != 1 ? "s" : ""),
          "hour" => " hour" . ($diff->h != 1 ? "s" : ""),
          "minute" => " minute" . ($diff->i != 1 ? "s" : ""),
          "second" => " second" . ($diff->s != 1 ? "s" : "")
        );
      } else {
        $prefixes = array(
          "year" => " yr" . ($diff->y != 1 ? "s" : ""),
          "month" => " mth" . ($diff->m != 1 ? "s" : ""),
          "day" => " day" . ($diff->d != 1 ? "s" : ""),
          "hour" => "hr" . ($diff->h != 1 ? "s" : ""),
          "minute" => " min" . ($diff->i != 1 ? "s" : ""),
          "second" => "s"
        );
      }

      $age = "";
      if($diff->y != 0) {
        $age = $diff->y . $prefixes["year"];
      } else if($diff->m != 0) {
       $age = $diff->m . $prefixes["month"];
     } else if($diff->d != 0) {
       $age = $diff->d . $prefixes["day"];
     } else if($diff->h != 0) {
       $age = $diff->h . $prefixes["hour"];
     } else if($diff->i != 0) {
       $age = $diff->i . $prefixes["minute"];
     } else if($diff->s != 0) {
       $age = $diff->s . $prefixes["second"];
      }

      if($current > $this) {
        if($suffix) {
          $age .= " ago";
        }
      } else {
        if($suffix) {
          $age .= " from now";
        }
      }

      return $age;
    }

  }


 ?>
