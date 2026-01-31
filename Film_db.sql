-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : sam. 31 jan. 2026 à 20:15
-- Version du serveur : 11.8.5-MariaDB-ubu2404
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Film_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `FAVORIS`
--

CREATE TABLE `FAVORIS` (
  `IdFavori` int(11) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdFilm` int(11) NOT NULL,
  `DateAjout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `FILMS`
--

CREATE TABLE `FILMS` (
  `IdFilm` int(11) NOT NULL,
  `Titre` varchar(255) NOT NULL,
  `Annee` smallint(6) NOT NULL,
  `Duree` smallint(6) NOT NULL COMMENT 'Durée en minutes',
  `Synopsis` text DEFAULT NULL,
  `PrixLocationDefault` decimal(5,2) NOT NULL,
  `CheminAffiche` varchar(500) DEFAULT NULL,
  `Note` decimal(2,1) DEFAULT 3.5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `FILMS`
--

INSERT INTO `FILMS` (`IdFilm`, `Titre`, `Annee`, `Duree`, `Synopsis`, `PrixLocationDefault`, `CheminAffiche`, `Note`) VALUES
(1, 'Inception', 2010, 148, 'Dom Cobb est un voleur expérimenté dans l\'art dangereux de l\'extraction : le fait de s\'infiltrer dans le subconscient pendant l\'état de sommeil.', 4.50, '/images/inception.jpg', 4.8),
(2, 'The Dark Knight', 2008, 152, 'Batman relève le plus grand défi de sa vie en affrontant le Joker, un criminel psychotique qui sème la terreur et le chaos à Gotham City.', 3.99, '/images/the_dark_knight.jpg', 4.9),
(3, 'Interstellar', 2014, 169, 'Un groupe d\'explorateurs voyage à travers un trou de ver dans l\'espace pour trouver une nouvelle planète habitable pour l\'humanité.', 4.20, '/images/interstellar.jpg', 4.6),
(4, 'The Shawshank Redemption', 1994, 142, 'Un bancier est condamné à la prison à vie et se lie d\'amitié avec un autre détenu, trouvant rédemption et espoir à travers des actes de décence commune.', 2.99, '/images/the_shawshank_redemption.jpg', 4.9),
(5, 'Pulp Fiction', 1994, 154, 'Les vies de deux tueurs à gages, d\'un boxeur, d\'un gangster et de sa femme s\'entrecroisent dans une série d\'histoires violentes et inattendues.', 3.50, '/images/pulp_fiction.jpg', 4.7),
(6, 'La La Land', 2016, 128, 'Une romance entre une actrice en herbe et un pianiste de jazz qui luttent pour concilier leurs ambitions artistiques avec leur relation.', 3.80, '/images/la_la_land.jpg', 4.3),
(7, 'Parasite', 2019, 132, 'Une famille pauvre s\'infiltre chez une famille riche en se faisant passer pour des personnes hautement qualifiées.', 4.10, '/images/parasite.jpg', 4.8),
(8, 'Avengers: Endgame', 2019, 182, 'Les Avengers tentent de restaurer l\'univers après les événements dévastateurs d\'Infinity War.', 4.99, '/images/avengers_endgame.jpg', 4.5),
(9, 'Le Parrain', 1972, 175, 'Le patriarche vieillissant d\'une dynastie criminelle transfère le contrôle de son empire clandestin à son fils réticent.', 3.75, '/images/le_parrain.jpg', 4.9),
(10, 'Forrest Gump', 1994, 142, 'L\'histoire d\'un homme au QI limité qui, sans le vouloir, influence certains des événements les plus importants du XXe siècle.', 3.25, '/images/forrest_gump.jpg', 4.8),
(11, 'Gladiator', 2000, 155, 'Un général romain trahi devient gladiateur et cherche à se venger de l\'empereur qui a assassiné sa famille.', 3.75, '/images/gladiator.jpg', 4.7),
(12, 'Mad Max: Fury Road', 2015, 120, 'Dans un monde post-apocalyptique, Max aide une rebelle à échapper à un tyran avec cinq de ses servantes.', 4.20, '/images/mad_max_fury_road.jpg', 4.6),
(13, 'John Wick', 2014, 101, 'Un ancien tueur à gages sort de sa retraite pour se venger des hommes qui ont tué son chien.', 3.99, '/images/john_wick.jpg', 4.4),
(14, 'Die Hard', 1988, 132, 'Un policier de New York tente de sauver sa femme lors d\'une prise d\'otages dans un gratte-ciel de Los Angeles.', 2.99, '/images/die_hard.jpg', 4.5),
(15, 'Mission: Impossible', 1996, 110, 'Un agent du IMF est accusé de trahison et doit découvrir le véritable traître.', 3.25, '/images/mission_impossible.jpg', 4.2),
(16, 'The Matrix', 1999, 136, 'Un hacker découvre que sa réalité est une simulation créée par des machines intelligentes.', 4.00, '/images/the_matrix.jpg', 4.8),
(17, 'Terminator 2: Le jugement dernier', 1991, 137, 'Un cyborg est envoyé du futur pour protéger un jeune garçon d\'un autre cyborg plus avancé.', 3.80, '/images/terminator_2_judgment_day.jpg', 4.7),
(18, 'Indiana Jones et les Aventuriers de l\'Arche perdue', 1981, 115, 'Un archéologue aventurier tente de mettre la main sur l\'Arche d\'Alliance avant les nazis.', 3.50, '/images/indiana_jones_et_les_aventuriers_de_l_arche_perdue.jpg', 4.6),
(19, 'Les gardiens de la galaxie', 2014, 121, 'Un groupe de criminels intergalactiques doivent s\'unir pour sauver la galaxie.', 4.10, '/images/guardians_of_the_galaxy.jpg', 4.5),
(20, 'Spider-Man: Into the Spider-Verse', 2018, 117, 'Un adolescent devient Spider-Man et rencontre d\'autres versions de lui-même d\'autres dimensions.', 4.30, '/images/spider_man_into_the_spider_verse.jpg', 4.9),
(21, 'The Social Network', 2010, 120, 'L\'histoire de la création de Facebook et des conflits qui ont suivi.', 3.65, '/images/the_social_network.jpg', 4.4),
(22, 'Le Discours d\'un Roi', 2010, 118, 'L\'histoire du roi George VI qui surmonte son bégaiement avec l\'aide d\'un orthophoniste.', 3.40, '/images/le_discours_d_un_roi.jpg', 4.5),
(23, 'Le livre de Green', 2018, 130, 'Un videur italo-américain sert de chauffeur à un pianiste afro-américain lors d\'une tournée dans le Sud en 1962.', 3.90, '/images/green_book.jpg', 4.6),
(24, 'Dunkerque', 2017, 106, 'Des soldats alliés sont piégés sur la plage de Dunkerque pendant la Seconde Guerre mondiale.', 3.75, '/images/dunkerque.jpg', 4.3),
(25, 'Le Labyrinthe de Pan', 2006, 118, 'En 1944, une jeune fille découvre un monde fantastique tout en vivant sous le régime franquiste.', 3.60, '/images/le_labyrinthe_de_pan.jpg', 4.7),
(26, 'Slumdog Millionaire', 2008, 120, 'Un jeune homme des bidonvilles de Mumbai participe à Qui veut gagner des millions ? et est accusé de tricherie.', 3.45, '/images/slumdog_millionaire.jpg', 4.5),
(27, 'La Vie est belle', 1997, 116, 'Un père utilise son imagination pour protéger son fils de l\'horreur d\'un camp de concentration.', 3.20, '/images/la_vie_est_belle.jpg', 4.9),
(28, 'Le Pianiste', 2002, 150, 'Un pianiste juif polonais survit à l\'Holocauste pendant la Seconde Guerre mondiale.', 3.85, '/images/le_pianiste.jpg', 4.8),
(29, 'Philadelphia', 1993, 125, 'Un avocat atteint du SIDA poursuit son cabinet pour discrimination.', 3.10, '/images/philadelphia.jpg', 4.3),
(30, 'Rain Man', 1988, 133, 'Un homme découvre qu\'il a un frère autiste savant dont il a hérité après la mort de son père.', 3.30, '/images/rain_man.jpg', 4.4),
(31, 'Les Visiteurs', 1993, 107, 'Un chevalier et son écuyer voyagent dans le temps et se retrouvent dans la France du XXe siècle.', 2.99, '/images/les_visiteurs.jpg', 4.2),
(32, 'Le Père Noël est une ordure', 1982, 92, 'Les bénévoles d\'une association d\'aide téléphonique passent une nuit de Noël mouvementée.', 2.75, '/images/le_pere_noel_est_une_ordure.jpg', 4.8),
(33, 'Bienvenue chez les Ch\'tis', 2008, 106, 'Un postier du Sud de la France est muté dans le Nord et découvre une culture différente.', 3.25, '/images/bienvenue_chez_les_ch_tis.jpg', 4.1),
(34, 'The Grand Budapest Hotel', 2014, 99, 'Les aventures de Gustave H, concierge d\'un célèbre hôtel européen, et de son protégé Zero.', 3.80, '/images/the_grand_budapest_hotel.jpg', 4.6),
(35, 'Superbad', 2007, 113, 'Deux amis du lycée tentent d\'acheter de l\'alcool pour une fête avant d\'entrer à l\'université.', 3.15, '/images/superbad.jpg', 4.2),
(36, 'Borat', 2006, 84, 'Un journaliste kazakh voyage aux États-Unis pour faire un documentaire sur la culture américaine.', 3.40, '/images/borat.jpg', 4.3),
(37, 'The Hangover', 2009, 100, 'Trois amis se réveillent après une enterrement de vie de garçon à Las Vegas sans souvenir de la nuit.', 3.50, '/images/the_hangover.jpg', 4.1),
(38, 'Zoolander', 2001, 89, 'Un mannequin vieillissant est manipulé pour assassiner le Premier ministre de Malaisie.', 2.90, '/images/zoolander.jpg', 4.0),
(39, 'Dumb and Dumber', 1994, 107, 'Deux amis simples d\'esprit voyagent à travers le pays pour rendre une valise pleine d\'argent.', 2.85, '/images/dumb_and_dumber.jpg', 4.0),
(40, 'Mean Girls', 2004, 97, 'Une étudiante nouvellement arrivée s\'intègre dans un lycée et découvre la hiérarchie sociale complexe.', 3.20, '/images/mean_girls.jpg', 4.2),
(41, 'Blade Runner 2049', 2017, 164, 'Un jeune Blade Runner découvre un secret enfoui depuis longtemps qui pourrait plonger la société dans le chaos.', 4.25, '/images/blade_runner_2049.jpg', 4.5),
(42, 'Arrival', 2016, 116, 'Une linguiste travaille avec l\'armée pour communiquer avec des extraterrestres qui ont atterri sur Terre.', 4.00, '/images/arrival.jpg', 4.6),
(43, 'Dune', 2021, 155, 'Paul Atreides se rend sur la planète désertique d\'Arrakis, la seule source de la substance la plus précieuse de l\'univers.', 4.50, '/images/dune.jpg', 4.4),
(44, 'E.T. l\'extra-terrestre', 1982, 115, 'Un garçon se lie d\'amitié avec un extraterrestre échoué sur Terre et l\'aide à rentrer chez lui.', 3.60, '/images/e_t_l_extra_terrestre.jpg', 4.7),
(45, 'Star Wars: Un nouvel espoir', 1977, 121, 'Luke Skywalker rejoint la Rébellion pour aider à détruire l\'Étoile de la Mort.', 3.95, '/images/star_wars_un_nouvel_espoir.jpg', 4.8),
(46, 'Avatar', 2009, 162, 'Un marine paraplégique est envoyé sur la planète Pandora dans un corps d\'avatar.', 4.40, '/images/avatar.jpg', 4.5),
(47, 'Back to the Future', 1985, 116, 'Un adolescent voyage accidentellement dans le temps de 30 ans en arrière.', 3.75, '/images/back_to_the_future.jpg', 4.8),
(48, 'The Thing', 1982, 109, 'Des chercheurs en Antarctique sont terrorisés par une forme de vie extraterrestre qui les imite.', 3.65, '/images/the_thing.jpg', 4.6),
(49, 'District 9', 2009, 112, 'Un extraterrestre devient amis avec un humain pendant une opération de relocalisation forcée.', 3.90, '/images/district_9.jpg', 4.4),
(50, 'Alien', 1979, 117, 'L\'équipage d\'un vaisseau spatial est attaqué par une créature extraterrestre mortelle.', 3.80, '/images/alien.jpg', 4.7),
(51, 'Seven', 1995, 127, 'Deux détectives traquent un tueur en série qui utilise les sept péchés capitaux comme modus operandi.', 3.85, '/images/seven.jpg', 4.7),
(52, 'The Silence of the Lambs', 1991, 118, 'Une jeune agent du FBI consulte un psychopathe emprisonné pour attraper un autre tueur en série.', 3.90, '/images/the_silence_of_the_lambs.jpg', 4.8),
(53, 'Gone Girl', 2014, 149, 'Un homme devient le principal suspect lorsque sa femme disparaît le jour de leur anniversaire de mariage.', 4.10, '/images/gone_girl.jpg', 4.5),
(54, 'Shutter Island', 2010, 138, 'Deux marshals enquêtent sur la disparition d\'un meurtrier dans un hôpital psychiatrique sur une île.', 3.95, '/images/shutter_island.jpg', 4.6),
(55, 'Memento', 2000, 113, 'Un homme souffrant d\'amnésie antérograde tente de retrouver le meurtrier de sa femme.', 3.70, '/images/memento.jpg', 4.8),
(56, 'Les infiltrés', 2006, 151, 'Un policier infiltré et un indic dans la police tentent de s\'identifier mutuellement.', 4.00, '/images/the_departed.jpg', 4.7),
(57, 'L.A. Confidential', 1997, 138, 'Trois policiers de Los Angeles enquêtent sur une série de meurtres dans les années 1950.', 3.65, '/images/l_a_confidential.jpg', 4.6),
(58, 'Heat', 1995, 170, 'Un détective traque un voleur professionnel déterminé lors d\'une dernière opération.', 3.80, '/images/heat.jpg', 4.7),
(59, 'Oldboy', 2003, 120, 'Un homme est séquestré pendant 15 ans sans explication, puis libéré et doit trouver son geôlier.', 4.15, '/images/oldboy.jpg', 4.8),
(60, 'Zodiac', 2007, 157, 'L\'enquête sur le tueur du Zodiaque qui a terrorisé la région de la baie de San Francisco.', 3.90, '/images/zodiac.jpg', 4.5),
(61, 'Le Voyage de Chihiro', 2001, 125, 'Une jeune fille entre dans un monde peuplé d\'esprits et doit travailler dans un bain public pour sauver ses parents.', 3.95, '/images/le_voyage_de_chihiro.jpg', 4.9),
(62, 'Toy Story', 1995, 81, 'Les jouets d\'un garçon prennent vie lorsqu\'il n\'est pas là, et un nouveau jouet menace leur hiérarchie.', 3.45, '/images/toy_story.jpg', 4.8),
(63, 'Le Roi Lion', 1994, 88, 'Un jeune lion prince fuit son royaume après la mort de son père, mais retourne pour réclamer son trône.', 3.60, '/images/le_roi_lion.jpg', 4.7),
(64, 'Coco', 2017, 105, 'Un jeune garçon rêve de devenir musicien et voyage dans le Pays des Morts.', 3.90, '/images/coco.jpg', 4.8),
(65, 'Vice-Versa', 2015, 95, 'Les émotions d\'une jeune fille se battent pour contrôler son comportement alors qu\'elle déménage.', 3.80, '/images/vice_versa.jpg', 4.6),
(66, 'Your Name', 2016, 107, 'Deux adolescents découvrent qu\'ils échangent leurs corps et cherchent à se rencontrer.', 4.20, '/images/your_name.jpg', 4.9),
(67, 'Les Indestructibles', 2004, 115, 'Une famille de super-héros forcée de vivre une vie normale se bat contre un ancien fan.', 3.70, '/images/les_indestructibles.jpg', 4.5),
(68, 'Shrek', 2001, 90, 'Un ogre solitaire voit sa marécage envahi par des personnages de contes de fées déportés.', 3.40, '/images/shrek.jpg', 4.6),
(69, 'Wall-E', 2008, 98, 'Dans un futur lointain, un petit robot nettoie une Terre déserte et tombe amoureux.', 3.85, '/images/wall_e.jpg', 4.8),
(70, 'Le Tombeau des lucioles', 1988, 89, 'Un jeune garçon et sa petite sœur luttent pour survivre au Japon pendant la Seconde Guerre mondiale.', 3.50, '/images/le_tombeau_des_lucioles.jpg', 4.9),
(71, 'La liste de Schindler', 1993, 195, 'Un homme d\'affaires allemand sauve plus de mille réfugiés juifs pendant l\'Holocauste.', 4.00, '/images/schindler_s_list.jpg', 4.9),
(72, 'Le Dernier Samouraï', 2003, 154, 'Un capitaine de l\'armée américaine est capturé par des samouraïs au Japon de l\'ère Meiji.', 3.75, '/images/le_dernier_samourai.jpg', 4.3),
(73, 'Braveheart', 1995, 178, 'William Wallace mène une révolte écossaise contre le roi d\'Angleterre Édouard Ier.', 3.85, '/images/braveheart.jpg', 4.5),
(74, 'Le Dernier Empereur', 1987, 163, 'La vie de Puyi, le dernier empereur de Chine, de son ascension à son déclin.', 3.60, '/images/le_dernier_empereur.jpg', 4.4),
(75, 'Lincoln', 2012, 150, 'Le président Abraham Lincoln travaille à faire adopter le 13e amendement abolissant l\'esclavage.', 3.70, '/images/lincoln.jpg', 4.3),
(76, 'The Imitation Game', 2014, 114, 'Alan Turing et son équipe tentent de craquer le code Enigma des nazis pendant la Seconde Guerre mondiale.', 3.80, '/images/the_imitation_game.jpg', 4.5),
(77, 'A Beautiful Mind', 2001, 135, 'L\'histoire du mathématicien John Forbes Nash Jr. et de sa lutte contre la schizophrénie.', 3.65, '/images/a_beautiful_mind.jpg', 4.4),
(78, 'The Theory of Everything', 2014, 123, 'L\'histoire de Stephen Hawking, son diagnostic de maladie et sa relation avec sa femme.', 3.75, '/images/the_theory_of_everything.jpg', 4.3),
(79, 'Milk', 2008, 128, 'L\'histoire de Harvey Milk, le premier homme ouvertement gay élu à une fonction publique en Californie.', 3.50, '/images/milk.jpg', 4.2),
(80, '12 Years a Slave', 2013, 134, 'Un homme noir libre est kidnappé et vendu comme esclave dans le Sud américain avant la guerre de Sécession.', 3.85, '/images/12_years_a_slave.jpg', 4.8),
(81, 'The Shining', 1980, 146, 'Un écrivain devient le gardien d\'un hôtel isolé où des forces surnaturelles l\'influencent.', 3.70, '/images/the_shining.jpg', 4.7),
(82, 'Get Out', 2017, 104, 'Un jeune homme afro-américain visite la famille de sa petite amie blanche et découvre un secret terrifiant.', 4.00, '/images/get_out.jpg', 4.6),
(83, 'Hereditary', 2018, 127, 'Une famille est hantée après la mort de leur grand-mère secrète.', 4.10, '/images/hereditary.jpg', 4.5),
(84, 'The Exorcist', 1973, 122, 'Une mère désespérée demande l\'aide de deux prêtres lorsque sa fille est possédée.', 3.60, '/images/the_exorcist.jpg', 4.8),
(85, 'A Quiet Place', 2018, 90, 'Une famille doit vivre en silence pour éviter des créatures qui chassent au son.', 4.00, '/images/a_quiet_place.jpg', 4.5),
(86, 'The Conjuring', 2013, 112, 'Des enquêteurs paranormaux aident une famille hantée par une présence maléfique dans leur ferme.', 3.90, '/images/the_conjuring.jpg', 4.5),
(87, 'Psycho', 1960, 109, 'Une secrétaire vole de l\'argent et se réfugie dans un motel isolé géré par un homme étrange.', 3.30, '/images/psycho.jpg', 4.7),
(88, 'Mister Babadook', 2014, 93, 'Une mère veuve et son fils sont hantés par une créature d\'un livre d\'images.', 3.70, '/images/the_babadook.jpg', 4.5),
(89, 'Ca', 2017, 135, 'Des enfants affrontent un clown maléfique qui se nourrit de leurs peurs.', 4.20, '/images/it.jpg', 4.6),
(90, 'Scream', 1996, 111, 'Un tueur masqué terrorise un lycée en utilisant des références aux films d\'horreur.', 3.50, '/images/scream.jpg', 4.3),
(91, 'Il était une fois dans l\'Ouest', 1968, 165, 'Des histoires s\'entrecroisent autour d\'un mystérieux étranger et d\'une veuve défendant son terrain.', 3.55, '/images/il_etait_une_fois_dans_l_ouest.jpg', 4.8),
(92, 'Django Unchained', 2012, 165, 'Un chasseur de primes et un esclave affranchi traquent des criminels dans le Sud américain avant la guerre de Sécession.', 4.10, '/images/django_unchained.jpg', 4.6),
(93, 'The Good, the Bad and the Ugly', 1966, 178, 'Trois hommes recherchent un trésor caché pendant la guerre de Sécession.', 3.80, '/images/the_good_the_bad_and_the_ugly.jpg', 4.9),
(94, 'True Grit', 2010, 110, 'Une jeune fille engage un marshal pour retrouver l\'homme qui a tué son père.', 3.65, '/images/true_grit.jpg', 4.3),
(95, 'No Country for Old Men', 2007, 122, 'Un chasseur trouve deux millions de dollars après une transaction de drogue qui a mal tourné.', 3.90, '/images/no_country_for_old_men.jpg', 4.8),
(96, 'Butch Cassidy and the Sundance Kid', 1969, 110, 'Deux hors-la-loi du Far West fuient vers la Bolivie après une série de braquages.', 3.40, '/images/butch_cassidy_and_the_sundance_kid.jpg', 4.4),
(97, 'Unforgiven', 1992, 130, 'Un ancien hors-la-loi reprend ses armes pour une dernière prime.', 3.70, '/images/unforgiven.jpg', 4.7),
(98, 'The Revenant', 2015, 156, 'Un trappeur est laissé pour mort par ses compagnons et survit pour se venger.', 4.15, '/images/the_revenant.jpg', 4.5),
(99, '3:10 pour Yuma', 2007, 122, 'Un propriétaire terrien appauvri accepte d\'escorter un célèbre hors-la-loi jusqu\'au train pour Yuma.', 3.60, '/images/3_10_to_yuma.jpg', 4.2),
(100, 'Titanic', 1997, 195, 'Une romance entre deux passagers de différentes classes sociales à bord du RMS Titanic.', 4.25, '/images/titanic.jpg', 4.8);

-- --------------------------------------------------------

--
-- Structure de la table `FILM_GENRE`
--

CREATE TABLE `FILM_GENRE` (
  `IdFilm` int(11) NOT NULL,
  `IdGenre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `FILM_GENRE`
--

INSERT INTO `FILM_GENRE` (`IdFilm`, `IdGenre`) VALUES
(2, 1),
(8, 1),
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
(45, 1),
(58, 1),
(67, 1),
(72, 1),
(73, 1),
(20, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(8, 3),
(11, 3),
(12, 3),
(18, 3),
(19, 3),
(20, 3),
(43, 3),
(45, 3),
(46, 3),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(65, 3),
(68, 3),
(94, 3),
(98, 3),
(5, 4),
(7, 4),
(19, 4),
(31, 4),
(32, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(47, 4),
(62, 4),
(65, 4),
(67, 4),
(68, 4),
(96, 4),
(10, 5),
(23, 5),
(27, 5),
(30, 5),
(6, 6),
(2, 8),
(3, 8),
(4, 8),
(7, 8),
(9, 8),
(10, 8),
(21, 8),
(22, 8),
(23, 8),
(24, 8),
(25, 8),
(26, 8),
(27, 8),
(28, 8),
(29, 8),
(30, 8),
(34, 8),
(42, 8),
(49, 8),
(53, 8),
(54, 8),
(56, 8),
(57, 8),
(59, 8),
(63, 8),
(70, 8),
(71, 8),
(72, 8),
(73, 8),
(74, 8),
(75, 8),
(76, 8),
(77, 8),
(78, 8),
(79, 8),
(80, 8),
(92, 8),
(97, 8),
(98, 8),
(100, 8),
(15, 9),
(25, 10),
(31, 10),
(44, 10),
(47, 10),
(61, 10),
(64, 10),
(66, 10),
(69, 10),
(24, 11),
(28, 11),
(71, 11),
(11, 12),
(22, 12),
(28, 12),
(71, 12),
(72, 12),
(73, 12),
(74, 12),
(75, 12),
(76, 12),
(77, 12),
(79, 12),
(80, 12),
(100, 12),
(48, 13),
(50, 13),
(81, 13),
(82, 13),
(83, 13),
(84, 13),
(85, 13),
(86, 13),
(87, 13),
(88, 13),
(89, 13),
(90, 13),
(6, 14),
(2, 15),
(5, 15),
(9, 15),
(13, 15),
(51, 15),
(52, 15),
(55, 15),
(56, 15),
(57, 15),
(58, 15),
(60, 15),
(95, 15),
(66, 16),
(78, 16),
(100, 16),
(1, 17),
(3, 17),
(8, 17),
(12, 17),
(16, 17),
(17, 17),
(41, 17),
(42, 17),
(43, 17),
(44, 17),
(45, 17),
(46, 17),
(47, 17),
(48, 17),
(49, 17),
(50, 17),
(69, 17),
(1, 18),
(5, 18),
(7, 18),
(13, 18),
(14, 18),
(15, 18),
(17, 18),
(41, 18),
(51, 18),
(52, 18),
(53, 18),
(54, 18),
(55, 18),
(59, 18),
(60, 18),
(81, 18),
(82, 18),
(83, 18),
(85, 18),
(87, 18),
(99, 18),
(91, 19),
(92, 19),
(93, 19),
(94, 19),
(95, 19),
(96, 19),
(97, 19),
(98, 19),
(99, 19);

-- --------------------------------------------------------

--
-- Structure de la table `GENRES`
--

CREATE TABLE `GENRES` (
  `IdGenre` int(11) NOT NULL,
  `LibelleGenre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `GENRES`
--

INSERT INTO `GENRES` (`IdGenre`, `LibelleGenre`) VALUES
(1, 'Action'),
(2, 'Animation'),
(3, 'Aventure'),
(4, 'Comédie'),
(5, 'Comédie dramatique'),
(6, 'Comédie romantique'),
(7, 'Documentaire'),
(8, 'Drame'),
(9, 'Espionnage'),
(10, 'Fantastique'),
(11, 'Guerre'),
(12, 'Historique'),
(13, 'Horreur'),
(14, 'Musical'),
(15, 'Policier'),
(16, 'Romance'),
(17, 'Science-Fiction'),
(18, 'Thriller'),
(19, 'Western');

-- --------------------------------------------------------

--
-- Structure de la table `LOCATIONS`
--

CREATE TABLE `LOCATIONS` (
  `IdLocation` int(11) NOT NULL,
  `IdUtilisateur` int(11) NOT NULL,
  `IdFilm` int(11) NOT NULL,
  `DateLocation` datetime DEFAULT current_timestamp(),
  `PrixFinal` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `TARIFSDYNAMIQUES`
--

CREATE TABLE `TARIFSDYNAMIQUES` (
  `IdTarif` int(11) NOT NULL,
  `JourSemaine` tinyint(4) NOT NULL COMMENT '1=lundi, 2=mardi, ..., 7=dimanche',
  `PourcentageReduction` decimal(5,2) NOT NULL COMMENT 'Ex: 15.00 pour -15%'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `TARIFSDYNAMIQUES`
--

INSERT INTO `TARIFSDYNAMIQUES` (`IdTarif`, `JourSemaine`, `PourcentageReduction`) VALUES
(1, 1, 0.00),
(2, 2, 15.00),
(3, 3, 0.00),
(4, 4, 10.00),
(5, 5, 0.00),
(6, 6, 20.00),
(7, 7, 25.00);

-- --------------------------------------------------------

--
-- Structure de la table `UTILISATEURS`
--

CREATE TABLE `UTILISATEURS` (
  `IdUtilisateur` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Nom` varchar(100) DEFAULT NULL,
  `Prenom` varchar(100) DEFAULT NULL,
  `DateInscription` datetime DEFAULT current_timestamp(),
  `Role` enum('USER','ADMIN') DEFAULT 'USER'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `UTILISATEURS`
--

INSERT INTO `UTILISATEURS` (`IdUtilisateur`, `Email`, `Password`, `Nom`, `Prenom`, `DateInscription`, `Role`) VALUES
(5, 'admin@mail.fr', '$2y$13$8ooH/F8nkucrVw9fJGx.huKc/UGbv8fKkcbucvI0Uzh.YWymO2fmK', 'systeme', 'admin', '2026-01-27 07:56:10', 'ADMIN'),
(6, 'test@mail.fr', '$2y$13$36cXMC3n.g6NmX8PTiAIQe6fVLK07HdH6DqYy.s21/KZUitnljSIC', 'test', 'test', '2026-01-27 08:05:14', 'USER');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `FAVORIS`
--
ALTER TABLE `FAVORIS`
  ADD PRIMARY KEY (`IdFavori`),
  ADD UNIQUE KEY `unique_favori_utilisateur_film` (`IdUtilisateur`,`IdFilm`),
  ADD KEY `fk_favoris_film` (`IdFilm`);

--
-- Index pour la table `FILMS`
--
ALTER TABLE `FILMS`
  ADD PRIMARY KEY (`IdFilm`);

--
-- Index pour la table `FILM_GENRE`
--
ALTER TABLE `FILM_GENRE`
  ADD PRIMARY KEY (`IdFilm`,`IdGenre`),
  ADD KEY `fk_film_genre_genre` (`IdGenre`);

--
-- Index pour la table `GENRES`
--
ALTER TABLE `GENRES`
  ADD PRIMARY KEY (`IdGenre`),
  ADD UNIQUE KEY `LibelleGenre` (`LibelleGenre`);

--
-- Index pour la table `LOCATIONS`
--
ALTER TABLE `LOCATIONS`
  ADD PRIMARY KEY (`IdLocation`),
  ADD KEY `fk_locations_utilisateur` (`IdUtilisateur`),
  ADD KEY `fk_locations_film` (`IdFilm`);

--
-- Index pour la table `TARIFSDYNAMIQUES`
--
ALTER TABLE `TARIFSDYNAMIQUES`
  ADD PRIMARY KEY (`IdTarif`),
  ADD UNIQUE KEY `JourSemaine` (`JourSemaine`);

--
-- Index pour la table `UTILISATEURS`
--
ALTER TABLE `UTILISATEURS`
  ADD PRIMARY KEY (`IdUtilisateur`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `FAVORIS`
--
ALTER TABLE `FAVORIS`
  MODIFY `IdFavori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `FILMS`
--
ALTER TABLE `FILMS`
  MODIFY `IdFilm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT pour la table `GENRES`
--
ALTER TABLE `GENRES`
  MODIFY `IdGenre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `LOCATIONS`
--
ALTER TABLE `LOCATIONS`
  MODIFY `IdLocation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `TARIFSDYNAMIQUES`
--
ALTER TABLE `TARIFSDYNAMIQUES`
  MODIFY `IdTarif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `UTILISATEURS`
--
ALTER TABLE `UTILISATEURS`
  MODIFY `IdUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `FAVORIS`
--
ALTER TABLE `FAVORIS`
  ADD CONSTRAINT `fk_favoris_film` FOREIGN KEY (`IdFilm`) REFERENCES `FILMS` (`IdFilm`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_favoris_utilisateur` FOREIGN KEY (`IdUtilisateur`) REFERENCES `UTILISATEURS` (`IdUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `FILM_GENRE`
--
ALTER TABLE `FILM_GENRE`
  ADD CONSTRAINT `fk_film_genre_film` FOREIGN KEY (`IdFilm`) REFERENCES `FILMS` (`IdFilm`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_film_genre_genre` FOREIGN KEY (`IdGenre`) REFERENCES `GENRES` (`IdGenre`) ON DELETE CASCADE;

--
-- Contraintes pour la table `LOCATIONS`
--
ALTER TABLE `LOCATIONS`
  ADD CONSTRAINT `fk_locations_film` FOREIGN KEY (`IdFilm`) REFERENCES `FILMS` (`IdFilm`),
  ADD CONSTRAINT `fk_locations_utilisateur` FOREIGN KEY (`IdUtilisateur`) REFERENCES `UTILISATEURS` (`IdUtilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;