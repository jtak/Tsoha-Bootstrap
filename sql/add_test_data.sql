-- Lisää INSERT INTO lauseet tähän tiedostoon
-- Kayttajat:
INSERT INTO Kayttaja (kayttajatunnus, salasana) VALUES ('Make', 'make1234'); -- make1234
INSERT INTO Kayttaja (kayttajatunnus, salasana) VALUES ('Viljami', 'viljami1234');   -- viljami1234
INSERT INTO Kayttaja (kayttajatunnus, salasana, yllapitaja) VALUES ('Kola', 'olli', true);

-- Aanestykset:
INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm) VALUES (1, 'Suurin suurhenkilö', 'Kuka on mielestäsi suurin suurmies koskaan?', '2016-04-01', '2016-05-15');
INSERT INTO Aanestys (tekija, aihe, kuvaus, alkupvm, loppupvm) VALUES (2, 'Paras kertsi', 'Missä on lipaston paras kertsi?', '2016-04-01', '2016-05-01');

--Aanestaneet:
INSERT INTO Aanestaneet (kayttaja, aanestys) VALUES (2, 1);
INSERT INTO Aanestaneet (kayttaja, aanestys) VALUES (2, 2);
INSERT INTO Aanestaneet (kayttaja, aanestys) VALUES (1, 2);
INSERT INTO Aanestaneet (kayttaja, aanestys) VALUES (3, 2);

--Vaihtoehdot:
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (1, 'TRUMP ON PARAS! JA TYHMYYS!', 'OOSSOM');
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (1, 'Jeesus Vipunen', 'Ei väliä ketä äänestää, kuha. äänestää kuhaa.');
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (2, 'Bio-3', '');
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (2, 'Gurula', 'ja navetta');
INSERT INTO Vaihtoehto (aanestys, nimi, lisatieto) VALUES (2, 'Bio-2', '');

--Äänet
INSERT INTO Aani (vaihtoehto) VALUES(2);
INSERT INTO Aani (vaihtoehto) VALUES(3);
INSERT INTO Aani (vaihtoehto) VALUES(3);
INSERT INTO Aani (vaihtoehto) VALUES(4);