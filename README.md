# Tietokantasovelluksen esittelysivu

Yleisiä linkkejä:

* [Linkki sovellukseeni](https://jttakkin.users.cs.helsinki.fi/tsoha)
* [Linkki dokumentaatiooni](doc/Dokumentaatio.pdf)

## Työn aihe

[Aihe](http://advancedkittenry.github.io/suunnittelu_ja_tyoymparisto/aiheet/Aanestys.html)

* Toteutus PHP:llä
* Laajempi versio, sisältää kirjautumisen, käyttäjähallinnan ja identifioidun äänestyksen

## Mallisivut

* [Etusivu](http://jttakkin.users.cs.helsinki.fi/tsoha/suunnitelmat/etusivu)
* [Listaus](http://jttakkin.users.cs.helsinki.fi/tsoha/suunnitelmat/listaus)
* [Äänestyssivu](http://jttakkin.users.cs.helsinki.fi/tsoha/suunnitelmat/aanestys)
* [Uusi äänestys](http://jttakkin.users.cs.helsinki.fi/tsoha/suunnitelmat/uusi)


## Toimivat testaussivut
* [Kirjautuminen](https://jttakkin.users.cs.helsinki.fi/tsoha/login)
* [Äänestysten listaus](https://jttakkin.users.cs.helsinki.fi/tsoha/aanestys/listaus)
* Listaussivun linkit vievät tarkastelemaan äänestyksen tietoja
* [Uuden äänestyksen luonti](https://jttakkin.users.cs.helsinki.fi/tsoha/aanestys/uusi)

##Käyttöohje
* Kirjaudu sisään käyttäjätunnuksella Make, salasana make1234
* Listaussivulle ilmestyy tällä käyttäjällä myös muokkauslinkit äänestyksille.
* Äänestysten muokkaaminen ja poistaminen toimivat. Suurin suurhenkilö -äänestyksen poistaminen ei toimi, koska se rikkoisi tietokannan vierasavaimet.


## Keskeneräisiä ominaisuuksia
* Mitkään muut sivut kuin etusivu eivät vielä testaa onko käyttäjä kirjautunut. Korjaan kunhan ehdin.
* Myöskään muokkaussivu ei tarkasta onko käyttäjä äänestyksen tekijä.


<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/80x15.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.