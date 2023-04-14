import paramiko, sys

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=sys.argv[1].split(':')[0], port=sys.argv[1].split(':')[1], username=sys.argv[2], password=sys.argv[3])
stdin, stdout, stderr = ssh.exec_command("show interface gpon onu | nomore | include {}".format(sys.argv[4]))
res = stdout.read().decode().split()
pos = res[0]
id = res[1]
stdin, stdout, stderr = ssh.exec_command(f"show interface gpon {pos} onu {id} | nomore")
lines = stdout.read().decode().split('\n')
type = lines[8].split()[3]
sn = lines[3].split()[3]
status = lines[10].split()[3]
signal = lines[30].split()[5]
print(f"{pos}/{id} {type} {sn} {status} {signal}")