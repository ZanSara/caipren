  #!/env/bin/python3
  # -*- coding: utf-8 -*-

from flask import render_template
import jinja2
from app import app
from .models import Prenotazione, Visitatore
import datetime, calendar


@app.route('/', methods=['GET'])
def index():
    today = datetime.datetime.now()
    prenotazioni = Prenotazione.query.filter_by(mese = today.month).all()

    # booked_days = { giorno : [lista degli ID delle prenotazioni per quel giorno] }
    booked_days = {}
    for i in range(1, 31):
        booked_days[i] = []

    for pren in prenotazioni:
        for day in range(pren.notti):
            if len(booked_days[pren.giorno_inizio+day]) > 15:
                print("ERRORE nel DATABASE: troppe prenotazioni!!")
                return
            booked_days[pren.giorno_inizio+day].append(pren.id)

    return render_template('main.html',
                            today = today,
                            booked_days = booked_days,
                            dayxmonth = calendar.monthrange(today.year, today.month),
                            prens=prenotazioni)
