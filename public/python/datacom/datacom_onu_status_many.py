import paramiko
import sys
HOST = sys.argv[1].split(':')[0]
PORT = sys.argv[1].split(':')[1]
USERNAME = sys.argv[2]
PASSWORD = sys.argv[3]
ONUS = sys.argv[4].split(',')

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=HOST, port=PORT, username=USERNAME, password=PASSWORD)
stdin, stdout, stderr = ssh.exec_command(
    "show interface gpon onu | nomore".format(sys.argv[4]))
res = stdout.read().decode()
for onu in ONUS:
    if res.__contains__(onu):
        for line in res.split("\n"):
            if onu in line:
                pos = line.split()[0]
                id = line.split()[1]
                stdin, stdout, stderr = ssh.exec_command(
                    f"show interface gpon {pos} onu {id} | nomore")
                lines = stdout.read().decode().split('\n')
                type = lines[8].split()[3]
                sn = lines[3].split()[3]
                status = lines[10].split()[3]
                signal = lines[30].split()[5]
                print(f"{pos}/{id} {type} {sn} {status} {signal}")
    else:
        print(f"- - - - -")
