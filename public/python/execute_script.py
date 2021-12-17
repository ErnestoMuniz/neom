import sys
import json
import telnetlib

#declare variables it got from shell_exec
olt = json.loads(sys.argv[1])
script_user = json.loads(sys.argv[2])
steps = json.loads(sys.argv[3])
variables = json.loads(sys.argv[4])
protocol = sys.argv[5]
port = sys.argv[6]

#replaces commands with the given variables
def cmdReplacer(cmd):
    cmd = cmd.replace(':username:', script_user['username'])
    cmd = cmd.replace(':password:', script_user['password'])
    for variable in steps['variables']:
        cmd = cmd.replace(f":{variable}:", '{}'.format(variables[f"{variable}"]))
    return cmd

#run the replace loop
count = 0
for step in steps['steps']:
    steps['steps'][count][1] = cmdReplacer(step[1])
    count += 1

#loops through commands in the olt
if (protocol == 'telnet'):
    tn = telnetlib.Telnet(host=olt['ip'], port=port)
    for step in steps['steps']:
        print(tn.read_until(step[0].encode('ascii')).decode('ascii'))
        if step[1] != '':
            tn.write(f"{step[1]}\n".encode('ascii'))
        count += 1
