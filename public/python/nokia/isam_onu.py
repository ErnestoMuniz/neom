import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST, 1023)

print(tn.read_until(b"<"))
tn.write(b"\r")
print(tn.read_until(b":").decode('ascii'))
tn.write(b"T\r")
print(tn.read_until(b": ").decode('ascii'))
tn.write(user.encode('ascii') + b"\r")
if password:
    tn.read_until(b": ")
    tn.write(password.encode('ascii') + b"\r")

print(tn.read_until(b"<").decode('ascii'))