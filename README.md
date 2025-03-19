# Product Catalog

## Přehled

Plně vybavený systém produktového katalogu postavený na PHP s využitím architektury MVC. Poskytuje funkce pro procházení
produktů, autentizaci uživatelů, správu nákupního košíku a administrativní možnosti.

## Architekrura

Aplikace se řídí architekturou Model-View-Controller (MVC):

- **Models**: Reprezentují datovou strukturu a obchodní logiku
- **Views**: Spravují prezentační vrstvu
- **Controllers**: Zpracovávají vstup uživatele a koordinují modely a pohledy

## Diagram toku dat

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   Browser   │ ─────►│   Router    │ ─────►│ Controller  │
└─────────────┘       └─────────────┘       └─────────────┘
                                                   │
                                                   ▼
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    View     │ ◄───── │   Cache    │ ◄───── │   Model     │
└─────────────┘       └─────────────┘       └─────────────┘
      │                                            │
      │                                            ▼
      │                                     ┌─────────────┐
      └────────────────────────────────────►│  Database   │
                                            └─────────────┘
```

## Klíčové komponenty

### Modely

- **Product**: Spravuje data produktů, filtrování a třídění
- **Category**: Řídí hierarchii a vztahy kategorií
- **User**: Spravuje uživatelská data, autentizaci a oprávnění
- **Cache**: Poskytuje mechanismus pro ukládání výsledků dotazů do mezipaměti
- **Database**: Spravuje připojení k databázi a dotazy

### Kontrolery

- **HomeController**: Spravuje stránku s výpisem produktů
- **ProductController**: Řídí detaily jednotlivých produktů
- **CartController**: Spravuje nákupní košík
- **CheckoutController**: Řídí proces objednávky
- **AdminController**: Poskytuje administrativní funkce
- **LoginController/RegisterController**: Spravují autentizaci uživatelů
- **ProfileController**: Spravuje uživatelská data a profil

### Pohledy

Pohledy jsou vykreslovány kontrolery a zobrazují uživatelské rozhraní.

## Klíčové funkce

### Správa produktů

- Výpis produktů s filtrováním a tříděním
- Hierarchie kategorií
- Správa skladových zásob
- Hodnotící systém

### Autentizace uživatelů

- Registrace a přihlášení uživatelů
- Hashování hesel pro bezpečnost
- Uživatelské role (uživatel, administrátor, vlastník)

### Nákupní košík

- Přidávání/odebírání produktů
- Úprava množství
- Proces objednávky

### Administrační panel

- Operace CRUD nad produkty
- Správa kategorií
- Správa uživatelů

## Database Schema

### Products Table

```sql
CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    brand VARCHAR(255),
    price NUMERIC(10,2) NOT NULL,
    category_id INTEGER,
    rating INTEGER[],
    stock INTEGER NOT NULL DEFAULT 0,
    CONSTRAINT product_category_id_fkey FOREIGN KEY (category_id)
        REFERENCES categories (id) ON DELETE CASCADE
);
```

## Schéma databáze

```sql
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    username VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role VARCHAR(5) NOT NULL DEFAULT 'user',
    phone_number VARCHAR(15),
    address TEXT,
    cart INTEGER[] NOT NULL DEFAULT '{}',
    liked INTEGER[] NOT NULL DEFAULT '{}',
    previous_purchases INTEGER[] NOT NULL DEFAULT '{}'
);
```

### Categories Table

```sql
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INTEGER,
    CONSTRAINT categories_parent_id_fkey FOREIGN KEY (parent_id)
        REFERENCES categories (id) ON DELETE SET NULL
);
```

## Instalace a nastavení

1. Naklonujte repozitář
2. Vytvořte databázi a naimportujte schéma SQL
3. Nakonfigurujte soubor `.env` s přihlašovacími údaji k databázi:
   ```
   POSTGRES_HOST=localhost
   POSTGRES_PORT=5432
   POSTGRES_NAME=your_database
   POSTGRES_USER=your_user
   POSTGRES_PASSWORD=your_password
   ```
4. Nainstalujte závislosti pomocí Composeru:
   ```
   composer install
   ```
5. Nastavte webový server (Apache/Nginx) tak, aby směřoval do adresáře projektu

## Bezpečnostní prvky

### PDO pro bezpečnost databáze

Aplikace používá PHP Data Objects (PDO) pro interakce s databází, což poskytuje bezpečný způsob připojení a provádění
dotazů. PDO využívá připravené dotazy k prevenci SQL injekcí.

#### Jak funguje PDO

1. **Připojení**: PDO se připojí k databázi s poskytnutými přihlašovacími údaji.
2. **Připravené dotazy**: Místo přímého vkládání hodnot do SQL se používají zástupné symboly.
3. **Vázání parametrů**: Hodnoty se navážou na zástupné symboly a PDO zajistí jejich bezpečnost.
4. **Provedení**: Dotaz se provede s bezpečnými hodnotami.

#### Příklad z kódu

```php
// From Database.php
public static function query($sql, $params = [])
{
    $pdo = self::connect();
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}
```

Usage example:

```php
// Secure query with parameter binding
$results = Database::query(
    "SELECT * FROM users WHERE username = :username",
    [':username' => $username]
);
```

### Hashování hesel

Aplikace využívá vestavěné funkce PHP `password_hash()` a `password_verify()` k bezpečnému hashování a ověřování hesel.
To zamezuje ukládání hesel v čitelné podobě.

#### Jak funguje hashování hesel

1. **Hashování**: Při registraci se heslo hashuje pomocí `password_hash()`.
2. **Uložení**: Do databáze se uloží pouze hash hesla, nikoli původní heslo.
3. **Ověření**: Při přihlášení se pomocí `password_verify()` kontroluje, zda se zadané heslo shoduje s uloženým hashem.
4. **Salt**: PHP automaticky generuje náhodný salt pro každé heslo, což zvyšuje bezpečnost.

#### Příklad z kódu

Hashing a password during registration:

```php
// From RegisterController.php
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
if ($hashedPassword === false) {
    throw new \Exception("Password hashing failed");
}
```

Verifying a password during login:

```php
// From Cache.php (initUser method)
$userdata = Database::query("SELECT * FROM users WHERE username = :username", [':username' => $username]);
if (!empty($userdata) && password_verify($password, $userdata[0]['password_hash'])) {
    // User is authenticated
    // ...
}
```

## Mechanismus mezipaměti (caching)

Aplikace implementuje systém ukládání do mezipaměti pro zlepšení výkonu snížením počtu dotazů do databáze. Třída `Cache`
spravuje ukládání produktů, kategorií a uživatelských dat.

## Výkonnostní optimalizace

- **Ukládání do mezipaměti**: Výsledky dotazů do databáze se ukládají, aby se snížila jejich četnost
- **Stránkování (pagination)**: Seznam produktů je stránkován, aby se zobrazovalo omezené množství položek najednou
- **Lazy Loading**: Data se načítají pouze v případě potřeby

## Bezpečnostní opatření

- **Validace vstupů**: Všechny uživatelské vstupy jsou validovány a očištěny
- **Ochrana proti CSRF**: Formuláře využívají CSRF tokeny k zabránění útoků CSRF
- **Ochrana proti XSS**: Výstupy jsou ošetřeny proti skriptovacím útokům
- **Ochrana proti SQL injekcím**: Připravené dotazy s PDO chrání proti SQL injekcím

## Budoucí vylepšení

- **Integrace API**: Přidání REST API endpointů pro bezhlavý e-commerce systém
- **Platební brána**: Integrace s platebními procesory
- **Nahrávání obrázků**: Přidání funkcionality pro nahrávání obrázků produktů
- **Optimalizace vyhledávání**: Implementace full-textového vyhledávání pro lepší objevování produktů
- **Reportování**: Přidání analytických a reportovacích funkcí  