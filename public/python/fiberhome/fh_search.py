import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST)

tn.read_until(b"Login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"Password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b">")
tn.write(b"enable\n")
tn.read_until(b"Password: ")
tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"cd onu\n")
tn.read_until(b"#")
tn.write(
    f"show onu-authinfo phy-id {sys.argv[4]}\n".encode('ascii'))
res = tn.read_until(b"#").decode('ascii').split('\r\n')[1].split(' ')[1].replace('-', '/')
if res == '/506]:':
    print('not found')
else:
    print(res)
