import telnetlib
import sys

HOST = sys.argv[1].split(':')[0]
PORT = sys.argv[1].split(':')[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST, PORT)

tn.read_until(b"name:")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password:")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b">")
tn.write(b"enable\n")
tn.read_until(b"#")
tn.write(f"display ont info summary 0/{sys.argv[4]} | no-more\n".encode('ascii'))
tn.read_until(b"}:")
tn.write(b"\n")
result = tn.read_until(b"#").decode('ascii').split('------------------------------------------------------------------------------')[5].split('\r\n')
result.pop(0)
result.pop(len(result) - 1)
for onu in result:
  onuStr = ''
  for attr in onu.split():
    onuStr += f"{attr.replace('-/-', '-40.00/0.00')} "
  print(onuStr)
