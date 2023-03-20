import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
superpass = sys.argv[4]
sn = sys.argv[5]

tn = telnetlib.Telnet(HOST)

tn.read_until(b"Username:")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"Password:")
    tn.write(password.encode('ascii') + b"\n")
buff = tn.expect([b">", b"#"])
if buff[0] == 0:
    tn.write(b"enable\n")
    tn.read_until(b"Password:")
    tn.write(f"{superpass}\n".encode('ascii'))
    tn.read_until(b"#")
tn.write(f"show gpon onu by sn {sn}\n".encode('ascii'))

tmp = tn.read_until(b"#").decode('ascii').splitlines()
tmp.pop()
del tmp[0:3]
print(tmp[0].replace('gpon_onu-', '').replace(':', '/'))