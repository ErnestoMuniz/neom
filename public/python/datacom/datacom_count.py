import paramiko, sys, re

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect(hostname=sys.argv[1].split(':')[0], port=sys.argv[1].split(':')[1], username=sys.argv[2], password=sys.argv[3])
stdin, stdout, stderr = ssh.exec_command("show interface gpon 1/{} onu | display curly-braces | nomore".format(sys.argv[4]))
res = stdout.read().decode()
res = ' '.join(res.split())
pos = re.findall("(?<=onu ).*?(?= )", res)
serials = re.findall("(?<=serial-number ).*?(?=;)", res)
status = re.findall("(?<=primary-status ).*?(?=;)", res)
signals = re.findall("(?<=rx-optical-pw ).*?(?=;)", res)
for i in range(len(status)):
  print(f"{pos[i]} {status[i]} {'-40.00' if signals[i] == 'N/A' or signals[i] == '0.00' else signals[i]} {serials[i]}")