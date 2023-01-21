-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: database:3306
-- Vytvořeno: Sob 21. led 2023, 14:47
-- Verze serveru: 10.9.4-MariaDB-1:10.9.4+maria~ubu2204
-- Verze PHP: 8.0.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `app_portal`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `insurances`
--

CREATE TABLE `insurances` (
  `insurance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `insurance_sum` float NOT NULL,
  `insurance_start_date` date NOT NULL,
  `insurance_end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `insurances`
--

INSERT INTO `insurances` (`insurance_id`, `user_id`, `product_id`, `insurance_sum`, `insurance_start_date`, `insurance_end_date`) VALUES
(2, 29, 6, 150000, '2023-01-17', '2023-06-07'),
(3, 29, 0, 250000, '2023-01-17', '2025-01-18'),
(12, 29, 0, 1021, '2023-01-20', '2023-02-17'),
(13, 29, 6, 1000000, '2023-01-20', '2023-02-02');

-- --------------------------------------------------------

--
-- Struktura tabulky `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(45) DEFAULT NULL,
  `product_desc` varchar(45) DEFAULT NULL,
  `product_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_desc`, `product_active`) VALUES
(0, 'Pojištění nemovitosti', 'Pojistěte si svou nemovitost', 1),
(5, 'Pojištění odpovědnosti', NULL, 1),
(6, 'Pojištění automobilu', NULL, 1),
(7, 'Pojištění bytu', NULL, 0),
(10, 'Pojištění odpovědnosti 2', NULL, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(80) NOT NULL,
  `user_firstname` varchar(55) NOT NULL,
  `user_lastname` varchar(55) NOT NULL,
  `user_password` varchar(225) NOT NULL,
  `user_telephone` varchar(255) NOT NULL,
  `user_birthdate` date NOT NULL,
  `user_city` varchar(55) NOT NULL,
  `user_address` varchar(55) NOT NULL,
  `user_psc` varchar(55) NOT NULL,
  `user_type` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_firstname`, `user_lastname`, `user_password`, `user_telephone`, `user_birthdate`, `user_city`, `user_address`, `user_psc`, `user_type`) VALUES
(29, 'admin@admin.cz', 'Ondra', 'Nyklíček', '$2y$10$eg.hmx8vqMJvYC7E9QQyFu0XFuYBdhugnvoeYRR5HC2CxS6Ew1.BW', '777777777', '1991-01-01', 'Pardubice', 'Hradní 6111', '506 01', 0),
(31, 'poma@admin.cz', 'Petr', 'Omáčka', '$2y$10$LJ8llelVDfnXW8rDWuRnYuGuoS50/Chn1.02qQqs.s8q6Tz3hJZ8C', '643 543 341', '1991-01-17', 'Praha', 'Karvinská 12', '543 01', 1),
(63, 'tpl@s.cz', 'Tomas', 'Plot', '$2y$10$9oR.qSMwUa2mAqe1l.sZ4OayAGNyEaMd48/57qYFbNp5AMbPdglhW', '765765765', '1981-01-12', 'Praha', 'A 1', '56001', 1),
(67, 'tomk@k.cz', 'Tomáš', 'Karel', '$2y$10$8MirZpLtHpKiVOHJB1IqruMOXFX.UP0z5EIDuNJOE1qyYGE4W0zCS', '721345324', '1993-12-01', 'Hradec', 'Kunětická 1', '500 01', 1);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `insurances`
--
ALTER TABLE `insurances`
  ADD PRIMARY KEY (`insurance_id`),
  ADD KEY `insurances_user` (`user_id`);

--
-- Indexy pro tabulku `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_telephone` (`user_telephone`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `insurances`
--
ALTER TABLE `insurances`
  MODIFY `insurance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pro tabulku `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `insurances`
--
ALTER TABLE `insurances`
  ADD CONSTRAINT `insurances_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
