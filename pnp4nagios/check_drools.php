<?php

#   This program is free software; you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation; either version 2 of the License, or
#   (at your option) any later version.
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with this program; if not, write to the Free Software
#   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

#   PNP Template for check_cpu.sh
#   Author: Mike Adolphs (http://www.matejunkie.com/

$opt[1] = "--vertical-label \"CPU [%]\" -u 100 -l 0 -r --title \"CPU Usage for $hostname / $servicedesc\" ";

$def[1] =  "DEF:used=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .=  "DEF:nice=$RRDFILE[2]:$DS[2]:AVERAGE " ;
$def[1] .=  "DEF:sys=$RRDFILE[3]:$DS[3]:AVERAGE " ;
$def[1] .=  "DEF:iowait=$RRDFILE[5]:$DS[5]:AVERAGE " ;
$def[1] .=  "DEF:irq=$RRDFILE[6]:$DS[6]:AVERAGE " ;
$def[1] .=  "DEF:softirq=$RRDFILE[4]:$DS[4]:AVERAGE " ;
$def[1] .=  "DEF:idle=$RRDFILE[7]:$DS[7]:AVERAGE " ;

$def[1] .= "COMMENT:\"\\t\\tLAST\\t\\t\\tAVERAGE\\t\\t\\tMAX\\n\" " ;

$def[1] .= "AREA:used#E80C3E:\"user   \":STACK " ;
$def[1] .= "GPRINT:used:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:used:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:used:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:nice#E8630C:\"nice   \":STACK " ;
$def[1] .= "GPRINT:nice:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:nice:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:nice:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:sys#008000:\"sys    \":STACK " ;
$def[1] .= "GPRINT:sys:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:sys:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:sys:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:iowait#0CE84D:\"iowait \":STACK " ;
$def[1] .= "GPRINT:iowait:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:iowait:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:iowait:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:irq#3E00FF:\"irq    \":STACK " ;
$def[1] .= "GPRINT:irq:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:irq:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:irq:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:softirq#1CC8E8:\"softirq\":STACK " ;
$def[1] .= "GPRINT:softirq:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:softirq:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:softirq:MAX:\"%6.2lf \\n\" " ;

$def[1] .= "AREA:idle#EEEEEE:\"idle   \":STACK " ; 
$def[1] .= "GPRINT:idle:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:idle:AVERAGE:\"%6.2lf \\t\\t\" " ;
$def[1] .= "GPRINT:idle:MAX:\"%6.2lf \\n\" " ;
?>
