import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
superpass = sys.argv[4]

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
tn.write(b"show pon onu uncfg\n")

tmp = tn.read_until(b"#").decode('ascii').splitlines()
tmp.pop()
del tmp[0:3]
for onu in tmp:
    print(' '.join(onu.split()).replace('gpon_olt-', ''))