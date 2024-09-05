import re
import struct
import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST)
tn.get_socket().send(struct.pack('!BBBHHBB', 255, 250, 31, 1200, 1200, 255, 240))

tn.read_until(b"login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"environment inhibit-alarms\n")
tn.read_until(b"#")
tn.write(f"show equipment ont index sn:{sys.argv[4]} xml\n".encode('ascii'))
pos = tn.read_until(b"#").decode('ascii').split('type="Gpon::OntIndex">')[1].split('</info>')[0]
spl = pos.split('/')
pon = f"{spl[0]}/{spl[1]}/{spl[2]}/{spl[3]}"
tn.write(f"show equipment ont status pon {pon} ont {pos} | match exact:1/1\n".encode('ascii'))
res = tn.read_until(b"#").decode('ascii').split('\r\n')
res.pop(0)
res.pop()
restxt = res[0].split()
restxt = '|'.join(restxt[0:7]) + '|' + ' '.join(restxt[7:-1])
tn.write(f"show equipment ont optics {res[0].split()[1]} | match exact:1/1\n".encode('ascii'))
res2 = tn.read_until(b"#").decode('ascii').split('\r\n')
tn.write(b"logout")
tn.close()
print(f'{restxt}|{"-40.00" if res2[1].split()[1] == "unknown" else res2[1].split()[1]}')
