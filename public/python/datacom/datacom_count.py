import paramiko, sys

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=sys.argv[1], username=sys.argv[2], password=sys.argv[3])
stdin, stdout, stderr = ssh.exec_command("show interface gpon 1/{} onu | display json".format(sys.argv[4]))
print(stdout.read().decode())