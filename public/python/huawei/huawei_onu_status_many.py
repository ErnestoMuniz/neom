import telnetlib
import sys

HOST = sys.argv[1].split(":")[0]
PORT = int(sys.argv[1].split(":")[1])
user = sys.argv[2]
password = sys.argv[3]
SNS = sys.argv[4].split(",")

tn = telnetlib.Telnet(HOST, PORT)

tn.read_until(b"name:")
tn.write(user.encode("ascii") + b"\n")
if password:
    tn.read_until(b"password:")
    tn.write(password.encode("ascii") + b"\n")
tn.read_until(b">")
tn.write(b"enable\n")
tn.read_until(b"#")
tn.write(b"undo smart\n")
tn.read_until(b"#")
total: list[dict[str, str]] = []
for sn in SNS:
    tn.write(f"display ont info by-sn {sn} | no-more\n".encode("ascii"))
    res = tn.read_until(b"#").decode("ascii")
    result = {
        "desc": res.split("Description             : ")[1].split("\r\n")[0],
        "pos": res.split("F/S/P                   : ")[1].split("\r\n")[0],
        "signal": res.split("Description             : ")[1].split("\r\n")[0],
        "sn": sn,
        "status": res.split("Description             : ")[1].split("\r\n")[0],
    }
    tn.write(
        f"display ont info summary {result['pos']} | include {res.split('SN                      : ')[1].split(' (')[0]}\n".encode(
            "ascii"
        )
    )
    res = tn.read_until(b"#").decode("ascii").split("\r\n")[4]
    result["signal"] = res.split()[4].split("/")[0]
    result["status"] = "active" if float(result["signal"]) > -40 else "inactive"
    total.append(result)
print(total)
