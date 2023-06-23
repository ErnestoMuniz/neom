import paramiko
import sys
import re

HOST = sys.argv[1].split(':')[0]
PORT = sys.argv[1].split(':')[1]
USERNAME = sys.argv[2]
PASSWORD = sys.argv[3]
ONUS = sys.argv[4].split(',')

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=HOST, port=PORT, username=USERNAME, password=PASSWORD)
stdin, stdout, stderr = ssh.exec_command(
    "show interface gpon onu | nomore")
res = stdout.read().decode()
for onu in ONUS:
    if onu != '' and res.__contains__(onu):
        for line in res.split("\n"):
            if onu in line:
                pos = line.split()[0]
                id = line.split()[1]
                stdin, stdout, stderr = ssh.exec_command(
                    f"show interface gpon {pos} onu {id} | nomore")
                lines = re.sub(' +', ' ', stdout.read().decode())
                type = re.search('(?:Equipment ID : )(.*)', lines).group(1)
                sn = re.search('(?:Serial Number : )(.*)', lines).group(1)
                status = re.search('(?:Operational state : )(.*)', lines).group(1)
                signal = re.search('(?:Rx Optical Power \[dBm\] : )(.*)', lines).group(1)
                print(f"{pos}/{id} {type} {sn} {status} {signal}")
    else:
        print(f"- - - - -")
