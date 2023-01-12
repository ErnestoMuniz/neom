import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
onus = sys.argv[4].split(',')

tn = telnetlib.Telnet(HOST)

tn.read_until(b"login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"environment inhibit-alarms\n")
tn.read_until(b"#")
for onu in onus:
    if onu != '':
        try:
            tn.write(
                f"show equipment ont index sn:{onu} xml\n".encode('ascii'))
            pos = tn.read_until(b"#").decode('ascii').split(
                'type="Gpon::OntIndex">')[1].split('</info>')[0]
            spl = pos.split('/')
            pon = f"{spl[0]}/{spl[1]}/{spl[2]}/{spl[3]}"
            tn.write(
                f"show equipment ont status pon {pon} ont {pos} | match exact:1/1\n".encode('ascii'))
            res = tn.read_until(b"#").decode('ascii').split('\r\n')
            res.pop(0)
            res.pop(0)
            res.pop()
            print(' '.join('\n'.join(res).split()))
        except:
            print("- - - - - - - - -")
    else:
        print("- - - - - - - - -")
