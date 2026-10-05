CREATE DATABASE IF NOT EXISTS gerenciamento_db;
USE gerenciamento_db;

CREATE TABLE usuarios(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE servicos (
	id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status_do_servico ENUM('Pendente', 'Finalizado') DEFAULT 'Pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_finalizado TIMESTAMP NULL DEFAULT NULL,
    valor_comissao DECIMAL(10,2) NULL DEFAULT NULL,
    usuario_id INT NOT NULL,
    
	CONSTRAINT fk_servicos_usuarios
		FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

ALTER TABLE servicos ADD FOREIGN KEY (usuario_id) REFERENCES usuarioS(id);

INSERT INTO usuarios (nome, email, senha) 
VALUES ('Administrador', 'admin@jminformatica.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1H1T./k13r1XjI8.uV8mP5S6qC2y3qW');


