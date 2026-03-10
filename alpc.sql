-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 10, 2026 at 02:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alpc`
--

-- --------------------------------------------------------

--
-- Table structure for table `nr_counter`
--

CREATE TABLE `nr_counter` (
  `id` int(11) NOT NULL,
  `datetime` datetime GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`,':',`sec`),'%Y-%m-%d %H:%i:%s')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `sec` int(11) NOT NULL,
  `LPC12` int(11) NOT NULL,
  `LPC13` int(11) NOT NULL,
  `LPC14` int(11) NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`LPC12` + `LPC13` + `LPC14`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nr_counter`
--

INSERT INTO `nr_counter` (`id`, `year`, `month`, `day`, `hour`, `min`, `sec`, `LPC12`, `LPC13`, `LPC14`) VALUES
(1, 2026, 3, 4, 16, 16, 44, 224, 224, 224),
(2, 2026, 2, 9, 20, 55, 6, 190, 260, 262),
(3, 2026, 2, 10, 7, 10, 0, 194, 194, 194),
(4, 2026, 2, 10, 20, 55, 0, 268, 268, 254),
(5, 2026, 2, 11, 7, 10, 0, 192, 176, 194),
(6, 2026, 2, 11, 20, 55, 0, 268, 268, 268),
(7, 2026, 2, 12, 7, 10, 3, 192, 194, 194),
(8, 2026, 2, 12, 20, 55, 5, 242, 256, 256),
(9, 2026, 2, 13, 7, 10, 0, 172, 192, 194),
(10, 2026, 2, 13, 20, 55, 1, 26, 190, 196),
(11, 2026, 2, 14, 7, 10, 5, 0, 198, 194),
(12, 2026, 2, 14, 20, 55, 7, 0, 0, 0),
(13, 2026, 2, 15, 7, 10, 0, 0, 0, 0),
(14, 2026, 2, 15, 20, 55, 2, 0, 0, 0),
(15, 2026, 2, 16, 7, 10, 6, 192, 192, 172),
(16, 2026, 2, 16, 20, 55, 3, 260, 258, 258),
(17, 2026, 2, 17, 7, 10, 7, 192, 192, 192),
(18, 2026, 2, 17, 20, 55, 0, 0, 0, 0),
(19, 2026, 2, 18, 7, 10, 2, 0, 0, 0),
(20, 2026, 2, 18, 20, 55, 0, 190, 192, 194),
(21, 2026, 2, 19, 7, 10, 0, 170, 192, 192),
(22, 2026, 2, 19, 20, 55, 1, 194, 194, 194),
(23, 2026, 2, 20, 7, 10, 0, 192, 192, 114),
(24, 2026, 2, 20, 20, 55, 0, 194, 192, 192),
(25, 2026, 2, 21, 7, 10, 0, 192, 192, 168),
(26, 2026, 2, 23, 20, 55, 0, 186, 184, 188),
(27, 2026, 2, 24, 7, 10, 0, 194, 178, 194),
(28, 2026, 2, 25, 20, 55, 0, 194, 190, 196),
(29, 2026, 2, 26, 7, 10, 0, 192, 194, 194),
(30, 2026, 2, 26, 20, 55, 0, 182, 206, 206),
(31, 2026, 2, 27, 7, 10, 0, 194, 180, 194),
(32, 2026, 2, 27, 20, 55, 1, 190, 190, 190),
(33, 2026, 3, 2, 20, 55, 0, 258, 232, 258),
(34, 2026, 3, 3, 7, 10, 0, 170, 192, 192),
(35, 2026, 3, 3, 20, 55, 3, 150, 252, 216);

-- --------------------------------------------------------

--
-- Table structure for table `sz_kr_counter`
--

CREATE TABLE `sz_kr_counter` (
  `id` int(11) NOT NULL,
  `datetime` datetime GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`,':',`sec`),'%Y-%m-%d %H:%i:%s')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `sec` int(11) NOT NULL,
  `LPC9` int(11) NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`LPC9`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sz_kr_counter`
--

INSERT INTO `sz_kr_counter` (`id`, `year`, `month`, `day`, `hour`, `min`, `sec`, `LPC9`) VALUES
(1, 2026, 3, 4, 16, 16, 44, 182),
(2, 2026, 2, 9, 20, 55, 0, 216),
(3, 2026, 2, 10, 7, 10, 0, 160),
(4, 2026, 2, 10, 20, 55, 0, 214),
(5, 2026, 2, 11, 7, 10, 0, 144),
(6, 2026, 2, 11, 20, 55, 0, 244),
(7, 2026, 2, 12, 7, 10, 0, 176),
(8, 2026, 2, 12, 20, 55, 0, 226),
(9, 2026, 2, 13, 7, 10, 0, 156),
(10, 2026, 2, 13, 20, 55, 0, 204),
(11, 2026, 2, 14, 7, 10, 0, 156),
(12, 2026, 2, 14, 20, 55, 0, 0),
(13, 2026, 2, 15, 7, 10, 0, 0),
(14, 2026, 2, 15, 20, 55, 0, 0),
(15, 2026, 2, 16, 7, 10, 0, 156),
(16, 2026, 2, 16, 20, 55, 0, 202),
(17, 2026, 2, 17, 7, 10, 0, 176),
(18, 2026, 2, 17, 20, 55, 0, 116),
(19, 2026, 2, 18, 7, 10, 0, 154),
(20, 2026, 2, 18, 20, 55, 0, 176),
(21, 2026, 2, 19, 7, 10, 0, 156),
(22, 2026, 2, 19, 20, 55, 0, 170),
(23, 2026, 2, 20, 7, 10, 0, 150),
(24, 2026, 2, 20, 20, 55, 0, 194),
(25, 2026, 2, 21, 7, 10, 0, 178),
(26, 2026, 2, 23, 20, 55, 0, 196),
(27, 2026, 2, 24, 7, 10, 0, 176),
(28, 2026, 2, 25, 20, 55, 0, 224),
(29, 2026, 2, 26, 7, 10, 0, 154),
(30, 2026, 2, 26, 20, 55, 0, 164),
(31, 2026, 2, 27, 7, 10, 0, 142),
(32, 2026, 2, 27, 20, 55, 0, 230),
(33, 2026, 3, 2, 20, 55, 0, 202),
(34, 2026, 3, 3, 7, 10, 0, 158),
(35, 2026, 3, 3, 20, 55, 0, 220);

-- --------------------------------------------------------

--
-- Table structure for table `tr_counter`
--

CREATE TABLE `tr_counter` (
  `id` int(11) NOT NULL,
  `datetime` datetime GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`,':',`sec`),'%Y-%m-%d %H:%i:%s')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `sec` int(11) NOT NULL,
  `LPC1` int(11) NOT NULL,
  `LPC2` int(11) NOT NULL,
  `LPC3` int(11) NOT NULL,
  `LPC4` int(11) NOT NULL,
  `shellcore` int(11) NOT NULL,
  `fin1` int(11) NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`LPC1` + `LPC2` + `LPC3` + `LPC4`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_counter`
--

INSERT INTO `tr_counter` (`id`, `year`, `month`, `day`, `hour`, `min`, `sec`, `LPC1`, `LPC2`, `LPC3`, `LPC4`, `shellcore`, `fin1`) VALUES
(1, 2026, 3, 4, 16, 16, 44, 130, 130, 130, 130, 530, 487),
(2, 2026, 2, 4, 16, 3, 55, 124, 124, 84, 92, 0, 0),
(3, 2026, 2, 4, 20, 55, 0, 154, 156, 106, 122, 0, 0),
(4, 2026, 2, 5, 7, 10, 0, 110, 112, 100, 100, 0, 0),
(5, 2026, 2, 5, 20, 55, 0, 144, 82, 62, 152, 0, 0),
(6, 2026, 2, 6, 7, 10, 0, 110, 112, 32, 86, 0, 0),
(7, 2026, 2, 6, 20, 55, 0, 124, 134, 114, 134, 0, 0),
(8, 2026, 2, 7, 7, 10, 0, 112, 112, 70, 102, 0, 0),
(9, 2026, 2, 7, 20, 55, 0, 98, 100, 76, 90, 0, 0),
(10, 2026, 2, 8, 7, 10, 0, 0, 0, 0, 0, 0, 0),
(11, 2026, 2, 8, 20, 55, 0, 0, 0, 0, 0, 0, 0),
(12, 2026, 2, 9, 7, 10, 0, 100, 80, 30, 14, 0, 0),
(13, 2026, 2, 9, 20, 55, 0, 12, 12, 146, 148, 0, 0),
(14, 2026, 2, 10, 7, 10, 0, 112, 114, 112, 112, 0, 0),
(15, 2026, 2, 10, 20, 55, 0, 152, 142, 124, 150, 0, 0),
(16, 2026, 2, 11, 7, 10, 0, 100, 114, 62, 112, 0, 0),
(17, 2026, 2, 11, 20, 55, 0, 86, 150, 154, 150, 0, 0),
(18, 2026, 2, 12, 7, 10, 0, 108, 114, 114, 112, 0, 0),
(19, 2026, 2, 12, 20, 55, 0, 120, 126, 126, 124, 0, 0),
(20, 2026, 2, 13, 7, 10, 0, 114, 114, 114, 52, 0, 0),
(21, 2026, 2, 13, 20, 55, 0, 130, 144, 130, 144, 0, 0),
(22, 2026, 2, 14, 7, 10, 0, 108, 114, 116, 114, 0, 0),
(23, 2026, 2, 14, 20, 55, 0, 0, 0, 0, 0, 0, 0),
(24, 2026, 2, 15, 7, 10, 0, 0, 0, 0, 0, 0, 0),
(25, 2026, 2, 15, 20, 55, 0, 0, 0, 0, 0, 0, 0),
(26, 2026, 2, 16, 7, 10, 0, 70, 110, 114, 112, 0, 0),
(27, 2026, 2, 16, 20, 55, 0, 162, 110, 162, 162, 0, 0),
(28, 2026, 2, 17, 7, 10, 0, 112, 112, 112, 114, 0, 0),
(29, 2026, 2, 17, 20, 55, 0, 114, 112, 98, 98, 0, 0),
(30, 2026, 2, 18, 7, 10, 0, 98, 110, 102, 102, 0, 0),
(31, 2026, 2, 18, 20, 55, 0, 114, 116, 114, 114, 0, 0),
(32, 2026, 2, 19, 7, 10, 0, 100, 112, 114, 114, 0, 0),
(33, 2026, 2, 19, 20, 55, 0, 124, 126, 128, 126, 0, 0),
(34, 2026, 2, 20, 7, 10, 0, 112, 108, 116, 98, 0, 0),
(35, 2026, 2, 20, 20, 55, 0, 150, 138, 64, 150, 0, 0),
(36, 2026, 2, 21, 7, 10, 0, 112, 42, 0, 112, 0, 0),
(37, 2026, 2, 23, 20, 55, 0, 62, 64, 62, 62, 253, 174),
(38, 2026, 2, 24, 7, 10, 0, 98, 98, 98, 86, 382, 428),
(39, 2026, 2, 25, 20, 55, 0, 122, 152, 154, 154, 576, 583),
(40, 2026, 2, 26, 7, 10, 0, 114, 112, 114, 114, 487, 453),
(41, 2026, 2, 26, 20, 55, 0, 120, 120, 112, 108, 481, 462),
(42, 2026, 2, 27, 7, 10, 0, 114, 114, 54, 114, 365, 398),
(43, 2026, 2, 27, 20, 55, 0, 146, 146, 136, 144, 590, 573),
(44, 2026, 3, 2, 20, 55, 0, 154, 152, 142, 140, 604, 585),
(45, 2026, 3, 3, 7, 10, 0, 110, 110, 110, 112, 413, 446),
(46, 2026, 3, 3, 20, 55, 0, 144, 156, 154, 156, 616, 612);

-- --------------------------------------------------------

--
-- Table structure for table `tr_loger_lpc1`
--

CREATE TABLE `tr_loger_lpc1` (
  `id` int(11) NOT NULL,
  `datetime` int(11) GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`),'%Y-%m-%d %H:%i')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `serial_data_1` int(20) NOT NULL,
  `serial_data_2` int(20) NOT NULL,
  `serial_data_3` int(20) NOT NULL,
  `serial_data_4` int(20) NOT NULL,
  `serial_data_5` int(10) NOT NULL,
  `serial_data_6` int(10) NOT NULL,
  `serial_data_7` int(10) NOT NULL,
  `serial_data_8` int(10) NOT NULL,
  `serial_data_9` int(10) NOT NULL,
  `serial_data_10` int(10) NOT NULL,
  `shift_data` int(10) NOT NULL,
  `lpdc_no` int(10) NOT NULL,
  `dies_no` int(10) NOT NULL,
  `operator_id` int(10) NOT NULL,
  `coating_id` int(11) NOT NULL,
  `r_lower_gate1_temp_1` int(11) NOT NULL,
  `r_lower_gate2_temp_1` int(11) NOT NULL,
  `r_lower_main1_temp_1` int(20) NOT NULL,
  `r_lower_main2_temp_1` int(20) NOT NULL,
  `l_upper_main_temp_1` int(20) NOT NULL,
  `l_lower_gate1_temp_1` int(20) NOT NULL,
  `l_lower_gate2_temp_1` int(20) NOT NULL,
  `l_lower_main1_temp_1` int(20) NOT NULL,
  `l_lower_main2_temp_1` int(20) NOT NULL,
  `pressure_room_temp_1` int(20) NOT NULL,
  `hoolding_room_temp_1` int(20) NOT NULL,
  `spare1` int(20) NOT NULL,
  `spare2` int(20) NOT NULL,
  `spare3` int(20) NOT NULL,
  `spare4` int(20) NOT NULL,
  `spare5` int(20) NOT NULL,
  `spare6` int(20) NOT NULL,
  `r_upper_sp_flow_1` int(20) NOT NULL,
  `r_upper_flow_1` int(20) NOT NULL,
  `l_upper_sp_flow_1` int(20) NOT NULL,
  `l_upper_flow_1` int(20) NOT NULL,
  `r_lower_cooling_air1_flow_1` int(20) NOT NULL,
  `l_lower_cooling_air1_flow_1` int(20) NOT NULL,
  `r_lower_cooling_air2_flow_1` int(20) NOT NULL,
  `l_lower_cooling_air2_flow_1` int(20) NOT NULL,
  `spare7` int(20) NOT NULL,
  `spare8` int(20) NOT NULL,
  `pressure_program_no_1` int(20) NOT NULL,
  `after_replenish_shot_1` int(20) NOT NULL,
  `after_coating_shot_1` int(20) NOT NULL,
  `r_lower_gate1_temp_2` int(20) NOT NULL,
  `r_lower_gate2_temp_2` int(20) NOT NULL,
  `r_lower_main1_temp_2` int(20) NOT NULL,
  `r_lower_main2_temp_2` int(20) NOT NULL,
  `l_lower_gate1_temp_2` int(20) NOT NULL,
  `l_lower_gate2_temp_2` int(20) NOT NULL,
  `l_lower_main1_temp_2` int(20) NOT NULL,
  `l_lower_main2_temp_2` int(20) NOT NULL,
  `pressure_room_temp_2` int(20) NOT NULL,
  `hoolding_room_temp_2` int(20) NOT NULL,
  `first_air_temp` int(20) NOT NULL,
  `first_water_temp` int(20) NOT NULL,
  `spare9` int(20) NOT NULL,
  `spare10` int(20) NOT NULL,
  `spare11` int(20) NOT NULL,
  `spare12` int(20) NOT NULL,
  `spare13` int(20) NOT NULL,
  `spare14` int(20) NOT NULL,
  `pressure_cycle1` int(20) NOT NULL,
  `pressure_cycle2` int(20) NOT NULL,
  `pressure_cycle3` int(20) NOT NULL,
  `check_ok_ng` int(20) NOT NULL,
  `spare15` int(20) NOT NULL,
  `spare16` int(20) NOT NULL,
  `spare17` int(20) NOT NULL,
  `spare18` int(20) NOT NULL,
  `spare19` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_loger_lpc4`
--

CREATE TABLE `tr_loger_lpc4` (
  `id` bigint(20) NOT NULL,
  `datetime` int(11) GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`),'%Y-%m-%d %H:%i')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `serial_data_1` int(45) NOT NULL,
  `serial_data_2` int(45) NOT NULL,
  `serial_data_3` int(45) NOT NULL,
  `serial_data_4` int(45) NOT NULL,
  `serial_data_5` int(45) NOT NULL,
  `serial_data_6` int(45) NOT NULL,
  `serial_data_7` int(45) NOT NULL,
  `serial_data_8` int(45) NOT NULL,
  `serial_data_9` int(45) NOT NULL,
  `serial_data_10` int(45) NOT NULL,
  `shift_data` int(45) NOT NULL,
  `lpdc_no` int(11) NOT NULL,
  `dies_no` int(11) NOT NULL,
  `operator_id` int(45) NOT NULL,
  `coating_id` int(45) NOT NULL,
  `r_lower_gate1_temp_1` int(11) NOT NULL,
  `r_lower_gate2_temp_1` int(11) NOT NULL,
  `r_lower_main1_temp_1` int(11) NOT NULL,
  `r_lower_main2_temp_1` int(11) NOT NULL,
  `l_upper_main_temp_1` int(11) NOT NULL,
  `l_lower_gate1_temp_1` int(11) NOT NULL,
  `l_lower_gate2_temp_1` int(11) NOT NULL,
  `l_lower_main1_temp_1` int(11) NOT NULL,
  `l_lower_main2_temp_1` int(11) NOT NULL,
  `pressure_room_temp_1` int(11) NOT NULL,
  `hoolding_room_temp_1` int(11) NOT NULL,
  `spare1` int(45) NOT NULL,
  `spare2` int(45) NOT NULL,
  `spare3` int(45) NOT NULL,
  `spare4` int(45) NOT NULL,
  `spare5` int(45) NOT NULL,
  `spare6` int(45) NOT NULL,
  `r_upper_sp_flow_1` int(11) NOT NULL,
  `r_upper_flow_1` int(11) NOT NULL,
  `l_upper_sp_flow_1` int(11) NOT NULL,
  `l_upper_flow_1` int(11) NOT NULL,
  `r_lower_cooling_air1_flow_1` int(11) NOT NULL,
  `l_lower_cooling_air1_flow_1` int(11) NOT NULL,
  `r_lower_cooling_air2_flow_1` int(11) NOT NULL,
  `l_lower_cooling_air2_flow_1` int(11) NOT NULL,
  `spare7` int(45) NOT NULL,
  `spare8` int(45) NOT NULL,
  `pressure_program_no_1` int(45) NOT NULL,
  `after_replenish_shot_1` int(45) NOT NULL,
  `after_coating_shot_1` int(45) NOT NULL,
  `r_lower_gate1_temp_2` int(11) NOT NULL,
  `r_lower_gate2_temp_2` int(11) NOT NULL,
  `r_lower_main1_temp_2` int(11) NOT NULL,
  `r_lower_main2_temp_2` int(11) NOT NULL,
  `l_lower_gate1_temp_2` int(11) NOT NULL,
  `l_lower_gate2_temp_2` int(11) NOT NULL,
  `l_lower_main1_temp_2` int(11) NOT NULL,
  `l_lower_main2_temp_2` int(11) NOT NULL,
  `pressure_room_temp_2` int(11) NOT NULL,
  `hoolding_room_temp_2` int(11) NOT NULL,
  `first_air_temp` int(11) NOT NULL,
  `first_water_temp` int(11) NOT NULL,
  `spare9` int(45) NOT NULL,
  `spare10` int(45) NOT NULL,
  `spare11` int(45) NOT NULL,
  `spare12` int(45) NOT NULL,
  `spare13` int(45) NOT NULL,
  `spare14` int(45) NOT NULL,
  `pressure_cycle1` int(45) NOT NULL,
  `pressure_cycle2` int(45) NOT NULL,
  `pressure_cycle3` int(45) NOT NULL,
  `check_ok_ng` int(45) NOT NULL,
  `spare15` int(45) NOT NULL,
  `spare16` int(45) NOT NULL,
  `spare17` int(45) NOT NULL,
  `spare18` int(45) NOT NULL,
  `spare19` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tr_loger_lpc6`
--

CREATE TABLE `tr_loger_lpc6` (
  `id` bigint(20) NOT NULL,
  `datetime` datetime GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`),'%Y-%m-%d %H:%i')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `serial_data_1` int(45) NOT NULL,
  `serial_data_2` int(45) NOT NULL,
  `serial_data_3` int(45) NOT NULL,
  `serial_data_4` int(45) NOT NULL,
  `serial_data_5` int(45) NOT NULL,
  `serial_data_6` int(45) NOT NULL,
  `serial_data_7` int(45) NOT NULL,
  `serial_data_8` int(45) NOT NULL,
  `serial_data_9` int(45) NOT NULL,
  `serial_data_10` int(45) NOT NULL,
  `shift_data` int(45) NOT NULL,
  `lpdc_no` int(11) NOT NULL,
  `dies_no` int(11) NOT NULL,
  `operator_id` int(45) NOT NULL,
  `coating_id` int(45) NOT NULL,
  `r_lower_gate1_temp_1` int(11) NOT NULL,
  `r_lower_gate2_temp_1` int(11) NOT NULL,
  `r_lower_main1_temp_1` int(11) NOT NULL,
  `r_lower_main2_temp_1` int(11) NOT NULL,
  `l_upper_main_temp_1` int(11) NOT NULL,
  `l_lower_gate1_temp_1` int(11) NOT NULL,
  `l_lower_gate2_temp_1` int(11) NOT NULL,
  `l_lower_main1_temp_1` int(11) NOT NULL,
  `l_lower_main2_temp_1` int(11) NOT NULL,
  `pressure_room_temp_1` int(11) NOT NULL,
  `hoolding_room_temp_1` int(11) NOT NULL,
  `spare1` int(45) NOT NULL,
  `spare2` int(45) NOT NULL,
  `spare3` int(45) NOT NULL,
  `spare4` int(45) NOT NULL,
  `spare5` int(45) NOT NULL,
  `spare6` int(45) NOT NULL,
  `r_upper_sp_flow_1` int(11) NOT NULL,
  `r_upper_flow_1` int(11) NOT NULL,
  `l_upper_sp_flow_1` int(11) NOT NULL,
  `l_upper_flow_1` int(11) NOT NULL,
  `r_lower_cooling_air1_flow_1` int(11) NOT NULL,
  `l_lower_cooling_air1_flow_1` int(11) NOT NULL,
  `r_lower_cooling_air2_flow_1` int(11) NOT NULL,
  `l_lower_cooling_air2_flow_1` int(11) NOT NULL,
  `spare7` int(45) NOT NULL,
  `spare8` int(45) NOT NULL,
  `pressure_program_no_1` int(45) NOT NULL,
  `after_replenish_shot_1` int(45) NOT NULL,
  `after_coating_shot_1` int(45) NOT NULL,
  `r_lower_gate1_temp_2` int(11) NOT NULL,
  `r_lower_gate2_temp_2` int(11) NOT NULL,
  `r_lower_main1_temp_2` int(11) NOT NULL,
  `r_lower_main2_temp_2` int(11) NOT NULL,
  `l_lower_gate1_temp_2` int(11) NOT NULL,
  `l_lower_gate2_temp_2` int(11) NOT NULL,
  `l_lower_main1_temp_2` int(11) NOT NULL,
  `l_lower_main2_temp_2` int(11) NOT NULL,
  `pressure_room_temp_2` int(11) NOT NULL,
  `hoolding_room_temp_2` int(11) NOT NULL,
  `first_air_temp` int(11) NOT NULL,
  `first_water_temp` int(11) NOT NULL,
  `spare9` int(45) NOT NULL,
  `spare10` int(45) NOT NULL,
  `spare11` int(45) NOT NULL,
  `spare12` int(45) NOT NULL,
  `spare13` int(45) NOT NULL,
  `spare14` int(45) NOT NULL,
  `pressure_cycle1` int(45) NOT NULL,
  `pressure_cycle2` int(45) NOT NULL,
  `pressure_cycle3` int(45) NOT NULL,
  `check_ok_ng` int(45) NOT NULL,
  `spare15` int(45) NOT NULL,
  `spare16` int(45) NOT NULL,
  `spare17` int(45) NOT NULL,
  `spare18` int(45) NOT NULL,
  `spare19` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tr_loger_lpc6`
--

INSERT INTO `tr_loger_lpc6` (`id`, `year`, `month`, `day`, `hour`, `min`, `serial_data_1`, `serial_data_2`, `serial_data_3`, `serial_data_4`, `serial_data_5`, `serial_data_6`, `serial_data_7`, `serial_data_8`, `serial_data_9`, `serial_data_10`, `shift_data`, `lpdc_no`, `dies_no`, `operator_id`, `coating_id`, `r_lower_gate1_temp_1`, `r_lower_gate2_temp_1`, `r_lower_main1_temp_1`, `r_lower_main2_temp_1`, `l_upper_main_temp_1`, `l_lower_gate1_temp_1`, `l_lower_gate2_temp_1`, `l_lower_main1_temp_1`, `l_lower_main2_temp_1`, `pressure_room_temp_1`, `hoolding_room_temp_1`, `spare1`, `spare2`, `spare3`, `spare4`, `spare5`, `spare6`, `r_upper_sp_flow_1`, `r_upper_flow_1`, `l_upper_sp_flow_1`, `l_upper_flow_1`, `r_lower_cooling_air1_flow_1`, `l_lower_cooling_air1_flow_1`, `r_lower_cooling_air2_flow_1`, `l_lower_cooling_air2_flow_1`, `spare7`, `spare8`, `pressure_program_no_1`, `after_replenish_shot_1`, `after_coating_shot_1`, `r_lower_gate1_temp_2`, `r_lower_gate2_temp_2`, `r_lower_main1_temp_2`, `r_lower_main2_temp_2`, `l_lower_gate1_temp_2`, `l_lower_gate2_temp_2`, `l_lower_main1_temp_2`, `l_lower_main2_temp_2`, `pressure_room_temp_2`, `hoolding_room_temp_2`, `first_air_temp`, `first_water_temp`, `spare9`, `spare10`, `spare11`, `spare12`, `spare13`, `spare14`, `pressure_cycle1`, `pressure_cycle2`, `pressure_cycle3`, `check_ok_ng`, `spare15`, `spare16`, `spare17`, `spare18`, `spare19`) VALUES
(1, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 2026, 3, 4, 14, 56, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 2026, 3, 4, 14, 57, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 2026, 3, 4, 14, 57, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 2026, 3, 4, 14, 58, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 2026, 3, 4, 15, 17, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 2026, 3, 4, 15, 17, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 2026, 3, 4, 15, 17, 16689, 12854, 48, 13378, 0, 0, 0, 0, 0, 0, 12336, 12336, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `wa_counter`
--

CREATE TABLE `wa_counter` (
  `id` int(11) NOT NULL,
  `datetime` datetime GENERATED ALWAYS AS (str_to_date(concat(`year`,'-',`month`,'-',`day`,' ',`hour`,':',`min`,':',`sec`),'%Y-%m-%d %H:%i:%s')) STORED,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `hour` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `sec` int(11) NOT NULL,
  `LPC11` int(11) NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`LPC11`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wa_counter`
--

INSERT INTO `wa_counter` (`id`, `year`, `month`, `day`, `hour`, `min`, `sec`, `LPC11`) VALUES
(1, 2026, 3, 4, 16, 16, 44, 162),
(2, 2026, 2, 9, 20, 55, 6, 220),
(3, 2026, 2, 10, 7, 10, 0, 164),
(4, 2026, 2, 10, 20, 55, 0, 232),
(5, 2026, 2, 11, 7, 10, 0, 164),
(6, 2026, 2, 11, 20, 55, 0, 166),
(7, 2026, 2, 12, 7, 10, 3, 152),
(8, 2026, 2, 12, 20, 55, 5, 220),
(9, 2026, 2, 13, 7, 10, 0, 164),
(10, 2026, 2, 13, 20, 55, 1, 218),
(11, 2026, 2, 14, 7, 10, 5, 164),
(12, 2026, 2, 14, 20, 55, 7, 0),
(13, 2026, 2, 15, 7, 10, 0, 0),
(14, 2026, 2, 15, 20, 55, 2, 0),
(15, 2026, 2, 16, 7, 10, 6, 164),
(16, 2026, 2, 16, 20, 55, 2, 160),
(17, 2026, 2, 17, 7, 10, 7, 164),
(18, 2026, 2, 17, 20, 55, 0, 0),
(19, 2026, 2, 18, 7, 10, 2, 0),
(20, 2026, 2, 18, 20, 55, 0, 164),
(21, 2026, 2, 19, 7, 10, 0, 166),
(22, 2026, 2, 19, 20, 55, 1, 162),
(23, 2026, 2, 20, 7, 10, 0, 166),
(24, 2026, 2, 20, 20, 55, 0, 168),
(25, 2026, 2, 21, 7, 10, 0, 166),
(26, 2026, 2, 23, 20, 55, 0, 134),
(27, 2026, 2, 24, 7, 10, 0, 166),
(28, 2026, 2, 25, 20, 55, 0, 164),
(29, 2026, 2, 26, 7, 10, 0, 166),
(30, 2026, 2, 26, 20, 55, 0, 174),
(31, 2026, 2, 27, 7, 10, 0, 164),
(32, 2026, 2, 27, 20, 55, 0, 162),
(33, 2026, 3, 2, 20, 55, 0, 218),
(34, 2026, 3, 3, 7, 10, 0, 166),
(35, 2026, 3, 3, 20, 55, 0, 224);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nr_counter`
--
ALTER TABLE `nr_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sz_kr_counter`
--
ALTER TABLE `sz_kr_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_counter`
--
ALTER TABLE `tr_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_loger_lpc1`
--
ALTER TABLE `tr_loger_lpc1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_loger_lpc4`
--
ALTER TABLE `tr_loger_lpc4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tr_loger_lpc6`
--
ALTER TABLE `tr_loger_lpc6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wa_counter`
--
ALTER TABLE `wa_counter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nr_counter`
--
ALTER TABLE `nr_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `sz_kr_counter`
--
ALTER TABLE `sz_kr_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tr_counter`
--
ALTER TABLE `tr_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tr_loger_lpc1`
--
ALTER TABLE `tr_loger_lpc1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_loger_lpc4`
--
ALTER TABLE `tr_loger_lpc4`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tr_loger_lpc6`
--
ALTER TABLE `tr_loger_lpc6`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wa_counter`
--
ALTER TABLE `wa_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
