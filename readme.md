# Backend - API CRUD com PHP e Docker

Esta é uma API RESTful desenvolvida em PHP para gerenciamento de usuários e produtos. A aplicação utiliza JWT para autenticação e é executada em um ambiente Docker, incluindo MySQL e Redis.

## **Pré-requisitos**

- Docker e Docker Compose instalados ([Instalação Docker](https://docs.docker.com/get-docker/))
- Git instalado

## **Passo a passo para instalação**

### **1. Clonar o repositório**

```sh
git clone https://github.com/christiantowers/backend-api.git
cd backend-api
```

### **2. Criar um arquivo de ambiente**

Crie ou ajuste o arquivo `.env` com as configurações (conforme seus enderecos locais):

```
applications/crud/.env
```

```
CI_ENVIRONMENT = development

app.CORS.allowOrigins = *
app.CORS.allowedOrigins = *
app.CORS.allowedMethods = GET, POST, OPTIONS, PUT, DELETE
app.CORS.allowedHeaders = Content-Type, Authorization, X-Requested-With

database.default.hostname = 127.0.0.1
database.default.database = crudapi
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
database.default.socket = /Applications/XAMPP/xamppfiles/var/mysql/mysql.sock

sessionDriver = RedisHandler
sessionSavePath = tcp://redis:6379
```

Edite o arquivo conforme necessário.

### **3. Subir os containers com Docker Compose**

```sh
script-start-docker-compose.sh
```

ou

```sh
docker-compose up -d
```

Isso irá iniciar:

- MySQL
- Redis
- PHP

### **4. Criar as tabelas no banco**

Execute as migrations (se houver):
Exe

```sh
docker exec -it {containter_name} php spark migrate
```

ou crie as tabelas direto no mySql com esse DUMP

```
CREATE TABLE crudapi.users (
	id int(10) NOT NULL auto_increment,
	name varchar(255) NOT NULL,
	email varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
)

-- indices para otimização
ALTER TABLE crudapi.users ADD UNIQUE INDEX idx_email (email);
ALTER TABLE crudapi.users ADD INDEX idx_name (name);
ALTER TABLE crudapi.users ADD INDEX idx_created_at (created_at);


INSERT INTO crudapi.users(id, name, email, password, created_at) VALUES (1, 'christian', 'christian@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2025-02-06 11:57:19');
INSERT INTO crudapi.users(id, name, email, password, created_at) VALUES (2, 'root', 'root@mail.com', '$2y$10$Pc/pIpUr9cM6td6VuI5F2OcozfeNYZXe5HHJIVFSdfcCmM8NLkyE.', '2025-02-06 14:40:41');


CREATE TABLE crudapi.products (
	id int(10) NOT NULL auto_increment,
	name varchar(255) NOT NULL,
	description text(65535),
	price decimal(10,2) NOT NULL,
	stock_quantity int(10) DEFAULT 0 NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
)

-- indices para otimização
ALTER TABLE crudapi.products ADD INDEX idx_name (name);
ALTER TABLE crudapi.products ADD INDEX idx_price (price);
ALTER TABLE crudapi.products ADD INDEX idx_stock_quantity (stock_quantity);
ALTER TABLE crudapi.products ADD INDEX idx_created_at (created_at);
ALTER TABLE crudapi.products ADD INDEX idx_updated_at (updated_at);


INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (1, 'Smartphone X', 'Smartphone com tela OLED de 6.5 polegadas', 2999.99, 50, '2025-02-07 20:41:58', '2025-02-07 20:41:58');
INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (2, 'Produto XYZ', 'Descrição detalhada do produto.', 99.99, 100, '2025-02-07 21:22:14', '2025-02-08 02:55:53');
INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (3, 'Monitor LG', 'Monitor 34''''4K', 1500.00, 2, '2025-02-07 23:50:53', '2025-02-07 23:50:53');
INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (5, 'Produto 1', '11 11 11 ', 111.00, 11, '2025-02-08 03:09:17', '2025-02-08 03:09:17');
INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (6, 'Produto 2', '22 22 222', 22.00, 2, '2025-02-08 03:09:34', '2025-02-08 03:09:34');
INSERT INTO crudapi.products(id, name, description, price, stock_quantity, created_at, updated_at) VALUES (7, 'Produto 3', '33 333', 333.00, 33, '2025-02-08 03:09:53', '2025-02-08 03:09:53');

```

### **5. Testar a API**

A API estará disponível em `http://localhost:8080`

## **Persistência de dados no MySQL**

Para garantir que os dados não sejam apagados ao destruir o container, um volume foi criado no `docker-compose.yml`. O MySQL armazena os dados em `./mysql_data`, então os dados são mantidos mesmo após a destruição da imagem.

Se precisar limpar os dados:

```sh
rm -rf mysql_data
```
