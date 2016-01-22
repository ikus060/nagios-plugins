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

# Usage %
$opt[1] = "--vertical-label \"Memory %\" -u 100 -l 0 -r --title \"Memory Usage for $hostname / $servicedesc\" ";
$def[1] =  "DEF:utilisation=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .= "COMMENT:\"\\t\\tLAST\\t\\t\\tAVERAGE\\t\\t\\tMAX\\n\" " ;
$def[1] .= "AREA:utilisation#E80C3E:\"utilisation\":STACK " ;
$def[1] .= "GPRINT:utilisation:LAST:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:utilisation:AVERAGE:\"%6.2lf %%\\t\\t\" " ;
$def[1] .= "GPRINT:utilisation:MAX:\"%6.2lf MB\\n\" " ;

# Usages
$opt[2] = "--vertical-label \"Memory\" -l 0 -r --title \"Memory Usage for $hostname / $servicedesc\" ";
$def[2] =  "DEF:used=$RRDFILE[2]:$DS[2]:AVERAGE " ;
$def[2] .= "COMMENT:\"\\t\\tLAST\\t\\t\\tAVERAGE\\t\\t\\tMAX\\n\" " ;
$def[2] .=  "DEF:buffer=$RRDFILE[3]:$DS[3]:AVERAGE " ;
$def[2] .=  "DEF:cached=$RRDFILE[4]:$DS[4]:AVERAGE " ;
$def[2] .=  "DEF:arc-cache=$RRDFILE[5]:$DS[5]:AVERAGE " ;

$def[2] .= "AREA:used#E80C3E:\"used    \":STACK " ;
$def[2] .= "GPRINT:used:LAST:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:used:AVERAGE:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:used:MAX:\"%6.2lf MB\\n\" " ;

$def[2] .= "AREA:buffer#fcaf3e:\"buffer  \":STACK " ;
$def[2] .= "GPRINT:buffer:LAST:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:buffer:AVERAGE:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:buffer:MAX:\"%6.2lf MB\\n\" " ;

$def[2] .= "AREA:cached#729fcf:\"cached  \":STACK " ;
$def[2] .= "GPRINT:cached:LAST:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:cached:AVERAGE:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:cached:MAX:\"%6.2lf MB\\n\" " ;

$def[2] .= "AREA:arc-cache#204a87:\"arccache\":STACK " ;
$def[2] .= "GPRINT:arc-cache:LAST:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:arc-cache:AVERAGE:\"%6.2lf MB\\t\\t\" " ;
$def[2] .= "GPRINT:arc-cache:MAX:\"%6.2lf MB\\n\" " ;

# Swap
$opt[3] = "--vertical-label \"CPU [%]\" -l 0 -r --title \"Swap Usage for $hostname / $servicedesc\" ";
$def[3] =  "DEF:swap=$RRDFILE[6]:$DS[6]:AVERAGE " ;
$def[3] .= "COMMENT:\"\\t\\tLAST\\t\\t\\tAVERAGE\\t\\t\\tMAX\\n\" " ;
$def[3] .= "AREA:swap#1CC8E8:\"swap\":STACK " ;
$def[3] .= "GPRINT:swap:LAST:\"%6.2lf MB\\t\\t\" " ;
$def[3] .= "GPRINT:swap:AVERAGE:\"%6.2lf MB\\t\\t\" " ;
$def[3] .= "GPRINT:swap:MAX:\"%6.2lf MB\\n\" " ;

?>

