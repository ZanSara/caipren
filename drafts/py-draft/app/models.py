  #!/env/bin/python3
  # -*- coding: utf-8 -*-

from app import db

class Pernottamento(db.Model):
    __tablename__ = 'pernottamenti'
    id = db.Column(db.Integer, primary_key=True)
    nome = db.Column(db.String(64))
    tel = db.Column(db.String(64))
    giorno_inizio = db.Column(db.Integer)
    posti = db.Column(db.Integer)
    durata = db.Column(db.Integer)
    note = db.Column(db.String(100))

    gestione = db.Column(db.Boolean)
    responsabile = db.Column(db.String(64))
    colore = db.Column(db.String(64))

    def __repr__(self):
        return '<Pernottamento {0}>'.format(self.nome)

