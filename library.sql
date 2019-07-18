-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 28 2019 г., 11:16
-- Версия сервера: 10.1.40-MariaDB
-- Версия PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `library`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `def_img_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `name`, `surname`, `description`, `def_img_id`) VALUES
(2, 'John Ronald Reuel', 'Tolkien', 'John Ronald Reuel Tolkien was an English writer  poet  philologist  and academic  who is best known as the author of the classic high fantasy works The Hobbit  The Lord of the Rings  and The Silmarillion.', 25),
(3, 'George Raymond Richard', 'Martin', 'George Raymond Richard Martin also known as GRRM  is an American novelist and short story writer in the fantasy  horror  and science fiction genres  screenwriter  and television producer. He is best known for his series of epic fantasy novels  A Song of Ice and Fire  which was adapted into the HBO series Game of Thrones (2011&ndash;2019).', 19),
(4, 'Agatha', 'Christie', 'Dame Agatha Mary Clarissa Christie, Lady Mallowan, was an English writer. She is known for her 66 detective novels and 14 short story collections, particularly those revolving around her fictional detectives Hercule Poirot and Miss Marple. Christie also wrote the world\'s longest-running play, a murder mystery, The Mousetrap, and, under the pen name Mary Westmacott, six romances. In 1971 she was appointed a Dame Commander of the Order of the British Empire  for her contribution to literature.', 27);

-- --------------------------------------------------------

--
-- Структура таблицы `author_has_image`
--

CREATE TABLE `author_has_image` (
  `author_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `author_has_image`
--

INSERT INTO `author_has_image` (`author_id`, `image_id`) VALUES
(2, 23),
(2, 25),
(3, 19),
(3, 20),
(4, 27);

-- --------------------------------------------------------

--
-- Структура таблицы `avatar`
--

CREATE TABLE `avatar` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `description` text NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `avatar`
--

INSERT INTO `avatar` (`id`, `filename`, `date`, `description`, `width`, `height`, `author`, `uploaded_by`) VALUES
(5, 'default.gif', '2019-05-27 07:58:53', 'Default avatar', 48, 48, 'Ruckus Wireless, Inc.', 4),
(8, 'ava1.png', '2019-05-27 12:34:50', 'A simple male avatar', 48, 48, 'Chim Kan', 4),
(10, 'user-7122286-48-21fnsm8.png', '2019-06-25 14:39:15', 'wtfava', 48, 48, 'edublogs', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ISBN` varchar(255) NOT NULL,
  `loan_time` int(11) NOT NULL DEFAULT '3',
  `description` text NOT NULL,
  `def_img_id` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `name`, `ISBN`, `loan_time`, `description`, `def_img_id`) VALUES
(3, 'The Fellowship of the Ring', '0345339703', 5, 'The Fellowship of the Ring is the first of three volumes of the epic novel The Lord of the Rings by the English author J. R. R. Tolkien. It is followed by The Two Towers and The Return of the King. It takes place in the fictional universe of Middle-earth. It was originally published on 29 July 1954 in the United Kingdom.', 11),
(4, 'A Game of Thrones', '0553573403', 4, 'A Game of Thrones is the first novel in A Song of Ice and Fire  a series of fantasy novels by the American author George R. R. Martin. It was first published on August 1  1996. The novel won the 1997 Locus Award and was nominated for both the 1997 Nebula Award and the 1997 World Fantasy Award. The novella Blood of the Dragon  comprising the Daenerys Targaryen chapters from the novel  won the 1997 Hugo Award for Best Novella. In January 2011  the novel became a New York Times Bestseller and reached #1 on the list in July 2011.', 22),
(5, 'The Two Towers', '0345339711', 3, 'The Two Towers is the second volume of J. R. R. Tolkien\'s high fantasy novel The Lord of the Rings. It is preceded by The Fellowship of the Ring and followed by The Return of the King.', 21),
(6, 'The Mysterious Affair at Styles', '9780007527496', 3, 'The Mysterious Affair at Styles is a detective novel by British writer Agatha Christie. It was written in the middle of the First World War, in 1916, and first published by John Lane in the United States in October 1920[1] and in the United Kingdom by The Bodley Head (John Lane\'s UK company) on 21 January 1921.', 29),
(10, 'wtfbook', 'wtfisbn', 3, 'wtfction', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `book_has_author`
--

CREATE TABLE `book_has_author` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book_has_author`
--

INSERT INTO `book_has_author` (`book_id`, `author_id`) VALUES
(3, 2),
(4, 3),
(5, 2),
(6, 4),
(10, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `book_has_genre`
--

CREATE TABLE `book_has_genre` (
  `book_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book_has_genre`
--

INSERT INTO `book_has_genre` (`book_id`, `genre_id`) VALUES
(3, 7),
(3, 12),
(4, 7),
(4, 13),
(4, 14),
(5, 7),
(5, 12),
(6, 2),
(10, 3),
(10, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `book_has_image`
--

CREATE TABLE `book_has_image` (
  `book_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book_has_image`
--

INSERT INTO `book_has_image` (`book_id`, `image_id`) VALUES
(3, 3),
(3, 11),
(4, 12),
(4, 22),
(5, 3),
(5, 21),
(6, 27),
(6, 29),
(10, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `book_instance`
--

CREATE TABLE `book_instance` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book_instance`
--

INSERT INTO `book_instance` (`id`, `state_id`, `book_id`, `store_id`) VALUES
(1, 1, 3, 1),
(2, 1, 3, 3),
(3, 1, 4, 4),
(4, 1, 4, 3),
(13, 1, 4, 1),
(14, 1, 4, 1),
(15, 1, 4, 1),
(16, 1, 4, 1),
(17, 1, 4, 1),
(18, 1, 3, 1),
(19, 1, 3, 1),
(20, 1, 3, 1),
(21, 1, 3, 1),
(22, 1, 3, 1),
(23, 1, 3, 1),
(24, 1, 3, 1),
(25, 1, 3, 1),
(26, 1, 3, 5),
(30, 1, 4, 5),
(31, 1, 4, 5),
(32, 1, 4, 5),
(33, 1, 4, 5),
(34, 1, 4, 5),
(35, 1, 4, 5),
(36, 1, 3, 3),
(37, 1, 3, 3),
(38, 1, 4, 3),
(39, 1, 4, 3),
(40, 1, 4, 3),
(41, 1, 4, 3),
(42, 1, 4, 3),
(43, 1, 4, 3),
(44, 1, 4, 3),
(45, 1, 4, 3),
(46, 1, 4, 3),
(47, 1, 3, 4),
(48, 1, 3, 4),
(49, 1, 4, 4),
(50, 1, 4, 4),
(51, 1, 4, 4),
(52, 1, 4, 4),
(53, 1, 4, 4),
(54, 1, 4, 4),
(55, 1, 4, 4),
(56, 1, 4, 4),
(57, 1, 4, 4),
(58, 1, 4, 4),
(59, 1, 4, 4),
(60, 1, 4, 4),
(61, 1, 4, 4),
(62, 1, 4, 4),
(63, 1, 5, 5),
(65, 1, 5, 5),
(67, 1, 5, 3),
(68, 1, 5, 3),
(69, 1, 5, 3),
(70, 1, 5, 3),
(71, 1, 5, 3),
(72, 1, 5, 3),
(73, 1, 5, 4),
(74, 2, 5, 4),
(75, 1, 5, 1),
(76, 1, 5, 1),
(77, 1, 5, 1),
(78, 1, 5, 1),
(79, 1, 5, 1),
(80, 1, 5, 1),
(81, 1, 5, 1),
(82, 1, 6, 5),
(83, 1, 6, 5),
(84, 1, 6, 5),
(86, 1, 6, 5),
(87, 1, 6, 5),
(88, 1, 6, 5),
(89, 1, 6, 5),
(90, 1, 6, 5),
(96, 1, 6, 4),
(97, 1, 6, 4),
(98, 1, 6, 4),
(105, 1, 3, 1),
(106, 1, 3, 1),
(107, 1, 3, 5),
(108, 1, 3, 5),
(109, 1, 3, 5),
(110, 1, 3, 5),
(111, 1, 3, 5),
(112, 1, 3, 5),
(113, 1, 3, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `content`, `date`, `book_id`, `user_id`) VALUES
(1, 'OMG! That\'s my favourite book!', '2019-05-29 15:12:54', 3, 4),
(4, 'It\'s good also', '2019-05-29 18:57:59', 4, 4),
(5, 'Wow, it\'s interesting', '2019-05-29 18:59:08', 4, 4),
(6, 'Super book, love it!', '2019-05-31 16:12:39', 3, 4),
(7, 'wtftext', '2019-06-25 23:19:39', 3, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `genre`
--

INSERT INTO `genre` (`id`, `name`) VALUES
(1, 'Horror fiction'),
(2, 'Detective novel'),
(3, 'Fiction'),
(4, 'Light fiction'),
(5, 'Chick lit'),
(6, 'Science-fiction'),
(7, 'Fantasy'),
(8, 'Business & Finance'),
(9, 'Politics'),
(10, 'Travel books'),
(11, 'Autobiography'),
(12, 'Adventure'),
(13, 'Political novel'),
(14, 'Epic fantasy');

-- --------------------------------------------------------

--
-- Структура таблицы `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `description` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `image`
--

INSERT INTO `image` (`id`, `filename`, `date`, `description`, `tags`, `width`, `height`, `author`, `uploaded_by`) VALUES
(1, 'def_author.png', '2019-05-27 10:23:44', 'Default author image', 'author', 640, 605, 'Domus by F.lli Saba', 4),
(2, 'def_book.jpg', '2019-05-27 10:23:44', 'Default book image', 'book', 265, 400, 'Okir Publishing Inc.', 4),
(3, 'lotr.jpg', '2019-05-27 13:39:06', 'The Lord of the Rings main poster image', 'lotr ring lord', 333, 499, 'Amazon.com', 4),
(11, 'The_Fellowship_of_the_Ring_cover.gif', '2019-05-28 21:53:06', 'The Fellowship of the Ring book cover', 'lotr ring lord Tolkien fellowship', 250, 419, 'wikipedia', 4),
(12, 'AGameOfThrones.jpg', '2019-05-28 21:57:12', 'A Game of Thrones book cover', 'GRRM GoT ice fire', 259, 385, 'wikipedia', 4),
(19, 'martin.jpg', '2019-05-29 20:23:24', 'GRRM wikipedia portrait', 'GRRM portrait', 377, 480, 'wikipedia', 4),
(20, 'martin2.jpg', '2019-05-29 21:49:56', 'GRRM interview', 'GRRM portrait', 640, 795, 'wikiwand', 4),
(21, '220px-The_Two_Towers_cover.gif', '2019-05-31 01:51:48', 'The Two Towers book cover', 'lotr ring lord Tolkien tower two', 220, 350, 'wikipedia', 4),
(22, '9780007548231.jpg', '2019-05-31 02:10:57', 'A Game of Thrones book cover modern', 'GoT song ice fire GRRM', 263, 400, 'wikipedia', 4),
(23, '220px-Tolkien_1916.jpg', '2019-05-31 09:59:07', 'Tolkien old photo', 'JRRT Tolkien', 220, 318, 'wikipedia', 4),
(25, 'JRR_Tolkien.jpg', '2019-05-31 10:06:10', 'Tolkien older', 'JRRT Tolkien', 300, 369, 'wikipedia', 4),
(27, 'agatha_christie.jpg', '2019-05-31 10:08:22', 'Agatha Christie', 'Agatha Christie cover ', 325, 499, 'wikipedia', 4),
(28, '220px-Agatha_Christie_3.jpg', '2019-05-31 10:08:46', 'Agatha Christie', 'Agatha Christie', 220, 299, 'wikipedia', 4),
(29, '9780812977202.jpeg', '2019-05-31 16:14:47', 'Agatha Christie book ', 'AG Christie Agatha Poirot', 292, 450, 'wikipedia', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `header` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `header`, `content`, `date`, `user_id`) VALUES
(1, 'Brexit in fiction: How literature latches on to the theme of political divisions', 'The chaos of politics  the searing divisions through society  the promised demise of faith in democracy: it might look like the country is in an unprecedented state of strife and upheaval over Brexit. But writers have been talking about this stuff for centuries.\r \r King Lear&rsquo;s shuffle into dementia as he tears apart his kingdom on a vain whim. Dickens&rsquo;s revolutionary bloodshed in A Tale of Two Cities conjuring tensions within and across borders. Humphrey Bogart and Ingrid Bergman challenging the imperialist march of the Nazis in Casablanca. How does culture sort out the bloody mess of social  political and geographical divisions? Politicians and bureaucrats don&rsquo;t have the answer. The man down the pub doesn&rsquo;t have the answer. So maybe it&rsquo;s time we look to literature.', '2019-05-27 21:27:53', 4),
(2, 'New book from GRRM', 'Today we can see, that GRRM published a new book', '2019-05-31 16:10:45', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'HR-worker'),
(2, 'Store-seller'),
(3, 'Admin'),
(4, 'Moderator'),
(5, 'Blog-writer'),
(6, 'User'),
(7, 'Regional-manager');

-- --------------------------------------------------------

--
-- Структура таблицы `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `staff`
--

INSERT INTO `staff` (`id`, `user_id`, `store_id`) VALUES
(16, 10, 4),
(19, 4, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `state_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `state`
--

INSERT INTO `state` (`id`, `state_name`) VALUES
(1, 'In stock'),
(2, 'Reserved'),
(3, 'Borrowed');

-- --------------------------------------------------------

--
-- Структура таблицы `store`
--

CREATE TABLE `store` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `store`
--

INSERT INTO `store` (`id`, `name`, `address`) VALUES
(1, 'Your book shelf', 'Genslerstra&szlig;e 34, 12527 Berlin'),
(2, 'Online Service', 'iLibrarium.com'),
(3, 'Books for You', 'Wa&szlig;mannsdorfer Chaussee 28, 10825 Berlin'),
(4, 'Read_mE.txt', 'Brandenburgische Stra&szlig;e 30, 12681 Berlin'),
(5, 'Best books', 'Leopoldstra&szlig;e 89, 14129 Berlin'),
(6, 'wtflol', 'sdfh&ouml;&szlig;');

-- --------------------------------------------------------

--
-- Структура таблицы `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `registered_by` int(11) NOT NULL,
  `registered_for` int(11) NOT NULL,
  `place_registered` int(11) NOT NULL,
  `book_instance_id` int(11) NOT NULL,
  `book_instance_book_id` int(11) NOT NULL,
  `date_registered` datetime NOT NULL,
  `date_ready` datetime NOT NULL,
  `date_before` datetime NOT NULL,
  `place_to_take` int(11) NOT NULL,
  `date_taken` datetime DEFAULT NULL,
  `date_returned` datetime DEFAULT NULL,
  `place_returned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `transaction`
--

INSERT INTO `transaction` (`id`, `registered_by`, `registered_for`, `place_registered`, `book_instance_id`, `book_instance_book_id`, `date_registered`, `date_ready`, `date_before`, `place_to_take`, `date_taken`, `date_returned`, `place_returned`) VALUES
(2, 4, 4, 5, 26, 3, '2019-05-30 23:10:03', '2019-05-31 23:10:03', '2019-08-30 23:10:03', 1, '2019-05-31 01:08:55', '2019-05-31 01:13:08', 5),
(3, 4, 10, 4, 47, 3, '2019-05-31 16:17:29', '2019-05-31 16:17:29', '2019-08-31 16:17:29', 4, '2019-05-31 16:17:55', '2019-05-31 16:18:12', 4),
(4, 4, 9, 4, 96, 6, '2019-06-22 12:20:41', '2019-06-23 12:20:41', '2019-09-22 12:20:41', 5, '2019-06-22 13:23:49', '2019-06-22 13:23:51', 4),
(5, 8, 8, 2, 82, 6, '2019-06-22 13:07:20', '2019-06-23 13:07:20', '2019-09-22 13:07:20', 4, '2019-06-22 13:23:23', '2019-06-22 13:23:28', 4),
(6, 4, 4, 4, 3, 4, '2019-06-22 13:41:10', '2019-06-22 13:41:10', '2019-10-22 13:41:10', 4, '2019-06-22 13:41:35', '2019-06-22 13:42:49', 4),
(7, 4, 4, 4, 3, 4, '2019-06-22 13:43:21', '2019-06-22 13:43:21', '2019-10-22 13:43:21', 4, '2019-06-22 13:43:49', '2019-06-22 13:45:18', 4),
(8, 4, 4, 4, 3, 4, '2019-06-22 13:45:35', '2019-06-22 13:45:35', '2019-10-22 13:45:35', 4, '2019-06-22 13:45:38', '2019-06-22 13:48:31', 4),
(44, 4, 4, 5, 37, 3, '2019-06-27 14:12:13', '2019-06-28 14:12:13', '2019-11-28 14:12:13', 3, '2019-06-27 15:45:45', '2019-06-27 15:46:13', 5),
(45, 4, 12, 5, 32, 4, '2019-06-27 15:47:58', '2019-06-27 15:47:58', '2019-10-27 15:47:58', 5, '2019-06-27 21:28:33', '2019-06-27 21:29:05', 5),
(46, 12, 12, 2, 74, 5, '2019-06-27 21:37:03', '2019-06-28 21:37:03', '2019-09-28 21:37:03', 4, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_id` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `nickname`, `email`, `password`, `avatar_id`) VALUES
(4, 'Paul', 'Nekrasov', 'vvestnik', 'vvestnik@vvestnik.com', '$2y$10$ej7ru83SG4Nu92qs2nQvKeCoiWS7JVm3yDR/1Hl0gGWkcUdpFMULW', 10),
(8, 'Alex', 'Smith', 'smitty', 'smitty@mail.com', '$2y$10$Knn8YGz/45XMM2F2XYZGtepqRiQhwMwc38lCeHZwGilHKlhf6LP4S', 5),
(9, 'Stan', 'Fisher', 'stanley227', 'stanley@home.com', '$2y$10$d1QxKRRH.o2kXi0hFnlOdOy8m8uGpYDQzEYn6WYEvIMOeblexANiC', 5),
(10, 'Max', 'Mustermann', 'max32', 'max32@microsoft.com', '$2y$10$OKjh/yqCHJDjv3FcDPj71OGwYg1K/FxQOn5hb7J9VMp56GujpZOKa', 5),
(11, 'Den', 'Dibney', 'den98', 'dennis98@den.de', '$2y$10$iOSJ.Aik8jAJ6N.ofZk6Qem/2/c48sgyqp4Z7siCJwOBOCIHPTdIG', 5),
(12, 'Anna', 'Crow', 'anna30', 'anna@me.com', '$2y$10$P2eteTWPE9b6msUisk7Kw.h4tQ2gN24Rj0Xg/WlkLTG.vR0wEI8ZG', 5),
(13, 'Say', 'Myname', 'boss2000', 'superboss@over9000.org', '$2y$10$0GTdjYnu5iFmUUftKL7gf.FIZJVeOeJ/C0AE45uXRcq.3/zRbP2XS', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `user_has_role`
--

CREATE TABLE `user_has_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '6'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `user_has_role`
--

INSERT INTO `user_has_role` (`user_id`, `role_id`) VALUES
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(8, 6),
(9, 3),
(9, 6),
(10, 2),
(10, 6),
(11, 1),
(11, 6),
(12, 5),
(12, 6),
(13, 1),
(13, 3),
(13, 6),
(13, 7);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `author_has_image`
--
ALTER TABLE `author_has_image`
  ADD PRIMARY KEY (`author_id`,`image_id`),
  ADD KEY `fk_author_has_image_image1_idx` (`image_id`),
  ADD KEY `fk_author_has_image_author1_idx` (`author_id`);

--
-- Индексы таблицы `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avatar_user1_idx` (`uploaded_by`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book_has_author`
--
ALTER TABLE `book_has_author`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `fk_book_has_author_author1_idx` (`author_id`),
  ADD KEY `fk_book_has_author_book1_idx` (`book_id`);

--
-- Индексы таблицы `book_has_genre`
--
ALTER TABLE `book_has_genre`
  ADD PRIMARY KEY (`book_id`,`genre_id`),
  ADD KEY `fk_book_has_genre_genre1_idx` (`genre_id`),
  ADD KEY `fk_book_has_genre_book1_idx` (`book_id`);

--
-- Индексы таблицы `book_has_image`
--
ALTER TABLE `book_has_image`
  ADD PRIMARY KEY (`book_id`,`image_id`),
  ADD KEY `fk_book_has_image_image1_idx` (`image_id`),
  ADD KEY `fk_book_has_image_book1_idx` (`book_id`);

--
-- Индексы таблицы `book_instance`
--
ALTER TABLE `book_instance`
  ADD PRIMARY KEY (`id`,`book_id`),
  ADD KEY `fk_book_instance_state1_idx` (`state_id`),
  ADD KEY `fk_book_instance_book1_idx` (`book_id`),
  ADD KEY `fk_book_instance_store1_idx` (`store_id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`,`book_id`,`user_id`),
  ADD KEY `fk_comment_book1_idx` (`book_id`),
  ADD KEY `fk_comment_user1_idx` (`user_id`);

--
-- Индексы таблицы `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_image_user1_idx` (`uploaded_by`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`,`user_id`),
  ADD KEY `fk_post_user1_idx` (`user_id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`,`user_id`),
  ADD KEY `fk_staff_user1_idx` (`user_id`),
  ADD KEY `fk_staff_store1_idx` (`store_id`);

--
-- Индексы таблицы `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`,`registered_by`,`registered_for`,`place_registered`,`book_instance_id`,`book_instance_book_id`),
  ADD KEY `fk_transaction_store1_idx` (`place_registered`),
  ADD KEY `fk_transaction_user1_idx` (`registered_by`),
  ADD KEY `fk_transaction_user2_idx` (`registered_for`),
  ADD KEY `fk_transaction_store2_idx` (`place_returned`),
  ADD KEY `fk_transaction_book_instance1_idx` (`book_instance_id`,`book_instance_book_id`),
  ADD KEY `fk_transaction_store3_idx` (`place_to_take`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_avatar1_idx` (`avatar_id`);

--
-- Индексы таблицы `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `fk_user_has_role_role1_idx` (`role_id`),
  ADD KEY `fk_user_has_role_user1_idx` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `avatar`
--
ALTER TABLE `avatar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `book_instance`
--
ALTER TABLE `book_instance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `author_has_image`
--
ALTER TABLE `author_has_image`
  ADD CONSTRAINT `fk_author_has_image_author1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_author_has_image_image1` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `avatar`
--
ALTER TABLE `avatar`
  ADD CONSTRAINT `fk_avatar_user1` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book_has_author`
--
ALTER TABLE `book_has_author`
  ADD CONSTRAINT `fk_book_has_author_author1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_book_has_author_book1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book_has_genre`
--
ALTER TABLE `book_has_genre`
  ADD CONSTRAINT `fk_book_has_genre_book1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_book_has_genre_genre1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book_has_image`
--
ALTER TABLE `book_has_image`
  ADD CONSTRAINT `fk_book_has_image_book1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_book_has_image_image1` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book_instance`
--
ALTER TABLE `book_instance`
  ADD CONSTRAINT `fk_book_instance_book1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_book_instance_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_book_instance_store1` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_book1` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_user1` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_store1` FOREIGN KEY (`store_id`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_staff_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_book_instance1` FOREIGN KEY (`book_instance_id`,`book_instance_book_id`) REFERENCES `book_instance` (`id`, `book_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_store1` FOREIGN KEY (`place_registered`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_store2` FOREIGN KEY (`place_returned`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_store3` FOREIGN KEY (`place_to_take`) REFERENCES `store` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_user1` FOREIGN KEY (`registered_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaction_user2` FOREIGN KEY (`registered_for`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_avatar1` FOREIGN KEY (`avatar_id`) REFERENCES `avatar` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `fk_user_has_role_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_has_role_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
