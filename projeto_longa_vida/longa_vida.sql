CREATE DATABASE longa_vida;
USE longa_vida;

CREATE TABLE plano (
    id_plano INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(255) NOT NULL,   -- Campo para o número do plano
    descricao TEXT NOT NULL,        -- Campo para a descrição do plano
    valor DECIMAL(10, 2) NOT NULL   -- Campo para o valor do plano
);

CREATE TABLE associado (
    id_associado INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL, -- Sigla do estado (ex: SP, RJ)
    cep VARCHAR(10) NOT NULL,   -- Formato: XXXXX-XXX
    email VARCHAR(255) UNIQUE NOT NULL,
    plano_id INT,
    FOREIGN KEY (plano_id) REFERENCES plano(id_plano)
);
