import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
pon = sys.argv[4].replace("/", "-")

tn = telnetlib.Telnet(HOST)

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
    f"show authorization slot {pon.split('-')[0]} pon {sys.argv[4].split('-')[1]}\n".encode('ascii'))
res = ''
for i in range(5):
    res += tn.read_until(b'stop--', timeout=0.1).decode('ascii')
    if res != '':
        tn.write(b' ')
    else:
        res += tn.read_until(b'#').decode('ascii')
res = res.replace(' --Press any key to continue Ctrl+c to stop--', '').replace('\x08', '').replace('     ', ' ').replace('    ', ' ').replace('   ', ' ').replace('  ', ' ').split('\r\n')
res = res[8:-2]
positions = []
for onu in res:
    positions.append(int(onu.split()[2]))
current = 0
for pos in positions:
    if current + 1 == pos:
        current += 1
    else:
        break
print(current+1)
