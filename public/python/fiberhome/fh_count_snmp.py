import sys, json
from easysnmp import Session

HOST = sys.argv[1]
COMMUNITY = sys.argv[2]
PON = sys.argv[3]

snmp = Session(hostname=HOST, community=COMMUNITY, version=2)
onus = []
for pos in range(1, 128):
    id = int(PON.split('/')[0]) * 2 ** 25 + int(PON.split('/')[1]) * 2 ** 19 + pos * 2 ** 8
    try:
        signal = int(snmp.get(f"1.3.6.1.4.1.5875.800.3.9.3.3.1.6.{id}").value) / 100
        onus.append({
            "description": snmp.get(f"1.3.6.1.4.1.5875.800.3.10.1.1.7.{id}").value,
            "pos": f"{PON}/{pos}",
            "signal": ("%.2f" % -40.00) if signal == 0.0 else ("%.2f" % signal),
            "sn": snmp.get(f"1.3.6.1.4.1.5875.800.3.10.1.1.10.{id}").value,
            "status": "Active" if snmp.get(f"1.3.6.1.4.1.5875.800.3.10.1.1.11.{id}").value == '1' else "Inactive"
        })
    except:
        break
print(json.dumps(onus))
