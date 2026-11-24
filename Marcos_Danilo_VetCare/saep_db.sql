-- ============================================================
-- SAEP — Script de criação e população do banco de dados
-- Sistema: VetCare (Clínica Veterinária)
-- Estudante: Marcos Danilo
-- Mínimo de 3 registros por tabela
-- ============================================================

CREATE DATABASE IF NOT EXISTS vetcare
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vetcare;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS agendamentos;
DROP TABLE IF EXISTS pets;
DROP TABLE IF EXISTS tutores;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  perfil VARCHAR(30) NOT NULL DEFAULT 'Atendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Senha de teste: 123456
INSERT INTO usuarios (nome, email, senha, perfil) VALUES
('Marcos Danilo Admin', 'admin@vetcare.local', '$2y$10$3ESmoISxMZq4b0.w0Jt3zeBPrsDKiADNfBZ8ZvOWQI2GVf/eL7FUG', 'Administrador'),
('Ana Atendente', 'atendente@vetcare.local', '$2y$10$3ESmoISxMZq4b0.w0Jt3zeBPrsDKiADNfBZ8ZvOWQI2GVf/eL7FUG', 'Atendente'),
('Dr. Paulo Veterinário', 'vet@vetcare.local', '$2y$10$3ESmoISxMZq4b0.w0Jt3zeBPrsDKiADNfBZ8ZvOWQI2GVf/eL7FUG', 'Veterinário');

CREATE TABLE tutores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(255) NOT NULL COMMENT 'CPF criptografado AES-256-CBC',
  telefone VARCHAR(20) NULL,
  email VARCHAR(120) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CPFs: 111.222.333-44 | 555.666.777-88 | 999.888.777-66
INSERT INTO tutores (nome, cpf, telefone, email) VALUES
('Luciana Ferreira', '91gRPxsyZRfVX+uRLf2tIw==', '(81) 98801-1122', 'luciana.ferreira@email.com'),
('Bruno Santos', 'AcvaJwhYUawr5DAJg1YPWw==', '(81) 98702-3344', 'bruno.santos@email.com'),
('Camila Rodrigues', 'd5+yfoPotpE09tiqS6WvBg==', '(81) 98603-5566', 'camila.rodrigues@email.com');

CREATE TABLE pets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tutor_id INT NOT NULL,
  nome VARCHAR(80) NOT NULL,
  especie VARCHAR(40) NOT NULL,
  raca VARCHAR(60) NULL,
  idade INT NULL,
  CONSTRAINT fk_pets_tutor
    FOREIGN KEY (tutor_id) REFERENCES tutores(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO pets (tutor_id, nome, especie, raca, idade) VALUES
(1, 'Thor', 'Cão', 'Labrador', 4),
(1, 'Mimi', 'Gato', 'Siamês', 2),
(2, 'Rex', 'Cão', 'Bulldog', 5),
(3, 'Luna', 'Gato', 'Persa', 3);

CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tutor_id INT NOT NULL,
  pet_id INT NOT NULL,
  data_agendamento DATETIME NOT NULL,
  servico VARCHAR(150) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Agendado',
  observacoes TEXT NULL,
  CONSTRAINT fk_agendamentos_tutor
    FOREIGN KEY (tutor_id) REFERENCES tutores(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_agendamentos_pet
    FOREIGN KEY (pet_id) REFERENCES pets(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO agendamentos (tutor_id, pet_id, data_agendamento, servico, status, observacoes) VALUES
(1, 1, '2026-12-08 09:00:00', 'Consulta de rotina', 'Agendado', 'Vacina anual'),
(2, 3, '2026-12-09 10:30:00', 'Vermifugação', 'Agendado', NULL),
(3, 4, '2026-12-10 14:00:00', 'Cirurgia de castração', 'Em andamento', 'Jejum de 8 horas'),
(1, 2, '2026-12-11 11:15:00', 'Retorno pós-operatório', 'Agendado', 'Avaliar pontos');

SELECT 'Banco vetcare criado e populado.' AS mensagem;
SELECT 'usuarios' AS tabela, COUNT(*) AS qtd FROM usuarios
UNION ALL SELECT 'tutores', COUNT(*) FROM tutores
UNION ALL SELECT 'pets', COUNT(*) FROM pets
UNION ALL SELECT 'agendamentos', COUNT(*) FROM agendamentos;
