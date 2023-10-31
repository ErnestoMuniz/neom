import telnetlib
import sys
import struct

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
superpass = sys.argv[4]
pos = sys.argv[5]

tn = telnetlib.Telnet(HOST)

tn.get_socket().send(struct.pack('!BBBHHBB', 255, 250, 31, 1200, 1200, 255, 240))

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
tn.write("show pon onu information gpon_olt-1/{}\n".format(pos).encode('ascii'))

tmp = tn.read_until(b"#").decode('ascii').splitlines()
del tmp[0:4]
tmp.pop()
tmp.pop()

onus = []
for onu in tmp:
    if onu.find('SN') >= 0:
        onus.append(onu.split())
tn.write(f"show pon power onu-rx gpon_olt-1/{pos}\n".encode('ascii'))

tmp = tn.read_until(b"#").decode('ascii').splitlines()
del tmp[0:3]
tmp.pop()
signals = []
for signal in tmp:
    signals.append(signal.replace(
        'no signal', '-40.00').replace('(dbm)', '').split()[1])
for i in range(len(onus)):
    print(f"{onus[i][0].replace(':', '/')} {onus[i][4]} {signals[i]} {onus[i][3].split('(')[1].split(')')[0]}")
