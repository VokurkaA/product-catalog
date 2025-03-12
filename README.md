# Product Catalog

## Obecný popis možností komunikace s databází prostřednictvím PDO

PDO (PHP Data Objects) je rozhraní pro přístup k databázím v PHP. Umožňuje nám komunikovat s různými databázovými systémy pomocí jednotného API. PDO podporuje připravené dotazy, což zvyšuje bezpečnost aplikace proti SQL injection útokům. Zde je několik základních možností komunikace s databází prostřednictvím PDO:

1. **Připojení k databázi**:
    ```php
    $dsn = 'pgsql:host=localhost;port=5432;dbname=testdb;';
    $username = 'dbuser';
    $password = 'dbpass';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    ```

2. **Provádění dotazů**:
    ```php
    $stmt = $pdo->query('SELECT * FROM users');
    $users = $stmt->fetchAll();
    ```

3. **Připravené dotazy**:
    ```php
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
    ```

4. **Vkládání dat**:
    ```php
    $stmt = $pdo->prepare('INSERT INTO users (username, email) VALUES (:username, :email)');
    $stmt->execute(['username' => $username, 'email' => $email]);
    ```

## Popis postupu hashování hesla a jeho následného ověření

Hashování hesla je proces, při kterém se heslo převede na jiný řetězec pomocí hashovací funkce. Tento proces je jednosměrný, což znamená, že z hashovaného hesla nelze zpětně získat původní heslo. V PHP se pro hashování hesel používá funkce `password_hash` a pro ověření hesla funkce `password_verify`.

1. **Hashování hesla**:
    ```php
    $password = 'user_password';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    ```

2. **Ověření hesla**:
    ```php
    $isPasswordValid = password_verify($password, $hashedPassword);
    if ($isPasswordValid) {
        // Heslo je správné
    } else {
        // Heslo je nesprávné
    }
    ```

## Popis a zdůvodnění řešení

Tato aplikace je navržena jako katalog produktů s možností správy produktů a kategorií. Aplikace je postavena na MVC (Model-View-Controller) architektuře, což zajišťuje oddělení logiky aplikace od uživatelského rozhraní. Toto oddělení umožňuje snadnější údržbu a rozšiřitelnost aplikace.

### Modely

Modely obsahují logiku pro práci s daty, jako jsou produkty, kategorie a uživatelé. Modely také komunikují s databází prostřednictvím PDO. Každý model reprezentuje jednu entitu v databázi a poskytuje metody pro manipulaci s těmito daty. Například model `Product` obsahuje metody pro filtrování a třídění produktů, zatímco model `User` obsahuje metody pro správu uživatelských dat.

### Kontrolery

Kontrolery zpracovávají uživatelské požadavky, volají metody modelů a vrací odpovědi ve formě pohledů. Každý kontroler je zodpovědný za jednu část aplikace. Například `ProductController` zpracovává požadavky týkající se produktů, zatímco `ProfileController` zpracovává požadavky týkající se uživatelského profilu. Kontrolery také zajišťují, že uživatelé mají správná oprávnění pro provádění určitých akcí.

### Pohledy

Pohledy obsahují HTML šablony, které jsou zobrazeny uživateli. Pohledy jsou zodpovědné za prezentaci dat, která jsou poskytována kontrolery. Například pohled `ProductView` zobrazuje detaily produktu, zatímco pohled `ProfileView` zobrazuje informace o uživatelském profilu. Pohledy jsou navrženy tak, aby byly snadno upravitelné a rozšiřitelné.

### Cache

Aplikace využívá cache pro ukládání často používaných dat, což zvyšuje výkon a snižuje zátěž na databázi. Cache je implementována pomocí třídy `Cache`, která ukládá data do paměti a do session. Tímto způsobem je možné rychle přistupovat k často používaným datům, jako jsou produkty a kategorie, aniž by bylo nutné opakovaně dotazovat databázi.

### Bezpečnost

Uživatelé mohou být přihlášeni a jejich data jsou bezpečně uložena pomocí hashování hesel. Pro hashování hesel je použita funkce `password_hash`, která zajišťuje, že hesla jsou uložena bezpečně a nelze je zpětně získat. Pro ověření hesel je použita funkce `password_verify`, která porovnává zadané heslo s hashovaným heslem uloženým v databázi.

### Databáze

Struktura databáze je navržena tak, aby splňovala požadavky aplikace. Databáze obsahuje tabulky pro produkty, kategorie a uživatele. Každá tabulka obsahuje sloupce, které reprezentují vlastnosti jednotlivých entit. Například tabulka `products` obsahuje sloupce pro název, popis, cenu a kategorii produktu. Databáze je inicializována pomocí skriptu `database.sql`, který vytváří tabulky a vkládá inicializační data.

### Závěr

Celkově je aplikace navržena tak, aby byla bezpečná, škálovatelná a snadno rozšiřitelná. Díky použití MVC architektury je možné snadno přidávat nové funkce a upravovat stávající kód. Použití cache zvyšuje výkon aplikace a snižuje zátěž na databázi. Bezpečnost uživatelských dat je zajištěna pomocí hashování hesel. Struktura databáze je navržena tak, aby splňovala požadavky aplikace a umožňovala snadnou správu dat.