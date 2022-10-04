import sys
import telnetlib

tn = telnetlib.Telnet(host=sys.argv[1], port='1023')
pos = sys.argv[2].replace('/', '-')
ssid2 = sys.argv[3]
password2 = sys.argv[4]
ssid5 = sys.argv[5]
password5 = sys.argv[6]

tn.read_until(b"<")
tn.write(f"\r".encode('ascii'))
tn.read_until(b":")
tn.write(f"T\r".encode('ascii'))
tn.read_until(b": ")
tn.write(b"SUPERUSER\r")
tn.read_until(b": ")
tn.write(b"ANS#150\r")

tn.read_until(b"<")

if ssid2 != '':
    tn.write(f'dlt-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-8;\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
    tn.write(f'ent-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-8::::PARAMNAME=InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID,PARAMVALUE="{ssid2}";\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
if password2 != '':
    tn.write(f'dlt-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-9;\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
    tn.write(f'ent-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-9::::PARAMNAME=InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.PreSharedKey.1.PreSharedKey,PARAMVALUE={password2};\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
if ssid5 != '':
    tn.write(f'dlt-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-10;\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
    tn.write(f'ent-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-10::::PARAMNAME=InternetGatewayDevice.LANDevice.1.WLANConfiguration.5.SSID,PARAMVALUE="{ssid5}";\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
if password5 != '':
    tn.write(f'dlt-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-11;\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
    tn.write(f'ent-HGUTR069-SPARAM::HGUTR069SPARAM-{pos}-11::::PARAMNAME=InternetGatewayDevice.LANDevice.1.WLANConfiguration.5.PreSharedKey.1.PreSharedKey,PARAMVALUE={password5};\r'.encode('ascii'))
    print(tn.read_until(b") :").decode('ascii'))
