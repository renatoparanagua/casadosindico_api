-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25-Ago-2020 às 04:38
-- Versão do servidor: 10.4.8-MariaDB
-- versão do PHP: 7.3.11

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
  `id` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `cartao_cnpj` varchar(255) DEFAULT NULL,
  `inscricao_estadual` varchar(45) DEFAULT NULL,
  `inscricao_municipal` varchar(45) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  `estado` varchar(80) DEFAULT NULL,
  `cidade` varchar(80) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `rua` varchar(255) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ramo_atividade` varchar(255) DEFAULT NULL,
  `numero_funcionarios` int(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'pendente' COMMENT 'pendente\nativo\ninativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_app_id` int(11) NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_contrato` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `afiliado`
--

INSERT INTO `afiliado` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `cartao_cnpj`, `inscricao_estadual`, `inscricao_municipal`, `cep`, `estado`, `cidade`, `bairro`, `numero`, `rua`, `complemento`, `telefone`, `email`, `ramo_atividade`, `numero_funcionarios`, `logo`, `status`, `data_cadastro`, `usuario_app_id`, `data_atualizacao`, `data_contrato`, `deleted_at`, `data_remocao`) VALUES
(223, 'Raffffffffzaoff Afiliado atualizado', 'Fant Afiliado atualizado', '34343434', 'dfgdfg', '44444ffff4', '33333333', '88136000', 'sc', 'palo', 'sae sebast', '3818', 'tomas', NULL, '99636294', 'jdjd@dd.ddj', 'fgfg', 2, 'sfdf', 'pendente', '2020-08-24 03:04:41', 2, '2020-08-24 03:04:41', NULL, NULL, NULL),
(233, 'dfdf', 'Fant Afildfdfiado atualizado', '34343434', NULL, NULL, NULL, 'ddd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2020-08-24 06:54:15', 2, '2020-08-24 06:54:15', NULL, NULL, NULL),
(234, 'dfdf', 'Fant Afildfdfiado atualizado', '34343434', NULL, NULL, NULL, 'uuuu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2020-08-24 08:00:21', 2, '2020-08-24 08:00:21', NULL, NULL, NULL),
(235, 'dfdf', 'Fant Afildfdfiado atualizado', '34343434', NULL, NULL, NULL, 'f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2020-08-24 08:00:27', 2, '2020-08-24 08:01:27', NULL, NULL, NULL),
(236, 'dfdf', 'Fant Afildfdfiado atualizado', '34343434', NULL, NULL, NULL, 'f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pendente', '2020-08-25 04:00:37', 2, '2020-08-25 04:00:37', NULL, NULL, NULL),
(237, 'dfdf', 'Fant Afildfdfiado atualizado', '34343434', NULL, NULL, NULL, 'f', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'dfdf@fgfg.ghgh', NULL, NULL, NULL, 'pendente', '2020-08-25 04:00:41', 2, '2020-08-25 04:25:07', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_categoria`
--

CREATE TABLE `afiliado_categoria` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_orcamento_interesse`
--

CREATE TABLE `afiliado_orcamento_interesse` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `interessado` int(1) NOT NULL DEFAULT 1,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nao_interessante` int(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `afiliado_regiao`
--

CREATE TABLE `afiliado_regiao` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `data_pagamento_plano` timestamp NULL DEFAULT NULL,
  `data_expiracao_plano` date DEFAULT NULL,
  `plano_assinatura_afiliado_regiao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bairro`
--

CREATE TABLE `bairro` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cidade_id` int(11) NOT NULL,
  `chave` varchar(150) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `blog`
--

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nome` varchar(255) NOT NULL,
  `imagem_principal` varchar(255) DEFAULT NULL,
  `descricao` longtext DEFAULT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'publicado' COMMENT 'publicado\nrascunho',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cartao_cnpj`
--

CREATE TABLE `cartao_cnpj` (
  `id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(45) NOT NULL DEFAULT 'pendente' COMMENT 'pendente\naceito\nrecusado',
  `arquivo` longblob NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `chave_url` text NOT NULL,
  `nome` varchar(80) NOT NULL,
  `imagem` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cidade`
--

CREATE TABLE `cidade` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado_id` int(11) NOT NULL,
  `chave` varchar(150) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `cidade`
--

INSERT INTO `cidade` (`id`, `nome`, `data_cadastro`, `data_atualizacao`, `estado_id`, `chave`, `deleted_at`, `data_remocao`) VALUES
(1, 'sdf', '2020-08-25 01:49:46', '2020-08-25 01:49:46', 1, 'sdf', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `condominio`
--

CREATE TABLE `condominio` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `cep` varchar(8) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sindico_id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(45) NOT NULL DEFAULT 'pendente' COMMENT 'pendente\naceito\nrecusado',
  `arquivo` longblob NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato_social`
--

CREATE TABLE `contrato_social` (
  `id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(45) NOT NULL DEFAULT 'pendente' COMMENT 'pendente\naceito\nrecusado',
  `arquivo` longblob NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `device`
--

CREATE TABLE `device` (
  `id` int(11) NOT NULL,
  `ip` text NOT NULL,
  `device_unique_id` text NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp(),
  `data_cadastro` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `device`
--

INSERT INTO `device` (`id`, `ip`, `device_unique_id`, `deleted_at`, `data_atualizacao`, `data_cadastro`) VALUES
(1, '127.0.0.1', 'abc1ssss23', NULL, '2020-08-24 08:23:05', '2020-08-24 07:56:41'),
(2, '127.0.0.1', 'abc123', NULL, '2020-08-24 07:59:10', '2020-08-24 07:59:10'),
(3, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:06:03', '2020-08-24 08:06:03'),
(4, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:11:03', '2020-08-24 08:11:03'),
(5, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:15:51', '2020-08-24 08:15:51'),
(6, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:16:13', '2020-08-24 08:16:13'),
(7, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:16:15', '2020-08-24 08:16:15'),
(8, '127.0.0.1', 'abc123', NULL, '2020-08-24 08:18:14', '2020-08-24 08:18:14'),
(9, '127.0.0.1', 'abc1sss23', NULL, '2020-08-24 08:25:30', '2020-08-24 08:25:30'),
(10, '127.0.0.1', 'sdfsdfsdfsdf', NULL, '2020-08-24 08:26:26', '2020-08-24 08:26:26'),
(11, '127.0.0.1', 'gg', NULL, '2020-08-24 16:43:30', '2020-08-24 16:43:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `chave` varchar(80) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `estado`
--

INSERT INTO `estado` (`id`, `nome`, `uf`, `data_cadastro`, `data_atualizacao`, `chave`, `deleted_at`) VALUES
(1, 'fgh', 'sc', '2020-08-25 01:49:34', '2020-08-25 01:49:34', 'sc', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado`
--

CREATE TABLE `franqueado` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token_asaas_producao` text DEFAULT NULL,
  `token_asaas_debug` text DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `senha` varchar(60) DEFAULT NULL,
  `cnpj` varchar(45) DEFAULT NULL,
  `inscricao_estadual` varchar(45) DEFAULT NULL,
  `inscricao_municipal` varchar(45) DEFAULT NULL,
  `cpf_responsavel` varchar(45) DEFAULT NULL,
  `rg_responsavel` varchar(45) DEFAULT NULL,
  `profissao_responsavel` varchar(45) DEFAULT NULL,
  `telefone_responsavel` varchar(45) DEFAULT NULL,
  `cep` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `cidade` varchar(45) DEFAULT NULL,
  `rua` varchar(45) DEFAULT NULL,
  `bairro` varchar(45) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado_regiao`
--

CREATE TABLE `franqueado_regiao` (
  `id` int(11) NOT NULL,
  `franqueado_id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'ativo' COMMENT 'inativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_inicio_atividade` date DEFAULT NULL,
  `data_fim_atividade` date DEFAULT NULL,
  `usuario_sistema_admin_id` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado_regiao_plano_disponibilizado`
--

CREATE TABLE `franqueado_regiao_plano_disponibilizado` (
  `id` int(11) NOT NULL,
  `franqueado_regiao_id` int(11) NOT NULL,
  `plano_disponivel_franqueado_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagem_orcamento`
--

CREATE TABLE `imagem_orcamento` (
  `id` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `caminho_imagem` text NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logerroemail`
--

CREATE TABLE `logerroemail` (
  `id` int(11) NOT NULL,
  `dataErro` datetime DEFAULT NULL,
  `mensagemErro` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logsendinblue`
--

CREATE TABLE `logsendinblue` (
  `id` int(11) NOT NULL,
  `dataCriacao` datetime DEFAULT NULL,
  `idOrcamento` int(11) DEFAULT NULL,
  `retorno` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2020_08_13_175053_afiliado_categoria', 1),
(10, '2020_08_13_175053_afiliado_orcamento_interesse', 1),
(11, '2020_08_13_175053_afiliado_regiao', 1),
(12, '2020_08_13_175053_bairro', 1),
(13, '2020_08_13_175053_blog', 1),
(14, '2020_08_13_175054_cartao_cnpj', 1),
(15, '2020_08_13_175054_categoria', 1),
(16, '2020_08_13_175054_cidade', 1),
(17, '2020_08_13_175055_condominio', 1),
(18, '2020_08_13_175055_contrato', 1),
(19, '2020_08_13_175055_contrato_social', 1),
(20, '2020_08_13_175056_estado', 1),
(21, '2020_08_13_175056_franqueado', 2),
(22, '2020_08_13_175056_franqueado_regiao', 2),
(23, '2020_08_13_175056_franqueado_regiao_plano_disponibilizado', 2),
(24, '2020_08_13_175057_imagem_orcamento', 2),
(25, '2020_08_13_175058_orcamento', 2),
(26, '2020_08_13_175058_parceiro', 2),
(27, '2020_08_13_175058_plano_assinatura_afiliado_regiao', 2),
(28, '2020_08_13_175058_plano_disponivel_franqueado', 2),
(29, '2020_08_13_175059_regiao', 2),
(30, '2020_08_13_175059_regiao_bairro', 2),
(31, '2020_08_13_175059_responsavel_afiliado', 2),
(32, '2020_08_13_175100_rua', 2),
(33, '2020_08_13_175100_sindico', 2),
(34, '2020_08_13_175101_usuario_sistema_admin', 2),
(35, '2020_08_13_175101_vistoria', 2),
(36, '2020_08_13_175101_vistoriador', 2),
(37, '2020_08_13_175104_vistoria_imagem', 2),
(38, '2020_08_13_181123_usuario_app', 2),
(39, '2020_08_14_181535_afiliado', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('004d5e065694a733361dea85756685bd9fe89961e6592fce4c33404a991e774bba0abe85bdd4f0ae', 6, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:16:13', '2020-08-24 08:16:13', '2021-08-24 05:16:13'),
('01e0d1a4d7eb5043e8474c933ba450ae552a0a2872f06e67239ecd1a2aad366b28988deece6b766b', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:41:01', '2020-08-24 07:41:01', '2021-08-24 04:41:01'),
('0edf1102d78ad89f0666a4034342a79f73a8584959c299becf9352f681333bc18c0d2ad6fbe0ea60', 1, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 08:23:05', '2020-08-24 08:23:05', '2021-08-24 05:23:05'),
('24a535a948e6315ddaf282679bc6053c1844bc35a2563c6bc5e4fbfbc29003ebcbb3e41190df67f3', 9, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 08:25:30', '2020-08-24 08:25:30', '2021-08-24 05:25:30'),
('24f9ba90c59eff2762f8d0bad6a972e69257a0d872ebb50b4776dc33d9e9294911a207dffa74bb9b', 4, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:11:03', '2020-08-24 08:11:03', '2021-08-24 05:11:03'),
('32d653140a70e828720029f9c222c2c64db84523005ba6cf5d5679ba20ed5c44abf125d2ec654834', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:46:49', '2020-08-24 07:46:49', '2021-08-24 04:46:49'),
('3493dbc80da72373f08d07e7261fa3be6cf618b29cd085240f6a606f40bc33825ef3e18e63802698', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:41:04', '2020-08-24 07:41:04', '2021-08-24 04:41:04'),
('3a1f77ac0bea04d4e2601db3e912f7b3c7399601f4bb6be1e77c8b284165265d1c7f9f3bceeccb09', 5, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:15:51', '2020-08-24 08:15:51', '2021-08-24 05:15:51'),
('3ca1e7c2310d249f26aedae680883cd343af652271eb3ede4e6bd2a0874503ac7a9f7ff7858f499e', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:38:09', '2020-08-24 07:38:09', '2021-08-24 04:38:09'),
('468593d16ab015aab5aa038ead348878180d706b36b41c8262ee9a5ac36a6f7e776762758200d78e', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:49:07', '2020-08-24 07:49:07', '2021-08-24 04:49:07'),
('4c9d0298577ec8d911502255d07cb77099421917dca2f9659b8b42de378fe39ab90838c034ff8de2', 10, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 16:43:23', '2020-08-24 16:43:23', '2021-08-24 13:43:23'),
('5196519db23c2e51ce63233e8fce2b0ae7a2aaba04f2ee847fcdb8f8fe6c7e83bc246df02a8ba8e4', 7, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:16:15', '2020-08-24 08:16:15', '2021-08-24 05:16:15'),
('6fceaadbe0572ba47de95f7cf6f4628584884c1f82d36118fa3dfcb5b3cbf4062a401d55652e07e0', 3, 3, '41eefdb061ba874122f33947b4f9e0d3', '[]', 0, '2020-08-22 22:52:48', '2020-08-22 22:52:48', '2021-08-22 19:52:48'),
('7420740d8c247a8ccdab7956f60c5de215add2970228fa00d54890ef89803fa82b1c139dd77eab98', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:38:12', '2020-08-24 07:38:12', '2021-08-24 04:38:12'),
('948020b1f65b0886648be4d95ceb33134e53e961e0d75a1cfd651569ee0daa42a61326b68a685d7e', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:49:37', '2020-08-24 07:49:37', '2021-08-24 04:49:37'),
('9fa772b99443ddd0463c5b618a3bca06547a1f9d7ba67a8cc8859ef1d2c6aad04d91c7548ab6b7ea', 3, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:06:03', '2020-08-24 08:06:03', '2021-08-24 05:06:03'),
('e689651a29090b9fb3e7b305399d4639e55962f196ae370a66511dc589f43ee46d63fd18a4b9da0c', NULL, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 07:41:02', '2020-08-24 07:41:02', '2021-08-24 04:41:02'),
('e9a33f476143bed19c8e25785e11fb34c5e9b3a1d19009d5428b61df2b2f4dece1d8a844a602fb20', 8, 3, 'e641dfee47e752d5e7dc5c7d3825e88a', '[]', 0, '2020-08-24 08:18:14', '2020-08-24 08:18:14', '2021-08-24 05:18:14'),
('ec2f5ea3fd2cc4bffb8be61f2e9b91b05dd115ee1a0732dab9c1bcf95131bd398b8f5bc344e176f8', 2, 3, 'c593eda9247da782950136b84c0c50aa', '[]', 0, '2020-08-24 08:26:32', '2020-08-24 08:26:32', '2021-08-24 05:26:32'),
('f137dcf365e64eb218946b12e3b145fd959217edce5e30eb5ff545fb9c9da213e6090c12b3995429', 11, 3, 'd41d8cd98f00b204e9800998ecf8427e', '[]', 0, '2020-08-24 16:56:11', '2020-08-24 16:56:11', '2021-08-24 13:56:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, NULL, 'Laravel Personal Access Client', 'nCwuKaafrGaLuklyZfp7qMJ9zALZokMT4wY9AO8K', NULL, 'http://localhost', 1, 0, 0, '2020-08-21 13:52:59', '2020-08-21 13:52:59'),
(2, NULL, 'Laravel Password Grant Client', 'cA0L9WPHMbGXn30Jxl8t1CTvrPkU44qGs5N2lFOg', 'users', 'http://localhost', 0, 1, 0, '2020-08-21 13:52:59', '2020-08-21 13:52:59'),
(3, NULL, 'Laravel Personal Access Client', 'InvhbMxibcPrq7kvZMi3U1XEmvYpPqRY1XCVpUD4', NULL, 'http://localhost', 1, 0, 0, '2020-08-21 13:53:11', '2020-08-21 13:53:11'),
(4, NULL, 'Laravel Password Grant Client', 'lVZ31QOJyua8b4hYskDtwGQwfqTyQWulJLY5nmk5', 'users', 'http://localhost', 0, 1, 0, '2020-08-21 13:53:11', '2020-08-21 13:53:11');

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
(1, 1, '2020-08-21 13:52:59', '2020-08-21 13:52:59'),
(2, 3, '2020-08-21 13:53:11', '2020-08-21 13:53:11');

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
  `id` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `status_sindico` int(1) DEFAULT NULL,
  `status_afiliado` int(1) DEFAULT NULL,
  `condominio_id` int(11) NOT NULL,
  `afiliado_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `parceiros`
--

CREATE TABLE `parceiros` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nome_responsavel` varchar(100) DEFAULT NULL,
  `telefone` varchar(11) DEFAULT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'pendente' COMMENT 'ativo\npendente\ninativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `id` int(11) NOT NULL COMMENT 'Aqu ficarão os plaos que os franqueados poderão disponibilizar para seus afiliados',
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statusPlano` int(11) NOT NULL,
  `quantidade_meses_vigencia` int(11) NOT NULL,
  `dias_trial` int(11) NOT NULL DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `franqueado_regiao_plano_disponibilizado_id` int(11) NOT NULL,
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `data_cancelamento` timestamp NULL DEFAULT NULL,
  `data_expiracao` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `plano_disponivel_franqueado`
--

CREATE TABLE `plano_disponivel_franqueado` (
  `id` int(11) NOT NULL COMMENT 'Aqu ficarão os plaos que os franqueados poderão disponibilizar para seus afiliados',
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `valor_comissao` decimal(10,2) NOT NULL DEFAULT 0.00,
  `statusPlano` int(11) NOT NULL,
  `quantidade_meses_vigencia` int(11) NOT NULL,
  `dias_trial` int(11) NOT NULL DEFAULT 0,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_sistema_admin_id` int(11) NOT NULL,
  `regiao_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `regiao`
--

CREATE TABLE `regiao` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `regiao_bairro`
--

CREATE TABLE `regiao_bairro` (
  `id` int(11) NOT NULL,
  `regiao_id` int(11) NOT NULL,
  `bairro_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `responsavel_afiliado`
--

CREATE TABLE `responsavel_afiliado` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `numero_documento` int(11) DEFAULT NULL,
  `CPF` varchar(45) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `afiliado_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `responsavel_afiliado`
--

INSERT INTO `responsavel_afiliado` (`id`, `nome`, `email`, `numero_documento`, `CPF`, `telefone`, `cargo`, `afiliado_id`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `data_remocao`) VALUES
(222, 'fgh', 'fgh', 3434, 'fgh', 'fgh', 'dgf', 223, '2020-08-24 01:21:34', '2020-08-24 01:21:34', NULL, NULL),
(223, 'dfgdfg', 'dfgdfg', 345345, 'sdfdf', 'sdfsdf', 'sdfsdf', 223, '2020-08-24 01:31:19', '2020-08-24 01:31:19', NULL, NULL),
(224, 'dfdf', 'sss@www.com', 34343434, 'hhh', 'telefone', 'hhh', 237, '2020-08-25 04:13:16', '2020-08-25 04:32:07', '2020-08-25 04:32:07', NULL),
(225, 'bbbbbbbb', 'dfdf@fgfg.ghgh', 34343434, 'hhh', 'telefone', 'hhh', 237, '2020-08-25 04:13:28', '2020-08-25 04:32:05', '2020-08-25 04:32:05', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rua`
--

CREATE TABLE `rua` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cep` varchar(8) NOT NULL,
  `bairro_id` int(11) NOT NULL,
  `chave` text NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessaousuario`
--

CREATE TABLE `sessaousuario` (
  `id` int(11) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `inicioSessao` datetime DEFAULT NULL,
  `fimSessao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `sindico`
--

CREATE TABLE `sindico` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `numero_documento` int(11) NOT NULL,
  `CPF` varchar(45) NOT NULL,
  `telefone` varchar(11) NOT NULL,
  `usuario_app_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `tipo` varchar(45) NOT NULL COMMENT 'sindico\nprestador\nvistoriador',
  `remember_token` text DEFAULT NULL,
  `token_noification` text DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `data_remocao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario_app`
--

INSERT INTO `usuario_app` (`id`, `email`, `senha`, `tipo`, `remember_token`, `token_noification`, `data_cadastro`, `data_atualizacao`, `deleted_at`, `data_remocao`) VALUES
(1, 'renato@email.com', '$2y$10$8sYODsnRojpm0rRpnrTkpOY4n04St1vziLd1R/yPAtfjWzaQS2uTa', 'sindico', NULL, NULL, '2020-08-21 16:38:54', '2020-08-22 18:15:23', NULL, NULL),
(2, 'renatoafiliado@email.com', '$2y$10$OJghIg8Dvpl6vI2/N/7h7ubVLAALKNsfdW67OELwn3087uMrKgY0W', 'afiliado', NULL, NULL, '2020-08-21 16:44:02', '2020-08-22 18:15:20', NULL, NULL),
(3, 'renatosindico@email.com', '$2y$10$dvdLAj22YdpelDkZhiUyx.KfKIbWZVgm8xe6WJiR9r4co9DHleDvu', 'sindico', NULL, NULL, '2020-08-22 22:52:33', '2020-08-22 22:52:33', NULL, NULL),
(4, 'renatosindico2@email.com', '$2y$10$.M9xe.lvG.mKuGP/YYGaq./WDKCLSpi1nBe0OK9HGwjBtwxIg0nsK', 'sindico', NULL, NULL, '2020-08-24 07:24:09', '2020-08-24 07:24:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_sistema_admin`
--

CREATE TABLE `usuario_sistema_admin` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo` varchar(45) NOT NULL COMMENT 'superadmin\nadmin',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoria`
--

CREATE TABLE `vistoria` (
  `id` int(11) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `data_vistoria` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_checkout` timestamp NULL DEFAULT NULL,
  `data_checkin` timestamp NULL DEFAULT NULL,
  `vistoriador_id` int(11) NOT NULL,
  `orcamento_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `latitude_checkin` double DEFAULT NULL,
  `longitude_checkin` double DEFAULT NULL,
  `latitude_checkout` double DEFAULT NULL,
  `longitude_checkout` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoriador`
--

CREATE TABLE `vistoriador` (
  `id` int(11) NOT NULL,
  `usuario_app_id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vistoria_imagem`
--

CREATE TABLE `vistoria_imagem` (
  `id` int(11) NOT NULL,
  `vistoria_id` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `caminho_imagem` text NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacvao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `afiliado`
--
ALTER TABLE `afiliado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_afiliados_usuario_app1_idx` (`usuario_app_id`);

--
-- Índices para tabela `afiliado_categoria`
--
ALTER TABLE `afiliado_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_afiliados_has_categorias_categorias1_idx` (`categoria_id`),
  ADD KEY `fk_afiliados_has_categorias_afiliados1_idx` (`afiliado_id`);

--
-- Índices para tabela `afiliado_orcamento_interesse`
--
ALTER TABLE `afiliado_orcamento_interesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_afiliado_has_orcamento_orcamento1_idx` (`orcamento_id`),
  ADD KEY `fk_afiliado_has_orcamento_afiliado1_idx` (`afiliado_id`);

--
-- Índices para tabela `afiliado_regiao`
--
ALTER TABLE `afiliado_regiao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_afiliados_has_regiao_regiao1_idx` (`regiao_id`),
  ADD KEY `fk_afiliados_has_regiao_afiliados1_idx` (`afiliado_id`),
  ADD KEY `fk_afiliado_regiao_plano_assinatura_afiliado_regiao1_idx` (`plano_assinatura_afiliado_regiao_id`);

--
-- Índices para tabela `bairro`
--
ALTER TABLE `bairro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bairro_cidade1_idx` (`cidade_id`);

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
  ADD KEY `fk_contrato_social_afiliados1_idx` (`afiliado_id`);

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
  ADD KEY `fk_cidade_estado1_idx` (`estado_id`);

--
-- Índices para tabela `condominio`
--
ALTER TABLE `condominio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_condominio_sindico1_idx` (`sindico_id`),
  ADD KEY `fk_condominio_regiao1_idx` (`regiao_id`);

--
-- Índices para tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contrato_social_afiliados1_idx` (`afiliado_id`);

--
-- Índices para tabela `contrato_social`
--
ALTER TABLE `contrato_social`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contrato_social_afiliados1_idx` (`afiliado_id`);

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
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_franqueado_has_regiao_regiao1_idx` (`regiao_id`),
  ADD KEY `fk_franqueado_has_regiao_franqueado1_idx` (`franqueado_id`),
  ADD KEY `fk_franqueado_regiao_usuario_sistema_admin1_idx` (`usuario_sistema_admin_id`);

--
-- Índices para tabela `franqueado_regiao_plano_disponibilizado`
--
ALTER TABLE `franqueado_regiao_plano_disponibilizado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_franqueado_regiao_has_plano_disponivel_franqueado_plano__idx` (`plano_disponivel_franqueado_id`),
  ADD KEY `fk_franqueado_regiao_has_plano_disponivel_franqueado_franqu_idx` (`franqueado_regiao_id`);

--
-- Índices para tabela `imagem_orcamento`
--
ALTER TABLE `imagem_orcamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_imagem_orcamento_orcamento1_idx` (`orcamento_id`);

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
-- Índices para tabela `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Índices para tabela `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Índices para tabela `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Índices para tabela `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Índices para tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orcamento_condominio1_idx` (`condominio_id`),
  ADD KEY `fk_orcamento_afiliados1_idx` (`afiliado_id`),
  ADD KEY `fk_orcamento_categorias1_idx` (`categoria_id`);

--
-- Índices para tabela `parceiros`
--
ALTER TABLE `parceiros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Índices para tabela `plano_assinatura_afiliado_regiao`
--
ALTER TABLE `plano_assinatura_afiliado_regiao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_plano_disponibilizado_franqueado_franqueado_regiao_plano_idx` (`franqueado_regiao_plano_disponibilizado_id`);

--
-- Índices para tabela `plano_disponivel_franqueado`
--
ALTER TABLE `plano_disponivel_franqueado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_plano_disponivel_franqueado_usuario_sistema_admin1_idx` (`usuario_sistema_admin_id`),
  ADD KEY `fk_plano_disponivel_franqueado_regiao1_idx` (`regiao_id`);

--
-- Índices para tabela `regiao`
--
ALTER TABLE `regiao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `regiao_bairro`
--
ALTER TABLE `regiao_bairro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_regiao_has_bairro_bairro1_idx` (`bairro_id`),
  ADD KEY `fk_regiao_has_bairro_regiao1_idx` (`regiao_id`);

--
-- Índices para tabela `responsavel_afiliado`
--
ALTER TABLE `responsavel_afiliado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_responsavelAfiliado_afiliados1_idx` (`afiliado_id`);

--
-- Índices para tabela `rua`
--
ALTER TABLE `rua`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rua_bairro1_idx` (`bairro_id`);

--
-- Índices para tabela `sessaousuario`
--
ALTER TABLE `sessaousuario`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sindico`
--
ALTER TABLE `sindico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sindico_usuario_app1_idx` (`usuario_app_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Índices para tabela `usuario_app`
--
ALTER TABLE `usuario_app`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `usuario_sistema_admin`
--
ALTER TABLE `usuario_sistema_admin`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vistoria`
--
ALTER TABLE `vistoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vistoria_vistoriador1_idx` (`vistoriador_id`),
  ADD KEY `fk_vistoria_orcamento1_idx` (`orcamento_id`);

--
-- Índices para tabela `vistoriador`
--
ALTER TABLE `vistoriador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vistoriador_usuario_app1_idx` (`usuario_app_id`);

--
-- Índices para tabela `vistoria_imagem`
--
ALTER TABLE `vistoria_imagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_imagem_vistoria1_idx` (`vistoria_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `afiliado`
--
ALTER TABLE `afiliado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT de tabela `afiliado_categoria`
--
ALTER TABLE `afiliado_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `afiliado_orcamento_interesse`
--
ALTER TABLE `afiliado_orcamento_interesse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `afiliado_regiao`
--
ALTER TABLE `afiliado_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bairro`
--
ALTER TABLE `bairro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cartao_cnpj`
--
ALTER TABLE `cartao_cnpj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de tabela `cidade`
--
ALTER TABLE `cidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `condominio`
--
ALTER TABLE `condominio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9554;

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contrato_social`
--
ALTER TABLE `contrato_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `device`
--
ALTER TABLE `device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `franqueado`
--
ALTER TABLE `franqueado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `franqueado_regiao`
--
ALTER TABLE `franqueado_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `franqueado_regiao_plano_disponibilizado`
--
ALTER TABLE `franqueado_regiao_plano_disponibilizado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagem_orcamento`
--
ALTER TABLE `imagem_orcamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logerroemail`
--
ALTER TABLE `logerroemail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `logsendinblue`
--
ALTER TABLE `logsendinblue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7436;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `parceiros`
--
ALTER TABLE `parceiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `plano_assinatura_afiliado_regiao`
--
ALTER TABLE `plano_assinatura_afiliado_regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Aqu ficarão os plaos que os franqueados poderão disponibilizar para seus afiliados', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `plano_disponivel_franqueado`
--
ALTER TABLE `plano_disponivel_franqueado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Aqu ficarão os plaos que os franqueados poderão disponibilizar para seus afiliados', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `regiao`
--
ALTER TABLE `regiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `regiao_bairro`
--
ALTER TABLE `regiao_bairro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `responsavel_afiliado`
--
ALTER TABLE `responsavel_afiliado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT de tabela `rua`
--
ALTER TABLE `rua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sessaousuario`
--
ALTER TABLE `sessaousuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1012;

--
-- AUTO_INCREMENT de tabela `sindico`
--
ALTER TABLE `sindico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario_app`
--
ALTER TABLE `usuario_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuario_sistema_admin`
--
ALTER TABLE `usuario_sistema_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `vistoria`
--
ALTER TABLE `vistoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vistoriador`
--
ALTER TABLE `vistoriador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vistoria_imagem`
--
ALTER TABLE `vistoria_imagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `afiliado`
--
ALTER TABLE `afiliado`
  ADD CONSTRAINT `fk_afiliados_usuario_app1` FOREIGN KEY (`usuario_app_id`) REFERENCES `usuario_app` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `afiliado_categoria`
--
ALTER TABLE `afiliado_categoria`
  ADD CONSTRAINT `fk_afiliados_has_categorias_afiliados1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_afiliados_has_categorias_categorias1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `afiliado_orcamento_interesse`
--
ALTER TABLE `afiliado_orcamento_interesse`
  ADD CONSTRAINT `fk_afiliado_has_orcamento_afiliado1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_afiliado_has_orcamento_orcamento1` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `afiliado_regiao`
--
ALTER TABLE `afiliado_regiao`
  ADD CONSTRAINT `fk_afiliado_regiao_plano_assinatura_afiliado_regiao1` FOREIGN KEY (`plano_assinatura_afiliado_regiao_id`) REFERENCES `plano_assinatura_afiliado_regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_afiliados_has_regiao_afiliados1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_afiliados_has_regiao_regiao1` FOREIGN KEY (`regiao_id`) REFERENCES `regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `bairro`
--
ALTER TABLE `bairro`
  ADD CONSTRAINT `fk_bairro_cidade1` FOREIGN KEY (`cidade_id`) REFERENCES `cidade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `cartao_cnpj`
--
ALTER TABLE `cartao_cnpj`
  ADD CONSTRAINT `fk_contrato_social_afiliados10` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `cidade`
--
ALTER TABLE `cidade`
  ADD CONSTRAINT `fk_cidade_estado1` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `condominio`
--
ALTER TABLE `condominio`
  ADD CONSTRAINT `fk_condominio_regiao1` FOREIGN KEY (`regiao_id`) REFERENCES `regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_condominio_sindico1` FOREIGN KEY (`sindico_id`) REFERENCES `sindico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `contrato`
--
ALTER TABLE `contrato`
  ADD CONSTRAINT `fk_contrato_social_afiliados11` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `contrato_social`
--
ALTER TABLE `contrato_social`
  ADD CONSTRAINT `fk_contrato_social_afiliados1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `franqueado_regiao`
--
ALTER TABLE `franqueado_regiao`
  ADD CONSTRAINT `fk_franqueado_has_regiao_franqueado1` FOREIGN KEY (`franqueado_id`) REFERENCES `franqueado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_franqueado_has_regiao_regiao1` FOREIGN KEY (`regiao_id`) REFERENCES `regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_franqueado_regiao_usuario_sistema_admin1` FOREIGN KEY (`usuario_sistema_admin_id`) REFERENCES `usuario_sistema_admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `franqueado_regiao_plano_disponibilizado`
--
ALTER TABLE `franqueado_regiao_plano_disponibilizado`
  ADD CONSTRAINT `fk_franqueado_regiao_has_plano_disponivel_franqueado_franquea1` FOREIGN KEY (`franqueado_regiao_id`) REFERENCES `franqueado_regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_franqueado_regiao_has_plano_disponivel_franqueado_plano_di1` FOREIGN KEY (`plano_disponivel_franqueado_id`) REFERENCES `plano_disponivel_franqueado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `imagem_orcamento`
--
ALTER TABLE `imagem_orcamento`
  ADD CONSTRAINT `fk_imagem_orcamento_orcamento1` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD CONSTRAINT `fk_orcamento_afiliados1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orcamento_categorias1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_orcamento_condominio1` FOREIGN KEY (`condominio_id`) REFERENCES `condominio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `plano_assinatura_afiliado_regiao`
--
ALTER TABLE `plano_assinatura_afiliado_regiao`
  ADD CONSTRAINT `fk_plano_disponibilizado_franqueado_franqueado_regiao_plano_d1` FOREIGN KEY (`franqueado_regiao_plano_disponibilizado_id`) REFERENCES `franqueado_regiao_plano_disponibilizado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `plano_disponivel_franqueado`
--
ALTER TABLE `plano_disponivel_franqueado`
  ADD CONSTRAINT `fk_plano_disponivel_franqueado_regiao1` FOREIGN KEY (`regiao_id`) REFERENCES `regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_plano_disponivel_franqueado_usuario_sistema_admin1` FOREIGN KEY (`usuario_sistema_admin_id`) REFERENCES `usuario_sistema_admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `regiao_bairro`
--
ALTER TABLE `regiao_bairro`
  ADD CONSTRAINT `fk_regiao_has_bairro_bairro1` FOREIGN KEY (`bairro_id`) REFERENCES `bairro` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_regiao_has_bairro_regiao1` FOREIGN KEY (`regiao_id`) REFERENCES `regiao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `responsavel_afiliado`
--
ALTER TABLE `responsavel_afiliado`
  ADD CONSTRAINT `fk_responsavelAfiliado_afiliados1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliado` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `rua`
--
ALTER TABLE `rua`
  ADD CONSTRAINT `fk_rua_bairro1` FOREIGN KEY (`bairro_id`) REFERENCES `bairro` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `sindico`
--
ALTER TABLE `sindico`
  ADD CONSTRAINT `fk_sindico_usuario_app1` FOREIGN KEY (`usuario_app_id`) REFERENCES `usuario_app` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `vistoria`
--
ALTER TABLE `vistoria`
  ADD CONSTRAINT `fk_vistoria_orcamento1` FOREIGN KEY (`orcamento_id`) REFERENCES `orcamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_vistoria_vistoriador1` FOREIGN KEY (`vistoriador_id`) REFERENCES `vistoriador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `vistoriador`
--
ALTER TABLE `vistoriador`
  ADD CONSTRAINT `fk_vistoriador_usuario_app1` FOREIGN KEY (`usuario_app_id`) REFERENCES `usuario_app` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `vistoria_imagem`
--
ALTER TABLE `vistoria_imagem`
  ADD CONSTRAINT `fk_imagem_vistoria1` FOREIGN KEY (`vistoria_id`) REFERENCES `vistoria` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
