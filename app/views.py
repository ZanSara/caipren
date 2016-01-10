  #!/env/bin/python3
  # -*- coding: utf-8 -*-

from flask import request, render_template
import jinja2
from app import app
from .models import Prenotazione, Visitatore
import datetime, calendar, json


@app.route('/<ris>', methods=['GET'])
def index(ris):

    today = datetime.datetime.today() + datetime.timedelta(days=6)


    prenxmonth = []
    prenotazioni = []
    for month in range(1,3):
        prenotazioni = Prenotazione.query.filter_by(mese = month).all()

        # booked_days = { giorno : [lista degli ID delle prenotazioni per quel giorno] }
        booked_days = {}
        for i in range(1, 31):
            booked_days[i] = []

        for pren in prenotazioni:
            for day in range(pren.notti):
                if len(booked_days[pren.giorno_inizio+day]) > 15:
                    print("ERRORE nel DATABASE: troppe prenotazioni!!")
                    return
                for n in range(pren.posti):
                    booked_days[pren.giorno_inizio+day].append((pren.id, pren.cliente, pren.colore))

        prenxmonth.append( {"month": month, "booked_days": booked_days, "dayxmonth": calendar.monthrange(today.year, month)})

    if ris == "ris":
        return render_template('main.html',
                            today = today,
                            prenxmonth = prenxmonth,
                            ris = True)

    return render_template('main.html',
                            today = today,
                            prenxmonth = prenxmonth,
                            ris = False)




@app.route('/data', methods=['GET'])
def get_data():
    prenid = request.args["prenid"]
    predata = Prenotazione.query.filter_by(id=prenid).first()
    clidata = Visitatore.query.filter_by(id=predata.cliente).first()

    print(predata.id, predata.responsabile)
    return json.dumps({
        "nome" : clidata.nome,
        "tel" : clidata.telefono,
        "id" : predata.id,
        "resp" : predata.responsabile
     })


