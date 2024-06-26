import telnetlib, sys

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
tn.write(f"interface gpon 0/{sys.argv[4].split('/')[0]}\n".encode('ascii'))
tn.read_until(b"#")
tn.write(f"display ont ipconfig {sys.argv[4].split('/')[1]} {sys.argv[5]} | no-more\n".encode('ascii'))
res = tn.read_until(b"#").decode('ascii').split('--------------------------------------------------------------------')
res.pop(0)
res.pop(len(res) - 1)

print(res)
