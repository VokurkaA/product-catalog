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

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    parent_id INTEGER,
    CONSTRAINT categories_parent_id_fkey FOREIGN KEY (parent_id)
        REFERENCES categories (id) ON DELETE SET NULL
);
