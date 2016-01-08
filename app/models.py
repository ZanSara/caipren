  #!/env/bin/python3
  # -*- coding: utf-8 -*-

from app import db

class Visitatore(db.Model):
    __tablename__ = 'visitatori'
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(64))
    telefono = db.Column(db.String(15), index=True, unique=True)
    prenotazioni = db.relationship('Prenotazione')

    def __repr__(self):
        return '<Cliente {0}>'.format(self.nome)

class Prenotazione(db.Model):
    __tablename__ = 'prenotazioni'
    id = db.Column(db.Integer, primary_key=True)
    cliente = db.Column(db.Integer, db.ForeignKey('visitatori.id'))
    giorno_inizio = db.Column(db.Integer)
    mese = db.Column(db.Integer)
    stagione = db.Column(db.Integer)
    notti = db.Column(db.Integer)
    responsabile = db.Column(db.String(64))
    note = db.Column(db.String(100))

    def __repr__(self):
        return '<Prenotazione {0}>'.format(self.id)
