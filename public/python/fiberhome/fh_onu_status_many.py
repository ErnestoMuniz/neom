import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
onus = sys.argv[4].split(',')
for i, onu in enumerate(onus):
    onu = onu[0:4] + onu[4:12].lower()
    onus[i] = onu

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
for onu in onus:
    if onu != '':
        tn.write(
            f"show onu-authinfo phy-id {onu}\n".encode('ascii'))
        res = tn.read_until(b"#").decode('ascii').split('\r\n')
        if res[1] == '[Error -506]: Onu is not authcated. ':
            print("- - - - -")
        else:
            pos = res[1].split(' ')[1]
            type = res[2].split()[2]
            sn = res[3].split()[2]
            tn.write(
                f"show onu_state slot {pos.split('-')[0]} pon {pos.split('-')[1]} onu {pos.split('-')[2]}\n".encode('ascii'))
            status = tn.read_until(b"#").decode('ascii').split('\r\n')[
                1].split()[8].replace('.', '')
            tn.write(
                f"show onu opticalpower-info phy-id {onu}\n".encode('ascii'))
            signal = '-40.00'
            try:
                signal = tn.read_until(b"#").decode('ascii').split('\r\n')[5].split()[3]
            except:
                pass
            print(f"{pos} {type} {sn} {status} {signal}")
    else:
        print("- - - - -")
