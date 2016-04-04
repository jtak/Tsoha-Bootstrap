-- Lisää INSERT INTO lauseet tähän tiedostoon
-- Kayttajat:
INSERT INTO Kayttaja (kayttajatunnus, salasana) VALUES ('Make', 'make1234');
INSERT INTO Kayttaja (kayttajatunnus, salasana) VALUES ('Viljami', 'viljami1234');

-- Aanestykset:
INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm) VALUES (1, 'Suurin suurhenkilö', 'Kuka on mielestäsi suurin suurmies koskaan?', '2016-04-01', '2016-05-15');

--Aanestaneet:
INSERT INTO Aanestaneet (kayttaja, aanestys) VALUES (2, 1);

--Vaihtoehdot:
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (1, 'TRUMP ON PARAS! JA TYHMYYS!', 'OOSSOM');
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (1, 'Jeesus Vipunen', 'Ei väliä ketä äänestää, kuha. äänestää kuhaa.');

--Äänet
INSERT INTO Aani (vaihtoehto) VALUES(2);