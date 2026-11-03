# Modelagem Física do Microblog

```sql
CREATE DATABASE microblog_2025 CHARACTER SET utf8mb4;
```

```sql
CREATE TABLE usuarios(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','editor') NOT NULL
);
```


```sql
CREATE TABLE categorias(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
);
```

```sql
CREATE TABLE noticias (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    titulo VARCHAR(150) NOT NULL,
    texto TEXT NOT NULL,
    resumo VARCHAR(500) NOT NULL,
    imagem VARCHAR(60) NOT NULL,
    destaque ENUM('sim', 'nao') NOT NULL,
    usuario_id INT NULL,
    categoria_id INT NULL,
    CONSTRAINT fk_noticias_usuarios
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL ON UPDATE NO ACTION,
    
    CONSTRAINT fk_noticias_categorias
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON DELETE SET NULL ON UPDATE NO ACTION
);
```













