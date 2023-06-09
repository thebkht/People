-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2023 at 09:08 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `readit_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `topics` varchar(100) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `comments` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `title`, `content`, `topics`, `views`, `comments`, `created_at`) VALUES
(2, 1, 'Apple’s Vision Pro is the Retina display moment for headsets', 'I still remember using the iPhone 4 for the first time in 2010. That was when Apple shipped its first-ever Retina display and Steve Jobs said that, once you use it, “you can’t go back.” It was something I couldn’t unsee, like looking through prescription glasses for the first time.<br />\r\n<br />\r\nThat’s exactly how I felt after a demo of the Apple Vision Pro yesterday at the company’s headquarters in Cupertino, California. A computer you strap to your face should be primarily judged not only by what you can do with it but also by the quality of what you can see through it. The Vision Pro blows away every other headset in this regard. It’s the industry’s Retina display moment. There’s no going back.<br />\r\n<br />\r\nThe headset packs an insane 23 megapixels into dual MicroOLED panels, meaning that each eye looks through a roughly 4K display. The $1,000 Meta Quest Pro, by contrast, has a resolution of 1800 x 1920 per eye. For those who need vision correction like me, Apple has partnered with Zeiss to sell prescription inserts that clip onto the inner-facing displays. That helps make the headset not only thinner but, in my experience, also much more comfortable to wear. <br />\r\n<br />\r\nAfter scanning my face and ears on an iPhone to calibrate the device to my head (the experience will be immediately familiar to anyone who has set up Face ID), I handed my glasses to an optometrist Apple had on-site to have my prescription made for the demo. After a few minutes, I was whisked into another brightly lit, temperature-controlled room with a headset that had my prescription inserted.<br />\r\n<br />\r\nVisually, the most memorable experience of my roughly 30-minute, highly controlled demo wasn’t the butterfly landing on my finger, the 3D Avatar clip, or even viewing Apple’s new surreal 3D photos and videos. It was when I had three windows open at once for Messages, Safari, and Photos. I used the headset’s eye tracking and pinching gesture to quickly (and I do mean quickly — navigating this way is incredibly intuitive) place each window at a different depth in the room.<br />\r\n<br />\r\nI placed Messages to my immediate right and almost uncomfortably close to my face, Safari in the middle of the room, and Photos against the wall I was facing. I could see Messages up close just as clearly as the text in the Photos app more than eight feet away. There were no discernible pixels anywhere.<br />\r\n<br />\r\nMy colleague David Pierce, who also tried the Vision Pro this week, has already pointed out that it will be a great TV. I agree and could see myself actually watching a movie in it, which is something I’d never say about the other headsets out there. It’s clear that Apple chose not to compromise on the quality of the visual experience, even if it means that buying the Vision Pro with prescription inserts will likely cost as much as a used Ford Focus.<br />\r\n<br />\r\nThere’s plenty about the Vision Pro that remains to be seen, namely the front face display that can show where the wearer’s eyes are looking. It wasn’t turned on for my demo and appears to not be finished. I wasn’t allowed to use the virtual keyboard, and the ability to take what Apple is calling “spatial” photos and videos through a dedicated button on the headset wasn’t enabled. I fully expect the software I experienced to change a lot before the device ships next year, which makes it easiest to judge the Vision Pro on what won’t change. <br />\r\n<br />\r\nApple’s goal for the Vision Pro is clearly to get developers building for the headset and figuring out its killer apps for future, cheaper versions, so not compromising on the display initially is the right call. It’s a compelling enough leap forward for headset optics alone to make the device worth trying. Like that first Retina display, it’s something you can’t unsee.<br />\r\n<br />\r\nI’ll share more about my experience of demoing the Vision Pro in Friday’s issue of Command Line, my weekly newsletter. You can subscribe below to get it in your inbox.', 'apple', 3, 1, '2023-06-08 08:46:23'),
(3, 1, 'All the features Apple didn’t mention in its WWDC 2023 keynote', 'Apple held a monstrous WWDC this year, and a ton of what was rumored, it turns out, will actually see the light of day. There’s the new 15-inch MacBook Air, M2-powered Mac Studio, and Apple’s finally realized AR headset — which we now know is called the Vision Pro.<br />\r\n<br />\r\nAs usual, Apple didn’t touch on everything new during its opening keynote. But lots of small features that could change the way you use your Apple devices (or are just fun to play with) get packed in, and we’ve collected as many of those as we could find here. To keep this article from being a mile long, I’ve noted in the top iOS 17 section where I could confirm features will hit other platforms. So take a deep breath and dive in.<br />\r\n<br />\r\niOS 17 and iPadOS 17<br />\r\nI’m a fan of a lot of the new stuff going on with iOS 17 and iPadOS 17. I love the idea of being able to pick up my phone and answer it while someone is recording a voicemail (what is this, 1996?) or leave a FaceTime video message. And Apple’s demonstration of collaboration on PDFs for iPad looks great. Most, if not all, of the new features below will hit both operating systems.<br />\r\n<br />\r\niPhones X, 8, and 8 Plus will no longer be supported. Looks like anyone hoping to keep Apple’s 2017 lineup up to date for one more year is out of luck. Apple had a nice streak of support for older phones — iOS 13, iOS 14, and iOS 15 could all run on the same phones — but then, iOS 16 went and dropped everything up to the iPhone 7 and the first-gen iPhone SE.<br />\r\niPadOS 17 will lose support for the first-gen iPad Pro and fifth-gen iPad.<br />\r\n<br />\r\nAirTag and Find My sharing is coming! I’ve been silently begging for this in my quiet moments since AirTags came out, and it’s finally here. Or will be. This also includes any other Find My accessories, and you can share with up to five people.<br />\r\nApple Maps gets offline downloads. Eight years after Google Maps added it, Apple Maps will finally get offline downloads.<br />\r\nEnhanced electric vehicle routing. You’ll be able to see charging stations on your route, with “real-time availability.” You can pick your preferred charging network, and it’ll show you what’s available along your route. Google Maps has had the feature since 2019, and it’s pretty darn handy.<br />\r\nApp shortcuts will show up in the Top Hit card in Spotlight search. So you can go straight to your preferred Apple Photo albums by searching for them from iOS Spotlight search, for example.<br />\r\nVisual Look Up lets you find recipes based on your food pics. You’ve been able to learn about things you took pictures of since iOS 15 using the Visual Look Up feature. Now, you can find recipes for similar dishes to ones you have pictures of in your Photos library. You’ll also be able to get info about aspects of a video or a cutout you pulled from Apple Photos.<br />\r\nCommunication Safety is expanding. It’s coming to AirDrop, photo picker, FaceTime messages, the new Contact Posters feature, and even third-party apps. That’s a pretty broad expansion to Apple’s warning system for nudity. The company is also adding the option to have those images blurred so you have to take one more step to view them.<br />\r\nMore privacy upgrades. Apple says you can choose which photos to share from within an app while keeping the rest of your Photos library private (though it’s unclear how that’s different from the same feature introduced in iOS 14 — I’ve asked Apple and will update if it responds), and apps can add events to the Apple Calendar app without being able to actually view the calendar. There’s also an expanded Lockdown Mode, allowing you to turn on Apple’s über private mode across all your devices, including the Apple Watch.<br />\r\nNew Memoji stickers. Now you get Halo, Smirk, and Peekaboo.<br />\r\nAutomatically sorted grocery lists in Reminders. Reminders will group your items automatically; you can change how you want them grouped, and your preferences stick around.<br />\r\nActivity history in Apple Home. The Home app will show who operated a door lock and when they did it as well as recent activity for garage doors, contact sensors, and your security system. A footnote says this will only be available if you’ve upgraded the Home app to the new Apple Home architecture.<br />\r\nCrossword puzzles in the News app. Apple has sherlocked crosswords! Apple News Plus subscribers only.<br />\r\nApple News Plus audio stories will be available in Apple’s Podcasts app.<br />\r\nSign in with any email address or phone number listed in your Apple ID account.<br />\r\nFreeform gets new tools. Some of this was mentioned in the keynote during the iPad section, but other tools coming to Apple’s collaborative whiteboard app include a ruler, watercolor brush, calligraphy pen, highlighter, and variable-width pen. The shape recognition feature Apple mentioned during this section isn’t mentioned on the iOS 17 preview page, so it’s possible that’s exclusive to iPads.<br />\r\nCrossfade between songs in Apple Music. Old Apple iTunes for Mac fans might remember the ability to add a crossfade between songs, meaning the volume would fade out at the end of one song and the beginning of the next would fade in. I hated that feature, but hey, maybe some of y’all liked it. And now, that feature is in the first iOS 17 beta, according to 9to5Mac.<br />\r\nRedesigned Now Playing. The Now Playing strip at the bottom of the Apple Music app in iOS 17 floats above the rest of the UI in Apple Music, and the tabs beneath it fade into the rest of the UI on either side of the floating controls, as noted by MacRumors.<br />\r\nCollaborative playlists. Though you could always share playlists, iOS 17 will let you fully collaborate on them with others. Anyone sharing the list will be able to add, reorder, and remove songs, and they will be able to react to song choices with emoji.<br />\r\nYou can now invoke Siri during a call or FaceTime on your phone. This feature, spotted by a Reddit user in the first iOS 17 beta, allows you to use Siri even when you’re on a call, expanding the ability to use Siri to hang up on a call, which was introduced in iOS 16.<br />\r\nYou can add links from one note to another. Apple spent some time talking about PDFs in the Notes app but didn’t talk about this nice upgrade. The company only mentions the feature on the iPadOS 17 preview page, but as MacRumors writes, it’s also on iOS 17, so it’s likely to be cross-platform.<br />\r\niPhone finally gets multiple timers. Who knows why even the Apple Watch got this feature before the iPhone, but in iOS 17, you’ll be able to set multiple timers, glory be. As with linking PDFs, Apple mentioned the feature for iPads, but it’s coming to iOS as well.<br />\r\nSafari gets a new “Listen to Page” feature. A new option is added to the page options of Safari that will have your iPhone read the webpage you’re looking at out loud. You can pause and unpause the feature.<br />\r\nInterrupt Siri. iOS 17 will let you interrupt the voice assistant without saying “Siri” again.<br />\r\nA new call controls section sits in the AirPods Pro settings. In the iOS 17 beta, this would let you configure how many times you squeeze the AirPods Pro stalks to end calls. Answering calls isn’t configurable, but given this is a beta, that could change.<br />\r\nPronouns in contacts. iOS 17 includes the ability to add a person’s pronouns to their contact card.<br />\r\nYou’ll be able to ping your Apple Watch from Control Center.<br />\r\niOS 17 will let you auto-delete verification codes in Messages and Mail.<br />\r\nmacOS Sonoma<br />\r\nApple’s newest version of its computer operating system will bring over more iOS design elements and features, with updated widgets that you can actually place on your desktop and a lock screen that follows the same design as that of your iPhone or iPad. Here’s what the company didn’t mention.<br />\r\n<br />\r\nmacOS Sonoma drops support for these Macs: 2017 MacBooks Pros; the beloved 2017 12-inch MacBook; and 2017 iMacs.<br />\r\nControl the composition of Center Stage. In Sonoma, you’ll be able to decide where you want to sit in the frame on video calls with new zoom and pan controls — that’ll be great if you want to show something on the call and don’t want your Mac deciding you need to be the focal point. You can recenter yourself at any time, and it appears this feature works with third-party apps as well.<br />\r\nShare your full-screen window in video call apps. The green button in the top left of your windows now has a new function in its drop-down menu. Maybe you didn’t know this, but when you hover your mouse over that button, it gives you some quick tiling options, along with the ability to quickly move an app window to another display in your setup. In Sonoma, you’ll be able to share that window on a video call now.<br />\r\nContinue your notes in Pages. For all those Apple Pages heads out there (there are dozens of us!), Sonoma will let you use the share button in Notes to send your work over to the Pages app to continue working there.<br />\r\nScreen sharing gets a boost. Sonoma adds a new screen-sharing feature that lets you activate a high-performance mode when you’re remotely accessing your Mac over a high-bandwidth connection. Apple’s Sonoma preview page says it will use the advanced media engine to bolster that connection to make the connection more responsive.<br />\r\nmacOS Sonoma lets Macs pair with Made for iPhone hearing devices. In addition to that, the new Personal Voice feature is coming to Macs as well as updates to text size adjustment, the ability to automatically pause autoplaying animated images on the web, and an updated experience for learning Mac Voice Control.<br />\r\nApple Mail updates. Mail will start surfacing travel-related emails at the top of your search results as the trip date approaches, and you’ll be able to add big emoji to your messages, making email more annoying than ever.<br />\r\n<br />\r\nwatchOS 10<br />\r\nwatchOS 10 is getting a widgety makeover later this year and bringing some cool new enhancements to cycling and hiking workouts, but that’s not all. Here’s what we found that Apple didn’t shine the spotlight on during its WWDC 2023 keynote.<br />\r\n<br />\r\nwatchOS 10 will only work for the Apple Watch Series 4 and up. Any watch compatible with watchOS 9 will be eligible for the update.<br />\r\nControl Center is now accessible by pressing the side button. Previously, this brought up all of your open apps.<br />\r\nMobile Device Management now comes to Apple Watch. Apple is opening up managed Apple Watches to enterprise customers, adding the ability to configure VPNs, deploy internal apps, and more.<br />\r\nYou can now add an Apple Watch to a group FaceTime. Of course, you would only join with audio since there’s no camera on the Apple Watch.<br />\r\nApple Maps downloads will work on the Apple Watch. But you have to be in range of your iPhone.<br />\r\nMedication reminders get follow-up notifications, and you can classify them as “critical” alerts.<br />\r\nFaceTime recorded video messages will be viewable on the Apple Watch.<br />\r\nApple hardware<br />\r\nApple hit some of its Mac lines with updates during its WWDC keynote, but it shuffled through each one incredibly quickly, so there are a few small details it left out. Here’s what I was able to find.<br />\r\n<br />\r\nMac Studio features: The Mac Studio gets Wi-Fi 6E and Bluetooth 5.3, both upgrades from the M1 version.<br />\r\nMac Pro features: Like the Mac Studio, the Mac Pro will support both Wi-Fi 6E and Bluetooth 5.3 out of the box.', 'wwdc 2023, apple, tech', 3, 0, '2023-06-08 09:08:08'),
(4, 1, 'Twitch walks back controversial ad rules policy', 'Twitch is reversing its newly announced rules concerning the way streamers could display ads on the platform after swift backlash from streamers and content creators.<br />\r\n<br />\r\nOn Tuesday, Twitch released new rules concerning the way streamers could display ads on the platform. The rules prohibited “burned in” video, display, and audio ads — the first two of which were popular and common formats used throughout Twitch. Twitch apparently did not discuss the new rules with ambassadors or streamers beforehand, and many were furious about the new policies.<br />\r\n<br />\r\nTwitch content creators took to social media to decry the changes. OTK, a network of popular, high-value streamers like Asmongold, released an open letter to Twitch, telling it, “The once-unique and admirable vision of a creator-first platform now feels like a fading and distant dream.”<br />\r\n<br />\r\nCharity streamers were upset and fearful, believing the new rules would impact their ability to raise money. It was the same with esports creators, as the new rules would have made it more difficult for the already struggling esports industry to monetize its broadcasts.<br />\r\n<br />\r\nTwitch apologized for the rollout, explaining that it would rewrite the rules for greater clarity. Now it seems that rewrite has turned into a full rescinding of the rules totally. From the company’s Twitter thread:<br />\r\n<br />\r\nYesterday, we released new Branded Content Guidelines that impacted your ability to work with sponsors to increase your income from streaming. These guidelines are bad for you and bad for Twitch, and we are removing them immediately. Sponsorships are critical to streamers’ growth and ability to earn income. We will not prevent your ability to enter into direct relationships with sponsors – you will continue to own and control your sponsorship business. We want to work with our community to create the best experience on Twitch, and to do that we need to be clear about what we’re doing and why we’re doing it. We appreciate your feedback and help in making this change.<br />\r\n<br />\r\nTwitch has updated the page outlining its ads policy with the section related to what kinds of ads are prohibited or allowed completely removed. Here’s an archived version with the old rules and the new, updated page.<br />\r\n<br />\r\nThe new rules would have been potentially devastating for creators, charities, esports broadcasts, and brands. Now, what seemed like another attempt to take a portion of streamer earnings has backfired.', 'twitch, creators, tech', 2, 0, '2023-06-08 10:23:10'),
(5, 1, 'GameStop fires its CEO', 'GameStop has fired CEO Matt Furlong, the company announced as part of its first quarter 2023 earnings on Wednesday. There’s no immediate replacement, though board chairman Ryan Cohen has been appointed executive chairman, the company said in a short press release about Furlong’s firing.<br />\r\n<br />\r\nCohen, who founded the e-commerce site Chewy, has invested in a number of “meme stocks,” including GameStop and Bed Bath & Beyond. His surprise sale of Bed Bath & Beyond stock in 2022 raised eyebrows and led to at least one lawsuit accusing him of pumping and dumping the stock. His initial investment in GameStop back in early 2021 led to an enormous rise in the stock and contributed to its status as a memestock beloved by the Reddit sub r/wallstreetbets.<br />\r\n<br />\r\nMatthew Furlong was fired on June 5th without cause, the company wrote in the 10-Q. Furlong started at GameStop in June 2021 — which was after the beginning of the chaos with GameStop’s stock price — and he oversaw things like GameStop’s move into NFTs and layoffs at the company.<br />\r\n<br />\r\nFurlong joined GameStop after eight years at Amazon; before he left Amazon, he was the leader of the company’s Australia business. As noted at Furlong’s hiring by Reuters, Cohen also brought in Amazon execs to the roles of chief financial officer, chief operations officer, chief technology officer, and chief growth officer. None of those execs are at the company any longer. Former CFO Michael Recupero was fired in July, former COO Jenna Owens left after just seven months in the role, former CTO Matt Francis left in April, and former chief growth officer Elliott Wilke left in September.<br />\r\n<br />\r\nGameStop has also made Mark Robinson the company’s new “principal executive officer” with a title of general manager, according to a form 10-Q from the company. Robinson has been at GameStop for nearly eight years, according to his LinkedIn, and he most recently served as the company’s general counsel.<br />\r\n<br />\r\nGameStop cancelled its earnings call today.<br />\r\n', 'gaming,entertainment,tech', 2, 0, '2023-06-08 13:45:02'),
(8, 5, 'testing', 'testing', '', 2, 0, '2023-06-09 01:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `content`, `created_at`) VALUES
(2, 2, 5, 'Awesome', '2023-06-08 16:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT 'user_318-563642.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `name`, `avatar`, `created_at`, `verified`) VALUES
(1, 'yusupovbg', 'b.yusupoff001@gmail.com', '$2y$10$yUnL3j2nlsm8cHXmrSzR4eL62k2rmkGR/ncJgWX5ZIgj7LFMBJJtq', 'Bakhtiyor', '1_bkht 2023-01.png', '2023-06-07 14:00:48', 1),
(4, 'bkhtdev', 'yusupovbg@gmail.com', '$2y$10$Qeoq/B7tNhTsBeKl95/GFOKZL4MJYitx.hRQI1aSol8380jl1eJJy', 'Bakhtiyor', '4_falsetech.png', '2023-06-07 20:56:21', 0),
(5, 'thisissteve', 'steve@xample.com', '$2y$10$IH.AXe9zGWV5skW1y67mWe8LFNMSAbzbYbiKQ6qu.SVtVEP.xKdeO', 'Steve', '5_labcom profile pohto new-02.png', '2023-06-08 08:40:38', 0),
(6, 'yuju', 'yusufbekweb@gmail.com', '$2y$10$4BLO1mRvu8qVYoABvUh/n.UowctL6oU7mHHgrXPjZzbMYk/TWMzTi', 'yusufbek', 'user_318-563642.jpg', '2023-06-08 09:59:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_followers`
--

CREATE TABLE `user_followers` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_followers`
--

INSERT INTO `user_followers` (`follower_id`, `followed_id`) VALUES
(4, 1),
(4, 6),
(5, 1),
(6, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_followers`
--
ALTER TABLE `user_followers`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_followers`
--
ALTER TABLE `user_followers`
  ADD CONSTRAINT `user_followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_followers_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
