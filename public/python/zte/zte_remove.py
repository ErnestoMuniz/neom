import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
superpass = sys.argv[4]
pos = sys.argv[5]

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
tn.write(b"configure terminal\n")
tn.read_until(b"#")
tn.write(f"interface gpon_olt-1/{pos.split('/')[1]}/{pos.split('/')[2]}\n".encode('ascii'))
tn.read_until(b"#")
tn.write(f"no onu {pos.split('/')[3]}\n".encode("ascii"))
tn.read_until(b"#")
tn.write(b"end\n")
tn.read_until(b"#")
tn.write(b"write\n")
tn.read_until(b"#")
