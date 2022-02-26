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
tn.write(f"display ont info by-sn {sys.argv[4]} | no-more\n".encode('ascii'))
tn.read_until(b"}:")
tn.write(b"\n")
result = tn.read_until(b"#").decode('ascii').split(':')
pos = result[3].replace(' ', '').split('\r')[0]
pon = result[2].replace(' ', '').split('\r')[0]
result = f'{pon}/{pos}'.replace('\n', '')
print(result)
