  #!/env/bin/python3
  # -*- coding: utf-8 -*-

from flask import request, render_template
from sqlalchemy import and_
from app import app
from .models import Pernottamento
import datetime, calendar, json

@app.route('/<ris>', methods=['GET'])
def index(ris):

    today = datetime.datetime.today() + datetime.timedelta(days=6*31)

    dayxmonth = [] # Useful to change absolute day number to day/month format
    daysum = 0
    for month in range(6,10):
        daysum += calendar.monthrange(today.year, month)[1]
        dayxmonth.append((month, daysum))
    print(dayxmonth)


    calendario = [[] for i in range(daysum)]
    gestioni = [0 for i in range(daysum)]
    prenlist = []

    for absday in range(daysum):
        print(prenlist)
        # Si da' la precedenza alle gestioni
        prenlist += Pernottamento.query.filter_by(giorno_inizio = absday).all()
        prenlist = sorted(prenlist, key=lambda tup: tup.gestione, reverse = True)

        for pren in prenlist:
            for posto in range(pren.posti):
                calendario[absday].append((pren.id, pren.colore, pren.gestione))
            if pren.gestione == 1:  # Se e' una gestione lo aggiungo ANCHE alle gestioni
                gestioni[absday] = pren.id
            if pren.giorno_inizio + pren.durata-1 <= absday:
                prenlist.pop(prenlist.index(pren))
            print(absday, pren.giorno_inizio, pren.durata, " - ", calendario[absday])
    if prenlist != []:
        print("WARNING: C'erano ancora prenotazioni da smaltire alla fine del calendario!!")

    if ris == "ris":
        return render_template('main.html',
                                today = today,
                                booked_days = calendario,
                                gestioni = gestioni,
                                daysum = daysum,
                                dayxmonth = dayxmonth,
                                firstweekday = datetime.date(today.year, 6, 1).weekday(),
                                ris = True)

    return render_template('main1.html',
                            today = today,
                            booked_days = calendario,
                            daysum = daysum,
                            dayxmonth = dayxmonth,
                            firstweekday = datetime.date(today.year, 6, 1).weekday(),
                            ris = False)



def fill_calendar(calendar, daysum):
    for day in range(1, daysum+1):
        prenlist += Pernottamento.query.filter_by(giorno_inizio = day).all()
        for pren in prenlist:
            calendar[absday].append((pren.id, pren.colore))
            pren.durata -= 1
            pren.day += 1
            if pren.durata == 0:
                prenlist.pop(prenlist.index(pren))
    return calendar






def abs2dm(absday, dayxmonth):
    n = 1
    while absday - month[n][1] <= 0:
        n += 1
    return makedm(absday - month[n-1][1], month[n-1][0])

def dm2abs(dm, dayxmonth):
    for month in range(dm["month"]):
        absday += month[1]
    return absday + dm["day"]

def makedm(day, month, monthrange):
    """ Consider months from June to September """
    if day > monthrange:
        month += 1
        day -= monthrange

    if month == 1:
        monthname = "Giu"
    elif month == 2:
        monthname = "Lug"
    elif month == 3:
        monthname = "Ago"
    else: # should be month == 4
        monthname = "Set"
    return { "month": month, "monthname": monthname, "day": day}



@app.route('/data/<cg>', methods=['GET'])
def get_data(cg):
    prenid = request.args["prenid"]
    predata = Pernottamento.query.filter_by(id=prenid).first()

    print(predata.id, predata.responsabile)
    return json.dumps({
        "nome" : predata.nome,
        "tel" : predata.tel,
        "prenid" : predata.id,
        "arrivo": predata.giorno_inizio,
        "durata": "{} notti".format(predata.durata),
        "posti": predata.posti,
        "resp" : predata.responsabile,
        "note": predata.note
     })


