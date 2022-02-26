import telnetlib

class FiberHome():

    def __init__(self, host, port, user, password):

        self.host = host
        self.user = user
        self.password = password
        self.port = port

    def Activate_ONU_Router(self, ip_olt, slot_pon_onuid, serial_onu, modelo_onu, desc, vlan, pppoe_cliente, password):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.write(f"LOGIN:::CTAG::UN={self.user},PWD={self.password};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"ADD-ONU::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid}:CTAG::AUTHTYPE=MAC,ONUID={serial_onu},ONUTYPE={modelo_onu},NAME={pppoe_cliente};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"SET-WANSERVICE::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid},ONUIDTYPE=MAC,ONUID={serial_onu}:CTAG::STATUS=1,MODE=2,CONNTYPE=2,VLAN={vlan},COS=7,QOS=1,NAT=1,IPMODE=3,PPPOEPROXY=2,PPPOEUSER={pppoe_cliente},PPPOEPASSWD={password},PPPOENAME=,PPPOEMODE=1,UPORT=0;\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"LOGOUT:::CTAG::;\n".encode('ascii'))
        tn.read_until(b";")

        tn.close()


    def Activate_ONU_Bridge(self, ip_olt, slot_pon_onuid, serial_onu, modelo_onu, desc, vlan):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.write(f"LOGIN:::CTAG::UN={self.user},PWD={self.password};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"ADD-ONU::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid}:CTAG::AUTHTYPE=MAC,ONUID={serial_onu},ONUTYPE={modelo_onu},NAME={desc};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"CFG-LANPORTVLAN::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid},ONUIDTYPE=MAC,ONUID={serial_onu},ONUPORT=NA-NA-NA-1:CTAG::CVLAN={vlan},CCOS=0;\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"LOGOUT:::CTAG::;\n".encode('ascii'))
        tn.read_until(b";")

        tn.close()

    def Activate_Router_ZTE(self, ip_olt, slot_pon_onuid, serial_onu, modelo_onu, pppoe_cliente, vlan):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.write(f"LOGIN:::CTAG::UN={self.user},PWD={self.password};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"ADD-ONU::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid}:CTAG::AUTHTYPE=MAC,ONUID={serial_onu},ONUTYPE={modelo_onu},NAME={pppoe_cliente};\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"CFG-VEIPSERVICE::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid},ONUIDTYPE=MAC,ONUID={serial_onu},ONUPORT=NA-NA-NA-1:CTAG::ServiceId=1,CVLANID={vlan},CCOS=0,ServiceModelProfile=ONUZTE,ServiceType=NONE;\n".encode('ascii'))
        tn.read_until(b";")

        tn.write(f"LOGOUT:::CTAG::;\n".encode('ascii'))
        tn.read_until(b";")

        tn.close()

    def Remove_ONU(self, ip_olt, slot_pon_onuid, serial_onu):

        tn = telnetlib.Telnet(host=self.host, port=self.port)

        ## -- login --
        tn.read_until(b"login: ")
        tn.write(self.user.encode('ascii') + b"\n")
        tn.read_until(b"password: ")
        tn.write(self.password.encode('ascii') + b"\n")

        tn.write(f"DEL-ONU::OLTID={ip_olt},PONID=NA-NA-{slot_pon_onuid}:CTAG::ONUIDTYPE=MAC,ONUID={serial_onu};\n".encode('ascii'))
        tn.read_until(b"$")

        tn.close()