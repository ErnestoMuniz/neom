import sys
from Nokia import Nokia

args = sys.argv
slot = args[4].split('/')[2]
pon = args[4].split('/')[3]
pos = args[4].split('/')[4]
try:
    nk = Nokia(args[1], '1023', args[2], args[3])
    nk.Add_ONT_Router_TL1(slot, pon, pos, args[5], args[6], args[7], args[8], args[9], args[10])
except:
    nk = Nokia(args[1], '23', args[2], args[3])
    nk.Add_ONT_Bridge_CLI(slot, pon, pos, args[5], args[6], args[7], args[8])