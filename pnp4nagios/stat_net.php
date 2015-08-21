<?php
################################################
#
# PNP v0.6 Template for Plugin stat_net.pl
#
# Thomas Sesselmann <t.sesselmann@dkfz.de> 2010
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#
# Changelog:
# v0.9  2010.10.22 (ts)
#
####

$base = 2; # Datasources per Group/Device

$smap = array (
  'in' => array(
    'type'   => 'AREA', 
    'color'  => '#0c0',
  ),
  'out' => array(
    'type'   => 'LINE1',
    'color'  => '#029',
  ),
);


$dev = array();
foreach ( $this->DS as $k=>$v ) {

  $i = intval($k/$base) +1;

  if ( $k%$base == 0 ) { # Some definitions at beginning
    if ( preg_match( "/^(.*)_in$/",$v['NAME'], $treffer ) ) {
      $dev[$i] = $treffer[1];
      $ds_name[$i] = $dev[$i];
    }
    $opt[$i] = "--vertical-label Bytes -lo --title '$hostname / $servicedesc - $ds_name[$i]' ";
    #$this->MACRO['DISP_HOSTNAME']
    #$this->MACRO['DISP_SERVICEDESC']
    $def[$i] = "";
    $UNIT[$i] = "B"; $UNIT[$i+1] = "B";
  }

  if ( preg_match( "/^$dev[$i]_(.*)$/",$v['NAME'], $treffer ) ) {
    $v['sname'] = $treffer[1];
  } else {
    $v['sname'] = $v['NAME'];
  }

  $def[$i] .= rrd::def("v$k", $v['RRDFILE'], $v['DS'], "AVERAGE" );

  #$def[$i] .= rrd::line(1,"v$k", $map_color[$k%$base], "$k\\n");
  $def[$i] .= $smap[$v['sname']]['type'].":v$k".$smap[$v['sname']]['color'].":".
                sprintf("'%-20s' ",$dev[$i]." ".$v['sname']);
  $def[$i] .= rrd::gprint("v$k","MAX","MAX\: %4.3lg%s$UNIT[$i]");
  $def[$i] .= rrd::gprint("v$k","MIN","MIN\: %4.3lg%s$UNIT[$i]");
  $def[$i] .= rrd::gprint("v$k","AVERAGE","AVG\: %4.3lg%s$UNIT[$i]");
  $def[$i] .= rrd::gprint("v$k","LAST","LAST\: %4.3lg%s$UNIT[$i]");
  $def[$i] .= rrd::comment("\\n");
  
  if ( $k%$base == $base-1 ) { # Some definitions at the end
    $def[$i] .= rrd::hrule(   10*131072, "#333", "10MBit/s");
    $def[$i] .= rrd::hrule(  100*131072, "#773", "100MBit/s");
    $def[$i] .= rrd::hrule( 1024*131072, "#484", "1GBit/s");
    $def[$i] .= rrd::hrule(10240*131072, "#844", "10GBit/s");
    $def[$i] .= rrd::comment("\\n");
  }

}

?>
