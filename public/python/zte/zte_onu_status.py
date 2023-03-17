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
    tn.read_until(b">")
    tn.write(b"enable\n")
    tn.read_until(b"Password:")
    tn.write(f"{superpass}\n".encode('ascii'))
    tn.read_until(b"#")
tn.write(f"show gpon onu by sn {sn}\n".encode('ascii'))

tmp = tn.read_until(b"#").decode('ascii').splitlines()
tmp.pop()
del tmp[0:3]
pos = tmp[0].replace('gpon_onu-', '')

tn.write(f"show gpon onu state gpon_olt-{pos.split(':')[0]} {pos.split(':')[1]}\n".encode('ascii'))
tmp = tn.read_until(b"#").decode('ascii').splitlines()
onu = tmp[3:-2][0].split()
tn.write(f"show pon power olt-rx gpon_onu-{pos}\n".encode('ascii'))
tmp = tn.read_until(b"#").decode('ascii').splitlines()
signal = tmp[3:-1][0].replace('no signal', '-40.00').split()[1].replace('(dbm)', '')
print(f"{onu[0]} {sn} {onu[3]} {signal}")