-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 02-Out-2020 às 09:47
-- Versão do servidor: 5.6.41-84.1
-- versão do PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gerenteh_casadosindico`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado`
--

CREATE TABLE `afiliado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `razao_social` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_fantasia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cartao_cnpj` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_estadual` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_municipal` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rumo_atividade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_funcionarios` int(11) DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente' COMMENT 'pendente/ativo/inativo',
  `usuario_app_id` bigint(20) UNSIGNED NOT NULL,
  `data_contrato` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `afiliado`
--

INSERT INTO `afiliado` (`id`, `razao_social`, `nome_fantasia`, `telefone`, `email`, `cnpj`, `cartao_cnpj`, `inscricao_estadual`, `inscricao_municipal`, `cep`, `estado`, `cidade`, `bairro`, `rua`, `numero`, `complemento`, `rumo_atividade`, `numero_funcionarios`, `logo`, `status`, `usuario_app_id`, `data_contrato`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(2, 'Desentupidora desentupiu', 'Desentupidora desentupiu', '+5548999636', NULL, 'asd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Desentupidora', NULL, NULL, 'pendente', 2, NULL, '2020-10-01 06:37:04', '2020-10-01 13:50:30', NULL),
(3, 'Sem nome', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 'pendente', 4, NULL, '2020-10-01 22:17:01', '2020-10-01 22:17:01', NULL),
(4, 'Empresa 1 LTDA', 'Empresa 1 LTDA', '+55999636294', NULL, '555.66.666-55/5555', NULL, '321654', '123456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Limpeza pesada', 0, NULL, 'pendente', 5, NULL, '2020-10-01 22:17:43', '2020-10-01 22:17:43', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_categoria`
--

CREATE TABLE `afiliado_categoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_orcamento_interesse`
--

CREATE TABLE `afiliado_orcamento_interesse` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interessado` tinyint(1) DEFAULT '1',
  `nao_interessante` tinyint(1) DEFAULT '-1',
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `orcamento_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `descartado` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `afiliado_orcamento_interesse`
--

INSERT INTO `afiliado_orcamento_interesse` (`id`, `interessado`, `nao_interessante`, `afiliado_id`, `orcamento_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `descartado`) VALUES
(1, 1, 0, 2, 2, '2020-10-01 06:37:14', '2020-10-01 19:29:19', NULL, 0),
(2, 0, -1, 4, 3, '2020-10-02 09:13:08', '2020-10-02 09:13:08', NULL, 1),
(3, 0, -1, 4, 3, '2020-10-02 09:25:41', '2020-10-02 09:25:41', NULL, 1),
(4, 0, -1, 4, 35, '2020-10-02 09:27:08', '2020-10-02 09:27:08', NULL, 1),
(5, 1, -1, 4, 3, '2020-10-02 09:29:22', '2020-10-02 09:29:22', NULL, 0),
(6, 0, -1, 4, 4, '2020-10-02 09:35:10', '2020-10-02 09:35:10', NULL, 1),
(7, 0, -1, 4, 5, '2020-10-02 09:36:15', '2020-10-02 09:36:15', NULL, 1),
(8, 1, -1, 4, 6, '2020-10-02 09:37:31', '2020-10-02 09:37:31', NULL, 0),
(9, 1, -1, 4, 6, '2020-10-02 09:37:36', '2020-10-02 09:37:36', NULL, 0),
(10, 1, -1, 4, 6, '2020-10-02 09:37:38', '2020-10-02 09:37:38', NULL, 0),
(11, 0, -1, 4, 7, '2020-10-02 09:37:48', '2020-10-02 09:37:48', NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_regiao`
--

CREATE TABLE `afiliado_regiao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `regiao_id` bigint(20) UNSIGNED NOT NULL,
  `plano_assinatura_afiliado_regiao_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_pagamento_plano` timestamp NULL DEFAULT NULL,
  `data_expiracao_plano` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_assinatura` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `afiliado_regiao`
--

INSERT INTO `afiliado_regiao` (`id`, `afiliado_id`, `regiao_id`, `plano_assinatura_afiliado_regiao_id`, `data_pagamento_plano`, `data_expiracao_plano`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `data_assinatura`) VALUES
(1, 4, 1, NULL, NULL, NULL, '2020-10-02 06:59:07', '2020-10-02 06:59:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairro`
--

CREATE TABLE `bairro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chave` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade_id` bigint(20) UNSIGNED NOT NULL,
  `regiao_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `bairro`
--

INSERT INTO `bairro` (`id`, `nome`, `chave`, `cidade_id`, `regiao_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Centro', 'centro', 1, 1, '2020-09-30 19:39:10', '2020-10-01 03:29:12', NULL),
(2, 'Córrego grande', 'corregogrande', 1, 1, '2020-09-30 19:39:10', '2020-10-01 03:29:21', NULL),
(3, 'São Sebastião', 'saosebastiao', 2, 1, '2020-10-01 04:51:14', '2020-10-01 04:51:14', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog`
--

CREATE TABLE `blog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publicado' COMMENT 'publicado/rascunho',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cartao_cnpj`
--

CREATE TABLE `cartao_cnpj` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente' COMMENT 'pendente/aceito/recusado',
  `arquivo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `chave_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `descricao`, `chave_url`, `imagem`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(80, 'Análise, diagnóstico de Água', 'Análise, diagnóstico de Água', '', '', '2020-09-29 03:38:33', '2020-09-29 03:38:33', NULL),
(81, 'Arquitetos', 'Arquitetos', '', '', '2020-09-29 03:39:02', '2020-09-29 03:39:02', NULL),
(82, 'Dedetização e controle de pragas', 'Dedetização e controle de pragas', '', '', '2020-09-29 03:39:34', '2020-09-29 03:39:34', NULL),
(83, 'Gesso', 'Gesso', '', '', '2020-09-29 03:39:34', '2020-09-29 03:39:34', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cidade`
--

CREATE TABLE `cidade` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chave` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cidade`
--

INSERT INTO `cidade` (`id`, `nome`, `chave`, `estado_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Florianópolis', 'florianopolis', 1, '2020-08-25 04:49:46', '2020-10-01 03:32:25', NULL),
(2, 'Palhoça', 'palhoca', 1, '2020-09-29 03:40:26', '2020-10-01 03:32:32', NULL),
(4, 'São José', 'saojose', 1, '2020-09-29 03:40:42', '2020-10-01 03:32:36', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `condominio`
--

CREATE TABLE `condominio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bairro` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endereco` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sindico_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `bairro_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `condominio`
--

INSERT INTO `condominio` (`id`, `nome`, `cep`, `bairro`, `endereco`, `numero`, `complemento`, `sindico_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `bairro_id`, `estado`, `cidade`) VALUES
(1, 'Turin', '88136-000', 'São Sebastião', 'Thomas domingos da silveira', '3818', '', 1, '2020-09-30 15:16:31', '2020-09-30 19:39:30', NULL, 1, 'Santa Catarina', 'Palhoça'),
(2, 'Turin', '88136-000', 'São Sebastião', 'Thomas domingos da silveira', '3818', '', 2, '2020-09-30 15:16:36', '2020-09-30 19:39:32', NULL, 1, 'Santa Catarina', 'Palhoça'),
(3, 'dddd', '2323', '23', '23', '23', '', 2, '2020-09-30 15:17:51', '2020-09-30 19:39:34', NULL, 2, '23', '23'),
(4, 'sdf', 'sdf', 'sdf', 'sdfs', 'df', '', 2, '2020-09-30 15:17:58', '2020-09-30 19:39:38', NULL, 2, 'sdf', 'sdf'),
(5, 'Residencial e comercial Central Park', '88136-000', 'Centro', 'Rua 12', '1234', '', 1, '2020-09-30 18:03:53', '2020-09-30 19:39:42', NULL, 2, 'Santa Catarina', 'Braço do Norte'),
(6, 'Residencial teste', '99898', 'Centro', 'Rua 12', '7', '', 1, '2020-10-01 03:29:57', '2020-10-01 03:29:57', NULL, 1, 'Santa Catarina', 'Florianópolis'),
(7, 'Residencial teste', '99898', 'Centro', 'Rua 12', '7', '', 1, '2020-10-01 03:36:43', '2020-10-01 03:36:43', NULL, 1, 'Santa Catarina', 'florianopolis'),
(8, 'Residencial teste', '99898', 'Centro', 'Rua 12', '7', '', 1, '2020-10-01 03:36:49', '2020-10-01 03:36:49', NULL, NULL, 'Santa Catarina', 'florianopdolis'),
(9, 'Residencial TURINO', '88136000', 'Centro', 'Rua Tomáz Domingos da Silveira', '3818', '', 1, '2020-10-01 04:47:03', '2020-10-01 04:47:03', NULL, NULL, 'SC', 'Palhoça'),
(10, 'dfd', '88040200', 'Centro', 'Rua Vereador Frederico Veras', '2500', '', 1, '2020-10-01 04:48:10', '2020-10-01 04:48:10', NULL, 1, 'SC', 'Florianópolis'),
(11, 'sdfsdf', '88136000', 'São Sebastião', 'Rua Tomáz Domingos da Silveira', '222', '', 1, '2020-10-01 04:49:01', '2020-10-01 04:49:01', NULL, NULL, 'SC', 'Palhoça'),
(12, 'Condominio NOVO', '88136000', 'São Sebastião', 'Rua Tomáz Domingos da Silveira', '1566', '', 1, '2020-10-01 04:51:39', '2020-10-01 04:51:39', NULL, NULL, 'SC', 'Palhoça'),
(13, 'Residencial teste', '99898', 'São Sebastião', 'Rua 12', '7', '', 1, '2020-10-01 04:54:22', '2020-10-01 04:54:22', NULL, NULL, 'Santa Catarina', 'Palhoça'),
(14, 'Residencial teste', '99898', 'São Sebastião', 'Rua 12', '7', '', 1, '2020-10-01 04:56:57', '2020-10-01 04:56:57', NULL, 3, 'Santa Catarina', 'Palhoça'),
(15, 'Agora vai', '88136000', 'São Sebastião', 'Rua Tomáz Domingos da Silveira', '3818', '', 1, '2020-10-01 04:57:50', '2020-10-01 04:57:50', NULL, 3, 'SC', 'Palhoça');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente' COMMENT 'pendente/aceito/recusado',
  `arquivo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato_social`
--

CREATE TABLE `contrato_social` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente' COMMENT 'pendente/aceito/recusado',
  `arquivo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `device`
--

CREATE TABLE `device` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_unique_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `device`
--

INSERT INTO `device` (`id`, `ip`, `device_unique_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, '10.0.0.106', 'ddd', '2020-09-29 21:51:13', '2020-09-29 21:51:13', NULL),
(2, 'ddd', 'ddd', '2020-09-29 21:51:38', '2020-09-29 21:51:38', NULL),
(3, '45.160.89.229', '12fdfd!43&dddDD34rdf', '2020-09-30 14:07:13', '2020-10-02 11:23:59', NULL),
(4, '45.160.89.229', 'android', '2020-09-30 14:29:33', '2020-10-02 11:59:12', NULL),
(5, '45.160.89.229', '222', '2020-10-02 12:07:12', '2020-10-02 12:07:12', NULL),
(6, '45.160.89.229', '0.68073932610123', '2020-10-02 12:07:26', '2020-10-02 12:07:26', NULL),
(7, '45.160.89.229', '0.95307393825213', '2020-10-02 12:22:04', '2020-10-02 12:22:04', NULL),
(8, '189.4.76.177', '0.24548573720923', '2020-10-02 12:26:30', '2020-10-02 12:26:30', NULL),
(9, '45.160.89.229', '0.36757205919646', '2020-10-02 12:26:40', '2020-10-02 12:26:40', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `estado`
--

CREATE TABLE `estado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chave` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `estado`
--

INSERT INTO `estado` (`id`, `nome`, `uf`, `chave`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Santa Catarina', 'sc', 'sc', '2020-08-25 04:49:34', '2020-09-29 03:39:51', NULL),
(2, 'Paraná', 'sc', 'sc', '2020-08-25 04:49:34', '2020-09-29 03:39:51', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado`
--

CREATE TABLE `franqueado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senha` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_estadual` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_municipal` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf_responsavel` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rg_responsavel` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profissao_responsavel` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_responsavel` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rua` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_asaas_producao` text COLLATE utf8mb4_unicode_ci,
  `token_asaas_debug` text COLLATE utf8mb4_unicode_ci,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `franqueado`
--

INSERT INTO `franqueado` (`id`, `nome`, `email`, `senha`, `cnpj`, `inscricao_estadual`, `inscricao_municipal`, `cpf_responsavel`, `rg_responsavel`, `profissao_responsavel`, `telefone_responsavel`, `cep`, `estado`, `cidade`, `bairro`, `rua`, `token_asaas_producao`, `token_asaas_debug`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Renato Franqueado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-02 06:43:41', '2020-10-02 06:43:41', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado_regiao`
--

CREATE TABLE `franqueado_regiao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ativo' COMMENT 'inativo',
  `franqueado_id` bigint(20) UNSIGNED NOT NULL,
  `regiao_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_sistema_admin_id` bigint(20) UNSIGNED NOT NULL,
  `data_inicio_atividade` date DEFAULT NULL,
  `data_fim_atividade` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `franqueado_regiao`
--

INSERT INTO `franqueado_regiao` (`id`, `status`, `franqueado_id`, `regiao_id`, `usuario_sistema_admin_id`, `data_inicio_atividade`, `data_fim_atividade`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(2, 'ativo', 1, 1, 1, NULL, NULL, '2020-10-02 06:44:47', '2020-10-02 06:44:47', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado_regiao_plano_disponibilizado`
--

CREATE TABLE `franqueado_regiao_plano_disponibilizado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `franqueado_regiao_id` bigint(20) UNSIGNED NOT NULL,
  `plano_disponivel_franqueado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem_orcamento`
--

CREATE TABLE `imagem_orcamento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `caminho_imagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `orcamento_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logerroemail`
--

CREATE TABLE `logerroemail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dataErro` datetime DEFAULT NULL,
  `mensagemErro` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logsendinblue`
--

CREATE TABLE `logsendinblue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `idOrcamento` int(11) DEFAULT NULL,
  `retorno` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_26_170513_create_table_usuario_app', 1),
(5, '2020_08_26_170520_create_table__afiliado', 1),
(6, '2020_08_26_170526_create_table__blog', 1),
(7, '2020_08_26_170531_create_table__categoria', 1),
(8, '2020_08_26_170536_create_table__log_erro_email', 1),
(9, '2020_08_26_170542_create_table__log_sendin_blue', 1),
(10, '2020_08_26_170551_create_table__parceiro', 1),
(11, '2020_08_26_170602_create_table__usuario_sistema_admin', 1),
(12, '2020_08_26_170608_create_table__regiao', 1),
(13, '2020_08_26_170617_create_table__plano_disponivel_franqueado', 1),
(14, '2020_08_26_170627_create_table__responsavel_afiliado', 1),
(15, '2020_08_26_170635_create_table__sessao_usuario', 1),
(16, '2020_08_26_170641_create_table__vistoriador', 1),
(17, '2020_08_26_170650_create_table__sindico', 1),
(18, '2020_08_26_170657_create_table__condominio', 1),
(19, '2020_08_26_170703_create_table__orcamento', 1),
(20, '2020_08_26_170712_create_table__vistoria', 1),
(21, '2020_08_26_170719_create_table__vistoria_imagem', 1),
(22, '2020_08_26_170731_create_table__afiliado_categoria', 1),
(23, '2020_08_26_170738_create_table__franqueado', 1),
(24, '2020_08_26_170746_create_table__franqueado_regiao', 1),
(25, '2020_08_26_170752_create_table__franqueado_regiao_plano_disponibilizado', 1),
(26, '2020_08_26_170815_create_table__plano_assinatura_afiliado_regiao', 1),
(27, '2020_08_26_170822_create_table__afiliado_regiao', 1),
(28, '2020_08_26_170828_create_table__imagem_orcamento', 1),
(29, '2020_08_26_170834_create_table__contrato_social', 1),
(30, '2020_08_26_170841_create_table__cartao_cnpj', 1),
(31, '2020_08_26_170911_create_table__contrato', 1),
(32, '2020_08_26_170917_create_table__estado', 1),
(33, '2020_08_26_170921_create_table__cidade', 1),
(34, '2020_08_26_170934_create_table__bairro', 1),
(35, '2020_08_26_170938_create_table__rua', 1),
(36, '2020_08_26_170959_create_table__afiliado_orcamento_interesse', 1),
(37, '2020_08_27_150550_device', 1),
(38, '2020_09_18_160002_add_franqueado_table__vistoriador', 1),
(39, '2020_09_18_160008_add_franqueado_table__sindico', 1),
(40, '2020_09_28_175308_remove_regiao_condominio', 1),
(41, '2020_09_28_175726_add_regiao_orcamento', 1),
(42, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(43, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(44, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(45, '2016_06_01_000004_create_oauth_clients_table', 2),
(46, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('dc15f79bbe4c1362fda3bdb3cfdb1cd70c4da63c5f3f7c1bb8039ba725529993d9f2caa3c3a26ae4', 2, 1, '481b54e7ccd6abca0f1e030d858d668d', '[]', 0, '2020-09-30 17:53:09', '2020-09-30 17:53:09', '2021-09-30 14:53:09'),
('0c7cc2eb4a21744012225e2c0e2ccc0c2f3e899dfa8d46957de8c5c187f693033ba53e099b4524e5', 4, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:02:48', '2020-10-02 15:02:48', '2021-10-02 12:02:48'),
('1a6b67521efeb77a7705777f6ffa3a9eff6085c207532b410f3dd3ea346d952566a86619e210c9a0', 3, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:05:50', '2020-10-02 15:05:50', '2021-10-02 12:05:50'),
('bb47340a5b42ee1a887d00f972e0564ff0d22c8ffa1d4c283a3772360e03a28175773a7653027b6f', 6, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:07:26', '2020-10-02 15:07:26', '2021-10-02 12:07:26'),
('f4d40da3e3875c4b28c798e3ddc8878ba09ed15bc74265f10fbd1c3964d40eb1d1cd66e9162ea6d8', 7, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:22:05', '2020-10-02 15:22:05', '2021-10-02 12:22:05'),
('bdd829029a9a11d2303101f2cd6ba968ceb7dd6bc74eedf0dbd971539cf09db4f758fac66ef49f8c', 8, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:26:30', '2020-10-02 15:26:30', '2021-10-02 12:26:30'),
('fa4f7c10c346d19decb3b298740970e15cfec5c8455881bc5af80d83f42fac767ea088d246977643', 9, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:26:40', '2020-10-02 15:26:40', '2021-10-02 12:26:40'),
('71ab5c5d1c02d5e01ada59ea665a37575d2dbf727dd4f88abe9eb715c5b0cd632708d9506fb39db7', 5, 1, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-10-02 15:31:12', '2020-10-02 15:31:12', '2021-10-02 12:31:12'),
('65de9ebbdbe8ef692c1aef8e068fbc6ba674fa9e7d4018b635a629065c35aa4ba3cd36c16b512928', 6, 1, '618765a3a067b3f0abdd821d49b14d34', '[]', 0, '2020-10-02 15:34:28', '2020-10-02 15:34:28', '2021-10-02 12:34:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'y', 'rHOBfb1Fb8ALCZoS9lrSrUQNSqqF1FK906U6Gp85', NULL, 'http://localhost', 1, 0, 0, '2020-09-30 17:02:30', '2020-09-30 17:02:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-09-30 17:02:30', '2020-09-30 17:02:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `status_sindico` tinyint(1) NOT NULL DEFAULT '1',
  `status_afiliado` tinyint(1) DEFAULT NULL,
  `condominio_id` bigint(20) UNSIGNED NOT NULL,
  `afiliado_id` bigint(20) UNSIGNED DEFAULT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `regiao_id` bigint(20) UNSIGNED DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `orcamento`
--

INSERT INTO `orcamento` (`id`, `nome`, `descricao`, `status`, `status_sindico`, `status_afiliado`, `condominio_id`, `afiliado_id`, `categoria_id`, `regiao_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(2, 'Pintura', 'Pintura de parede', 4, 1, NULL, 5, 4, 80, 1, '2020-09-30 19:27:11', '2020-10-02 09:40:51', NULL),
(3, 'Pintura', 'Pintura de parede', 1, 1, NULL, 5, NULL, 80, 1, '2020-09-30 19:27:20', '2020-09-30 19:27:20', NULL),
(4, 'Pintura', 'Pintura de parede', 1, 1, NULL, 5, NULL, 80, 1, '2020-09-30 19:27:28', '2020-09-30 19:27:28', NULL),
(5, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:27:30', '2020-09-30 23:00:07', NULL),
(6, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:27:32', '2020-09-30 23:00:07', NULL),
(7, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, NULL, '2020-09-30 19:27:40', '2020-09-30 23:00:07', NULL),
(8, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:28:08', '2020-09-30 23:00:07', NULL),
(9, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:28:17', '2020-09-30 23:00:07', NULL),
(10, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:28:19', '2020-09-30 23:00:07', NULL),
(11, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:48:10', '2020-09-30 23:00:07', NULL),
(12, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:48:16', '2020-09-30 23:00:07', NULL),
(13, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:54:36', '2020-09-30 23:00:07', NULL),
(14, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:54:44', '2020-09-30 23:00:07', NULL),
(15, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:09', '2020-09-30 23:00:07', NULL),
(16, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:18', '2020-09-30 23:00:07', NULL),
(17, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:25', '2020-09-30 23:00:07', NULL),
(18, NULL, NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:32', '2020-09-30 23:00:07', NULL),
(19, NULL, NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:39', '2020-09-30 23:00:07', NULL),
(20, 'Pintura', 'Pintura de parede', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:55:58', '2020-09-30 23:00:07', NULL),
(21, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:56:05', '2020-09-30 23:00:07', NULL),
(22, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:56:56', '2020-09-30 23:00:07', NULL),
(23, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:56:57', '2020-09-30 23:00:07', NULL),
(24, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:57:21', '2020-09-30 23:00:07', NULL),
(25, 'Pintura', NULL, 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:57:34', '2020-09-30 23:00:07', NULL),
(26, 'Pintura', 'sssss', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 19:57:40', '2020-09-30 23:00:07', NULL),
(28, 'Pintura', 'sss', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:07:27', '2020-09-30 23:00:07', NULL),
(29, 'sdfsdf', 'sdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:12:33', '2020-09-30 23:00:07', NULL),
(30, 'sdfsdf', 'sdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:12:44', '2020-09-30 23:00:07', NULL),
(31, 'sdfsdf', 'sdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:13:10', '2020-09-30 23:00:07', NULL),
(32, 'sdfsdf', 'sdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:14:35', '2020-09-30 23:00:07', NULL),
(33, 'dfs', 'sdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:15:40', '2020-09-30 23:00:07', NULL),
(34, 'dafds', 'fsdfsdf', 1, 1, NULL, 4, NULL, 80, 1, '2020-09-30 20:16:29', '2020-09-30 23:00:07', NULL),
(35, 'noooo', 'hhhhhh', 1, 1, NULL, 5, NULL, 80, 1, '2020-10-01 06:59:19', '2020-10-01 06:59:19', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `parceiros`
--

CREATE TABLE `parceiros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_responsavel` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente' COMMENT 'ativo/pendente/inativo',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_assinatura_afiliado_regiao`
--

CREATE TABLE `plano_assinatura_afiliado_regiao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statusPlano` int(11) NOT NULL,
  `quantidade_meses_vigencia` int(11) NOT NULL,
  `dias_trial` int(11) NOT NULL DEFAULT '0',
  `franqueado_regiao_plano_disponibilizado_id` bigint(20) UNSIGNED NOT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `data_cancelamento` timestamp NULL DEFAULT NULL,
  `data_expiracao` date DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_disponivel_franqueado`
--

CREATE TABLE `plano_disponivel_franqueado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statusPlano` int(11) NOT NULL,
  `quantidade_meses_vigencia` int(11) NOT NULL,
  `dias_trial` int(11) NOT NULL DEFAULT '0',
  `usuario_sistema_admin_id` bigint(20) UNSIGNED NOT NULL,
  `regiao_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `regiao`
--

CREATE TABLE `regiao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `regiao`
--

INSERT INTO `regiao` (`id`, `nome`, `descricao`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Grande Florianópolis', 'Grande Florianópolis', '2020-09-30 19:26:53', '2020-10-02 03:14:26', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `responsavel_afiliado`
--

CREATE TABLE `responsavel_afiliado` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_documento` int(11) DEFAULT NULL,
  `CPF` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `afiliado_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rua`
--

CREATE TABLE `rua` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chave` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bairro_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessaousuario`
--

CREATE TABLE `sessaousuario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `inicioSessao` datetime DEFAULT NULL,
  `fimSessao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sindico`
--

CREATE TABLE `sindico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CPF` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_app_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `franqueado_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sindico`
--

INSERT INTO `sindico` (`id`, `nome`, `CPF`, `numero_documento`, `telefone`, `usuario_app_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `franqueado_id`) VALUES
(1, 'Renato Paranaguá da Silva', '111.111.111-11', '666666', '48 99963-6294', 1, '2020-09-30 14:49:36', '2020-09-30 18:07:45', NULL, NULL),
(2, 'Sem nome', NULL, NULL, NULL, 2, '2020-09-30 14:53:10', '2020-09-30 14:53:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_app`
--

CREATE TABLE `usuario_app` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sindico/prestador/vistoriador',
  `token_notification` text COLLATE utf8mb4_unicode_ci,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuario_app`
--

INSERT INTO `usuario_app` (`id`, `email`, `senha`, `tipo`, `token_notification`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'renatosindico@email.com', '$2y$10$EswV2cKZpf7.LCRlchcZyukWy2KYfzEnMuiTRnBBfPOMpswOg83am', 'sindico', NULL, '2020-09-30 14:30:16', '2020-09-30 14:30:16', NULL),
(2, 'renatosind@email.com', '$2y$10$vSToIfxaYwXLj/PKtS4R6.aAQsQATwDFYB.aHvRLkPr8oWyMJgHXu', 'sindico', NULL, '2020-09-30 14:53:09', '2020-09-30 14:53:09', NULL),
(3, 'renatoprestador@email.com', '$2y$10$Ary8Wn7MeFoPFkGE9QxspOFW7BR64Kw/4mRSFW7WPTAbJXTB/yraK', 'afiliado', NULL, '2020-10-01 22:05:41', '2020-10-01 22:05:41', NULL),
(4, 'renatoprestador2@email.com', '$2y$10$OLnNTdhilc.wFPeOQC6tluJr80RZsKh4xVHiGwCKzHVt4SaQb1ISu', 'afiliado', NULL, '2020-10-01 22:10:29', '2020-10-01 22:10:29', NULL),
(5, 'prestador1@email.com', '$2y$10$loO4uD0rw1ArfAx.n4ZZn.hfbVuvgX7mYs2zRt3CHmIS8q../ZtZ2', 'afiliado', NULL, '2020-10-01 22:17:42', '2020-10-01 22:17:42', NULL),
(6, 'sfgf@fgdfg.dff', '$2y$10$oHxKC8R6Lb8qbvlZzKlkHOQppkl.kRVWDQGGJmG2diJOgedXpkoNe', 'sindico', NULL, '2020-10-02 12:34:28', '2020-10-02 12:34:28', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_sistema_admin`
--

CREATE TABLE `usuario_sistema_admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `tipo` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'superadmin/admin',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuario_sistema_admin`
--

INSERT INTO `usuario_sistema_admin` (`id`, `nome`, `email`, `senha`, `status`, `tipo`, `data_cadastro`, `data_atualizacao`, `deleted_at`) VALUES
(1, 'Admin', 'admin@email.com', '123', 0, 'superadmin', '2020-10-02 06:44:30', '2020-10-02 06:44:30', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoria`
--

CREATE TABLE `vistoria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descricao` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_vistoria` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_checkin` timestamp NULL DEFAULT NULL,
  `latitude_checkin` double DEFAULT NULL,
  `longitude_checkin` double DEFAULT NULL,
  `data_checkout` timestamp NULL DEFAULT NULL,
  `latitude_checkout` double DEFAULT NULL,
  `longitude_checkout` double DEFAULT NULL,
  `vistoriador_id` bigint(20) UNSIGNED NOT NULL,
  `orcamento_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoriador`
--

CREATE TABLE `vistoriador` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_app_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `franqueado_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoria_imagem`
--

CREATE TABLE `vistoria_imagem` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `caminho_imagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `vistoria_id` bigint(20) UNSIGNED NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `afiliado`
--
ALTER TABLE `afiliado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `afiliado_usuario_app_id_foreign` (`usuario_app_id`);

--
-- Índices para tabela `afiliado_categoria`
--
ALTER TABLE `afiliado_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `afiliado_categoria_afiliado_id_foreign` (`afiliado_id`),
  ADD KEY `afiliado_categoria_categoria_id_foreign` (`categoria_id`);

--
-- Índices para tabela `afiliado_orcamento_interesse`
--
ALTER TABLE `afiliado_orcamento_interesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `afiliado_orcamento_interesse_afiliado_id_foreign` (`afiliado_id`),
  ADD KEY `afiliado_orcamento_interesse_orcamento_id_foreign` (`orcamento_id`);

--
-- Índices para tabela `afiliado_regiao`
--
ALTER TABLE `afiliado_regiao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `afiliado_regiao_afiliado_id_foreign` (`afiliado_id`),
  ADD KEY `afiliado_regiao_regiao_id_foreign` (`regiao_id`),
  ADD KEY `afiliado_regiao_plano_assinatura_afiliado_regiao_id_foreign` (`plano_assinatura_afiliado_regiao_id`);

--
-- Índices para tabela `bairro`
--
ALTER TABLE `bairro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bairro_cidade_id_foreign` (`cidade_id`),
  ADD KEY `bairro_regiao_id_foreign` (`regiao_id`);

--
-- Índices para tabela `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cartao_cnpj`
--
ALTER TABLE `cartao_cnpj`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cartao_cnpj_afiliado_id_foreign` (`afiliado_id`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cidade_estado_id_foreign` (`estado_id`);

--
-- Índices para tabela `condominio`
--
ALTER TABLE `condominio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `condominio_sindico_id_foreign` (`sindico_id`),
  ADD KEY `condominio_bairro_id_foreign` (`bairro_id`);

--
-- Índices para tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrato_afiliado_id_foreign` (`afiliado_id`);

--
-- Índices para tabela `contrato_social`
--
ALTER TABLE `contrato_social`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrato_social_afiliado_id_foreign` (`afiliado_id`);

--
-- Índices para tabela `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `franqueado`
--
ALTER TABLE `franqueado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `franqueado_regiao`
--
ALTER TABLE `franqueado_regiao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `franqueado_regiao_plano_disponibilizado`
--
ALTER TABLE `franqueado_regiao_plano_disponibilizado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `imagem_orcamento`
--
ALTER TABLE `imagem_orcamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `logerroemail`
--
ALTER TABLE `logerroemail`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `logsendinblue`
--
ALTER TABLE `logsendinblue`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `parceiros`
--
ALTER TABLE `parceiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `plano_assinatura_afiliado_regiao`
--
ALTER TABLE `plano_assinatura_afiliado_regiao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `plano_disponivel_franqueado`
--
ALTER TABLE `plano_disponivel_franqueado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `regiao`
--
ALTER TABLE `regiao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `responsavel_afiliado`
--
ALTER TABLE `responsavel_afiliado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `rua`
--
ALTER TABLE `rua`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sessaousuario`
--
ALTER TABLE `sessaousuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sindico`
--
ALTER TABLE `sindico`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuario_app`
--
ALTER TABLE `usuario_app`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuario_sistema_admin`
--
ALTER TABLE `usuario_sistema_admin`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vistoria`
--
ALTER TABLE `vistoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vistoriador`
--
ALTER TABLE `vistoriador`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vistoria_imagem`
--
ALTER TABLE `vistoria_imagem`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `afiliado`
--
ALTER TABLE `afiliado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `afiliado_categoria`
--
ALTER TABLE `afiliado_categoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `afiliado_orcamento_interesse`
--
ALTER TABLE `afiliado_orcamento_interesse`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `afiliado_regiao`
--
ALTER TABLE `afiliado_regiao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `bairro`
--
ALTER TABLE `bairro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `blog`
--
ALTER TABLE `blog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cartao_cnpj`
--
ALTER TABLE `cartao_cnpj`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT de tabela `cidade`
--
ALTER TABLE `cidade`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `condominio`
--
ALTER TABLE `condominio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contrato_social`
--
ALTER TABLE `contrato_social`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `device`
--
ALTER TABLE `device`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `estado`
--
ALTER TABLE `estado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `franqueado`
--
ALTER TABLE `franqueado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `franqueado_regiao`
--
ALTER TABLE `franqueado_regiao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `franqueado_regiao_plano_disponibilizado`
--
ALTER TABLE `franqueado_regiao_plano_disponibilizado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagem_orcamento`
--
ALTER TABLE `imagem_orcamento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logerroemail`
--
ALTER TABLE `logerroemail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logsendinblue`
--
ALTER TABLE `logsendinblue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `parceiros`
--
ALTER TABLE `parceiros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `plano_assinatura_afiliado_regiao`
--
ALTER TABLE `plano_assinatura_afiliado_regiao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `plano_disponivel_franqueado`
--
ALTER TABLE `plano_disponivel_franqueado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regiao`
--
ALTER TABLE `regiao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `responsavel_afiliado`
--
ALTER TABLE `responsavel_afiliado`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `rua`
--
ALTER TABLE `rua`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sessaousuario`
--
ALTER TABLE `sessaousuario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sindico`
--
ALTER TABLE `sindico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario_app`
--
ALTER TABLE `usuario_app`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuario_sistema_admin`
--
ALTER TABLE `usuario_sistema_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `vistoria`
--
ALTER TABLE `vistoria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vistoriador`
--
ALTER TABLE `vistoriador`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vistoria_imagem`
--
ALTER TABLE `vistoria_imagem`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
