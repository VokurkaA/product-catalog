# Product Catalog

## Komunikace s databází prostřednictvím PDO

Aplikace využívá třídu PDO (PHP Data Objects) pro přístup k databázi, což poskytuje bezpečné a konzistentní rozhraní pro práci s různými typy databází. V našem projektu je implementována komunikace s PostgreSQL databází.

### Třída Database

Hlavní součástí komunikace s databází je třída `Database` v souboru `App/Models/Database.php`, která poskytuje následující funkce:

1. **Inicializace připojení**
   - Načítání konfiguračních údajů z `.env` souboru
   - Automatická inicializace při prvním použití

2. **Připojení k databázi**
   - Využití PDO pro vytvoření spojení s PostgreSQL databází
   - Nastavení chybového režimu na výjimky pro snadnější zpracování chyb
   - Nastavení výchozího způsobu získávání dat jako asociativní pole

3. **Dotazování databáze**
   - Metoda `query()` pro provádění SQL dotazů s parametry
   - Použití připravených výrazů (prepared statements) pro zabezpečení proti SQL injection
   - Automatické zpracování a vrácení výsledků

### Příklad použití PDO v aplikaci

```php
// Příklad dotazu z třídy Cache
$userdata = Database::query("SELECT * FROM users WHERE username = :username", [':username' => $username]);

// Příklad aktualizace dat v databázi
$query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
Database::query($query, [':username' => $username, ':email' => $email, ':id' => $id]);
```

### Výhody použití PDO

- **Bezpečnost**: Ochrana proti SQL injection útoků díky parametrizovaným dotazům
- **Přenositelnost**: Snadný přechod mezi různými databázovými systémy
- **Efektivita**: Připravené výrazy zlepšují výkon opakovaných dotazů
- **Zpracování chyb**: Robustní systém výjimek pro lepší zachycení a řešení problémů

## Hashování hesla a jeho ověření

Aplikace implementuje bezpečnou správu hesel využitím PHP funkcí pro hashování a ověřování.

### Hashování hesla při registraci

Při registraci nového uživatele nebo změně hesla se používá funkce `password_hash()` s algoritmem BCRYPT:

```php
// Příklad z RegisterController.php
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Příklad z AdminController.php
$users[$id]->password = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
```

### Ověření hesla při přihlášení

Pro ověření správnosti hesla při přihlášení se používá funkce `password_verify()`:

```php
// Příklad z Cache.php
if (!empty($userdata) && password_verify($password, $userdata[0]['password_hash'])) {
    // Uživatel je úspěšně ověřen
}
```

### Výhody zvoleného řešení

1. **Bezpečnost**:
   - BCRYPT automaticky obsahuje "sůl" (salt), která je unikátní pro každý hash
   - Algoritmus je navržen tak, aby byl odolný vůči útokům hrubou silou
   - Výsledný hash obsahuje všechny informace potřebné pro ověření (algoritmus, cost parametr, sůl)

2. **Adaptabilita**:
   - Nativní PHP funkce umožňují v budoucnu snadno přejít na novější, bezpečnější algoritmy
   - Změny vyžadují minimální úpravy kódu

3. **Snadné použití**:
   - Intuitivní API bez nutnosti manuální správy soli nebo dalších parametrů

## Popis a zdůvodnění řešení

### Architektura aplikace

Projekt využívá architekturu MVC (Model-View-Controller), která rozděluje aplikaci na tři hlavní komponenty:

1. **Modely** (`App/Models/`):
   - Reprezentují datové struktury a business logiku
   - Zapouzdřují přístup k databázi a manipulaci s daty
   - Implementují validaci dat a aplikační pravidla

2. **Pohledy** (`App/Views/`):
   - Starají se o prezentační vrstvu
   - Obsahují šablony pro zobrazení dat uživateli
   - Oddělují aplikační logiku od prezentace

3. **Kontrolery** (`App/Controllers/`):
   - Zpracovávají požadavky uživatelů
   - Koordinují spolupráci mezi modely a pohledy
   - Řídí tok aplikace a přesměrování

### Implementované bezpečnostní prvky

1. **Ochrana proti SQL injection**:
   - Použití parametrizovaných dotazů (prepared statements) via PDO
   - Žádné přímé vkládání uživatelských vstupů do SQL dotazů

2. **Zabezpečení hesel**:
   - Bezpečné hashování hesel pomocí BCRYPT
   - Neukládání hesel v čitelné podobě

3. **Validace a sanitace vstupů**:
   - Ošetření všech uživatelských vstupů před jejich zpracováním
   - Použití `htmlspecialchars()` pro prevenci XSS útoků
   - Validace emailových adres, telefonních čísel a dalších údajů

4. **Autorizace a autentizace**:
   - Kontrola uživatelských rolí pro přístup k admin funkcím
   - Zabezpečení citlivých operací pouze pro přihlášené uživatele

### Datový model a cache

Aplikace používá třídu `Cache` pro efektivní správu dat v paměti a redukci opakovaných dotazů na databázi:

1. **Správa session a paměti**:
   - Ukládání dat v session pro persistenci mezi požadavky
   - Vnitřní cache v paměti pro rychlý přístup v rámci jednoho požadavku

2. **Synchronizace s databází**:
   - Automatická synchronizace změn v uživatelských datech s databází
   - Inicializace dat z databáze při prvním přístupu

### Zdůvodnění technických rozhodnutí

1. **Použití PDO místo MySQLi nebo jiných alternativ**:
   - Abstrakce databázové vrstvy pro snadnou přenositelnost
   - Lepší podpora zabezpečených dotazů a zpracování chyb
   - Konzistentní API napříč různými databázovými systémy

2. **Volba BCRYPT pro hashování hesel**:
   - Standardizovaný a osvědčený algoritmus s dobrou bezpečnostní pověstí
   - Integrovaná "sůl" a automatické nastavení vhodné složitosti
   - Nativní podpora v PHP bez nutnosti externích knihoven

3. **Implementace vrstvené architektury (MVC)**:
   - Oddělení zodpovědností pro lepší údržbu a rozšíření kódu
   - Znovu použitelné komponenty napříč aplikací
   - Snadnější testování jednotlivých částí systému

4. **Využití vlastní cache vrstvy**:
   - Optimalizace výkonu redukcí redundantních databázových dotazů
   - Zachování konzistence dat během jedné session
   - Flexibilní mechanismus pro správu dat v paměti

### Shrnutí

Aplikace je navržena s důrazem na:
- **Bezpečnost**: Ochrana proti běžným typům útoků
- **Výkon**: Efektivní správa paměti a databázových dotazů
- **Udržitelnost**: Jasná struktura a oddělení zodpovědností
- **Rozšiřitelnost**: Modulární design umožňující snadné přidávání nových funkcí

Implementace kombinuje moderní postupy vývoje PHP aplikací s osvědčenými bezpečnostními praktikami pro vytvoření robustního, bezpečného a efektivního produktového katalogu.