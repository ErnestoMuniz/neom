import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
pos = sys.argv[4]

tn = telnetlib.Telnet(HOST)

tn.read_until(b"login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"environment inhibit-alarms\n")
tn.read_until(b"#")
tn.write(f"configure equipment ont interface {pos} admin-state down\n".encode('ascii'))
tn.read_until(b"#")
tn.write(f"configure equipment ont no interface {pos}\n".encode('ascii'))

print(tn.read_until(b"#").decode('ascii'))
tn.write(b"logout")
tn.close()
