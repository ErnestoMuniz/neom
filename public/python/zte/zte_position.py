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

positions = []
blank = False
for onu in tmp:
    if onu.find('SN') >= 0:
        positions.append(int(onu.split(':')[1].split(' ')[0]))
for i, position in enumerate(positions):
    if i + 1 != position:
        print(i + 1)
        blank = True
        break
if not blank:
    print(positions[-1] + 1)
