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
tn.write(b"display ont autofind all | no-more\n")
tn.read_until(b"}:")
tn.write(b"\n")
result = tn.read_until(b"#").decode('ascii').split('----------------------------------------------------------------------------')
result.pop(0)
result.pop(len(result) - 1)
for onu in result:
  pos = onu.split('\r\n')[2].split(': ')[1]
  sn = onu.split('\r\n')[3].split('(')[1].replace(')', '')
  print(pos+' '+sn)
