
CREATE TABLE IF NOT EXISTS `appinfo` (
  `INFO_id` int(11) NOT NULL AUTO_INCREMENT,
  `INFO_request` varchar(100) NOT NULL,
  `INFO_value` varchar(100) NOT NULL,
  PRIMARY KEY (`INFO_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `appinfo`
--

INSERT INTO `appinfo` (`INFO_id`, `INFO_request`, `INFO_value`) VALUES
(1, 'System Version', '1.0');