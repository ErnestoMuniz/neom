import paramiko, sys, re

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=sys.argv[1].split(':')[0], port=sys.argv[1].split(':')[1], username=sys.argv[2], password=sys.argv[3])
stdin, stdout, stderr = ssh.exec_command('show system memory')
res = stdout.read().decode().split()
total = f"{res[14]} {res[15]}"
used = f"{res[23]} {res[24]}"
if 'GiB' in total:
  total = float(total.replace(' GiB', '')) * 1000
else:
  total = float(total.replace(' MiB', ''))
if 'GiB' in used:
  used = float(used.replace(' GiB', '')) * 1000
else:
  used = float(used.replace(' MiB', ''))
print(used / total * 100)