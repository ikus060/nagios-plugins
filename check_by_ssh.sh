#! /bin/bash
#
# Wrapper arround check_by_ssh to avoid returning CRITICAL when the plugin timeout.
#
# @AUTHOR: Patrik Dufresne (http://patrikdufresne.com)
# Copyright 2012 Patrik Dufresne
# Last modified 2012-11-02
# Please send all comments, suggestions, bugs and patches to (info AT patrikdufresne DOT com)
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, version 2 of the License.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
###

# You should provide a meaningful VERSION
VERSION=0.1
# Who can be contacted about this?
AUTHOR="Patrik Dufresne"

declare -rx PROGNAME=${0##*/}
declare -rx PROGPATH=${0%/*}/

# Replacement for the exit function, will cleanup any tempfiles or such
# before exiting.
function cleanup {
	exit $1
}


if [ -r "${PROGPATH}utils.sh" ] ; then
	source "${PROGPATH}utils.sh"
else
	echo "Can't find utils.sh. This plugin needs to be run from the same directory as utils.sh which is most likely something like /usr/lib/nagios/plugins or /usr/lib64/nagios/plugins"
	printf "Currently being run from %s\n" "$PROGPATH"
	# Since we couldn't define STATE_UNKNOWN since reading utils.sh
    # failed, we use 3 here but everywhere else after this use cleanup $STATE
	cleanup 3
fi

# Check if check_by_ssh exists!
CHECK_BY_SSH="/omd/sites/prod/lib/nagios/plugins/check_by_ssh"
if [ ! -x "$CHECK_BY_SSH" ] ; then
  echo "The utility $CHECK_BY_SSH is not installed. Exiting."
  cleanup $STATE_UNKNOWN
fi

DATA=$("$CHECK_BY_SSH" "$@" 2>&1)
STATE=$?

# Check if timeout
TIMEOUT_STATE=$(echo "$DATA" | egrep -c "Plugin timed out after .* seconds")
if [ $TIMEOUT_STATE -gt 0 ]; then
  echo "$DATA" | sed 's/CRITICAL/UNKNOWN/g'
  exit $STATE_UNKNOWN
else
  echo "$DATA"
  cleanup $STATE
fi

