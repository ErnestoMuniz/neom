import telnetlib
import sys
import struct

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]

tn = telnetlib.Telnet(HOST)
tn.get_socket().send(struct.pack('!BBBHHBB', 255, 250, 31, 1200, 1200, 255, 240))

tn.read_until(b"Login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"Password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b">")
tn.write(b"enable\n")
tn.read_until(b"Password: ")
tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"cd onu\n")
tn.read_until(b"#")
tn.write(
    f"show authorization slot {sys.argv[4].split('/')[0]} pon {sys.argv[4].split('/')[1]}\n".encode('ascii'))
res = ''
for i in range(5):
    res += tn.read_until(b'stop--', timeout=0.1).decode('ascii')
    if res != '':
        tn.write(b' ')
    else:
        res += tn.read_until(b'#').decode('ascii')
res = res.replace(' --Press any key to continue Ctrl+c to stop--', '').replace('\x08', '').replace('     ', ' ').replace('    ', ' ').replace('   ', ' ').replace('  ', ' ').split('\r\n')
res = res[8:-2]
resultado = ''
for onu in res:
    tn.write(f"show optic_module slot {sys.argv[4].split('/')[0]} pon {sys.argv[4].split('/')[1]} onu {onu.split(' ')[2]}\n".encode('ascii'))
    opt = tn.read_until(b'#').decode('ascii').split('\r\n')
    try:
        opt = opt[9].split('RECV POWER   :')[1].replace('	(Dbm)', '').replace(' ', '')
    except:
        opt = '-40.00'
    resultado +=  str(onu).strip() + " " + str(opt) + "\n"
print(resultado)