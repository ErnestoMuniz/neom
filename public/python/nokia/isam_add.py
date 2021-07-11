import telnetlib
import sys

HOST = sys.argv[1]
user = sys.argv[2]
password = sys.argv[3]
pos = sys.argv[4]
serial = sys.argv[5]
vlan = sys.argv[6]
description = sys.argv[7]
type = sys.argv[8]

tn = telnetlib.Telnet(HOST)

tn.read_until(b"login: ")
tn.write(user.encode('ascii') + b"\n")
if password:
    tn.read_until(b"password: ")
    tn.write(password.encode('ascii') + b"\n")
tn.read_until(b"#")
tn.write(b"environment inhibit-alarms\n")
tn.read_until(b"#")
if type == "router":
    tn.write(f"configure equipment ont interface {pos} sw-ver-pland auto desc1 {description} desc2 neom sernum {serial} sw-dnload-version auto pland-cfgfile1 disabled dnload-cfgfile1 auto\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont interface {pos} admin-state up\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont slot {pos}/14 planned-card-type veip plndnumdataports 1 plndnumvoiceports 0\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont slot {pos}/14 admin-state up\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure qos interface {pos}/14/1 upstream-queue 0 bandwidth-profile name:HSI_1G_UP\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure qos interface {pos}/14/1 queue 0 shaper-profile name:HSI_1G_DOWN\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure interface port uni:{pos}/14/1 admin-up\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure bridge port {pos}/14/1 max-unicast-mac 32\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure bridge port {pos}/14/1 vlan-id {vlan} tag single-tagged\n".encode('ascii'))
else:
    tn.write(f"configure equipment ont interface {pos} sw-ver-pland disabled desc1 {description} desc2 neom sernum {serial} sw-dnload-version disabled  pland-cfgfile1 disabled dnload-cfgfile1 disabled\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont interface {pos} admin-state up\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont slot {pos}/1 planned-card-type ethernet plndnumdataports 8 plndnumvoiceports 0\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure equipment ont slot {pos}/1 admin-state up\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure qos interface {pos}/1/1 upstream-queue 0 bandwidth-profile name:HSI_1G_UP\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure qos interface {pos}/1/1 queue 0 shaper-profile name:HSI_1G_DOWN\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure interface port uni:{pos}/1/1 admin-up\n".encode('ascii'))
    tn.read_until(b"#")
    tn.write(f"configure bridge port {pos}/1/1 max-unicast-mac 32\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure bridge port {pos}/1/1 vlan-id {vlan} tag single-tagged\n".encode('ascii'))
    tn.read_until(b"$")
    tn.write(f"configure bridge port {pos}/1/1 pvid {vlan}\n".encode('ascii'))
