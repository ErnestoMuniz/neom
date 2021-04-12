from Exscript.util.start import quickstart
from Exscript.util.match import first_match

def do_something(job, host, conn):
    conn.execute('RTRV-HGUTR069-SPARAM::HGUTR069SPARAM-1-1-1-2-12-9::')
    print("The response was", repr(conn.response))

quickstart('telnet://10.240.160.14', do_something)
