#!/usr/bin/python

import sys
import osimctrl.osimctrl as osc

#############################
### CONFIGURE THESE PATHS ###
#############################

# The path to your OpenSimulator binary directory
binaryPath = "/home/opensim/opensim/opensim-current/bin"

# You can change this to an exact path if required.
screenPath = "screen"

#####################
### END OF CONFIG ###
#####################

componentName = "Robust"
screenName = componentName

##############
### SCRIPT ###
##############
osc.main(binaryPath, screenPath, componentName, screenName)