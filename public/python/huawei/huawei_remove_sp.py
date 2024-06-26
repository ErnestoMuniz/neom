import telnetlib
import sys

HOST = sys.argv[1].split(':')[0]
PORT = sys.argv[1].split(':')[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST, PORT)

tn.read_until(b"name:")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password:")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b">")
tn.write(b"enable\n")
tn.read_until(b"#")
tn.write(b"undo smart\n")
tn.read_until(b"#")
tn.write(b"config\n")
tn.read_until(b"#")
tn.write(f"undo service-port {sys.argv[4]}\n".encode('ascii'))
result = tn.read_until(b"#").decode('ascii')
print(result)
