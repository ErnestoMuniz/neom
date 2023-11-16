import telnetlib
import sys
import re
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
tn.write(f"show discovery slot all pon all\n".encode('ascii'))
res = '\n'.join(tn.read_until(b"#").decode('ascii').split('\n')[1:-3])
# res = """----- ONU Unauth Table, SLOT = 15, PON = 16, ITEM = 2 -----
# No  OnuType        PhyId        PhyPwd     LogicId                  LogicPwd     Why 
# --- -------------- ------------ ---------- ------------------------ ------------ ---
# 1   5506-01-A1     FHTT09165470            fiberhome                             1  
# 2   5506-01-A1     FHTT00000000            fiberhome                             1  

# ----- ONU Unauth Table, SLOT = 4, PON = 5, ITEM = 1 -----
# No  OnuType        PhyId        PhyPwd     LogicId                  LogicPwd     Why 
# --- -------------- ------------ ---------- ------------------------ ------------ ---
# 1   5506-01-A1     FHTT99999999            fiberhome                             1  """

pos = re.findall('(?<== )(\d*)((?=,)|(?= ))',res)
pons = []
for i in range(int(len(pos) / 3)):
    pons.append([int(pos[3 * i][0]), int(pos[3 * i + 1][0]), int(pos[3 * i + 2][0])])
onus = re.findall('(?<=\d)(.*)(?=\d  )', res)
c = 0
result = ''
for i in range(len(pons)):
    for j in range(pons[i][2]):
        result += f"{pons[i][0]}-{pons[i][1]} {onus[c].split()[1]}\n"
        c += 1
print(result)