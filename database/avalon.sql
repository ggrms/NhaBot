-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 06/09/2018 às 13:18
-- Versão do servidor: 5.7.22-0ubuntu0.17.10.1
-- Versão do PHP: 7.1.17-0ubuntu0.17.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `avalon`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avalon`
--

CREATE TABLE `avalon` (
  `id` int(13) NOT NULL DEFAULT '1',
  `players` varchar(400) DEFAULT NULL,
  `cards` varchar(400) DEFAULT NULL,
  `total` int(11) DEFAULT '0',
  `result` varchar(10) DEFAULT NULL,
  `startedGame` int(11) DEFAULT NULL,
  `currentMission` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `avalon`
--

INSERT INTO `avalon` (`id`, `players`, `cards`, `total`, `result`, `startedGame`, `currentMission`) VALUES
(-289219698, 'a:1:{i:0;s:25:\"Gabriel Goulart:549517427\";}', 'a:0:{}', 1, NULL, NULL, NULL),
(-276317519, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(400) NOT NULL,
  `team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Fazendo dump de dados para tabela `card`
--

INSERT INTO `card` (`id`, `name`, `description`, `team`) VALUES
(11, 'Merlin', 'O Merlin sabe quem são os jogadores do mal (com exceção do Mordred). Seu objetivo é guiar seu time a vitória. Mas cuidado para não se revelar demais, pois a Assassina terá a chance de matá-lo caso seu time ganhe e virar o jogo!', 0),
(12, 'Percival', 'O Percival sabe quem é o Merlin (A não ser que aja uma Morgana no jogo). Seu Objetivo é atrair a atenção para si e proteger o Merlin de ser morto pela Assassina.', 0),
(13, 'Servo Leal de Artur', 'Seu objetivo é fazer as missões darem sucesso e tentar descobrir quem são os infiltrados de Mordred. Tente descobrir quem é o Merlin para que ele te guie a vitória.', 0),
(14, 'Servo Leal de Artur', 'Seu objetivo é fazer as missões darem sucesso e tentar descobrir quem são os infiltrados de Mordred. Tente descobrir quem é o Merlin para que ele te guie a vitória.', 0),
(15, 'Servo Leal de Artur', 'Seu objetivo é fazer as missões darem sucesso e tentar descobrir quem são os infiltrados de Mordred. Tente descobrir quem é o Merlin para que ele te guie a vitória.', 0),
(16, 'Serva Leal de Artur', 'Seu objetivo é fazer as missões darem sucesso e tentar descobrir quem são os infiltrados de Mordred. Tente descobrir quem é o Merlin para que ele te guie a vitória.', 0),
(17, 'Morgana', 'A Morgana aparece como Merlin para o Percival confundindo-o sobre quem é o verdadeiro Merlin. Convença-o que você é o Merlin e se infiltre nas missões e faça-as falhar.', 1),
(18, 'Mordred', 'O Mordred é invisível para o Merlin. Seu objetivo é se aproveitar disso para se infiltrar nas missões e fazê-las falhar!', 1),
(19, 'Assassina', 'Seu objetivo é se infiltrar nas missões e fazê-las falhar. Mas fique atento e tente descobrir quem é o Merlin, pois caso seu time perca, você terá a chance de tentar adivinhar quem é ele e ganhar o jogo!', 1),
(20, 'Minion do Mordred', 'Seu objetivo é se infiltrar nas missões e fazê-las falhar', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `mission`
--

CREATE TABLE `mission` (
  `id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `players` varchar(400) DEFAULT NULL,
  `cards` varchar(400) DEFAULT NULL,
  `result` varchar(400) DEFAULT NULL,
  `success` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `surname` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `avalon`
--
ALTER TABLE `avalon`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de tabela `mission`
--
ALTER TABLE `mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
