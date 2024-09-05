import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST)

tn.read_until(b"login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"environment inhibit-alarms\n")
tn.read_until(b"#")
tn.write(
    "show pon ber-stats {} | match exact:1/1\n".format(sys.argv[4]).encode('ascii'))
onus = tn.read_until(b"environment#").decode('ascii').split('\r\n')
tn.write(b"logout")
tn.close()
del onus[0]
del onus[-1]
positions = []
for onu in onus:
    positions.append(int(onu.split()[1]))
current = 0
for pos in positions:
    if current + 1 == pos:
        current += 1
    else:
        break
print(current+1)
