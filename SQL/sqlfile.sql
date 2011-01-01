SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Tabelstructuur voor tabel `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` bigint(11) NOT NULL auto_increment,
  `account_name` varchar(100) NOT NULL,
  `account_security_clearence` int(11) NOT NULL,
  `account_online_time` varchar(250) NOT NULL,
  PRIMARY KEY  (`account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Tabelstructuur voor tabel `channels`
--

CREATE TABLE IF NOT EXISTS `channels` (
  `channel_id` int(11) NOT NULL auto_increment,
  `channel_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Tabelstructuur voor tabel `commands`
--

CREATE TABLE IF NOT EXISTS `commands` (
  `cmd_id` int(11) NOT NULL auto_increment,
  `cmd_alias` varchar(50) NOT NULL,
  `cmd_name` varchar(50) NOT NULL,
  `cmd_class` varchar(100) NOT NULL,
  `cmd_class_function` varchar(100) NOT NULL,
  `cmd_security_clearence` int(11) NOT NULL default '0',
  `cmd_channel_clearence` int(11) NOT NULL,
  `cmd_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`cmd_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `commands`
--

INSERT INTO `commands` (`cmd_id`, `cmd_alias`, `cmd_name`, `cmd_class`, `cmd_class_function`, `cmd_security_clearence`, `cmd_channel_clearence`, `cmd_added`) VALUES
(1, '2', '!test', '', '', 0, 0, '2010-11-29 12:40:15'),
(2, '0', '!Hello', 'HelloWorld', 'core', 700, 0, '2010-11-29 13:03:40'),
(3, '0', '!hug', 'HelloWorld', 'hug', 0, 0, '2010-11-29 19:17:13'),
(4, '0', '!loadmodule', 'Modules', 'CmdLoadModule', 900, 0, '2010-11-29 19:29:58'),
(5, '0', '!reloadmodule', 'Modules', 'CmdReloadModule', 900, 0, '2010-11-29 19:30:54'),
(6, '0', '!unloadmodule', 'Modules', 'CmdUnloadModule', 900, 0, '2010-11-29 19:31:31'),
(7, '3', '!knuf', '', '', 0, 0, '2010-12-06 16:52:02'),
(8, '0', '!kill', 'HelloWorld', 'FunKill', 100, 0, '2010-12-06 17:34:30'),
(9, '0', '!clearence', 'HelloWorld', 'UserAccess', 0, 0, '2010-12-13 22:13:59'),
(10, '0', '!raw', 'Basics', 'DoRawLine', 1000, 0, '2010-12-13 22:40:46'),
(11, '0', '!adduser', 'ChannelClearence', 'AddUserClearence', 0, 300, '2010-12-14 20:30:02');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `configurations`
--

CREATE TABLE IF NOT EXISTS `configurations` (
  `config_id` int(11) NOT NULL auto_increment,
  `config_name` varchar(100) NOT NULL,
  `config_value` text NOT NULL,
  `config_last_edit` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Gegevens worden uitgevoerd voor tabel `configurations`
--

INSERT INTO `configurations` (`config_id`, `config_name`, `config_value`, `config_last_edit`) VALUES
(1, 'mainchannel', '#Vii.bot', '2010-12-14 17:58:46');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nicknames`
--

CREATE TABLE IF NOT EXISTS `nicknames` (
  `nick_id` int(11) NOT NULL auto_increment,
  `nick_nickname` varchar(50) NOT NULL,
  `nick_old_nickname` varchar(50) NOT NULL,
  `nick_auth_id` int(11) NOT NULL,
  `nick_last_changed` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nick_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Tabelstructuur voor tabel `userlists`
--

CREATE TABLE IF NOT EXISTS `userlists` (
  `userlist_id` int(11) NOT NULL auto_increment,
  `userlist_channel_id` int(11) NOT NULL,
  `userlist_auth_id` int(11) NOT NULL,
  `userlist_clearence` int(11) NOT NULL,
  PRIMARY KEY  (`userlist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;