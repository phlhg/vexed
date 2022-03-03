-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 01. Mai 2020 um 20:25
-- Server-Version: 10.4.10-MariaDB
-- PHP-Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `graph`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_codes`
--

CREATE TABLE `ph_codes` (
  `id` int(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `code` varchar(21) DEFAULT NULL,
  `uses` int(4) DEFAULT 1,
  `uses_init` int(4) DEFAULT 1,
  `expires` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `ph_codes`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_comments`
--

CREATE TABLE `ph_comments` (
  `id` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `time` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_media`
--

CREATE TABLE `ph_media` (
  `id` int(8) NOT NULL,
  `post` int(8) NOT NULL,
  `type` varchar(20) NOT NULL,
  `size` int(20) NOT NULL,
  `width` int(5) NOT NULL DEFAULT 0,
  `height` int(5) NOT NULL DEFAULT 0,
  `user` int(8) NOT NULL,
  `time` int(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_posts`
--

CREATE TABLE `ph_posts` (
  `id` int(10) NOT NULL,
  `user` int(10) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `date` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_relations`
--

CREATE TABLE `ph_relations` (
  `id` int(10) NOT NULL,
  `user` int(10) DEFAULT NULL,
  `follow` int(10) DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT 0,
  `date` int(10) NOT NULL,
  `intensity` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `ph_relations`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_users`
--

CREATE TABLE `ph_users` (
  `id` int(10) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `admin` int(1) DEFAULT 0,
  `confirmed` int(1) DEFAULT 0,
  `verified` int(1) DEFAULT 0,
  `private` int(1) DEFAULT 0,
  `banned` int(3) DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `website` mediumtext NOT NULL,
  `security` varchar(256) DEFAULT NULL,
  `created` int(20) DEFAULT NULL,
  `conditions` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ph_votes`
--

CREATE TABLE `ph_votes` (
  `id` int(8) NOT NULL,
  `user` int(8) NOT NULL,
  `post` int(8) NOT NULL,
  `vote` int(2) NOT NULL DEFAULT 0,
  `time` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `ph_codes`
--
ALTER TABLE `ph_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_comments`
--
ALTER TABLE `ph_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_media`
--
ALTER TABLE `ph_media`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_posts`
--
ALTER TABLE `ph_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_relations`
--
ALTER TABLE `ph_relations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_users`
--
ALTER TABLE `ph_users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ph_votes`
--
ALTER TABLE `ph_votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `ph_codes`
--
ALTER TABLE `ph_codes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `ph_comments`
--
ALTER TABLE `ph_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `ph_media`
--
ALTER TABLE `ph_media`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `ph_posts`
--
ALTER TABLE `ph_posts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `ph_relations`
--
ALTER TABLE `ph_relations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `ph_users`
--
ALTER TABLE `ph_users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `ph_votes`
--
ALTER TABLE `ph_votes`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
