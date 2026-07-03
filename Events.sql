-- phpMyAdmin SQL Dump
-- version 5.2.3-1.el9
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июл 03 2026 г., 11:01
-- Версия сервера: 10.5.29-MariaDB
-- Версия PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Events`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('meropriiatiia-vep-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1780575396),
('meropriiatiia-vep-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1780575396;', 1780575396),
('meropriiatiia-vep-cache-livewire-rate-limiter:5dbfb29edad4520f18d3b62494195febc210129f', 'i:1;', 1780571840),
('meropriiatiia-vep-cache-livewire-rate-limiter:5dbfb29edad4520f18d3b62494195febc210129f:timer', 'i:1780571840;', 1780571840),
('meropriiatiia-vep-cache-livewire-rate-limiter:b921463c108fb51836e5ff816efd26efaef0bf7b', 'i:1;', 1780232189),
('meropriiatiia-vep-cache-livewire-rate-limiter:b921463c108fb51836e5ff816efd26efaef0bf7b:timer', 'i:1780232189;', 1780232189);

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `daily_start_time` time DEFAULT NULL,
  `daily_end_time` time DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','completed','archived') NOT NULL DEFAULT 'draft',
  `venue_name` varchar(255) DEFAULT NULL,
  `venue_address` varchar(255) DEFAULT NULL,
  `venue_lat` decimal(10,7) DEFAULT NULL,
  `venue_lng` decimal(10,7) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `questions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`questions`)),
  `venue_how_to_get` text DEFAULT NULL,
  `show_privacy_section` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_policy` text DEFAULT NULL,
  `personal_data_consent` text DEFAULT NULL,
  `show_personal_data_consent` tinyint(1) NOT NULL DEFAULT 0,
  `show_cookie_banner` tinyint(1) NOT NULL DEFAULT 0,
  `privacy_cookie_banner_title` varchar(255) DEFAULT NULL,
  `privacy_cookie_banner_text` text DEFAULT NULL,
  `privacy_cookie_policy` text DEFAULT NULL,
  `poster_image` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `media_image` varchar(255) DEFAULT NULL,
  `media_description` text DEFAULT NULL,
  `is_media_visible` tinyint(1) NOT NULL DEFAULT 0,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `gallery_view_count` int(11) NOT NULL DEFAULT 0,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `registration_type` enum('none','external','yandex','yandex_api') DEFAULT 'none',
  `registration_url` varchar(255) DEFAULT NULL,
  `yandex_form_url` varchar(255) DEFAULT NULL,
  `yandex_form_id` varchar(100) DEFAULT NULL,
  `is_registration_open` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `description`, `start_date`, `end_date`, `daily_start_time`, `daily_end_time`, `timezone`, `status`, `venue_name`, `venue_address`, `venue_lat`, `venue_lng`, `capacity`, `registration_deadline`, `questions`, `venue_how_to_get`, `show_privacy_section`, `privacy_policy`, `personal_data_consent`, `show_personal_data_consent`, `show_cookie_banner`, `privacy_cookie_banner_title`, `privacy_cookie_banner_text`, `privacy_cookie_policy`, `poster_image`, `logo`, `video_url`, `media_image`, `media_description`, `is_media_visible`, `gallery`, `gallery_view_count`, `social_links`, `contact_email`, `contact_phone`, `registration_type`, `registration_url`, `yandex_form_url`, `yandex_form_id`, `is_registration_open`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'IХ Всероссийский слёт молодёжи Общественной организации «Всероссийский Электропрофсоюз» ', 'ix-vserossiiskii-slet-molodezi-obshhestvennoi-organizacii-vserossiiskii-elektroprofsoiuz', '<p>«Пятый элемент: В поисках смыслов»</p>', '2026-09-28', '2026-10-03', '09:00:00', '20:00:00', 'Europe/Moscow', 'published', 'Гостиница Cosmos Stay Le Rond Сочи', 'Ленинградская улица, 7А, посёлок городского типа Дагомыс, городской округ Сочи, Краснодарский край', 43.6555540, 39.6581830, NULL, NULL, '[]', '<p><strong>На общественном транспорте, примерно, 44.2 км, 1 ч 53 мин.</strong></p><p>Международный аэропорт Сочи имени В. И. Севастьянова<br>Пешком 79 м, около 1 мин в пути.<br>39 станций до остановки «Электросети», около 1 ч 4 мин в пути.<br>Автобусы: 105, 105с<br>20 станций до остановки «Чайная фабрика», около 28 мин в пути.<br>Автобусы: 154, 169, 75, 81<br>Пешком 1,03 км, около 12 мин в пути.<br>Ленинградская улица, 7А</p>', 0, NULL, NULL, 0, 1, 'Политика использования файлов cookie', 'Мы используем файлы cookie для улучшения работы сайта, анализа трафика и показа персонализированного контента. Продолжая использовать сайт, вы соглашаетесь на сбор данных.', '<h2>Политика использования файлов cookie</h2><p><strong>1. Общие положения</strong></p><p>Настоящая Политика использования файлов cookie (далее — «Политика») регулирует порядок сбора и обработки данных с помощью файлов cookie на веб-сайте <code>events.elprof.ru</code> (далее — «Сайт»).</p><p>Используя Сайт, вы соглашаетесь с применением файлов cookie в соответствии с настоящей Политикой. Если вы не согласны с использованием cookie, вам следует изменить настройки своего браузера или прекратить использование Сайта.</p><p>---</p><p><strong>2. Что такое файлы cookie</strong></p><p>Файлы cookie — это небольшие текстовые файлы, которые веб-сервер отправляет на ваше устройство (компьютер, планшет или смартфон) при посещении Сайта. Эти файлы позволяют Сайту распознавать ваше устройство, запоминать ваши действия и предпочтения в течение определённого времени.</p><p>---</p><p><strong>3. Какие типы файлов cookie мы используем</strong></p><p>На Сайте применяются следующие категории файлов cookie:</p><p><strong>Обязательные (технические) файлы cookie</strong></p><p>Эти файлы необходимы для корректной работы Сайта. Они обеспечивают базовые функции: навигацию по страницам, заполнение форм, доступ к защищённым разделам (например, личному кабинету). Без этих файлов Сайт не может функционировать должным образом.</p><p><strong>Функциональные файлы cookie</strong></p><p>Позволяют Сайту запоминать ваш выбор и настройки: предпочитаемый язык, часовой пояс и другие параметры. Эти файлы делают использование Сайта более удобным и персонализированным.</p><p><strong>Аналитические файлы cookie</strong></p><p>Собирают обезличенную информацию о том, как посетители взаимодействуют с Сайтом: какие страницы посещают чаще всего, с каких устройств заходят, в какое время. Эти данные помогают нам анализировать работу Сайта и улучшать его.</p><p>---</p><p><strong>4. Цели использования файлов cookie</strong></p><p>Мы используем файлы cookie для:</p><p>- обеспечения стабильной и безопасной работы Сайта;</p><p>- сохранения ваших настроек и предпочтений;</p><p>- анализа посещаемости и улучшения качества услуг;</p><p>- корректной работы системы регистрации на мероприятия и восстановления билетов.</p><p>Мы не используем файлы cookie для отслеживания вашей активности на сторонних сайтах и не показываем таргетированную рекламу.</p><p>---</p><p><strong>5. Сроки хранения файлов cookie</strong></p><p>Срок хранения зависит от типа файла cookie:</p><p>- <strong>Сессионные файлы cookie</strong> удаляются автоматически после закрытия браузера.</p><p>- <strong>Постоянные файлы cookie</strong> сохраняются на устройстве до истечения установленного срока или до их удаления вами вручную.</p><p>Максимальный срок хранения постоянных файлов cookie на нашем Сайте не превышает двух лет.</p><p>---</p><p><strong>6. Передача данных третьим лицам</strong></p><p>Данные, собранные с помощью файлов cookie, могут передаваться сторонним сервисам веб-аналитики исключительно в обезличенном виде для анализа посещаемости Сайта. Передача персональных данных, позволяющих идентифицировать конкретного пользователя, третьим лицам не осуществляется.</p><p>---</p><p><strong>7. Управление файлами cookie</strong></p><p>Вы можете управлять файлами cookie через настройки своего браузера. Большинство браузеров позволяют:</p><p>- просматривать сохранённые файлы cookie;</p><p>- удалять отдельные или все файлы cookie;</p><p>- блокировать приём файлов cookie с определённых сайтов или полностью;</p><p>- настроить автоматическую очистку cookie при закрытии браузера.</p><p>Обратите внимание: отключение обязательных файлов cookie может привести к ограничению функциональности Сайта — в частности, к невозможности зарегистрироваться на мероприятие, войти в личный кабинет или восстановить билет.</p><p>При первом посещении Сайта вы видите информационное уведомление об использовании cookie. Нажимая кнопку «Принять», вы подтверждаете своё согласие на обработку данных в соответствии с настоящей Политикой.</p><p>---</p><p><strong>8. Правовые основания обработки</strong></p><p>Обработка данных с помощью файлов cookie осуществляется на основании:</p><p>- вашего явного согласия (для функциональных и аналитических cookie);</p><p>- необходимости исполнения функций Сайта (для обязательных технических cookie).</p><p>Вы вправе отозвать своё согласие в любой момент, изменив настройки браузера или удалив сохранённые файлы cookie.</p><p>---</p><p><strong>9. Изменение Политики</strong></p><p>Администрация Сайта оставляет за собой право вносить изменения в настоящую Политику. Новая редакция вступает в силу с момента её публикации на Сайте. Актуальная версия всегда доступна по адресу: <code>https://events.elprof.ru/cookie-policy</code>.</p><p>Рекомендуем периодически проверять эту страницу для ознакомления с возможными изменениями.</p><p>---</p><p><strong>10. Контактная информация</strong></p><p>Если у вас возникли вопросы относительно настоящей Политики или использования файлов cookie, вы можете связаться с нами:</p><p>- <strong>Email:</strong> <a href=\"mailto:privacy@elprof.ru\">elprof@elprof.ru</a></p><p>- <strong>Форма обратной связи:</strong> на Сайте в разделе «Контакты»</p>', 'events/posters/01KSZ5906BGAA5NWRRMBGSENVQ.png', 'events/logos/01KSZ5906CGBW9WVXM6JN37S8A.png', NULL, 'events/media/01KT1370GR0NX5CQK26S0QJJXQ.jpg', '<h2><strong>Дорогие друзья!</strong></h2><p>Сердечно приветствую вас на IХ<strong> </strong>Всероссийском молодёжном слёте Общественной организации «Всероссийский Электропрофсоюз»!</p><p style=\"text-align: justify;\">Это значимое событие не только для молодежной аудитории общественного объединения, но и всего отраслевого профсоюзного движения.</p><p style=\"text-align: justify;\">Здесь, на площадке форума, в уютном местечке Черноморского побережья собрались 120 энергичных и целеустремленных представителей молодого поколения Профсоюза. Символично, что наш форум проходит в юбилейный, 120-летний год образования отраслевого профсоюзного движения.  Это не просто совпадение, а глубокий знак преемственности, единства и сохранения традиций массового профессионального объединения.</p><p style=\"text-align: justify;\">За это время наш Профсоюз прошёл огромный путь, пережил множество испытаний и всегда оставался верным своим гуманным принципам – солидарности, справедливости и заботы о людях. Сегодня вы, молодёжь – живое продолжение этой славной истории. Каждый из вас – это новая страница в летописи нашего движения, его энергия, идеи и стремление к развитию.</p><p style=\"text-align: justify;\">Число 120 для нас сегодня – символ гармонии прошлого и будущего. Это 120 голосов, уникальных взглядов, свежих решений, которые вместе создают мощный импульс для обновления и роста. Вы те, кто завтра будет определять вектор развития отраслевого профсоюзного движения, внедрять цифровые и организационные инновации, укреплять социальные гарантии и приумножать традиции профсоюзного братства.</p><p style=\"text-align: justify;\">Особенно важно, что в нашем форуме не первый год принимают участие зарубежные друзья. Тем самым мы подчеркиваем приверженность к развитию международных контактов, в том числе на уровне молодежных отраслевых сообществ дружественных стран.</p><p style=\"text-align: justify;\">Благодарю вас за активную жизненную позицию и преданность нашему общему делу. Желаю вам насыщенной и продуктивной работы. Пусть эти дни станут для вас временем открытий, новых знакомств и полезного опыта. Уверен, что здесь вы найдёте единомышленников, обретёте уверенность в своих силах и получите заряд вдохновения. Пусть ваши инициативы будут смелыми, а решения ответственными!</p><p style=\"text-align: justify;\"></p><p style=\"text-align: justify;\">С уважением и верой в молодёжь,</p><p style=\"text-align: justify;\"><strong>Председатель Профсоюза                                           Ю.Б. Офицеров</strong></p><p><strong> </strong></p><p></p>', 1, '[\"events\\/gallery\\/01KSZA9TYKJEKEGF7DY9XATY81.jpg\",\"events\\/gallery\\/01KSZA9TYM1SERFPE49T4XQFSR.jpg\",\"events\\/gallery\\/01KSZA9TYM1SERFPE49T4XQFST.jpg\",\"events\\/gallery\\/01KSZA9TYM1SERFPE49T4XQFSS.jpg\",\"events\\/gallery\\/01KSZA9TYN10T84NGV4DP998Q2.jpg\",\"events\\/gallery\\/01KSZA9TYN10T84NGV4DP998Q6.jpg\",\"events\\/gallery\\/01KSZA9TYN10T84NGV4DP998Q4.jpg\",\"events\\/gallery\\/01KT70WPX546WA1C0FSBJZMFDZ.JPG\",\"events\\/gallery\\/01KSZA9TYN10T84NGV4DP998Q5.jpg\",\"events\\/gallery\\/01KSZA9TYM1SERFPE49T4XQFSV.jpg\",\"events\\/gallery\\/01KSZA9TYN10T84NGV4DP998Q3.jpg\",\"events\\/gallery\\/01KSZA9TYPF1B79DFQMYX6YXMD.jpg\",\"events\\/gallery\\/01KT70WPX38PZ2JY6WB2CTC1MS.JPG\",\"events\\/gallery\\/01KT70WPX4R9RZFZBZX2RTR5TF.JPG\",\"events\\/gallery\\/01KSZA9TYPF1B79DFQMYX6YXME.jpg\",\"events\\/gallery\\/01KT70WPX4R9RZFZBZX2RTR5TG.JPG\",\"events\\/gallery\\/01KSZA9TYPF1B79DFQMYX6YXMC.jpg\",\"events\\/gallery\\/01KT70WPX4R9RZFZBZX2RTR5TH.JPG\",\"events\\/gallery\\/01KT70WPX546WA1C0FSBJZMFDY.JPG\"]', 5, '[{\"platform\":\"custom\",\"url\":\"https:\\/\\/vk.com\\/electrictradeunion\",\"icon\":\"VK\"},{\"platform\":\"custom\",\"url\":\"https:\\/\\/rutube.ru\\/channel\\/26068722\\/\",\"icon\":\"RUTUBE\"},{\"platform\":\"custom\",\"url\":\"https:\\/\\/max.ru\\/id7736019571_biz\",\"icon\":\"MAX\"}]', 'elrpof@elprof.ru', '+7 (495) 938-83-78', 'yandex', NULL, '<script src=\"https://forms.yandex.ru/_static/embed.js\"></script><iframe src=\"https://forms.yandex.ru/cloud/6a14036190fa7bee223a8ecf?iframe=1\" frameborder=\"0\" name=\"ya-form-6a14036190fa7bee223a8ecf\" width=\"650\"></iframe>', '6a14036190fa7bee223a8ecf', 1, NULL, '2026-05-31 10:58:47', '2026-07-03 06:42:47', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `event_days`
--

CREATE TABLE `event_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_days`
--

INSERT INTO `event_days` (`id`, `event_id`, `date`, `label`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-28', 'сент. 28, 2026', NULL, 1, 1, '2026-05-31 13:41:59', '2026-05-31 13:43:20'),
(2, 1, '2026-09-29', 'сент. 29, 2026', 'смена образовательных площадок через ½ дня', 2, 1, '2026-05-31 13:48:47', '2026-05-31 15:33:59'),
(3, 1, '2026-09-30', 'сент. 30, 2026', 'смена образовательных площадок через ½ дня', 3, 1, '2026-05-31 15:41:37', '2026-05-31 15:41:37'),
(4, 1, '2026-10-01', 'окт. 1, 2026', NULL, 4, 1, '2026-05-31 16:21:22', '2026-05-31 16:21:22'),
(5, 1, '2026-10-02', 'окт. 2, 2026', NULL, 5, 1, '2026-05-31 16:29:00', '2026-05-31 16:29:00'),
(6, 1, '2026-10-03', 'окт. 3, 2026', NULL, 6, 1, '2026-05-31 16:34:50', '2026-05-31 16:34:50');

-- --------------------------------------------------------

--
-- Структура таблицы `event_documents`
--

CREATE TABLE `event_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_documents`
--

INSERT INTO `event_documents` (`id`, `event_id`, `title`, `file_path`, `file_type`, `sort_order`, `created_at`, `updated_at`) VALUES
(4, 1, 'Положение о Слете 2026', 'events/documents/Положение о Слете 2026.pdf', NULL, 1, '2026-06-01 05:03:23', '2026-06-04 09:15:40'),
(5, 1, 'Прил. 1 Программа Молодежного Слёта 2026', 'events/documents/Прил. 1 Программа Молодежного Слёта 2026.pdf', NULL, 2, '2026-06-01 05:03:23', '2026-06-04 09:15:40'),
(6, 1, 'Прил. 2 Памятка', 'events/documents/Прил. 2 Памятка.pdf', NULL, 3, '2026-06-01 05:03:23', '2026-06-04 09:15:40'),
(7, 1, 'Прил. 3 Оргкомитет', 'events/documents/Прил. 3 Оргкомитет.pdf', NULL, 4, '2026-06-01 05:06:04', '2026-06-04 09:15:40'),
(8, 1, 'Прил. 4  Анкета участника', 'events/documents/Прил. 4  Анкета участника.pdf', NULL, 5, '2026-06-01 05:06:04', '2026-06-04 09:15:40'),
(9, 1, 'Прил. 5 Согласие на распр ПДн', 'events/documents/Прил. 5 Согласие на распр ПДн.pdf', NULL, 6, '2026-06-01 05:06:04', '2026-06-04 09:15:40'),
(10, 1, 'Прил. 6 Согласие на обр ПДн', 'events/documents/Прил. 6 Согласие на обр ПДн .pdf', NULL, 7, '2026-06-01 05:06:04', '2026-06-04 09:15:40'),
(11, 1, 'Прил. 7 Положение о Спартакиаде', 'events/documents/Прил. 7 Положение о Спартакиаде.pdf', NULL, 8, '2026-06-01 05:06:04', '2026-06-04 09:15:40'),
(12, 1, 'Прил. 8 Положение о Конкурсе', 'events/documents/Прил. 8 Положение о Конкурсе.pdf', NULL, 9, '2026-06-01 05:06:04', '2026-06-04 09:15:40');

-- --------------------------------------------------------

--
-- Структура таблицы `event_faq`
--

CREATE TABLE `event_faq` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `faq_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_faq`
--

INSERT INTO `event_faq` (`id`, `event_id`, `faq_id`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-05-31 16:46:38', '2026-05-31 16:46:38'),
(2, 1, 2, 2, '2026-05-31 16:46:38', '2026-05-31 16:46:38');

-- --------------------------------------------------------

--
-- Структура таблицы `event_guest`
--

CREATE TABLE `event_guest` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_keynote` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_guest`
--

INSERT INTO `event_guest` (`id`, `event_id`, `guest_id`, `sort_order`, `is_visible`, `created_at`, `updated_at`, `is_keynote`) VALUES
(1, 1, 1, 1, 1, '2026-05-31 13:38:42', '2026-07-03 06:42:47', 0),
(2, 1, 2, 2, 1, '2026-05-31 13:38:42', '2026-07-03 06:42:47', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `event_speaker`
--

CREATE TABLE `event_speaker` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `speaker_id` bigint(20) UNSIGNED NOT NULL,
  `is_keynote` tinyint(1) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_speaker`
--

INSERT INTO `event_speaker` (`id`, `event_id`, `speaker_id`, `is_keynote`, `is_visible`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 1, 1, '2026-05-31 13:32:38', '2026-05-31 13:32:38'),
(2, 1, 4, 0, 1, 2, '2026-05-31 13:32:38', '2026-05-31 13:32:38'),
(3, 1, 2, 1, 1, 3, '2026-05-31 13:32:38', '2026-06-29 11:38:39'),
(4, 1, 1, 0, 1, 4, '2026-05-31 13:32:38', '2026-05-31 13:32:38');

-- --------------------------------------------------------

--
-- Структура таблицы `event_testimonial`
--

CREATE TABLE `event_testimonial` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `testimonial_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `exports`
--

CREATE TABLE `exports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `exporter` varchar(255) NOT NULL,
  `processed_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_rows` int(10) UNSIGNED NOT NULL,
  `successful_rows` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'Как зарегистрироваться на мероприятие?', '<p>Регистрация доступна на нашем сайте через форму. Заполните все обязательные поля, подтвердите email</p>', '2026-05-31 16:44:10', '2026-05-31 16:44:10'),
(2, 'Будут ли доступны записи выступлений?', '<p>Все записи будут размещены в течение месяца после мероприятия. Доступ сохраняется на 12 месяцев.</p>', '2026-05-31 16:45:10', '2026-05-31 16:45:10');

-- --------------------------------------------------------

--
-- Структура таблицы `guests`
--

CREATE TABLE `guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `guests`
--

INSERT INTO `guests` (`id`, `name`, `position`, `organization`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Юрий Офицеров', 'Председатель Общественной организации \"Всероссийский Электропрофсоюз\"', NULL, '<p></p>', 'guests/01KSZE7DXWWZY56CEREK7HH1E8.jpg', '2026-05-31 13:35:13', '2026-05-31 17:19:25'),
(2, 'Александр Мурушкин', 'Заместитель Председателя Общественной организации \"Всероссийский Электропрофсоюз\"', NULL, '<p></p>', 'guests/01KSZECTCMTX6GMRWWF1JSSKJM.jpg', '2026-05-31 13:38:10', '2026-05-31 17:19:41');

-- --------------------------------------------------------

--
-- Структура таблицы `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `background_color` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `is_button_visible` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `event_id`, `title`, `subtitle`, `image`, `background_color`, `button_text`, `button_url`, `is_button_visible`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'hero-slides/01KSZBKGBX7TPBSF6NQE2R5THP.jpg', '#0f172a', NULL, NULL, 0, 4, 1, '2026-05-31 12:49:23', '2026-05-31 18:22:28'),
(2, 1, NULL, NULL, 'hero-slides/01KSZBPM42Q0FPCAE1MYPZ7D8Z.jpg', '#0f172a', NULL, NULL, 0, 2, 1, '2026-05-31 12:51:05', '2026-05-31 12:51:05'),
(3, 1, NULL, NULL, 'hero-slides/01KSZBPM43HF53DGJJMAAY0NKT.jpg', '#0f172a', NULL, NULL, 0, 3, 1, '2026-05-31 12:51:05', '2026-05-31 12:51:05'),
(4, 1, NULL, NULL, 'hero-slides/01KSZBPM43HF53DGJJMAAY0NKV.jpg', '#0f172a', NULL, NULL, 0, 1, 1, '2026-05-31 12:51:05', '2026-05-31 18:22:28');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_29_120000_create_events_table', 1),
(5, '2026_04_29_120010_create_speakers_table', 1),
(6, '2026_04_29_120020_create_guests_table', 1),
(7, '2026_04_29_120030_create_locations_table', 1),
(8, '2026_04_29_120040_create_hero_slides_table', 1),
(9, '2026_04_29_120050_create_event_days_table', 1),
(10, '2026_04_29_120060_create_schedule_events_table', 1),
(11, '2026_04_29_120070_create_event_speaker_table', 1),
(12, '2026_04_29_120080_create_event_guest_table', 1),
(13, '2026_04_29_135600_update_speakers_table', 1),
(14, '2026_04_29_135610_update_guests_table', 1),
(15, '2026_04_29_141100_create_faqs_table', 1),
(16, '2026_04_29_141110_remove_faq_from_events_table', 1),
(17, '2026_04_29_142000_replace_location_id_with_text_in_schedule_events', 1),
(18, '2026_04_29_142010_drop_locations_table', 1),
(19, '2026_04_29_143100_remove_max_participants_from_events_table', 1),
(20, '2026_04_29_164400_create_event_faq_table', 1),
(21, '2026_04_29_164410_make_faqs_global_pool', 1),
(22, '2026_04_29_170600_add_background_color_to_hero_slides_table', 1),
(23, '2026_04_29_173900_simplify_event_speaker_and_event_guest_pivots', 1),
(24, '2026_05_25_121956_make_event_related_fields_nullable', 1),
(25, '2026_05_25_122522_make_hero_slides_image_nullable', 1),
(26, '2026_05_25_133537_add_yandex_form_url_to_events_table', 1),
(27, '2026_05_25_141012_extend_registration_type_enum', 1),
(28, '2026_05_25_141647_create_event_documents_table', 1),
(29, '2026_05_25_143042_add_manual_registration_to_events_table', 1),
(30, '2026_05_25_154720_add_icon_image_to_schedule_events_table', 1),
(31, '2026_05_25_155617_add_is_keynote_to_event_guest_table', 1),
(32, '2026_05_26_081338_remove_is_featured_from_events_table', 1),
(33, '2026_05_26_083004_update_yandex_form_field_in_events_table', 1),
(34, '2026_05_26_124741_add_is_button_visible_to_hero_slides_table', 1),
(35, '2026_05_27_120100_add_media_section_to_events_table', 1),
(36, '2026_05_27_151123_add_privacy_fields_to_events_table', 1),
(37, '2026_05_30_114936_create_testimonials_table', 1),
(38, '2026_05_30_120216_add_show_testimonials_section_to_events_table', 1),
(39, '2026_05_30_121702_create_testimonial_event_table', 1),
(40, '2026_05_30_122509_remove_show_testimonials_section_from_events_table', 1),
(41, '2026_05_30_130924_add_is_visible_to_event_guest_table', 1),
(42, '2026_05_30_132955_add_is_keynote_to_event_guest_table', 1),
(43, '2026_05_30_134207_add_is_visible_to_event_speaker_table', 1),
(44, '2026_05_30_142711_create_event_testimonial_table', 1),
(45, '2026_06_01_100000_add_schedule_performance_indexes', 2),
(46, '2026_06_10_093452_simplify_event_statuses', 3),
(47, '2026_06_11_114950_add_gallery_view_count_to_events_table', 4),
(48, '2026_06_29_000000_add_privacy_cookie_banner_text_to_events_table', 4),
(49, '2026_06_29_000001_add_show_cookie_banner_to_events_table', 4),
(50, '2026_06_29_000002_add_privacy_cookie_banner_title_to_events_table', 4),
(51, '2026_06_29_000003_add_privacy_cookie_policy_to_events_table', 4),
(52, '2026_06_29_000004_add_show_personal_data_consent_to_events_table', 4),
(53, '2026_06_30_093726_add_registration_fields_to_events_table', 5),
(54, '2026_06_30_093726_create_participants_table', 5),
(55, '2026_06_30_112803_create_permission_tables', 5),
(56, '2026_06_30_161844_extend_phone_column_in_participants_table', 6),
(57, '2026_07_01_000000_create_exports_table', 7),
(58, '2026_07_02_092815_recreate_participants_table', 8),
(59, '2026_07_02_092816_create_newsletters_table', 8),
(60, '2026_07_02_100045_recreate_participants_table', 8),
(61, '2026_07_02_100422_create_newsletters_table', 8),
(62, '2026_07_02_110903_add_event_id_to_newsletters_table', 8),
(63, '2026_07_02_114302_fix_newsletters_table_columns', 8),
(64, '2026_07_02_125504_extend_registration_type_enum', 8);

-- --------------------------------------------------------

--
-- Структура таблицы `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `newsletters`
--

CREATE TABLE `newsletters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `recipients_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `participants`
--

CREATE TABLE `participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `answer_id` varchar(255) NOT NULL,
  `checkin_token` varchar(64) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'registered',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `ticket_sent_at` timestamp NULL DEFAULT NULL,
  `souvenir_given` tinyint(1) NOT NULL DEFAULT 0,
  `documentation_given` tinyint(1) NOT NULL DEFAULT 0,
  `clothing_given` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'ViewAny:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(2, 'View:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(3, 'Create:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(4, 'Update:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(5, 'Delete:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(6, 'DeleteAny:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(7, 'Restore:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(8, 'ForceDelete:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(9, 'ForceDeleteAny:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(10, 'RestoreAny:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(11, 'Replicate:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(12, 'Reorder:EventDay', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(13, 'ViewAny:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(14, 'View:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(15, 'Create:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(16, 'Update:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(17, 'Delete:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(18, 'DeleteAny:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(19, 'Restore:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(20, 'ForceDelete:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(21, 'ForceDeleteAny:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(22, 'RestoreAny:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(23, 'Replicate:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(24, 'Reorder:Event', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(25, 'ViewAny:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(26, 'View:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(27, 'Create:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(28, 'Update:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(29, 'Delete:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(30, 'DeleteAny:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(31, 'Restore:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(32, 'ForceDelete:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(33, 'ForceDeleteAny:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(34, 'RestoreAny:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(35, 'Replicate:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(36, 'Reorder:Faq', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(37, 'ViewAny:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(38, 'View:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(39, 'Create:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(40, 'Update:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(41, 'Delete:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(42, 'DeleteAny:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(43, 'Restore:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(44, 'ForceDelete:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(45, 'ForceDeleteAny:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(46, 'RestoreAny:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(47, 'Replicate:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(48, 'Reorder:Guest', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(49, 'ViewAny:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(50, 'View:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(51, 'Create:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(52, 'Update:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(53, 'Delete:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(54, 'DeleteAny:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(55, 'Restore:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(56, 'ForceDelete:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(57, 'ForceDeleteAny:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(58, 'RestoreAny:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(59, 'Replicate:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(60, 'Reorder:Participant', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(61, 'ViewAny:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(62, 'View:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(63, 'Create:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(64, 'Update:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(65, 'Delete:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(66, 'DeleteAny:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(67, 'Restore:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(68, 'ForceDelete:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(69, 'ForceDeleteAny:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(70, 'RestoreAny:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(71, 'Replicate:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(72, 'Reorder:Role', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(73, 'ViewAny:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(74, 'View:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(75, 'Create:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(76, 'Update:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(77, 'Delete:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(78, 'DeleteAny:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(79, 'Restore:Speaker', 'web', '2026-06-30 11:26:59', '2026-06-30 11:26:59'),
(80, 'ForceDelete:Speaker', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(81, 'ForceDeleteAny:Speaker', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(82, 'RestoreAny:Speaker', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(83, 'Replicate:Speaker', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(84, 'Reorder:Speaker', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(85, 'ViewAny:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(86, 'View:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(87, 'Create:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(88, 'Update:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(89, 'Delete:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(90, 'DeleteAny:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(91, 'Restore:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(92, 'ForceDelete:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(93, 'ForceDeleteAny:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(94, 'RestoreAny:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(95, 'Replicate:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(96, 'Reorder:Testimonial', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(97, 'ViewAny:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(98, 'View:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(99, 'Create:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(100, 'Update:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(101, 'Delete:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(102, 'DeleteAny:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(103, 'Restore:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(104, 'ForceDelete:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(105, 'ForceDeleteAny:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(106, 'RestoreAny:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(107, 'Replicate:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(108, 'Reorder:User', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00'),
(109, 'View:ScheduleCalendarWidget', 'web', '2026-06-30 11:27:00', '2026-06-30 11:27:00');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-06-30 11:23:16', '2026-06-30 11:23:16');

-- --------------------------------------------------------

--
-- Структура таблицы `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `schedule_events`
--

CREATE TABLE `schedule_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_day_id` bigint(20) UNSIGNED NOT NULL,
  `speaker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_image` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `is_break` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `schedule_events`
--

INSERT INTO `schedule_events` (`id`, `event_day_id`, `speaker_id`, `start_time`, `end_time`, `title`, `description`, `location`, `icon`, `icon_image`, `color`, `is_break`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '09:00:00', '19:00:00', 'Подготовка и выдача набора участника и программы проведения Слёта. Регистрация участников', NULL, 'Зал «Меркурий»', NULL, NULL, NULL, 0, 1, '2026-05-31 13:41:59', '2026-05-31 13:41:59'),
(2, 1, NULL, '14:00:00', '00:00:00', 'Заезд участников', NULL, 'Ресепшен отеля', NULL, NULL, NULL, 0, 2, '2026-05-31 13:41:59', '2026-05-31 13:41:59'),
(3, 1, NULL, '15:00:00', '16:00:00', 'Совещание с наставниками групп Молодежного слета', NULL, 'Залы «Сатурн», «Венера», «Юпитер», Патио 1, Патио 2', NULL, NULL, NULL, 0, 3, '2026-05-31 13:41:59', '2026-05-31 13:41:59'),
(4, 1, NULL, '15:00:00', '19:00:00', 'Подготовка к презентации делегаций федеральных округов (домашняя заготовка).', NULL, 'Залы «Сатурн», «Венера», «Юпитер», Патио 1, Патио 2', NULL, NULL, NULL, 0, 4, '2026-05-31 13:43:20', '2026-05-31 13:43:20'),
(5, 1, NULL, '18:00:00', '19:00:00', 'Ужин', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 5, '2026-05-31 13:44:56', '2026-05-31 13:44:56'),
(6, 1, NULL, '19:00:00', '21:00:00', 'Открытие слета. Вечер знакомств. Презентация делегаций профсоюзной молодёжи федеральных округов. ', NULL, 'Зал «Юпитер»', NULL, NULL, NULL, 0, 6, '2026-05-31 13:45:33', '2026-05-31 13:45:33'),
(7, 2, NULL, '07:00:00', '07:30:00', 'Зарядка (по индивидуальному плану участника). ', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 1, '2026-05-31 13:48:47', '2026-05-31 13:48:47'),
(8, 2, NULL, '08:00:00', '09:30:00', 'Завтрак', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 2, '2026-05-31 13:48:47', '2026-05-31 13:48:47'),
(10, 2, 1, '09:30:00', '13:00:00', 'Образовательная площадка № 1 - Команда № 1 (ProВОДА)', '«Основы эффективных переговоров: Подготовка, стратегия, тактика, практика. Разбор и изучение успешных переговорных кейсов». ', 'Зал «Юпитер» левый сектор', NULL, NULL, NULL, 1, 3, '2026-05-31 13:58:02', '2026-05-31 18:35:02'),
(11, 2, 2, '09:00:00', '13:00:00', 'Образовательная площадка № 2 - Команда № 2 (ProДВИЖЕНИЕ)', '«Цифровая трансформация и экспансия Искусственного интел-лекта: Современные технологии и их влияния на Профсоюз».', 'Зал «Юпитер» центральный сектор', NULL, NULL, NULL, 1, 4, '2026-05-31 14:01:09', '2026-05-31 18:35:02'),
(12, 2, 4, '09:30:00', '13:00:00', 'Образовательная площадка № 3 - Команда № 3 (ProГРЭС)   ', '«Возможности и задачи Профсоюза по обеспечению техносферной безопасности в условиях новых вызовов и реалий». ', NULL, NULL, NULL, NULL, 1, 5, '2026-05-31 15:28:08', '2026-05-31 15:28:08'),
(13, 2, 5, '09:30:00', '13:00:00', 'Образовательная площадка № 4  -  Команда № 4 (ProЖАРКА)', '«Психология успеха: От идеи к реализации. Эффективная постановка целей, минимизация страхов и сомнений при их достижении».', 'Зал «Сатурн»', NULL, NULL, NULL, 0, 6, '2026-05-31 15:29:08', '2026-05-31 15:29:08'),
(14, 2, NULL, '13:00:00', '14:30:00', 'Обед', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 7, '2026-05-31 15:30:19', '2026-05-31 15:30:19'),
(15, 2, 1, '14:30:00', '18:00:00', 'Образовательная площадка №1 - Команда №2 (ProДВИЖЕНИЕ)', '«Основы эффективных переговоров: Подготовка, стратегия, тактика, практика. Разбор и изучение успешных переговорных кейсов». ', 'Зал «Юпитер» левый сектор', NULL, NULL, NULL, 0, 8, '2026-05-31 15:33:59', '2026-05-31 15:38:43'),
(16, 2, 2, '14:30:00', '18:00:00', 'Образовательная площадка №2 - Команда №3 (ProГРЭС)', '«Цифровая трансформация и экспансия Искусственного интел-лекта: Современные технологии и их влияния на Профсоюз».', 'Зал «Юпитер» центральный сектор', NULL, NULL, NULL, 0, 9, '2026-05-31 15:34:47', '2026-05-31 15:38:43'),
(17, 2, 4, '14:30:00', '18:00:00', 'Образовательная площадка №3 - Команда № 4 (ProЖАРКА)', '«Возможности и задачи Профсоюза по обеспечению техносферной безопасности в условиях новых вызовов и реалий». ', 'Зал «Юпитер» правый сектор', NULL, NULL, NULL, 1, 10, '2026-05-31 15:35:40', '2026-05-31 15:38:43'),
(18, 2, 5, '14:30:00', '18:00:00', 'Образовательная площадка №4  -  Команда №1 (ProВОДА)', '«Психология успеха: От идеи к реализации. Эффективная постановка целей, минимизация страхов и сомнений при их достижении».', 'Зал «Сатурн»', NULL, NULL, NULL, 1, 11, '2026-05-31 15:36:47', '2026-05-31 15:38:43'),
(19, 2, NULL, '18:00:00', '19:00:00', 'Время для работы над индивидуальными заданиями', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 12, '2026-05-31 15:40:16', '2026-05-31 15:40:16'),
(20, 2, NULL, '19:00:00', '21:00:00', 'Ужин', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 13, '2026-05-31 15:40:16', '2026-05-31 15:40:16'),
(21, 3, NULL, '07:00:00', '07:30:00', 'Зарядка (по индивидуальному плану участника). ', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 1, '2026-05-31 15:41:37', '2026-05-31 15:41:37'),
(22, 3, NULL, '08:00:00', '09:30:00', 'Завтрак', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 2, '2026-05-31 15:44:38', '2026-05-31 15:44:38'),
(23, 3, 1, '09:30:00', '13:00:00', 'Образовательная площадка №1 -  Команда №3 (ProГРЭС)', '«Основы эффективных переговоров: Подготовка, стратегия, тактика, практика. Разбор и изучение успешных переговорных кейсов». ', 'Зал «Юпитер» левый сектор', NULL, NULL, NULL, 0, 3, '2026-05-31 15:45:40', '2026-05-31 16:13:50'),
(24, 3, 2, '09:30:00', '13:00:00', 'Образовательная площадка №2 - Команда №4 (ProЖАРКА)', '«Цифровая трансформация и экспансия Искусственного интел-лекта: Современные технологии и их влияния на Профсоюз».', 'Зал «Юпитер» правый сектор', NULL, NULL, NULL, 0, 4, '2026-05-31 15:47:54', '2026-05-31 16:13:50'),
(25, 3, 4, '09:30:00', '13:00:00', 'Образовательная площадка №3 - Команда №1 (ProВОДА)', '«Возможности и задачи Профсоюза по обеспечению техносферной безопасности в условиях новых вызовов и реалий». ', 'Зал «Юпитер» правый сектор', NULL, NULL, NULL, 0, 5, '2026-05-31 15:47:54', '2026-05-31 16:13:50'),
(26, 3, 5, '09:30:00', '13:00:00', 'Образовательная площадка №4 - Команда №2 (ProДВИЖЕНИЕ)', '«Психология успеха: От идеи к реализации. Эффективная постановка целей, минимизация страхов и сомнений при их достижении».', 'Зал «Сатурн»', NULL, NULL, NULL, 0, 6, '2026-05-31 15:51:19', '2026-05-31 16:13:50'),
(27, 3, NULL, '13:00:00', '14:30:00', 'Обед', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 7, '2026-05-31 16:12:43', '2026-05-31 16:12:43'),
(28, 3, 1, '14:30:00', '18:00:00', 'Образовательная площадка №1 - Команда №4 (ProЖАРКА)', '«Основы эффективных переговоров: Подготовка, стратегия, тактика, практика. Разбор и изучение успешных переговорных кейсов». ', 'Зал «Юпитер» левый сектор', NULL, NULL, NULL, 0, 8, '2026-05-31 16:18:04', '2026-05-31 16:18:04'),
(29, 3, 2, '14:30:00', '18:00:00', 'Образовательная площадка №2  - Команда №1 (ProВОДА)', '«Цифровая трансформация и экспансия Искусственного интел-лекта: Современные технологии и их влияния на Профсоюз».', 'Зал «Юпитер» центральный сектор', NULL, NULL, NULL, 0, 9, '2026-05-31 16:18:04', '2026-05-31 16:18:04'),
(30, 3, 4, '14:30:00', '18:00:00', 'Образовательная площадка №3 - Команда №2 (ProДВИЖЕНИЕ)', '«Возможности и задачи Профсоюза по обеспечению техносферной безопасности в условиях новых вызовов и реалий». ', 'Зал «Юпитер» правый сектор', NULL, NULL, NULL, 0, 10, '2026-05-31 16:18:04', '2026-05-31 16:18:04'),
(31, 3, 5, '14:30:00', '18:00:00', 'Образовательная площадка №4 - Команда №3 (ProГРЭС)', '«Психология успеха: От идеи к реализации. Эффективная постановка целей, минимизация страхов и сомнений при их достижении».', 'Зал «Сатурн»', NULL, NULL, NULL, 0, 11, '2026-05-31 16:18:04', '2026-05-31 16:18:04'),
(32, 3, NULL, '18:00:00', '19:00:00', 'Время на подготовку индивидуальных заданий в группах', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 12, '2026-05-31 16:18:44', '2026-05-31 16:18:44'),
(33, 3, NULL, '19:00:00', '21:00:00', 'Ужин', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 13, '2026-05-31 16:19:18', '2026-05-31 16:19:18'),
(34, 4, NULL, '07:00:00', '07:30:00', 'Зарядка (по индивидуальному плану участника). ', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 1, '2026-05-31 16:21:22', '2026-05-31 16:21:22'),
(35, 4, NULL, '08:00:00', '09:30:00', 'Завтрак', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 2, '2026-05-31 16:22:31', '2026-05-31 16:22:31'),
(36, 4, NULL, '09:30:00', '11:00:00', 'Панельная дискуссия «Престиж-встреча: «Социальное партнерство вчера, сегодня, завтра».', NULL, 'Зал «Юпитер»', NULL, NULL, NULL, 0, 3, '2026-05-31 16:23:11', '2026-05-31 16:23:11'),
(37, 4, NULL, '11:15:00', '13:00:00', 'Творческая площадка, представление визиток участников. Презентации заданий команд', NULL, 'Зал «Юпитер»', NULL, NULL, NULL, 0, 4, '2026-05-31 16:23:45', '2026-05-31 16:23:45'),
(38, 4, NULL, '13:00:00', '14:00:00', 'Обед', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 5, '2026-05-31 16:24:30', '2026-05-31 16:24:30'),
(39, 4, NULL, '14:00:00', '14:15:00', 'Убытие участников на территорию спортивного комплекса', NULL, NULL, NULL, NULL, NULL, 0, 6, '2026-05-31 16:25:33', '2026-05-31 16:25:33'),
(40, 4, NULL, '14:30:00', '18:00:00', 'Спортивные мероприятия: мини-футбол, волейбол, плавание (эстафета)', NULL, 'Спорт. база отеля «Дагомыс»', NULL, NULL, NULL, 0, 7, '2026-05-31 16:26:27', '2026-05-31 16:26:27'),
(41, 4, NULL, '19:00:00', '23:00:00', 'Подведение итогов. Награждение победителей. Закрытие IХ ВСМ ВЭП. Товарищеский ужин', NULL, 'Зал «Юпитер»', NULL, NULL, NULL, 0, 8, '2026-05-31 16:26:59', '2026-05-31 16:26:59'),
(42, 5, NULL, '07:00:00', '07:30:00', 'Зарядка (по индивидуальному плану участника).', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 1, '2026-05-31 16:29:00', '2026-05-31 16:29:00'),
(43, 5, NULL, '08:00:00', '09:30:00', 'Завтрак', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 2, '2026-05-31 16:29:00', '2026-05-31 16:29:00'),
(44, 5, NULL, '10:00:00', '14:00:00', 'Организованный отъезд участников на экскурсию.', NULL, NULL, NULL, NULL, NULL, 0, 3, '2026-05-31 16:30:13', '2026-05-31 16:30:13'),
(45, 5, NULL, '14:00:00', '15:00:00', 'Обед ', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 4, '2026-05-31 16:32:33', '2026-05-31 16:32:33'),
(46, 5, NULL, '15:00:00', '18:00:00', 'Свободное время ', NULL, 'Территория отеля', NULL, NULL, NULL, 0, 5, '2026-05-31 16:32:33', '2026-05-31 16:32:33'),
(47, 5, NULL, '18:00:00', '21:00:00', 'Ужин', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 6, '2026-05-31 16:32:33', '2026-05-31 16:32:33'),
(48, 6, NULL, '08:00:00', '10:00:00', 'Завтрак', NULL, 'Ресторан отеля', NULL, NULL, NULL, 0, 1, '2026-05-31 16:34:50', '2026-05-31 16:34:50'),
(49, 6, NULL, '10:00:00', '12:00:00', 'Отъезд участников', NULL, NULL, NULL, NULL, NULL, 0, 2, '2026-05-31 16:34:50', '2026-05-31 16:34:50');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0l8uNwUBm3jCRCtKlghjRiWo9hQCsDuBRVmx8Jsf', NULL, '31.76.77.180', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJMNjVjZzFSMmxZZU9UR1NPV3Z2TG4wR2hnYktkSGo2QWhESGZHUk9PIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780639111),
('1SsVquh436VxXjtcGWV9WYJDZperq6widvfv6Ulk', NULL, '31.76.45.14', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiIwdlNzRjlXZ1g3VTFUc0RibkhZVUN3eHlkS1dWc1liYkp3YUZVMHhTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639008),
('3ALydu8kRs4Kv91q2IoEOLxzOJpBjB8BwsThgM10', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiJ1S05RajVSZ2VsVDdTUXd1dWhBS043Vm5YQmdaVU84a2ZBR2dBSHFuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780633869),
('3EtHifZw695ZrCI7c94elXLbkL7PUYEfC9QQCtKr', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJFa3IxckE3bDJkSm5XQzhPck9SR0VpdDFNNWxiV2tSRzY0Wk8yREdzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780637958),
('3pWMWWJV8SzSBoVKKfGd7CrJZPe6SMmgbt2vMnGz', NULL, '176.65.139.234', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI5TDhrcGFMZlphNzVneGFFbE16ZU9LZ3l6SEF4TGZSaFBPNjBudHhyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780635369),
('714b7OZnseweDbfioIlkPYPOfcI7EiLliCpVCq6Y', NULL, '144.31.153.53', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJrbzVpMGMzQkpERURrM3BlVVFTSEo0VmJUYVhraldiN2Q1TDY1NmVGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780643671),
('A6rHIAskzpJhdcQy5mwAPTJEeeVOGWqdVT44yar0', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiJ5c25RWFpnbUNQOEd4dnBzYjFEcnhucG9zcXNGSkF2UUZackREbjlOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780633881),
('acnhCfi17f8tiX7rlRB89RHArKvaOCtJzCLC7tRl', NULL, '31.76.45.14', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJQMHoyWk1ETXZlVDQ4VTdhblBmTG13S1owNjlUOVZNZnp1MGs2WkdiIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780639006),
('AIDBo3jYiRdEkV9mNgpkIeXpZrhHZfXHa9BX8hk1', NULL, '43.156.156.96', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiI0YTIxMVJtNVJZdFdGWEdCU0tqdXEydEJMYmVtalpWTWRuZjB5TVZ2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780631832),
('aJIeH6zj1h8ues4eZxxTYDBn5YbdyMW8PiLFNKQO', NULL, '3.90.220.242', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZQjRTMlJNSXY1dE1kOTk4R1A0TWhZQWdxa1RLTWFmUmRadEJyZnE4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780642523),
('apaMJ8krbOkpg49aW4rKQrF2NTyPmh2p51xpqiyK', NULL, '195.98.182.114', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwTzFDZDNpU1N4eEpWemVvQ2JMc0o3aXBDZVB0RDRBaEJGcmdaTU1tIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780644216),
('aSLcpBpcYjDkB5ZnEwomRpJ9K3tepOc8c699IAtC', NULL, '2.27.28.231', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJ1d2tab3hKa2tOMGJqekNQRE00b1VaTHBCOEtpWmlzTmRjcnd0cmZUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780634468),
('auHmMqZpQVHfGoqgXeOczIQDI8EnDYKeQfEiWJCp', NULL, '176.98.177.178', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJuaG9pVGtXdnV6TlpESWFiMldzWEZBWm85RHlNdjFhbXVwWGJHcVFBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639690),
('bbCywAv9iyfisfkc1pjYWtr8MBZuM8yuw5oHLRfg', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiJxV1NZbDNSOTBPVnhacUxjbzhGejFyWDIzUVQ0dE53b1RwbDI5U2dRIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780633870),
('bzHHUC9kBhV4wLCWkvLsO3dJgzFNz671LkebgHHj', NULL, '103.85.115.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJWajVTc1hRTWNGODVOcVpuc3NydEgyRzZaVXU0ZTYzUWRXU1pXNzlvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780640306),
('C1luhYfh7mlc13MY1Rke5uplL6B6Wz3HP5zcepVS', NULL, '195.98.182.114', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJaTGp4U2M5YnNLM1BYQjB2WE5UUHZKeURKSzhKU2FDZDBsUlIzNHNZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780644124),
('c4Rxkwdmxgj9fOvexFAgWVdi3aPeDzwgLFUsbTs1', NULL, '31.76.77.180', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJHVHduVTR4ZEcxcDdqbWxJbzNRdlc1WHp5Q2RSTVk2M0Z1V2JxN1BnIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639112),
('DNN4tqG39lVkVa1fjsU3obNw8cTcbRn6WMxt72J6', NULL, '31.76.77.180', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJnWndDOHZaS21WTU1tWDNrMmVjTHVoQ2RFVmhHWDI1V1JxOVRTUWwyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639111),
('dpdZumSoxMrqGDsdhC07YDQaWKIBShdBTJYvSOzG', NULL, '195.133.20.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJhVW1IdEdEWnU1b0pZNWVIbjgySEdVQ1dkME1QVnFFV0ljZlh5YlNxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780646492),
('e6gd62wmoRh6e6wCnXJXJ0JCQRjcUqhNY7A4VReT', NULL, '194.88.98.92', 'Mozilla/5.0 (compatible; Infrawatch/1.0; +https://infrawat.ch/)', 'eyJfdG9rZW4iOiJzdEpMRkN0MEZ2NldOS29rb3RSMGI1bWg2UVJBYTc1TUhaTDdCS3FFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780631690),
('EdwdBNyYIg22zNIBwMBoNjj84vapAC5UJ9mODCj3', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiJzZjF5ZGVtUDREOGtkWnVNaGFUOXV6cmZsVkxXZm1yczhuTGdjb3JlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780633921),
('eiAxCHqe9vlamycFpybVj6uJ314XqHt3HiTaPptc', NULL, '176.98.177.178', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJtRXVTc1VZSVB0UjZtcE85WFJ5ZHVrNzZleW13Vk9MZFhPQ2l5T1Q0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780639690),
('EWvTPUs4BboWO9R5f5gJqNt1NAZFD8WAOyvZSvGj', NULL, '195.98.182.114', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:151.0) Gecko/20100101 Firefox/151.0', 'eyJfdG9rZW4iOiJqejhoM2xvR09kcnA1VE1WWFB2Z0kyUlFTQldUVGt2c2dOVEtlTGVFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639729),
('fAy7CKCg82FPyjYmnrNLgnNWWqAMgJf3rKvcPRjA', NULL, '5.83.150.97', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJyZEJLdzBsM3FCS29MNmxOd011ODlQYVc0czBGVm9xdTBnRWVMYlpWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780634128),
('gTuV7EQnRnQb9ZtVzhDaP5FoC6P1HEUR5lgGEeLJ', NULL, '2.27.43.116', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJPNXNSZ0V3TGZkU210QUdOZVg4cGxmWWhadWF1cDhwT2tVMmN4N29EIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780631352),
('GZkB99ol2y559uVZFWBOBocQMIIoTiC668V47V3w', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJIYmZzR2VqRUgwQkZvRDFlRnkwa1VWVUJPcGFHN3hjaGVDTkhsZko4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780637952),
('h2epjkbdwT8sDLOH780LmTYHUlFTJ8IT3hpK7P9d', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJjZ2pZRlpzUmk3UTVPSXo5QU9Jekhxc2Z2bXBMVmtkdXhXcW5tZllQIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780637964),
('hELZn4zeoLOUm7R3foJdqNJZDKrVuBHcA4oR7MIt', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJoY25MdFNCTmF4cEJqM1BlNWpibzA0SmlGcTE3NFNseTh5Rmk0V2dxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780637947),
('hNd8p7kdi9lTIsYgMpDV7qUmb5aPRmif4Ck8hbsJ', NULL, '2.27.43.116', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJrNGh6Z0NkanRoZjB3c1pxd1J4bVMwclpZc1RrczJFMmg4cG1Kc1BtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780631353),
('i8XUOyQeqkkWxnAfBAEskWGxb19OmVhAGiDrXLjF', NULL, '31.76.45.14', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJ0aUtIV3ZqWXNvb1JEN3ZocFpyQnNTWWt3Z0E3SEMxS2pSMHI5WFdDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639007),
('ivfDLBakuseegDPPtDxxQQ96XRifRLTDBaINY0xW', NULL, '69.5.169.104', 'Mozilla/5.0 (compatible; Infrawatch/1.0; +https://infrawat.ch/)', 'eyJfdG9rZW4iOiJZUEozbHBCZk1ZczIzS0ZYS3JNZDlGckg3OFhYZXlFbnRlbE1xb1BXIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780633428),
('lAElJziQLGC4xYB5Q4Zk7cJF8ybL7YK4aj4vREyi', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJYUlNmbW9BVVM1cThWclRDODdOVW1HNnZQNGp1T2ttNlQ1ek8xYUFYIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780637964),
('lc9DhwHNeWIaagZ7enrkg3sDdZZXGSDgr9kwcbDp', NULL, '144.31.153.53', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiI1dGpFbFpPa2liUDcxUFlBenJJYlFVYTZBRktBcnZSc3FCYVVzVG9MIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780643670),
('mLXRBWL8z8YQzVBc3IAGvNZOaql0bqodfNIsOPWy', NULL, '195.3.135.195', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:127.0) Gecko/20100101 Firefox/127.0', 'eyJfdG9rZW4iOiJPVnZibU1qSXdqUzZNT0VhYzV3TDAwOUdEeWNNZHlJRVhBMW4wbDYwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780644577),
('NJ7GfwAcmtHuLuhncD7g6QBdugkP8lnZD0wO9gJz', NULL, '103.85.115.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJIU25tRmJadVZScGk1c05FY2hXMlJjQndTcU5PWllnM0Z1Z2N4aWk3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780640304),
('NpYZWjK0doUax7SBzP1PAOQKLy2oyozP5HaOIIZY', NULL, '2.27.28.231', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJjMng4aTVYSEJ1WXFvcHp3ZTQxYUNobkptRUxTTHlVZ3ZTeFFSSlo2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780634467),
('o71tb8D8BEJbuKhpo3buYp0xRfRU5leQwfNFKPi3', NULL, '144.31.153.53', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJYQzlrQUgzUTFuTEYxb2s5MzlHTFVPd2Q2YVV3VWNHd2lTT3BMREt2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780643670),
('Panx2DfzqlCeaIgg44eXqXP6jubygTVhj4CVOYPr', NULL, '170.106.180.246', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiI5UFlOVVUzVHVWTGg4OTljU2hHTTdiQ2lrMFJiVkZrSFZxeWJ2NUxKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780644633),
('pctyPzkrmrHkcmcowggwTE7J3wRqOhrkoqM86BaH', NULL, '45.130.213.227', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiI4bExlSGd2SU1yYnNkT2JIZzRERHBEalNmTk5UYmE0S2VtT2tHTGQ1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780632320),
('RrSRHiolSuRyw2c0rRfhVpU3n7hbld9ZTwVzIJ6u', NULL, '2.27.28.231', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJEUEx0cmk4V3Q1TzZubTFmMWVzVFVHNTRGYWZCekc3S3BpZWJwdlQxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780634467),
('RzLtKcn4igjihVU4XehN3oJ3ze9gNKxTSohCjitP', NULL, '195.133.20.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJkaVpkTUxQdTlZeUpLUlNDNU9xb1B6a1c5YTdydXhJV0JQenZYdDRtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780646492),
('T2tCV75mdxPSv2uxI2pQgxy0g9S5vIKCaDEYoaPN', NULL, '195.58.34.220', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJ6TVFBdXRVNmt5MFVGMWJ5RXU4dkp5RUtoaDJQNVJ2WE5ZSVR2V3Q0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780637944),
('t86Qh36t2q0IVhXSlqTd85GA8GojeRck0LZm9WkK', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiJnMTl6ZUh1TlFYSUlmNUdTQ0dyam1KS1VjS2E2b3loQnd4b1BPOTZGIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780633921),
('TmmrOlz3VxpqdHxpf588sCA0HYh0EdpuWvSlqKIb', NULL, '195.98.182.114', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:151.0) Gecko/20100101 Firefox/151.0', 'eyJfdG9rZW4iOiJLR0xIY2RkMjA1U0VUdjlQWWVma2RBZmxZRnBmYnNNbFdNWUUxRVdtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780647063),
('UAK7xyw20E0XVeng9TTOpUV04SCmC2BxOHYjCLgA', NULL, '5.83.150.97', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJaNVNFVzRwTjBINUJib3NqRk9Ib3JiOEtnTk5DS1liV3lxRW56RzdHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780634129),
('UJ5oIpHri4bSpUrEpiWpInry8uy1vZpj6LaQSYIs', NULL, '2.27.43.116', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJHNEZ3WUFCQjdZRlhvek84M2kxblI4WFVaMXFjZ3RWYzNYc2ZoZ1REIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780631354),
('uPKhSUAbRq7fxXRxcb2xnRr7lnLfARQwP8N2b9i6', NULL, '176.98.177.178', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJUWWVYUXVCbjMwVFdKNkkxaFI0cUVrbEZ4eW94VXRSSkJTblhsWnhjIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780639691),
('v6K3XsMzttEELwgLQY0V5sMsSiS6mTbukz4ypyMO', NULL, '49.51.132.100', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJzazZtQ0cxU0RRTWY5MmFETTVXU1pvWm9vTGVQNTVYZEREUmJ3R3pHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780644117),
('VVXiaBgzzZ9elqQQvUcriGqNxIILwpX6PzrBQj6t', NULL, '45.130.213.227', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJQN296SmtrejRrejVWbnZPd2JNRXd1dVp2M3J5dDh5dkpCSDhwVHpvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780632320),
('w1GsPvI0vUMo4E76EXpubihWObtDRZku70YwhPB3', NULL, '43.156.249.28', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'eyJfdG9rZW4iOiJ4b0hFNEd4dzlzcGJEdHo0bUFHSWk3bFFvcmMxT2tEclFSZHVyZHNKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780632043),
('w5WRGYeHyOE0X6q95KCK1w78YlAZ2NM1COY9mLmp', NULL, '45.130.213.227', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiI4SHhnc0xnTDQ3dHJYUThCZ3c5STlEYXkwREZXTU4zRWlCeVVVc1NNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780632320),
('X0au4rFmgg4lxuDXiQ6wIkFVhVOXMOwcS6b3Un8t', NULL, '195.133.20.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJOZlN2Qzk0NHlDNWhEN24wRW55T3FLVklnVGwybjJaUU1xcWo4Tkx6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780646491),
('x6gqhu4thcKKZhaW6nt0kdRzuVOpViVThdblmzmW', NULL, '103.85.115.118', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJJTzJVQVk2NzY2aDVZQ0RVZUluSm5GTkMwTTRaU2pURWdqR25neWRBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780640304),
('YJHaIa1ZeOJIuiWP6yKAz0kMiPUhBzXbq4SAyl3w', NULL, '66.228.53.46', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJSaENtZnNPZ20xRTlLN1RpSEdEeDBhSTVEd0tTQ28yRUR5c2lxZHpSIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9ldmVudHMuZWxwcm9mLnJ1Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1780634623),
('yOFqOeulzpQm3mZkFvavnwEP7qx3f8iEtKymz5Ov', NULL, '85.142.100.82', '\"Mozilla/5.0 (compatible; CyberOKInspect/1.0; +https://www.cyberok.ru/policy.html)\"', 'eyJfdG9rZW4iOiIxMlRnSHl6SFNiT2hKU0hFMEJCSUJZSjRoc1V2QW1OSEJOT3dId05EIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780633926),
('Zs9N6ASg6EUvLO5KWWhOvBF4KISzdvzaYuHpx8aW', NULL, '5.83.150.97', 'jitsi-scanner-go/1.0', 'eyJfdG9rZW4iOiJQUTVkYWkxVDlxYWNud0ZlWnRnZUFDMzBReWxmWVJ2UDVrSHVJdGI4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8xODUuNDAuMTUyLjExOSIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780634128);

-- --------------------------------------------------------

--
-- Структура таблицы `speakers`
--

CREATE TABLE `speakers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `speakers`
--

INSERT INTO `speakers` (`id`, `name`, `position`, `organization`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Свищева Елена', 'Юрист по гражданским и трудовым спорам, медиатор Липецкого областного суда', NULL, '<p><em>В период с 2011-2021 гг. - председатель профсоюзной организации, член европейского профсоюзного комитета</em></p>', 'speakers/01KT1N5YMW5NE4NP6GGKJSB1KV.jpeg', '2026-05-31 12:55:09', '2026-06-01 10:15:13'),
(2, 'Маторкин Владислав', 'Генеральный директор ООО \"КЛОНАР\" г. Москва и CLONAR LLC, Dubai. ', NULL, '<p><em>Резидент Инновационного центра Аквариум&quot;. Digital евангелист. Создатель первой в России системы цифровых продавцов. Выпускник Кубанского IT-акселератора, г. Краснодар</em></p>', 'speakers/01KT1N2QYGG6816T58Q9VSYJHJ.jpg', '2026-05-31 12:56:22', '2026-06-01 10:13:28'),
(4, 'Дмитрий Васильев', 'Основатель Центра Техносферной Безопасности «ПРОФИ». ', NULL, '<p><em>Бизнес-консультант по работе с контролирующими органами. Трекер стартапов по треку образование. Старший преподаватель КубГТУ. Аккредитованный эксперт Министерства труда и социального развития.</em></p>', 'speakers/01KSZRSDTJ7NF8B14NDB1XED06.jpg', '2026-05-31 12:57:21', '2026-05-31 16:39:48'),
(5, 'Аида Гамидова', 'Учредитель и директор АНО «Махачкалинский центр НЛП».', NULL, '<p></p>', 'speakers/01KSZRRAD63GWMM80SQ5BJ1VGA.jpg', '2026-05-31 12:57:51', '2026-05-31 16:39:12');

-- --------------------------------------------------------

--
-- Структура таблицы `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `testimonial_event`
--

CREATE TABLE `testimonial_event` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `testimonial_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'pavel.a.klimov', 'pavel.a.klimov@elprof.ru', NULL, '$2y$12$xfhrNWSI5OS.5ltLFzTcMO5IBNDWzGoGhRr4xrBmAVZCg9Z0UyKIm', 'U2ds3vGeXmF02JDdoYDKObj99bap8SZOR5opYQAxAelbxT8CzHULGzUPUyB7', '2026-05-31 09:14:59', '2026-05-31 09:14:59'),
(3, 'PashGUN', 'pavelask@mail.ru', NULL, '$2y$12$uz6Sq1twFCwX7htkawUfJeYK.6rwndOpIPJ5E/B06kKfEQIaSb.w.', NULL, '2026-06-30 13:19:23', '2026-06-30 13:19:37');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`),
  ADD KEY `events_created_by_foreign` (`created_by`),
  ADD KEY `events_status_start_date_end_date_index` (`status`,`start_date`,`end_date`);

--
-- Индексы таблицы `event_days`
--
ALTER TABLE `event_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_days_event_id_date_unique` (`event_id`,`date`),
  ADD KEY `event_days_event_sort_index` (`event_id`,`sort_order`),
  ADD KEY `event_days_event_date_index` (`event_id`,`date`),
  ADD KEY `event_days_active_index` (`is_active`);

--
-- Индексы таблицы `event_documents`
--
ALTER TABLE `event_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_documents_event_id_foreign` (`event_id`);

--
-- Индексы таблицы `event_faq`
--
ALTER TABLE `event_faq`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_faq_event_id_faq_id_unique` (`event_id`,`faq_id`),
  ADD KEY `event_faq_faq_id_foreign` (`faq_id`);

--
-- Индексы таблицы `event_guest`
--
ALTER TABLE `event_guest`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_guest_event_id_guest_id_unique` (`event_id`,`guest_id`),
  ADD KEY `event_guest_guest_id_foreign` (`guest_id`);

--
-- Индексы таблицы `event_speaker`
--
ALTER TABLE `event_speaker`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `event_speaker_event_id_speaker_id_unique` (`event_id`,`speaker_id`),
  ADD KEY `event_speaker_speaker_id_foreign` (`speaker_id`);

--
-- Индексы таблицы `event_testimonial`
--
ALTER TABLE `event_testimonial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_testimonial_event_id_foreign` (`event_id`),
  ADD KEY `event_testimonial_testimonial_id_foreign` (`testimonial_id`);

--
-- Индексы таблицы `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exports_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hero_slides_event_id_foreign` (`event_id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Индексы таблицы `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Индексы таблицы `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `newsletters_event_id_foreign` (`event_id`);

--
-- Индексы таблицы `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `participants_checkin_token_unique` (`checkin_token`),
  ADD KEY `participants_event_id_status_index` (`event_id`,`status`),
  ADD KEY `participants_answer_id_index` (`answer_id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Индексы таблицы `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Индексы таблицы `schedule_events`
--
ALTER TABLE `schedule_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_events_day_sort_time_index` (`event_day_id`,`sort_order`,`start_time`),
  ADD KEY `schedule_events_speaker_index` (`speaker_id`),
  ADD KEY `schedule_events_day_time_index` (`event_day_id`,`start_time`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `speakers`
--
ALTER TABLE `speakers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonials_event_id_foreign` (`event_id`);

--
-- Индексы таблицы `testimonial_event`
--
ALTER TABLE `testimonial_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonial_event_testimonial_id_foreign` (`testimonial_id`),
  ADD KEY `testimonial_event_event_id_foreign` (`event_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `event_days`
--
ALTER TABLE `event_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `event_documents`
--
ALTER TABLE `event_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `event_faq`
--
ALTER TABLE `event_faq`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `event_guest`
--
ALTER TABLE `event_guest`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `event_speaker`
--
ALTER TABLE `event_speaker`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `event_testimonial`
--
ALTER TABLE `event_testimonial`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `exports`
--
ALTER TABLE `exports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT для таблицы `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `participants`
--
ALTER TABLE `participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `schedule_events`
--
ALTER TABLE `schedule_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT для таблицы `speakers`
--
ALTER TABLE `speakers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `testimonial_event`
--
ALTER TABLE `testimonial_event`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `event_days`
--
ALTER TABLE `event_days`
  ADD CONSTRAINT `event_days_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event_documents`
--
ALTER TABLE `event_documents`
  ADD CONSTRAINT `event_documents_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event_faq`
--
ALTER TABLE `event_faq`
  ADD CONSTRAINT `event_faq_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_faq_faq_id_foreign` FOREIGN KEY (`faq_id`) REFERENCES `faqs` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event_guest`
--
ALTER TABLE `event_guest`
  ADD CONSTRAINT `event_guest_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_guest_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event_speaker`
--
ALTER TABLE `event_speaker`
  ADD CONSTRAINT `event_speaker_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_speaker_speaker_id_foreign` FOREIGN KEY (`speaker_id`) REFERENCES `speakers` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `event_testimonial`
--
ALTER TABLE `event_testimonial`
  ADD CONSTRAINT `event_testimonial_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_testimonial_testimonial_id_foreign` FOREIGN KEY (`testimonial_id`) REFERENCES `testimonials` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `exports`
--
ALTER TABLE `exports`
  ADD CONSTRAINT `exports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD CONSTRAINT `hero_slides_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `newsletters`
--
ALTER TABLE `newsletters`
  ADD CONSTRAINT `newsletters_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `schedule_events`
--
ALTER TABLE `schedule_events`
  ADD CONSTRAINT `schedule_events_event_day_id_foreign` FOREIGN KEY (`event_day_id`) REFERENCES `event_days` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_events_speaker_id_foreign` FOREIGN KEY (`speaker_id`) REFERENCES `speakers` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `testimonial_event`
--
ALTER TABLE `testimonial_event`
  ADD CONSTRAINT `testimonial_event_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `testimonial_event_testimonial_id_foreign` FOREIGN KEY (`testimonial_id`) REFERENCES `testimonials` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
