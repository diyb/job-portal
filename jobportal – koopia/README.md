# JobPortal – tööotsimise ja värbamise keskkond

PHP 8+ · MySQL · PDO · Bootstrap 5 · MVC-laadne struktuur. Kolm rolli: tööotsija, tööandja, administraator.

## Paigaldus (XAMPP)

1. Kopeeri kaust `jobportal/` kausta `C:\xampp\htdocs\` (Linux/Mac: `/opt/lampp/htdocs/`).
2. Käivita XAMPP-is **Apache** ja **MySQL**.
3. Ava phpMyAdmin (`http://localhost/phpmyadmin`) → **Import** → vali `database.sql` → **Go**.
   See loob andmebaasi `jobportal`, tabelid ja demoandmed.
4. Vajadusel muuda andmebaasi andmeid failis `inc/database.php` (XAMPP vaikimisi: kasutaja `root`, parool tühi).
5. Ava `http://localhost/jobportal/` (admin: `http://localhost/jobportal/jobportal_admin/`).

> Bootstrapi CSS/JS ja ikoonid laetakse CDN-ist, seega vajad arenduse ajal internetiühendust.

## Paigaldus olemasolevasse Dockeri keskkonda (web + MySQL konteiner `db`, root/root)

1. Pane kaust `jobportal/` oma veebikonteineri juurkausta.
2. Impordi phpMyAdminis `database.sql` (loob andmebaasi `jobportal`).
3. `inc/database.php` vaikeseaded on `db` / `root` / `root`; vajadusel muuda.
4. Ava `http://localhost:<veebi-port>/jobportal/`.

## Paigaldus Dockeriga (eraldi, oma docker-compose.yml)

1. Paki ZIP lahti kausta `jobportal/` ja ava selle kaustas terminal.
2. Käivita: `docker compose up -d --build`
3. Oota ~30 sekundit (esimesel korral imporditakse `database.sql` automaatselt).
4. Ava **http://localhost/jobportal/** (admin: http://localhost/jobportal/jobportal_admin/, phpMyAdmin: http://localhost:8080).

Kasulikud käsud:
- Peata: `docker compose down`
- Lähtesta andmebaas algandmetele: `docker compose down -v` ja siis uuesti `docker compose up -d`
- Logid: `docker compose logs -f web`

Kui port 80 on hõivatud (nt XAMPP töötab), peata XAMPP või muuda `docker-compose.yml` real `"80:80"` väärtuseks `"8081:80"`;
siis on aadress http://localhost:8081/jobportal/.

## Demokontod

| Roll | E-post | Parool |
|---|---|---|
| Administraator | admin@jobportal.ee | `Admin123!` |
| Tööotsija | denis@gmail.com | `Parool123!` |
| Tööandja | tech@techsolutions.ee | `Parool123!` |
| Tööandja | info@dreamworks.ee | `Parool123!` |

**Enne päris kasutamist kustuta/muuda demokontode paroolid.**

## Funktsioonid

**Tööotsija:** registreerimine ja sisselogimine, profiil, CV üleslaadimine (PDF/DOC/DOCX), tööpakkumiste otsing ja filtreerimine
(märksõna, valdkond, asukoht, töökoormus, palgavahemik, sorteerimine, lehekülgedeks jaotus), lemmikud, kandideerimine,
kandideerimiste ajalugu ja staatus, sõnumid ja teavitused, kaebuse esitamine kuulutuse kohta.

**Tööandja:** ettevõtte profiil ja logo, kuulutuste lisamine/muutmine/kustutamine, kandidaatide ja CV-de vaatamine,
kandideerimise staatuse muutmine (Uus / Ülevaatamisel / Sobiv / Tagasi lükatud), statistika, sõnumid.

**Administraator** (`jobportal_admin/`): dashboard, kasutajad (blokeerimine, rollid, kustutamine), ettevõtted, kuulutused
(muutmine, sulgemine, kustutamine), kategooriad ja asukohad, kandideerimiste ülevaade, kaebused, portaali seaded.

## Kaustastruktuur

```
jobportal/
├── controller/          kontrollerid (Auth, User, Job, Company, Application, Favorite, Message, Employer)
├── model/               andmebaasi klassid (User, Company, Job, Category, Location, Application, CV, Favorite, ...)
├── view/                tavakasutaja ja tööandja vaated
├── jobportal_admin/     eraldi admini keskkond (controllerAdmin, modelAdmin, routeAdmin, viewAdmin, public)
├── inc/                 database.php (PDO ühendus), functions.php (abifunktsioonid)
├── route/routing.php    URL -> kontrolleri tegevus
├── public/              css, js, logos
├── uploads/cv/          CV-failid (veebist otse ligipääsmatud)
├── database.sql
└── index.php            sisenemispunkt (?page=...)
```

## Turvalisus

- Paroolid: `password_hash()` / `password_verify()`.
- Kõik SQL päringud kasutavad PDO prepared statement'e.
- Väljund on `e()` (htmlspecialchars) abil escape'itud – XSS kaitse.
- Kõikidel POST vormidel on CSRF token; sisselogimisel `session_regenerate_id()`.
- Rollikontroll marsruudi tasemel (`role` valik) ja objektide omaniku kontroll (tööandja saab muuta ainult oma kuulutusi,
  tööotsija ainult oma profiili ja CV-sid).
- CV üleslaadimine: laiendi, MIME-tüübi (`finfo`) ja suuruse kontroll, juhuslik failinimi, kaust `uploads/cv/` on
  `.htaccess`-iga suletud ning allalaadimine käib läbi PHP õiguste kontrolli (omanik, kandideerimist näinud tööandja, admin).
- Logode kaustas on PHP käivitamine keelatud.
- Blokeeritud kasutaja ei saa sisse logida ega jätka olemasoleva sessiooniga.

## Edasiarendus

Võimalikud lisad: e-posti teavitused (PHPMailer), parooli taastamine, kuulutuste tähtaeg, CV eelvaade,
mitmekeelsus, pildid/logod adminis modereerimiseks.
