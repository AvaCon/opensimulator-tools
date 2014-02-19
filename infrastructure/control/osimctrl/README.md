# README #

A simple couple of scripts for starting components of OpenSimulator.  This is an
alternative to the /etc/init.d script found elsewhere in this repository.

These scripts work using screen instances.

It would be possible to make these scripts much more sophisticated (e.g.
automatic detection of whether the grid services are running before starting a
simulator, etc.).  

However, currently they are deliberately being kept simple in order to keep the
user close to what is really happening.  This is to facilitate debugging and the
ability to deal with the many possible failure conditions.  This might change in
the future.

# Installation #

1. Copy src/* to a location of your choice
2. If you are using a Python earlier than 2.7m you will need to copy argparse.py
[1] into the same location.

# Configuration #

Before use, you will need to edit the binaryPath at the top of both files to
point them at your OpenSimulator installation.

You can also change the screen name if you want.  In the case of simctrl.py 
you could 
 
1. Copy the script
2. Rename it
3. Change the script name

to run more than one simulator on the same machine.

# Use #

simctrl.py controls a simulator instance whilst robustctrl.py can control a
Robust grid service instance.

If the OpenSimulator installation is in standalone mode then it can be
controlled purely with the simctrl.py script.

Both these scripts provide a number of commands to control their components.
These are

start   - start an instance of the component.
stop    - stop the component if it is running.
restart - restart the component if it is running.
attach  - attach to the current screen instance of the component if it is running.
status  - get the current status of the component.

There are also a few optional switches

-n, --noattach

Do not automatically attach to the screen component once it has started.

-a, --autorestart

If a component fails, then automatically restart it.  If you use this option
then you can only stop the component with the stop command instead of manually
attaching to the screen and shutting it down (since the surrounding loop will
simply start it again).  Alternatively, you can send a SIGTERM to the bash
script which acts as the simple loop.  This will be a child process from the
screen instance).

-h. --help

Display usage text

It provides start, stop, restart, attach and status commands, along with
--noattach and --autorestart options

# Files #

## robustctrl.py, simctrl.py ##
These files control a robust instance and a simulator respectively.

# Bugs #

Because stop currently involves stuff text onto the screen buffer, this will
fail if there is something already there.

# References #
[1] http://argparse.googlecode.com/hg/argparse.py
