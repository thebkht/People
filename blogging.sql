-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2023 at 06:50 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blogging`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `topics` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `topics`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'AI boosters need to learn how to edit', '', 'I feel a little insane typing this because it is so incredibly obvious, but I guess it needs to be said: part of creativity is knowing when to stop.<br />\r\n<br />\r\nOver and over, from AI art proponents, I’ve heard that we can simply extend a beloved piece of art — whether that’s the Mona Lisa or the cover of Abbey Road — to infinity. And in some ways, I’m sympathetic! One of the first ways we locate our creativity is by extending a work we loved; I wrote my very first short story in the universe of Ursula K. Le Guin’s Catwings around the age of six. It is possibly still kicking around in my dad’s attic somewhere.<br />\r\n<br />\r\nDerivative work is valuable and a longstanding tradition. Shakespeare didn’t come up with the plot for Hamlet; it’s based on a 12th-century work called Historia Danica. The absolute teen-girl classic Clueless is based on Jane Austen’s Emma. The Wind Done Gone and Wide Sargasso Sea are based on Gone With the Wind and Jane Eyre, respectively. Arguably all literary criticism is derivative work, as it requires the existence of some other work to interpret. <br />\r\n<br />\r\nLately, there have been a great many tweets about people doing this kind of work with AI. I am not entirely surprised to see people “extending” the background landscape of the Mona Lisa or the famous cover of Abbey Road using AI tools. Some of the excitement around these kinds of AI art is laudable, even. Creativity is a fucking rush! If this is your first time encountering it, I bet it feels world-changing. I work as a writer for a reason, you know?<br />\r\n<br />\r\nAnd so, in that spirit, for those of you who are thinking creatively for the first time, I’d like to introduce you to the idea of the edit.<br />\r\n<br />\r\nWhen you make something, you draw boundaries. Most of the things I publish on this site have words cut out of them, sometimes thousands of words. Sometimes that’s because I’m trying to be thoughtful about my reader’s time — as much as I love a sentence, it doesn’t serve the overall piece — and sometimes it’s because I’m Up To Something.<br />\r\n<br />\r\nThe most famous example of being Up To Something is Henry James’ The Turn of the Screw. There’s an absence at the center of the work that produces the horror necessary for the ghost story — the reader’s mind fills in the blank with whatever scares them most. (Calvin and Hobbes’ much-referenced but never explained Noodle Incident works this way also.) It is a deliberate artistic choice to leave things unfinished or to make a specific crop.<br />\r\n<br />\r\nLet’s take the Mona Lisa as an example. Consider the original for a moment: one of the words most associated with it is “mysterious.” There’s the half-smile, the fact that the art was commissioned by Francesco del Giocondo but for some reason never delivered, and a famous heist, where the painting went missing for about two years. There is no outline on the figure, which makes her seem more lifelike, and the detail work around her mouth and eyes, as well as a lack of eyebrows, make her expression ambiguous. There has been a long fight about whether the landscape behind her is a real place. The way it’s painted, moodily, certainly means the fight will go on for quite some time.<br />\r\n<br />\r\nNow let’s consider Kody Young’s version, made with Adobe Firefly. The background has been expanded significantly, pulling focus from the detail work that makes the Mona Lisa famous. The figure, the woman the portrait was made of, is a torso with no legs, emerging from a dark, cloudlike blob. The additional landscape, if anything, detracts from the vision that’s fascinated art lovers for years. Sure, there’s more, but it’s not doing anything useful. This is bad art.<br />\r\n<br />\r\nAnd there’s something weird going on with Young’s post, in which he frames his derivative work as “the rest” of the Mona Lisa instead of being honest: he’s making fan art. Much like fanfic, the Kody Young Mona Lisa is about filling in the things an original creator chooses to leave out. Lots of derivative works hinge on this — including Wide Sargasso Sea — but they do not pretend to be the “rest” of a work of art. Besides being confused about the purpose of a background, Young seems confused about what art even is.<br />\r\n<br />\r\nThe Mona Lisa doesn’t need more background because the purpose of the work is in the foreground. Shit, the painting’s named for her! And despite what Marvel movie aficionados might prefer, stories do not need to go on forever. There are lots of stories out there; you can simply enjoy another. I think it is possible to make interesting AI art, even interesting derivative AI art — but in order to do so, creators need to think about what to leave out and where to stop. Computers do not supply purpose. People do.<br />\r\n<br />\r\nThe artist made a stopping point for a reason. Maybe that reason was bad, or maybe that stopping point didn’t work — bad art is a side effect of trying to make art — but there is no “extended” Mona Lisa. The full version of the original is the original. But if you want to make a meaningful derivative work, there should be a reason it exists.', '2023-06-02 07:02:07', '0000-00-00 00:00:00'),
(2, 1, 'Twitter just closed the book on academic research', 'science,twitter,tech', 'Twitter was once a mainstay of academic research — a way to take the pulse of the internet. But as new owner Elon Musk has attempted to monetize the service, researchers are struggling to replace a once-crucial tool. Unless Twitter makes another about-face soon, it could close the chapter on an entire era of research. <br />\r\n<br />\r\n“Research using social media data, it was mostly Twitter-ology,” says Gordon Pennycook, an associate professor of behavioral science at the University of Regina. “It was the primary source that people were using,”<br />\r\n<br />\r\nUntil Musk’s takeover, Twitter’s API — which allows third-party developers to gather data — was considered one of the best on the internet. It enabled studies into everything from how people respond to weather disasters to how to stop misinformation from spreading online. The problems they addressed are only getting worse, making this kind of research just as important as ever. But Twitter decided to end free access to its API in February and launched paid tiers in March. The company said it was “looking at new ways to continue serving” academia but nevertheless started unceremoniously cutting off access to third-party users who didn’t pay. While the cutoff caused problems for many different kinds of users, including public transit agencies and emergency responders, academics are among the groups hit the hardest.<br />\r\n<br />\r\nResearchers who’ve relied on Twitter for years tell The Verge they’ve had to stop using it. It’s just too expensive to pay for access to its API, which has reportedly skyrocketed to $42,000 a month or more for an enterprise account. Scientists have lost a key vantage point into human behavior as a result. And while they’re scrambling to find new sources, there’s no clear alternative yet.<br />\r\n<br />\r\nTwitter gave researchers a way to observe people’s real reactions instead of having to ask study participants how they think they might react in certain scenarios. That’s been crucial for Pennycook’s research into strategies to prevent misinformation from fomenting online, for instance, by showing people content that asks them to think about accuracy before sharing a link.<br />\r\n<br />\r\nWithout being able to see what an individual actually tweets, researchers like Pennycook might be limited to asking someone in a survey what kind of content they would share on social media. “It’s basically hypothetical,” says Pennycook. “For tech companies who would actually be able to implement one of these interventions, they would not be impressed by that ... We had to do experiments somewhere to show that it actually can work in the wild.”<br />\r\n<br />\r\nIn April, a group of academics, journalists, and other researchers called the Coalition for Independent Technology Research sent a letter to Twitter asking it to help them maintain access. The coalition surveyed researchers and found that Twitter’s new restrictions jeopardized more than 250 different projects. It would also signal the end of at least 76 “long-term efforts,” the letter says, including code packages and tools. With enforcement of Twitter’s new policies somewhat haphazard (some users were kicked off the platform before others), the coalition set up a mutual aid effort. Scientists scrambled to harvest as much data as they could before losing their own access keys, and others offered to help them collect that data or donated their own access to Twitter’s API to researchers who lost it.<br />\r\n<br />\r\nTwitter’s most affordable API tier, at $100 a month, would only allow third parties to collect 10,000 per month. That’s just 0.3 percent of what they previously had free access to in a single day, according to the letter. And even its “outrageously expensive” enterprise tier, the coalition argued, wasn’t enough to conduct some ambitious studies or maintain important tools.<br />\r\n<br />\r\nOne such tool is Botometer, a system that rates how likely it is that a Twitter account is a bot. While Musk has expressed skepticism of things like disinformation research, he’s actually used Botometer publicly — to estimate how many bots were on the platform during his attempt to get out of the deal he made to buy Twitter. Now, his move to charge for API access could bring on Botometer’s demise.<br />\r\n<br />\r\nA notice on Botometer’s website says that the tool will probably stop working soon. “We are actively seeking solutions to keep this website alive and free for our users, which will involve training a new machine-learning model and working with Twitter’s new paid API plans,” it says. “Please note that even if it is feasible to build a new version of the Botometer website, it will have limited functionalities and quotas compared to the current version due to Twitter’s restricted API.”<br />\r\n<br />\r\nThe impending shutdown is a personal blow to Botometer co-creator Kai-Cheng Yang, a researcher studying misinformation and bots on social media who recently earned his PhD in informatics at Indiana University Bloomington. “My whole PhD, my whole career, is pretty much based on Twitter data right now. It’s likely that it’s no longer available for the future,” Yang tells The Verge. When asked how he might have to approach his work differently now, he says, “I’ve been asking myself that question constantly.”<br />\r\n<br />\r\nOther researchers are similarly nonplussed. “The platform went from one of the most transparent and accessible on the planet to truly bottom of the barrel,” says letter signatory Rebekah Tromble, director of the Institute for Data, Democracy, and Politics (IDDP) at George Washington University. Some of Tromble’s previous work, studying political conversations on Twitter, was actually funded by the company before it changed its API policies.<br />\r\n<br />\r\n“Twitter’s API has been absolutely vital to the research that I’ve been doing for years now,” Tromble tells The Verge. And like Yang, she has to pivot in response to the platform’s new pricing schemes. “I’m simply not studying Twitter at the moment,” she says.<br />\r\n<br />\r\nBut there aren’t many other options for gathering bulk data from social media. While scraping data from a website without the use of an API is one option, it’s more tedious work and can be fraught with other risks. Twitter and other platforms have tried to curtail scraping, in part because it can be hard to discern whether it’s being done in the public interest or for malicious purposes like phishing.<br />\r\n<br />\r\nMeanwhile, other social media giants have been even more restrictive than Twitter with API access, making it difficult to pivot to a different platform. And the restrictions seem to be getting tougher — last month, Reddit similarly announced that it would start to limit third-party access to its API.<br />\r\n<br />\r\n“I just wonder if this is the beginning of companies now becoming less and less willing to have the API for data sharing,” says Hause Lin, a post-doctoral research fellow at MIT and the University of Regina developing ways to stop the spread of hate speech and misinformation online. “It seems like totally the landscape is changing, so we don’t know where it’s heading right now,” Lin tells The Verge.<br />\r\n<br />\r\nThere are signs that things could take an even sharper turn for the worse. Last week, inews reported that Twitter had told some researchers they would need to delete data they had already collected through its decahose, which provides a random sample of 10 percent of all the content on the platform unless they pay for an enterprise account that can run upwards of $42,000 a month. The move amounts to “the big data equivalent of book burning,” one unnamed academic who received the notice reportedly told inews.<br />\r\n<br />\r\nThe Verge was unable to verify that news with Twitter, which now routinely responds to inquiries from reporters with a poop emoji. None of the researchers The Verge spoke to had received such a notice, and it seems to so far be limited to users who previously paid to use the decahose (just one use of Twitter’s API that previously would have been free or low-cost for academics).<br />\r\n<br />\r\nBoth Tromble and Yang have used decahose for their work in the past. “Never before did Twitter ever come back to researchers and say that now the contract is over, you have to give up all the data,” Tromble says. “It’s a complete travesty. It will devastate a bunch of really important ongoing research projects.”<br />\r\n<br />\r\nOther academics similarly tell The Verge that Twitter’s reported push to make researchers “expunge all Twitter data stored and cached in your systems” without an enterprise subscription would be devastating. It could prevent students from completing work they’ve invested years into if they’re forced to delete the data before publishing their findings. Even if they’ve already published their work, access to the raw data is what allows other researchers to test the strength of the study by being able to replicate it.<br />\r\n<br />\r\n“That’s really important for transparent science,” Yang says. “This is just a personal preference — I would probably go against Twitter’s policy and still share the data, make it available because I think science is more important in this case.”<br />\r\n<br />\r\nTwitter was a great place for digital field experiments in part because it encouraged people from different backgrounds to meet in one place online. That’s different from Facebook or Mastodon, which tend to have more friction between social circles. This centralization sometimes fostered conflict — but to academics, it was valuable.<br />\r\n<br />\r\n“If the research is not going to be as good, we won’t be able to know as much about the world as we did before,” Pennycook says. “And so maybe we’ll figure out a way to bridge that gap, but we haven’t figured it out yet.”', '2023-06-02 07:07:13', '0000-00-00 00:00:00'),
(3, 1, 'Anti-harassment service Block Party leaves Twitter amid API changes', 'twitter,tech,apps', 'Block Party, an anti-harassment service designed to combat abusive content on Twitter, is the latest third-party app to leave the platform in light of Twitter locking most of its API access behind a paywall. Announced in a blog post last night, Block Party’s anti-harassment tools for Twitter are being placed on an immediate, indefinite hiatus, with the developers claiming that changes to Twitter’s API pricing (which starts from $100 per month) have “made it impossible for Block Party’s Twitter product to continue in its current form.”<br />\r\n<br />\r\nBlock Party’s services allowed users to automate a great deal of their Twitter moderation, with filtering and block list features that automatically block accounts that like or retweet posts you don’t want to associate with. The company said that everything from its Twitter service — including both free and premium account features — will stop working today, May 31st, and that users will be able to access a read-only archive of their Lockout Folder and block lists until June 30th.<br />\r\n<br />\r\n“We’re heartbroken that we won’t be able to help protect you from harassers and spammers on the platform, at least for now,” said Block Party in the blog post. In an FAQ addressing the hiatus, the company added “We tried very hard to stay on the platform, and still hope to return in the future. We’re so sorry for any impact this disruption may have on your safety or experience on Twitter.”<br />\r\n<br />\r\nBlock Party notes that while its flagship Twitter product is on hiatus, the company is still developing additional services like its new Privacy Party browser extension. Privacy Party can be used to reduce harassment, cyberstalking, impersonation, and fraud across social media accounts, and is available in alpha today for existing Block Party users.<br />\r\n<br />\r\nBlock Party and many other third-party Twitter applications relied upon the social media platforms’ free API access, which was limited to 1,500 tweets and effectively replaced by a paid basic tier earlier this year. The new basic tier allows accounts to post 3,000 tweets for $100 a month (which may not be sufficient for many non-profit third-party services) while some enterprise-level plans reportedly cost as much as $42,000 per month.<br />\r\n<br />\r\nEarlier this month Twitter had to reverse course and make exceptions for weather, emergency, and transportation services that were forced to leave the platform due to the high cost of the new API tiers.', '2023-06-02 07:22:39', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `post_topics`
--

CREATE TABLE `post_topics` (
  `post_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'yusupovbg', 'b.yusupoff001@gmail.com', '$2y$10$UDfzxlEHc4oUjv8T1NY1i.xRM4/ydMZwv49xrXFXNnw0qKp8mOHOi', '2023-06-02 02:07:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `topic_id` (`topics`);

--
-- Indexes for table `post_topics`
--
ALTER TABLE `post_topics`
  ADD KEY `post_id` (`post_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `post_topics`
--
ALTER TABLE `post_topics`
  ADD CONSTRAINT `post_topics_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `post_topics_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
