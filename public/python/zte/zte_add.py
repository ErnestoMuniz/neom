import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
superpass = sys.argv[4]
pos = sys.argv[5]
model = sys.argv[6]
serial = sys.argv[7]
desc = sys.argv[8]
vlan = sys.argv[9]
userpppoe = sys.argv[10]
passpppoe = sys.argv[11]

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
tmp = tn.read_until(b"#").decode('ascii')
tn.write(
    f"interface gpon_olt-1/{pos.split('/')[0]}/{pos.split('/')[1]}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"onu {pos.split('/')[2]} type {model.split(':')[0]} sn {serial}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"exit\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"interface gpon_onu-1/{pos.split('/')[0]}/{pos.split('/')[1]}:{pos.split('/')[2]}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"name {desc}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"tcont 1 profile UP-1G\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"gemport 1 tcont 1\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"exit\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"interface vport-1/{pos.split('/')[0]}/{pos.split('/')[1]}.{pos.split('/')[2]}:1\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"service-port 1 user-vlan {vlan} vlan {vlan}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"exit\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"pon-onu-mng gpon_onu-1/{pos.split('/')[0]}/{pos.split('/')[1]}:{pos.split('/')[2]}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
tn.write(
    f"service 1 gemport 1 vlan {vlan}\n".encode('ascii'))
tmp += tn.read_until(b"#").decode('ascii')
if model.split(':')[1] != 'Router':
    tn.write(
        f"vlan port eth_0/1 mode tag vlan {vlan}\n".encode('ascii'))
    tmp += tn.read_until(b"#").decode('ascii')
# else:
#     tn.write(
#         f"wan-ip 1 ipv4 mode pppoe username {userpppoe} password {passpppoe} vlan-profile {vlan} host 1\n".encode('ascii'))
#     tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"end\n")
tmp += tn.read_until(b"#").decode('ascii')
tn.write(b"write\n")
tmp += tn.read_until(b"#").decode('ascii')
print(tmp)
