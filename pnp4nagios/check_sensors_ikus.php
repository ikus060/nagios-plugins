<?php
#
# PNP4Nagios template for check_openmanage 
# Author:     Trond Hasle Amundsen
# Contact:     t.h.amundsen@usit.uio.no
# Website:      http://folk.uio.no/trondham/software/check_openmanage.html
# Date:     2011-06-01
#
# $Id: check_openmanage.php 20353 2011-06-06 13:10:52Z trondham $
#
# Copyright (C) 2008-2011 Trond H. Amundsen
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
# General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Array with different colors
$colors = array(
    "fce94f", 
    "fcaf3e", 
    "8ae232", 
    "729fcf", 
    "ad7fa8", 
    "ef2920",
    "c4a000", 
    "ce5c00", 
    "4e9a06", 
    "204a87", 
    "5c3566", 
    "a40000",
    "edd400", 
    "f57900", 
    "73d216", 
    "3465a4", 
    "75507b", 
    "cc0000");
$colors = array("0022ff", "22ff22", "ff0000", "00aaaa", "ff00ff",
		"ffa500", "cc0000", "0000cc", "0080C0", "8080C0",
		"FF0080", "800080", "688e23", "408080", "808000",
		"000000", "00FF00", "0080FF", "FF8000", "800000",
		"FB31FB");

# Counters
$t = 0;      # temp probe counter
$w = 0;      # watt probe counter
$v = 0;      # volt probe counter
$f = 0;      # fan probe counter
$o = 0;      # other probe counter

# Flags
$visited_amp  = 0;

# IDs
$id_temp  = 1;
$id_watt  = 2;
$id_volt  = 3;
$id_fan   = 4;
$id_other = 5;

# Enclosure id
$enclosure_id = '';

# Default title
$def_title = 'Sensors';

# Temperature unit
if (!defined('tempunit_defined')) {
    define('tempunit_defined', 1);

    function tempunit($arg) 
    {
    $unit   = 'unknown';
    $vlabel = 'unknown';
        
    switch ($arg) {
    default:
        $vlabel = "Celsius";
        $unit = "°C";
        break;
    case "F":
        $vlabel = "Fahrenheit";
        $unit = "°F";
        break;
    case "K":
        $vlabel = "Kelvin";
        $unit = "K";
        break;
    case "R":
        $vlabel = "Rankine";
        $unit = "°R";
        break;
    }
    return array($unit, $vlabel);
    }
}

#------------------------------------------------------
#  MAIN LOOP
#------------------------------------------------------
# Loop through the performance data
foreach ($this->DS as $KEY=>$VAL) {

    $label = $VAL['LABEL'];

    # TEMPERATURES
    if (preg_match('/^temp/', $label) ||
            preg_match('/TIN$/', $label) ||
            preg_match('/Temp$/', $label) ||
            preg_match('/Physicalid\d/', $label) ||
            preg_match('/Core\d/', $label) ||
            $VAL['UNIT'] == "C") {

        # Temperature unit and vertical label
        list ($unit, $vlabel) = tempunit($VAL['UNIT']);

        # Long label
        $label = preg_replace('/^(.*)Temp$/', '$1', $label);
        $label = preg_replace('/^(.*)TIN$/', '$1', $label);
        $label = preg_replace('/_/', ' ', $label);

        $ds_name[$id_temp] = "Temperatures";

        $opt[$id_temp] = "--slope-mode --vertical-label \"$vlabel\" --title \"$def_title: Chassis Temperatures\" ";
        if (isset($def[$id_temp])) {
            $def[$id_temp] .= rrd::def("var$KEY", $VAL['RRDFILE'],$VAL['DS'],"AVERAGE") ;
        }
        else {
            $def[$id_temp] = rrd::def("var$KEY",$VAL['RRDFILE'],$VAL['DS'],"AVERAGE") ;
        }
        $def[$id_temp] .= rrd::line1("var$KEY", "#".$colors[$t++], rrd::cut($label,20) );
        $def[$id_temp] .= rrd::gprint("var$KEY", array("LAST", "MAX", "AVERAGE"), "%4.1lf $unit");
    }

    # WATTAGE PROBE
    elseif (preg_match('/^W/', $label) || preg_match('/Power$/', $label)) {

        # Long label
        $label = preg_replace('/^(.+)Power$/', '$1', $label);

        $ds_name[$id_watt] = "Power Consumption";
        $vlabel = "Watts";

        $title = $ds_name[$id_watt];

        $opt[$id_watt] = "--slope-mode --vertical-label \"$vlabel\" --title \"$def_title: $title\" ";
        if(isset($def[$id_watt])){
            $def[$id_watt] .= rrd::def("var$KEY", $VAL['RRDFILE'], $VAL['DS'],"AVERAGE");
        }
        else {
            $def[$id_watt] = rrd::def("var$KEY",$VAL['RRDFILE'],$VAL['DS'],"AVERAGE");
        }
        $def[$id_watt] .= rrd::line1("var$KEY", "#".$colors[$w++], rrd::cut($label,18) ) ;
        $def[$id_watt] .= rrd::gprint("var$KEY", array("LAST", "MAX", "AVERAGE"),"%8.2lf A");

    }

    # VOLTAGE PROBE
    elseif ( preg_match('/^V/', $label) ||
            preg_match('/^in\d$/', $label) ||
            preg_match('/^AVCC$/', $label) ||
            preg_match('/V$/', $label) ||
            preg_match('/^3VSB$/', $label) ||
            $VAL['UNIT'] == "V") {

        # Long label
        $label = preg_replace('/^V(\d+)_(.+)/', '$2', $label);
        $label = preg_replace('/_/', ' ', $label);

        # Short label
        $label = preg_replace('/^V(\d+)$/', 'Probe $1', $label);
        
        $ds_name[$id_volt] = "Voltage Probes";
        $vlabel = "Volts";

        $title = $ds_name[$id_volt];

        $opt[$id_volt] = "--slope-mode --vertical-label \"$vlabel\" --title \"$def_title: $title\" ";
        if(isset($def[$id_volt])){
            $def[$id_volt] .= rrd::def("var$KEY", $VAL['RRDFILE'], $VAL['DS'],"AVERAGE");
        }
        else {
            $def[$id_volt] = rrd::def("var$KEY",$VAL['RRDFILE'],$VAL['DS'],"AVERAGE");
        }
        $def[$id_volt] .= rrd::line1("var$KEY", "#".$colors[$v++], rrd::cut($label,18) ) ;
        $def[$id_volt] .= rrd::gprint("var$KEY", array("LAST", "MAX", "AVERAGE"),"%8.2lf V");
    }

    # FANS (RPMs)
    elseif (preg_match('/^fan/i', $label) || $VAL['UNIT'] == "RPM") {

        $ds_name[$id_fan] = "Fan Speeds";
        $vlabel = "Volts";

        $title = $ds_name[$id_fan];

        $opt[$id_fan] = "-X0 --slope-mode --vertical-label \"RPMs\" --title \"$def_title: $title\" ";
        if(isset($def[$id_fan])){
            $def[$id_fan] .= rrd::def("var$KEY",$VAL['RRDFILE'],$VAL['DS'], "AVERAGE") ;
        }
        else {
            $def[$id_fan] = rrd::def("var$KEY",$VAL['RRDFILE'],$VAL['DS'], "AVERAGE") ; 
        }
        $def[$id_fan] .= rrd::line1("var$KEY", "#".$colors[$f++], rrd::cut($label,18) ) ;
        $def[$id_fan] .= rrd::gprint("var$KEY", array("LAST", "MAX", "AVERAGE"), "%6.0lf RPM");
    }

    # OTHERS
    else {

        if ($VAL['UNIT'] == "%%") {
            $vlabel = "%";
            $upper = " --upper=101 ";
            $lower = " --lower=0 ";
        }
        else {
            $vlabel = $VAL['UNIT'];
            $upper = "";
            $lower = "";
        }

        $opt[$id_other] = '--vertical-label "' . $vlabel . '" --title "' . $def_title . ': ' . $label . '"' . $upper . $lower;
        $ds_name[$id_other] = $VAL['LABEL'];
        $def[$id_other]  = rrd::def     ("var$KEY", $VAL['RRDFILE'], $VAL['DS'], "AVERAGE");
        #$def[$id_other] .= rrd::gradient("var$KEY", "3152A5", "BDC6DE", rrd::cut($VAL['NAME'],16), 20);
        $def[$id_other] .= rrd::line1   ("var$KEY", "#".$colors[$o++], rrd::cut($label,18) );
        $def[$id_other] .= rrd::gprint  ("var$KEY", array("LAST","MAX","AVERAGE"), "%3.4lf ".$VAL['UNIT']);
        #$def[$id_other] .= rrd::comment("Default Template\\r");
        #$def[$id_other] .= rrd::comment("Command " . $VAL['TEMPLATE'] . "\\r");
        $id_other++;
    }
    
}

?>
