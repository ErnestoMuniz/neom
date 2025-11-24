from FiberHome import FiberHome
import sys

if sys.argv[9] == 'AN5506-01-A1':
    fh = FiberHome(sys.argv[1], '3337', sys.argv[3], sys.argv[4])
    fh.Activate_ONU_Bridge(sys.argv[2], sys.argv[5], sys.argv[7], sys.argv[9], sys.argv[6], sys.argv[8])
if sys.argv[9] == 'AN5506-02-F' or sys.argv[9] == 'AN5506-04-FA':
    fh = FiberHome(sys.argv[1], '3337', sys.argv[3], sys.argv[4])
    fh.Activate_ONU_Router(sys.argv[2], sys.argv[5], sys.argv[7], sys.argv[9], sys.argv[6], sys.argv[8], sys.argv[10], sys.argv[11])
if sys.argv[9] == 'HG6145E':
    fh = FiberHome(sys.argv[1], '3337', sys.argv[3], sys.argv[4])
    fh.Activate_Router_ZTE(sys.argv[2], sys.argv[5], sys.argv[7], sys.argv[9], sys.argv[6], sys.argv[8])