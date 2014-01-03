CREATE DATABASE IF NOT EXISTS `ircbot` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ircbot`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `account_name` varchar(100) NOT NULL,
  `account_security_clearance` int(11) NOT NULL,
  `account_online_time` varchar(250) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `channels`
--

DROP TABLE IF EXISTS `channels`;
CREATE TABLE IF NOT EXISTS `channels` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(50) NOT NULL,
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `commands`
--

DROP TABLE IF EXISTS `commands`;
CREATE TABLE IF NOT EXISTS `commands` (
  `cmd_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmd_alias` varchar(50) NOT NULL,
  `cmd_name` varchar(50) NOT NULL,
  `cmd_class` varchar(100) NOT NULL,
  `cmd_class_function` varchar(100) NOT NULL,
  `cmd_security_clearance` int(11) NOT NULL DEFAULT '0',
  `cmd_channel_clearance` int(11) NOT NULL,
  `cmd_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cmd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `commands`
--

INSERT INTO `commands` (`cmd_id`, `cmd_alias`, `cmd_name`, `cmd_class`, `cmd_class_function`, `cmd_security_clearance`, `cmd_channel_clearance`, `cmd_added`) VALUES
(1, '2', '!test', '', '', 0, 0, '2010-11-29 11:40:15'),
(2, '0', '!Hello', 'HelloWorld', 'core', 700, 0, '2010-11-29 12:03:40'),
(3, '0', '!hug', 'HelloWorld', 'hug', 0, 0, '2010-11-29 18:17:13'),
(4, '0', '!loadmodule', 'Modules', 'CmdLoadModule', 900, 0, '2010-11-29 18:29:58'),
(5, '0', '!reloadmodule', 'Modules', 'CmdReloadModule', 900, 0, '2010-11-29 18:30:54'),
(6, '0', '!unloadmodule', 'Modules', 'CmdUnloadModule', 900, 0, '2010-11-29 18:31:31'),
(7, '3', '!knuf', '', '', 0, 0, '2010-12-06 15:52:02'),
(8, '0', '!kill', 'HelloWorld', 'FunKill', 100, 0, '2010-12-06 16:34:30'),
(9, '0', '!clearance', 'HelloWorld', 'UserAccess', 0, 0, '2010-12-13 21:13:59'),
(10, '0', '!raw', 'Basics', 'DoRawLine', 1000, 0, '2010-12-13 21:40:46'),
(11, '0', '!adduser', 'ChannelClearence', 'AddUserClearence', 0, 300, '2010-12-14 19:30:02');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `configurations`
--

DROP TABLE IF EXISTS `configurations`;
CREATE TABLE IF NOT EXISTS `configurations` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(100) NOT NULL,
  `config_value` text NOT NULL,
  `config_last_edit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `configurations`
--

INSERT INTO `configurations` (`config_id`, `config_name`, `config_value`, `config_last_edit`) VALUES
(1, 'mainchannel', '#Vii.bot', '2010-12-14 16:58:46');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nicknames`
--

DROP TABLE IF EXISTS `nicknames`;
CREATE TABLE IF NOT EXISTS `nicknames` (
  `nick_id` int(11) NOT NULL AUTO_INCREMENT,
  `nick_nickname` varchar(50) NOT NULL,
  `nick_old_nickname` varchar(50) NOT NULL,
  `nick_auth_id` int(11) NOT NULL,
  `nick_last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userlists`
--

DROP TABLE IF EXISTS `userlists`;
CREATE TABLE IF NOT EXISTS `userlists` (
  `userlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `userlist_channel_id` int(11) NOT NULL,
  `userlist_auth_id` int(11) NOT NULL,
  `userlist_clearance` int(11) NOT NULL,
  PRIMARY KEY (`userlist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;