import telnetlib

class Nokia():

    def __init__(self, host, port, user, password):
        self.host = host
        self.user = user
        self.password = password
        self.port = port

    ## -- CLI --
    def Add_ONT_Router_and_PPPoE_CLI(self, slot, pon, ontid, descricao01, descricao02, serial_com, vlan, usuario, senha):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        # PASSO I
        tn.write(f"configure equipment ont interface 1/1/{slot}/{pon}/{ontid} desc1 \"{descricao01}\" desc2 \"{descricao02}\" sernum {serial_com}: sw-ver-pland auto sw-dnload-version auto pland-cfgfile1 auto dnload-cfgfile1 auto\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure equipment ont interface 1/1/{slot}/{pon}/{ontid} admin-state up\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure equipment ont slot 1/1/{slot}/{pon}/{ontid}/14 planned-card-type veip plndnumdataports 1 plndnumvoiceports 0 admin-state up\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure qos interface 1/1/{slot}/{pon}/{ontid}/14/1 upstream-queue 0 bandwidth-profile name:HSI_1G_UP\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure interface port uni:1/1/{slot}/{pon}/{ontid}/14/1 admin-up\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure bridge port 1/1/{slot}/{pon}/{ontid}/14/1 max-unicast-mac 32 max-committed-mac 1\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure bridge port 1/1/{slot}/{pon}/{ontid}/14/1 vlan-id {vlan} tag single-tagged\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))

        # PASSO II
        tn.write(f"ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-1::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice..1.X_CT-COM_WANGponLinkConfig.VLANIDMark,PARAMVALUE={vlan};\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-2::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username,PARAMVALUE={usuario};\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-3::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password,PARAMVALUE={senha};\n".encode('ascii'))
        tn.read_until(b"$")

    def Add_ONT_Bridge_CLI(self, slot, pon, ontid, descricao01, descricao02, serial_com, vlan):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.read_until(b"#")
        tn.write(f"environment inhibit-alarms\n".encode('ascii'))
        print(tn.read_until(b"#").decode('ascii'))
        tn.write(f"configure equipment ont interface 1/1/{slot}/{pon}/{ontid} sw-ver-pland disabled desc1 \"{descricao01}\" desc2 \"{descricao02}\" sernum {serial_com} sw-dnload-version disabled pland-cfgfile1 disabled dnload-cfgfile1 disabled\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure equipment ont interface 1/1/{slot}/{pon}/{ontid} admin-state up\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure equipment ont slot 1/1/{slot}/{pon}/{ontid}/1 planned-card-type ethernet plndnumdataports 1 plndnumvoiceports 0 admin-state up\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure ethernet ont 1/1/{slot}/{pon}/{ontid}/1/1 admin-state up\n".encode('ascii'))
        print(tn.read_until(b"#").decode('ascii'))
        tn.write(f"configure interface port uni:1/1/{slot}/{pon}/{ontid}/1/1 admin-up\n".encode('ascii'))
        print(tn.read_until(b"#").decode('ascii'))
        tn.write(f"configure qos interface 1/1/{slot}/{pon}/{ontid}/1/1 upstream-queue 0 bandwidth-profile name:HSI_1G_UP\n".encode('ascii'))
        print(tn.read_until(b"#").decode('ascii'))
        tn.write(f"configure bridge port 1/1/{slot}/{pon}/{ontid}/1/1 max-unicast-mac 32 max-committed-mac 1\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure bridge port 1/1/{slot}/{pon}/{ontid}/1/1 vlan-id {vlan} tag untagged\n".encode('ascii'))
        print(tn.read_until(b"$").decode('ascii'))
        tn.write(f"configure bridge port 1/1/{slot}/{pon}/{ontid}/1/1 pvid {vlan}\n".encode('ascii'))
        print(tn.read_until(b"#").decode('ascii'))

    def Remove_ONT_CLI(self, slot, pon, ontid):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.write(f"configure equipment ont interface 1/1/{slot}/{pon}/{ontid} admin-state down\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"configure equipment ont no interface 1/1/{slot}/{pon}/{ontid}\n".encode('ascii'))
        tn.read_until(b"$")

    def Reboot_ONT_CLI(self, slot, pon, ontid):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")
        tn.write(f"admin equipment ont interface 1/1/{slot}/{pon}/{ontid} reboot with-active-image\n".encode('ascii'))
        tn.read_until(b"$")

    #-- TL1 --
    def Add_ONT_Router_TL1(self, slot, pon, ontid, descricao01, descricao02, serial_com, vlan, usuario, senha):

        ## -- login --
        tn = telnetlib.Telnet(host=self.host, port=self.port)

        serial_com = serial_com.replace(':', '')

        tn.read_until(b"<")
        tn.write(f"\r".encode('ascii'))
        tn.read_until(b":")
        tn.write(f"T\r".encode('ascii'))
        tn.read_until(b": ")
        tn.write(b"SUPERUSER\r")
        tn.read_until(b": ")
        tn.write(b"ANS#150\r")

        tn.read_until(b"<")
        tn.write(f'ENT-ONT::ONT-1-1-{slot}-{pon}-{ontid}::::DESC1="{descricao01}",DESC2="{descricao02}",SERNUM={serial_com},SWVERPLND=AUTO,OPTICSHIST=ENABLE,PLNDCFGFILE1=AUTO,DLCFGFILE1=AUTO,VOIPALLOWED=VEIP;ED-ONT::ONT-1-1-{slot}-{pon}-{ontid}:::::IS;\r'.encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"ENT-ONTCARD::ONTCARD-1-1-{slot}-{pon}-{ontid}-14:::VEIP,1,0::IS;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"ENT-LOGPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-14-1:::;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"ED-ONTVEIP::ONTVEIP-1-1-{slot}-{pon}-{ontid}-14-1:::::IS;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"SET-QOS-USQUEUE::ONTL2UNIQ-1-1-{slot}-{pon}-{ontid}-14-1-0::::USBWPROFNAME=HSI_1G_UP;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"SET-VLANPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-14-1:::MAXNUCMACADR=4,CMITMAXNUMMACADDR=1;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"ENT-VLANEGPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-14-1:::0,{vlan}:PORTTRANSMODE=SINGLETAGGED;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f"ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-1::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.X_CT-COM_WANGponLinkConfig.VLANIDMark,PARAMVALUE=VLAN;\r".encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f'ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-2::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Username,PARAMVALUE={usuario};\r'.encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))
        tn.write(f'ENT-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-{slot}-{pon}-{ontid}-3::::PARAMNAME=InternetGatewayDevice.WANDevice.1.WANConnectionDevice.1.WANPPPConnection.1.Password,PARAMVALUE={senha};\r'.encode('ascii'))
        print(tn.read_until(b") :").decode('ascii'))

    def Add_ONT_Bridge_TL1(self, slot, pon, ontid, descricao01, descricao02, vlan):

        ## -- login --
        tn = telnetlib.Telnet(host=self.host, port=self.port)

        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.write(f'ENT-ONT::ONT-1-1-{slot}-{pon}-{ontid}::::DESC1="{descricao01}",DESC2="{descricao02}",SERNUM=ALCLB3EA97D1,SWVERPLND=DISABLED,;\n'.encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ED-ONT::ONT-1-1-{slot}-{pon}-{ontid}:::::IS;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ENT-ONTCARD::ONTCARD-1-1-{slot}-{pon}-{ontid}-1:::10_100BASET,1,0::IS;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ENT-LOGPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-1-1:::;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ED-ONTVEIP::ONTVEIP-1-1-{slot}-{pon}-{ontid}-1-1:::::IS;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"SET-QOS-USQUEUE::ONTL2UNIQ-1-1-{slot}-{pon}-{ontid}-1-1-0::::USBWPROFNAME=HSI_1G_UP;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"SET-VLANPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-1-1:::MAXNUCMACADR=32,CMITMAXNUMMACADDR=1;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"ENT-VLANEGPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-1-1:::0,{vlan}:PORTTRANSMODE=UNTAGGED;\n".encode('ascii'))
        tn.read_until(b"$")
        tn.write(f"SET-VLANPORT::ONTL2UNI-1-1-{slot}-{pon}-{ontid}-1-1:::DEFAULTCVLAN={vlan};\n".encode('ascii'))
        tn.read_until(b"$")

    def Remove_ONT_TL1(self, slot, pon, ontid):

        ## -- login --
        tn = telnetlib.Telnet(host=self.host, port=self.port)

        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.write(f'ED-ONT::ONT-1-1-{slot}-{pon}-{ontid}:::::OOS;\n'.encode('ascii'))
        tn.read_until(b"$")
        tn.write(f'DLT-ONT::ONT-1-1-{slot}-{pon}-{ontid}::;\n'.encode('ascii'))
        tn.read_until(b"$")

    def Reboot_ONT_TL1(self, slot, pon, ontid):

        ## -- login --
        tn = telnetlib.Telnet(host=self.host, port=self.port)

        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.write(f'INIT-SYS::ONT--1-1-{slot}-{pon}-{ontid}:::4;\n'.encode('ascii'))
        tn.read_until(b"$")