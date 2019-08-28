<?php
class Queries{ 
    //with parameters
    public $param1;
    public $param2;
    public $param3;
    public $param4;
    
    protected $funkcjaRankingowa1;
    protected $findColumnInTable;
    protected $partycjaObliczeniowa2;
    public $lotyLataKontynenty;
    public function __construct($param1 = null, $param2 = null, $param3 = null, $param4 = null){
        if ($param1 !== null) {
            $this->param1 = $param1;
        }
        if ($param2 !== null) {
            $this->param2 = $param2;
            $this->findInTable = 'SELECT '.$this->param1.' FROM '.$this->param2;
        }
        if ($param3 !== null) {
            $this->param3 = $param3;
        }
        if ($param4 !== null) {
            $this->param4 = $param4;
            $this->findInTable = 'SELECT '.$this->param1.' FROM '.$this->param2.' where '.$this->param3.'='.$this->param4.'';
        }
        $this->funkcjaRankingowa1 = 'SELECT TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa) AS TRASA,
        SUM(lot.liczba_biletow) AS "LICZBA SPRZEDANYCH BILETÓW",
        RANK() OVER(ORDER BY SUM(lot.liczba_biletow) DESC) AS "RANKING"
        FROM lot, trasa, lotnisko l1, lotnisko l2, data, rok
        WHERE lot.id_trasy = trasa.id_trasy 
        AND trasa.id_lotniska = l1.id_lotniska
        AND trasa.id_lotniska2 = l2.id_lotniska
        AND lot.id_daty = data.id_daty
        AND data.id_roku = rok.id_roku
        AND rok.rok = '.$this->param1.' - 1
        GROUP BY TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)';
		$this->partycjaObliczeniowa2 = 'SELECT przewoznik.nazwa AS PRZEWOŹNIK,
			samolot.nazwa AS "NAZWA SAMOLOTU", 
			m2.nazwa AS DO,
			TO_CHAR(data.data, \'HH:MM\') AS "GODZINA ODLOTU",
			ROUND(100*(lot.wolne_miejsca)/SUM(samolot.miejsca) OVER (PARTITION BY m2.nazwa, data.data),2) "PROCENT WOLNYCH MIEJSC"
			FROM lot, samolot, przewoznik, data, rok, trasa, lotnisko l1, lotnisko l2, miasto m1, miasto m2
			WHERE lot.id_samolotu = samolot.id_samolotu
			AND lot.id_przewoznika = przewoznik.id_przewoznika
			AND lot.id_daty = data.id_daty
			AND data.id_roku = rok.id_roku
			AND lot.id_trasy = trasa.id_trasy 
			AND trasa.id_lotniska = l1.id_lotniska
			AND trasa.id_lotniska2 = l2.id_lotniska
			AND l1.id_miasta = m1.id_miasta
			AND l2.id_miasta = m2.id_miasta
			AND rok.rok = '.$this->param1.'
			AND ROUND(100*(lot.wolne_miejsca)/(samolot.miejsca),2) > 50';
        
        $this->lotyLataKontynenty = 'select lot.id_lotu from lot, kontynent, kraj, miasto, lotnisko, trasa, data, rok
            where lot.id_trasy = trasa.id_trasy and trasa.id_lotniska=lotnisko.id_lotniska
            and miasto.id_miasta=lotnisko.id_miasta and kraj.id_kraju = miasto.id_kraju
            and kontynent.id_kontynentu=kraj.id_kontynentu and kontynent.id_kontynentu = '.$this->param1.'
            and lot.id_daty = data.id_daty and data.id_roku=rok.id_roku and rok.rok='.$this->param2.'';
    }
    //without parameters
    protected static $zapytanieRollup1 = 'SELECT NVL(TO_CHAR(przewoznik.nazwa), \' \') AS PRZEWOŹNIK, 
    NVL(TO_CHAR(samolot.nazwa), \' \') AS SAMOLOT, 
    SUM(lot.zysk) AS ZYSKI, 
    SUM(lot.strata) AS STRATY, 
    SUM(lot.przychod) AS PRZYCHOD 
    FROM lot, przewoznik, samolot
    WHERE lot.id_przewoznika = przewoznik.id_przewoznika
    AND lot.id_samolotu = samolot.id_samolotu
    GROUP BY ROLLUP(przewoznik.nazwa, samolot.nazwa)';
    protected static $zapytanieRollup2 = 'SELECT NVL(TO_CHAR(TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), \' \') AS TRASA, 
    NVL(TO_CHAR(TO_CHAR(pilot.imie ||\' \'|| pilot.nazwisko)), \' \') AS PILOT, 
    ROUND(AVG(lot.przychod),2) AS PRZYCHÓD 
    FROM lot, trasa, pilot, lotnisko l1, lotnisko l2
    WHERE lot.id_trasy = trasa.id_trasy
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND lot.id_pilota = pilot.id_pilota
    GROUP BY ROLLUP((TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), (TO_CHAR(pilot.imie ||\' \'|| pilot.nazwisko)))';
    protected static $zapytanieCube1 = 'SELECT NVL(TO_CHAR(przewoznik.nazwa), \' \') AS PRZEWOŹNIK, 
    NVL(TO_CHAR(TO_CHAR(lot.czas, \'DD/MM/RRRR\')), \' \') AS DATA, 
    SUM(lot.liczba_biletow) AS "SPRZEDANE BILETY" 
    FROM lot, przewoznik
    WHERE lot.id_przewoznika = przewoznik.id_przewoznika
    GROUP BY CUBE(przewoznik.nazwa, lot.czas)';
    protected static $zapytanieCube2 = 'SELECT NVL(TO_CHAR(przewoznik.nazwa), \' \') AS PRZEWOŹNIK, 
    NVL(TO_CHAR(TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), \' \') AS TRASA, 
    ROUND(AVG(lot.zysk),2) AS "ŚREDNIA ZYSKÓW", 
    ROUND(AVG(lot.strata),2) AS "ŚREDNIA STRAT",
    ROUND(AVG(lot.przychod),2) AS "ŚREDNI PRZYCHÓD" 
    FROM lot, przewoznik, trasa, lotnisko l1, lotnisko l2
    WHERE lot.id_trasy = trasa.id_trasy
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND lot.id_przewoznika = przewoznik.id_przewoznika
    GROUP BY CUBE(przewoznik.nazwa, (TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)))';
    protected static $zapytanieGroupingSets1 = 'SELECT NVL(TO_CHAR(przewoznik.nazwa), \' \') AS PRZEWOŹNIK, 
    NVL(TO_CHAR(TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), \' \') AS TRASA, 
    NVL(TO_CHAR(TO_CHAR(lot.czas, \'DD/MM/RRRR\')), \' \') AS DATA, 
    SUM(lot.przychod) AS PRZYCHÓD
    FROM lot, przewoznik, trasa, lotnisko l1, lotnisko l2
    WHERE lot.id_trasy = trasa.id_trasy
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND lot.id_przewoznika = przewoznik.id_przewoznika
    GROUP BY GROUPING SETS((przewoznik.nazwa), (przewoznik.nazwa, (TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa))), (lot.czas))';
    protected static $zapytanieGroupingSets2 = 'SELECT NVL(TO_CHAR(przewoznik.nazwa), \' \') AS PRZEWOŹNIK, 
    NVL(TO_CHAR(TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), \' \') AS TRASA, 
    NVL(TO_CHAR(TO_CHAR(lot.czas, \'DD/MM/RRRR\')), \' \') AS DATA, 
    SUM(lot.liczba_biletow) AS "LICZBA SPRZEDANYCH BILETÓW"
    FROM lot, przewoznik, trasa, lotnisko l1, lotnisko l2
    WHERE lot.id_trasy = trasa.id_trasy
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND lot.id_przewoznika = przewoznik.id_przewoznika
    GROUP BY GROUPING SETS((przewoznik.nazwa, (TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa)), lot.czas), (lot.czas, (TO_CHAR(l1.nazwa ||\' - \'|| l2.nazwa))))';
    protected static $partycjaObliczeniowa1 = 'SELECT przewoznik.nazwa AS LINIE, 
    kwartal.id_kwartalu AS KWARTAŁ, 
    lot.przychod AS PRZYCHÓD, 
    SUM(lot.przychod) OVER (PARTITION BY przewoznik.nazwa, kwartal.id_kwartalu) "CAŁKOWITY PRZYCHÓD",
    ROUND(100*lot.przychod/(SUM(lot.przychod) OVER (PARTITION BY przewoznik.nazwa)),2) "UDZIAŁ W \'%\'"
    FROM lot, przewoznik, data, kwartal
    WHERE lot.id_przewoznika = przewoznik.id_przewoznika
    AND lot.id_daty = data.id_daty
    AND data.id_kwartalu = kwartal.id_kwartalu
    AND EXTRACT(YEAR FROM data.data) = EXTRACT(YEAR FROM SYSDATE) - 1';

    protected static $oknoObliczeniowe1 = 'SELECT TO_CHAR(data.data, \'HH:MM\') AS "GODZINA ODLOTU",
    przewoznik.nazwa AS LINIA,
    l2.oznaczenie,
    m2.nazwa AS DO,
    lot.liczba_biletow AS "LICZBA PASAŻERÓW",
    SUM(lot.liczba_biletow) OVER (PARTITION BY przewoznik.nazwa, m2.nazwa ORDER BY TO_CHAR(data.data, \'HH:MM\') RANGE BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS "SUMA SPRZEDANYCH BILETÓW"
    FROM lot, data, przewoznik, trasa, lotnisko l1, lotnisko l2, miasto m1, miasto m2
    WHERE lot.id_daty = data.id_daty
    AND lot.id_przewoznika = przewoznik.id_przewoznika
    AND lot.id_trasy = trasa.id_trasy 
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND l1.id_miasta = m1.id_miasta
    AND l2.id_miasta = m2.id_miasta';
    protected static $oknoObliczeniowe2 = 'SELECT miesiac.nazwa AS MIESIĄC,
    przewoznik.nazwa AS LINIA,
    m2.nazwa AS DO,
    lot.przychod AS PRZYCHÓD,
    SUM(lot.przychod) OVER (PARTITION BY przewoznik.nazwa, m2.nazwa ORDER BY miesiac.id_miesiaca, przewoznik.nazwa, m2.nazwa RANGE BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS "SUMA PRZYCHODU"
    FROM lot, data, miesiac, rok, przewoznik, trasa, lotnisko l1, lotnisko l2, miasto m1, miasto m2
    WHERE lot.id_daty = data.id_daty
    AND data.id_miesiaca = miesiac.id_miesiaca
    AND data.id_roku = rok.id_roku
    AND lot.id_przewoznika = przewoznik.id_przewoznika
    AND lot.id_trasy = trasa.id_trasy 
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND l1.id_miasta = m1.id_miasta
    AND l2.id_miasta = m2.id_miasta
    AND rok.rok between 2000 AND 2010';
    protected static $funkcjaRankingowa2 = 'SELECT kraj.nazwa AS "KRAJ",
    SUM(lot.liczba_biletow) AS "LICZBA TURYSTÓW",
    RANK() OVER(ORDER BY SUM(lot.liczba_biletow) DESC) AS "RANKING"
    FROM lot, trasa, lotnisko l1, lotnisko l2, kraj, miasto, data, rok, kwartal
    WHERE lot.id_trasy = trasa.id_trasy 
    AND trasa.id_lotniska = l1.id_lotniska
    AND trasa.id_lotniska2 = l2.id_lotniska
    AND l2.id_miasta = miasto.id_miasta
    AND miasto.id_kraju = kraj.id_kraju
    AND lot.id_daty = data.id_daty
    AND data.id_roku = rok.id_roku
    AND data.id_kwartalu = kwartal.id_kwartalu
    AND rok.rok = 2010
    AND kwartal.nazwa = \'pierwszy\'
    AND lot.stan != \'Odwołany\'
    GROUP BY kraj.nazwa';
}
?>