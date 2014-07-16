#!/usr/bin/python

import pprint
import re
import sys

if len(sys.argv) <= 1:
    print "Usage: %s <stats-log-path>"
    sys.exit(1)

#lineRe = re.compile("(.* .*) - (.*) : (\d+)[ ,]([^:]*)")
#lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[\d\.-]+)(?: (?:\D+))?(?P<delta>[\d\.-]+)?")
lineRe = re.compile("(.* .*) - (.*) : (?P<abs>[^,]+)(?:, )?(?P<delta>[^,]+)?")
valueRe = re.compile("([^ %/]+)")

# Structure
# statName => { 'abs' : [values], 'delta' : [values] }
# delta may not be present
data = {}

with open(sys.argv[1]) as f:
    for line in f:    
        match = lineRe.match(line)
        
        if match != None:
            statFullName = match.group(2)       
            
            rawValue = match.group("abs")
            #print match.lastindex
            #print rawValue
                
            valueMatch = valueRe.match(rawValue)
            value = valueMatch.group(1)            
                        
                # print "%s: %s" % (statFullName, value)                
            
            if not statFullName in data:
                entry = { 'abs' : [], 'delta' : [] }
                data[statFullName] = entry                
                
            data[statFullName]['abs'].append(float(value))
            
            # Handle delta value if present
            if match.group("delta"):                
                rawValue = match.group("delta")
                
                valueMatch = valueRe.match(rawValue)
                value = valueMatch.group(1)               
                    
                data[statFullName]['delta'].append(float(value))                
                
        #else:
        #    print "Ignoring [%s]" % (line)
            
longestKey = max(data, key = len)
    
for statName, values in sorted(data.items()):
    
    absValues = values['abs']    
    sys.stdout.write("%-*s: %s to %s" % (len(longestKey), statName, min(absValues), max(absValues)))
    
    deltaValues = values['delta']
    
    if len(deltaValues) > 0:
        print ", %s to %s" % (min(deltaValues), max(deltaValues))
    else:
        print
         