-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2021 at 05:52 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ad_id` int(11) NOT NULL,
  `ad_name` varchar(255) NOT NULL,
  `ad_email` varchar(255) NOT NULL,
  `ad_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ad_id`, `ad_name`, `ad_email`, `ad_pass`) VALUES
(1, 'George Mapili', 'adminofficial@admin.com', '$2y$10$e/zvqHjfz/4MKCaPbHp7ZupjFrDre7smW0.4wyciRsSn65ap4OVA6');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `aId` int(11) NOT NULL,
  `pId` int(11) NOT NULL,
  `pName` varchar(255) NOT NULL,
  `pEmail` varchar(255) NOT NULL,
  `pAddress` varchar(255) NOT NULL,
  `pMobile` varchar(255) NOT NULL,
  `pDoctor` varchar(255) NOT NULL,
  `dFee` varchar(255) NOT NULL,
  `aReason` varchar(255) NOT NULL,
  `aDate` varchar(255) NOT NULL,
  `aTime` varchar(255) NOT NULL,
  `aStatus` varchar(255) NOT NULL DEFAULT 'pending',
  `aMadeOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `pPrescription` varchar(255) NOT NULL,
  `pTotalPay` int(5) NOT NULL,
  `pDischarge` int(11) NOT NULL DEFAULT 0,
  `pMedicineFee` int(11) NOT NULL,
  `pAmountPay` int(11) NOT NULL,
  `pChange` int(11) NOT NULL,
  `patientStatus` varchar(50) NOT NULL,
  `labTest` text NOT NULL,
  `labResult` text NOT NULL,
  `dischargedOn` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bloodtype`
--

CREATE TABLE `bloodtype` (
  `bloodType_id` int(11) NOT NULL,
  `bloodType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bloodtype`
--

INSERT INTO `bloodtype` (`bloodType_id`, `bloodType`) VALUES
(1, 'A+'),
(2, 'A-'),
(3, 'B+'),
(4, 'B-'),
(7, 'O+'),
(8, 'O-'),
(11, 'AB+'),
(12, 'AB-');

-- --------------------------------------------------------

--
-- Table structure for table `discharged_patient`
--

CREATE TABLE `discharged_patient` (
  `dpId` int(11) NOT NULL,
  `pId` int(11) NOT NULL,
  `pName` varchar(255) NOT NULL,
  `pEmail` varchar(255) NOT NULL,
  `pAddress` varchar(255) NOT NULL,
  `pMobile` varchar(255) NOT NULL,
  `pDoctor` varchar(255) NOT NULL,
  `pPrescription` text NOT NULL,
  `pDisease` varchar(255) NOT NULL,
  `pTotalAmount` int(11) NOT NULL,
  `pStatus` varchar(100) NOT NULL,
  `pMadeOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `pAmountPay` int(11) NOT NULL,
  `pChange` int(11) NOT NULL,
  `labTest` varchar(255) NOT NULL,
  `labResult` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `diseases_treatment`
--

CREATE TABLE `diseases_treatment` (
  `dtId` int(11) NOT NULL,
  `dtName` varchar(255) NOT NULL,
  `dtMeaning` text NOT NULL,
  `dtSymptoms` text NOT NULL,
  `dtPrevention` text NOT NULL,
  `dtTreatment` text NOT NULL,
  `dtMadeOn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diseases_treatment`
--

INSERT INTO `diseases_treatment` (`dtId`, `dtName`, `dtMeaning`, `dtSymptoms`, `dtPrevention`, `dtTreatment`, `dtMadeOn`) VALUES
(1, 'Fever', 'A fever is a temporary increase in your body temperature, often due to an illness. Having a fever is a sign that something out of the ordinary is going on in your body.\r\n\r\nFor an adult, a fever may be uncomfortable, but usually isn\'t a cause for concern unless it reaches 103 F (39.4 C) or higher. For infants and toddlers, a slightly elevated temperature may indicate a serious infection.\r\n\r\nFevers generally go away within a few days. A number of over-the-counter medications lower a fever, but sometimes it\'s better left untreated. Fever seems to play a key role in helping your body fight off a number of infections.', 'Sweating \r\nChills and shivering\r\nHeadache\r\nMuscle aches\r\nLoss of appetite\r\nIrritability\r\nDehydration\r\nGeneral weakness', 'Wash your hands often and teach your children to do the same, especially before eating, after using the toilet, after spending time in a crowd or around someone who\'s sick, after petting animals, and during travel on public transportation.\r\nShow your children how to wash their hands thoroughly, covering both the front and back of each hand with soap and rinsing completely under running water.\r\nCarry hand sanitizer with you for times when you don\'t have access to soap and water.\r\nTry to avoid touching your nose, mouth or eyes, as these are the main ways that viruses and bacteria can enter your body and cause infection.\r\nCover your mouth when you cough and your nose when you sneeze, and teach your children to do likewise. Whenever possible, turn away from others when coughing or sneezing to avoid passing germs along to them.\r\nAvoid sharing cups, water bottles and utensils with your child or children.', 'In the case of a high fever, or a low fever that\'s causing discomfort, your doctor may recommend an over-the-counter medication, such as acetaminophen (Tylenol, others) or ibuprofen (Advil, Motrin IB, others).\r\n\r\nUse these medications according to the label instructions or as recommended by your doctor. Be careful to avoid taking too much. High doses or long-term use of acetaminophen or ibuprofen may cause liver or kidney damage, and acute overdoses can be fatal. If your child\'s fever remains high after a dose, don\'t give more medication; call your doctor instead.\r\n\r\nDon\'t give aspirin to children, because it may trigger a rare, but potentially fatal, disorder known as Reye\'s syndrome.', '2020-11-25 15:11:46'),
(2, 'Allergies', 'Allergies occur when your immune system reacts to a foreign substance such as pollen, bee venom or pet dander or a food that doesn\'t cause a reaction in most people.\r\n\r\nYour immune system produces substances known as antibodies. When you have allergies, your immune system makes antibodies that identify a particular allergen as harmful, even though it isn\'t. When you come into contact with the allergen, your immune system\'s reaction can inflame your skin, sinuses, airways or digestive system.\r\n\r\nThe severity of allergies varies from person to person and can range from minor irritation to anaphylaxis — a potentially life-threatening emergency. While most allergies can\'t be cured, treatments can help relieve your allergy symptoms.', 'Because there are so many possible causes, the symptoms of allergies vary widely. Airborne allergens, like pollen and pet dander, are likely to cause:\r\n\r\nEye irritation\r\nRunny nose\r\nStuffy nose\r\nPuffy, watery eyes\r\nSneezing\r\nInflamed, itchy nose and throat', 'Prevention\r\nAvoid the outdoors between 5-10 a.m. and save outside activities for late afternoon or after a heavy rain, when pollen levels are lower.\r\nKeep windows in your living spaces closed to lower exposure to pollen.\r\nTo keep cool, use air conditioners and avoid using window and attic fans.\r\nWear a medical alert bracelet or other means to communicate to others about your allergy in case of a reaction.\r\nDiscuss a prescription for epinephrine (e.g., EpiPen) with your healthcare provider, if you have risk of serious allergic reaction.\r\nReview product labels carefully before buying or consuming any item\r\nKnow what you are eating or drinking.', 'The easiest and most effective way to treat allergies is to get rid of or avoid the cause. Where unavoidable, some lifestyle changes can reduce your allergy symptoms. For example, if you are allergic to dust mites, make an effort to keep your room clean and free of dust by frequent vacuuming, dusting, and washing of bedding.\r\n\r\nFor pollen allergies, avoid being outside when pollen counts are high and keep the windows to your room shut.\r\n\r\nBecause it is very difficult to avoid certain allergens, medication may be necessary to lessen symptoms caused by allergens, other than food and drugs.', '2020-11-25 15:19:34'),
(3, 'Cold and Flu', 'Colds and influenza (flu) are the most common illnesses among college students. \r\n\r\nBoth of these illnesses are upper respiratory infections, meaning they involve your nose, throat, and lungs. Viruses cause both colds and flu by increasing inflammation of the membranes in the nose and throat.', 'Flu symptoms come on suddenly and affect the body all over. Flu symptoms are usually more serious than a cold and include:\r\n\r\nheadache,\r\nmore intense pain and fatigue, and\r\nmore severe, often dry cough.', 'The flu is probably only contagious during the first three days of illness, and the incubation period is 24-72 hours, meaning you might not show symptoms for three days after contracting the virus. It is rare to catch a cold virus through the air – most transmission occurs via hand-to-hand contact. To prevent colds, flu, and other illnesses, follow these tips:\r\n\r\nWash your hands often (which is good advice for keeping healthy in any situation). Keep them away from your nose, eyes, and mouth. Use an instant hand sanitizer when you can’t wash your hands.\r\nGet regular exercise and eat well.\r\nFollow good sleep habits.\r\nGet a flu shot each fall (offered to all students at a lower cost by UHS each fall) ', 'If any problem is causing you discomfort, you should seek medical care.\r\n\r\nSeek medical attention promptly if you have:\r\n\r\na fever of 102° F or greater (which may indicate a more serious infection),\r\na persisting cough, especially with a significant fever (which could indicate pneumonia),\r\na persistent sore throat (especially if runny nose does not develop - which could indicate a strep infection), or\r\nany cold lasting more than 10 days.', '2020-11-25 15:19:34'),
(4, 'Diarrhea', 'Everyone occasionally has diarrhea — loose, watery and more-frequent bowel movements. You might also have abdominal cramps and produce a greater volume of stool. Diarrhea varies in specific symptoms, severity and duration.\r\n\r\nAcute diarrhea, which lasts from two days to two weeks, is typically caused by a bacterial, viral or parasitic infection of some sort.\r\n\r\nChronic diarrhea lasts longer than does acute diarrhea, generally more than four weeks. Chronic diarrhea can indicate a serious disorder, such as ulcerative colitis or Crohn\'s disease, or a less serious condition, such as irritable bowel syndrome.', 'watery, loose stools\r\nfrequent bowel movements\r\ncramping or pain in the abdomen, nausea, bloating\r\npossibly fever or bloody stools, depending on the cause', 'Avoid foods that are milk-based, greasy, high-fiber, or very sweet because these are likely to aggravate diarrhea.\r\nAvoid caffeine and alcohol.\r\nDo not eat solid food if you have signs of dehydration (thirst, light-headed, dark urine). Instead, drink about 2 cups of clear fluids per hour (if vomiting isn’t present), such as sports drinks and broth. Water alone is not enough because your body needs sodium and sugar to replace what it’s losing.', 'Begin eating normal meals within 12 hours, but stick to food that is bland and won’t irritate your intestine. Some doctors suggest the “BRAT“ diet which includes foods that are low in fiber, fat, and sugar. BRAT stands for Bananas, Rice, Applesauce, and Toast.\r\nUse over-the-counter lactobacillus acidophilus capsules or tablets. These bacteria help maintain a healthy intestine, and are found in yogurt with live active cultures.\r\nDecrease level of exercise until symptoms are gone.\r\nOver-the-counter drugs, such as Imodium A-D, should only be used if absolutely necessary because it is important to let diarrhea flush out the bacteria or parasite that’s causing the infection.', '2020-11-25 15:26:03'),
(5, 'Headaches', 'Everyone suffers the occasional mild headache, but if you experience debilitating pain and/or abnormally frequent headaches, you probably want to find relief. There are countless causes of headaches, which differ for each person, so you’ll have to do some experimenting to figure out the cause of your pain. Fortunately, the vast majority of headaches are primary headaches, not the result of underlying medical conditions. The three most common types are cluster, tension-type, and migraine.\r\n\r\nA cluster headache affects a specific point of the head, often the eye, and is characterized by sharp, piercing pain. Migraine and tension-type headaches are far more common. “Tension“ headaches are now called “tension-type“ headaches because pain is not only caused by stress, but also poor posture, depression, and even sexual activity. In fact, recent studies have shown a connection between low serotonin levels and so-called “tension“ headaches.', 'Just as the causes vary for each headache sufferer, so do the symptoms and severity of pain. Health professionals can often diagnose the type of headache you suffer based on your symptoms.\r\n\r\nSymptoms of a migraine:\r\n\r\npulsing or throbbing quality\r\nbegins with intense pain on one side of the head, which eventually spreads\r\nfelt on one or both sides of the head\r\nlasts several hours\r\nsevere enough to interfere with routine activities\r\nmay be accompanied by nausea or vomiting\r\nsometimes preceded by visual changes, such as an aura of zigzag lines or flashes of light\r\nlight and noise can make the headache worse, while sleep tends to relieve symptoms', 'Be aware of early symptoms so you can try to stop the headache as soon as it begins.\r\nDon’t smoke, and if you do, quit.\r\nDon’t skip meals.\r\nCut down on caffeine and alcohol (reduce caffeine intake gradually because withdrawal may cause headaches).\r\nStop all over-the-counter medicines and herbal remedies.\r\nMaintain a regular eating and sleeping schedule.\r\nExercise regularly.\r\nIncorporate relaxation activities into your daily routine, such as meditation, yoga, stretching exercises, and massage\r\nImprove your posture, possibly by adjusting your workstation.', 'Ice pack held over the eyes or forehead\r\nHeating pad set on low or hot shower to relax tense neck and shoulder muscles\r\nSleep, or at least resting in a dark room\r\nTaking breaks from stressful situations\r\nRegular exercise to increase endorphin levels and relax muscles. Even if you already have a headache, exercising may relieve the pain. However, intense exercise may bring on a headache.\r\nOccasional use of over-the-counter medicines such as acetaminophen, ibuprofen, or aspirin can relieve both migraine and tension headaches. *\r\nPrescription drugs for severe headaches', '2020-11-25 15:26:03'),
(8, 'Mononucleosis', 'Mononucleosis is an illness caused by the Epstein-Barr virus (EBV), which is spread through saliva.', 'The most well known symptom of “mono“ is extreme fatigue, forcing the infected person to nap frequently. If you experience such extreme fatigue accompanied by other symptoms, such as swollen lymph glands and spleen, sore throat, fever, loss of appetite, and muscle aches, you may want to get tested for mono. The basis for testing whether you have mono is the presence of antibodies produced by white blood cells. Many people infected with mono don’t get sick, or have such mild symptoms they don’t know they have it. EBV is usually in the body 30-50 days before an infected person develops symptoms. Surprisingly, 80-95% of adults in the US have been infected by the time they’re 40, but only about 20% know they’ve had mono.', 'Avoiding someone with mono can be hard because infected individuals often do not show symptoms. Because the incubation period is so long, a person can be contagious 1-2 months before showing any symptoms, and some people don’t show symptoms at all. Even after signs of mono have disappeared, a person may still be producing the virus. A strong immune system, maintained by healthy diet, exercise, and adequate sleep, can help you from getting ill. Fortunately, mono is not very contagious, and is usually only passed through intimate contact, such as kissing. [top]', 'Mono is a virus, so antibiotics won’t help. Make sure you get plenty of rest, eat healthy foods, avoid alcohol (because your liver may be inflamed and drinking weakens immune responses), drink plenty of fluids, take aspirin or an aspirin substitute to reduce pain and fever, gargle salt water to relieve sore throat, and avoid strenuous activity. Because your spleen may be swollen, it is important not to engage in contact sports which could rupture your spleen. Returning to normal activity too quickly increases your chances of relapse.', '2020-11-25 15:28:07'),
(15, 'test', 'test', 'test', 'test', 'test', '2021-05-02 07:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `dId` int(11) NOT NULL,
  `dName` varchar(255) NOT NULL,
  `dEmail` varchar(255) NOT NULL,
  `dAddress` varchar(255) NOT NULL,
  `dMobile` varchar(255) NOT NULL,
  `dSpecialization` varchar(255) NOT NULL,
  `dSpecializationInfo` text NOT NULL,
  `dProfileImg` varchar(255) NOT NULL,
  `dFee` int(11) NOT NULL,
  `dMadeOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `dPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`dId`, `dName`, `dEmail`, `dAddress`, `dMobile`, `dSpecialization`, `dSpecializationInfo`, `dProfileImg`, `dFee`, `dMadeOn`, `dPassword`) VALUES
(25, 'Dr. Bailey Sheehan', 'bailey@gmail.com', '12345 St.', '09510192919', 'Cardiologists', 'They’re experts on the heart and blood vessels. You might see them for heart failure, a heart attack, high blood pressure, or an irregular heartbeat.', '18060193915fd70717276e0.jpeg', 5000, '2020-12-14 06:32:55', '$2y$10$QvPidR/ro2fmrJK9jWLIe.6qbMybpqPoNXK1P5bNuFI9zEKWh18g6'),
(26, 'Dr. Ayaz Lewis', 'ayaz@gmail.com', '123456 St.', '09223345131', 'Dermatologists', 'Have problems with your skin, hair, nails? Do you have moles, scars, acne, or skin allergies? Dermatologists can help.', '868689955fd707dcc8dee.png', 6000, '2020-12-14 06:36:12', '$2y$10$lhCSeb2zogA2tT8m6jC7MOZSh.1A0nM1JDCrHUe.DbdM/e2TXVGum'),
(27, 'Dr. Caspar Mclean', 'caspar@gmail.com', '123 St.', '09123463542', 'Immunologists', 'They treat immune system disorders such as asthma, eczema, food allergies, insect sting allergies, and some autoimmune diseases.', '16468818425fd7082062b8e.jpeg', 5000, '2020-12-14 06:37:20', '$2y$10$m6nt2dTAXqqiDTBcxNyCWeyCEU2m6t.V1f7ioxBzw10Sl2RBkyo2C');

-- --------------------------------------------------------

--
-- Table structure for table `ffmedicaldisease`
--

CREATE TABLE `ffmedicaldisease` (
  `md_id` int(11) NOT NULL,
  `md_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ffmedicaldisease`
--

INSERT INTO `ffmedicaldisease` (`md_id`, `md_name`) VALUES
(1, 'diabetes'),
(2, 'hypertension'),
(3, 'cancer'),
(4, 'stroke'),
(5, 'heartTrouble'),
(6, 'arthritis'),
(7, 'convulsion'),
(8, 'bleeding'),
(9, 'acuteInfections'),
(10, 'venereal'),
(11, 'hereditary');

-- --------------------------------------------------------

--
-- Table structure for table `loginlog`
--

CREATE TABLE `loginlog` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `try_time` bigint(20) NOT NULL,
  `patient_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medicalinformation`
--

CREATE TABLE `medicalinformation` (
  `medInfoId` int(11) NOT NULL,
  `pId` int(11) NOT NULL,
  `pName` varchar(255) NOT NULL,
  `pAge` int(11) NOT NULL,
  `pBloodType` varchar(255) NOT NULL,
  `pWeight` int(11) NOT NULL,
  `pHeight` int(11) NOT NULL,
  `pAllergy` varchar(255) NOT NULL,
  `pMedicalInfo` varchar(255) NOT NULL,
  `pValidInfo` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `msgId` int(11) NOT NULL,
  `msgPatientId` int(11) NOT NULL,
  `msgPatientName` varchar(255) NOT NULL,
  `msgContent` text NOT NULL,
  `msgMadeOn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nurse_receptionist`
--

CREATE TABLE `nurse_receptionist` (
  `nId` int(11) NOT NULL,
  `nName` varchar(255) NOT NULL,
  `nEmail` varchar(255) NOT NULL,
  `nAddress` varchar(255) NOT NULL,
  `nMobile` varchar(255) NOT NULL,
  `nProfileImg` varchar(255) NOT NULL,
  `nPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nurse_receptionist`
--

INSERT INTO `nurse_receptionist` (`nId`, `nName`, `nEmail`, `nAddress`, `nMobile`, `nProfileImg`, `nPassword`) VALUES
(6, 'Mary Jane Watson', 'jane@gmail.com', '123457 Main St.', '09523575333', '11452238395fe6d14e22426.jpeg', '$2y$10$WI5HxWrQWttkSNTE0bVqZevDNJR7soF9OhhdRUihEpkDL4CzWxspG'),
(10, 'Test', 'test@gmail.com', '12345 Main St.', '+639550596223', '11249402855fdc3dde397b0.jpeg', '$2y$10$Gw.Y7WrvEiNfm0RxX1jkw.4YbJFi/Whfk4cVOTHcM78pqTckbBg56');

-- --------------------------------------------------------

--
-- Table structure for table `patientappointment`
--

CREATE TABLE `patientappointment` (
  `pId` int(11) NOT NULL,
  `pName` varchar(255) NOT NULL,
  `pEmail` varchar(255) NOT NULL,
  `pAddress` varchar(255) NOT NULL,
  `pAge` int(11) NOT NULL,
  `pGender` varchar(255) NOT NULL,
  `pMobile` varchar(255) NOT NULL,
  `pPassword` varchar(255) NOT NULL,
  `pProfile` varchar(255) NOT NULL,
  `pMadeOn` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `returnee_patient`
--

CREATE TABLE `returnee_patient` (
  `rpId` int(11) NOT NULL,
  `pId` int(11) NOT NULL,
  `pName` varchar(100) NOT NULL,
  `pEmail` varchar(100) NOT NULL,
  `pAddress` varchar(255) NOT NULL,
  `pMobile` varchar(100) NOT NULL,
  `pDoctor` varchar(100) NOT NULL,
  `pPrescription` varchar(255) NOT NULL,
  `pDisease` varchar(100) NOT NULL,
  `pTotalAmount` int(11) NOT NULL,
  `pStatus` varchar(100) NOT NULL,
  `pAmountPay` int(11) NOT NULL,
  `pChange` int(11) NOT NULL,
  `rpMadeOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `labTest` varchar(255) NOT NULL,
  `labResult` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `walkinpatient`
--

CREATE TABLE `walkinpatient` (
  `walkInId` int(11) NOT NULL,
  `walkInName` varchar(255) NOT NULL,
  `walkInEmail` varchar(255) NOT NULL,
  `walkInAddress` varchar(255) NOT NULL,
  `walkInAge` int(11) NOT NULL,
  `walkInGender` varchar(255) NOT NULL,
  `walkInMobile` varchar(255) NOT NULL,
  `walkInDoctor` varchar(255) NOT NULL,
  `walkInPrescription` text NOT NULL,
  `walkInDisease` varchar(255) NOT NULL,
  `walkInTotalPay` varchar(255) NOT NULL,
  `walkInDischarged` varchar(255) NOT NULL DEFAULT '0',
  `walkInStatus` varchar(100) NOT NULL,
  `walkInMadeOn` timestamp NOT NULL DEFAULT current_timestamp(),
  `doctorFee` int(11) NOT NULL,
  `medicineFee` int(11) NOT NULL DEFAULT 0,
  `labTest` text NOT NULL,
  `labResult` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`aId`);

--
-- Indexes for table `bloodtype`
--
ALTER TABLE `bloodtype`
  ADD PRIMARY KEY (`bloodType_id`);

--
-- Indexes for table `discharged_patient`
--
ALTER TABLE `discharged_patient`
  ADD PRIMARY KEY (`dpId`);

--
-- Indexes for table `diseases_treatment`
--
ALTER TABLE `diseases_treatment`
  ADD PRIMARY KEY (`dtId`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`dId`);

--
-- Indexes for table `ffmedicaldisease`
--
ALTER TABLE `ffmedicaldisease`
  ADD PRIMARY KEY (`md_id`);

--
-- Indexes for table `loginlog`
--
ALTER TABLE `loginlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicalinformation`
--
ALTER TABLE `medicalinformation`
  ADD PRIMARY KEY (`medInfoId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`msgId`);

--
-- Indexes for table `nurse_receptionist`
--
ALTER TABLE `nurse_receptionist`
  ADD PRIMARY KEY (`nId`);

--
-- Indexes for table `patientappointment`
--
ALTER TABLE `patientappointment`
  ADD PRIMARY KEY (`pId`);

--
-- Indexes for table `returnee_patient`
--
ALTER TABLE `returnee_patient`
  ADD PRIMARY KEY (`rpId`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `walkinpatient`
--
ALTER TABLE `walkinpatient`
  ADD PRIMARY KEY (`walkInId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `aId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bloodtype`
--
ALTER TABLE `bloodtype`
  MODIFY `bloodType_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `discharged_patient`
--
ALTER TABLE `discharged_patient`
  MODIFY `dpId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diseases_treatment`
--
ALTER TABLE `diseases_treatment`
  MODIFY `dtId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `dId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `ffmedicaldisease`
--
ALTER TABLE `ffmedicaldisease`
  MODIFY `md_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `loginlog`
--
ALTER TABLE `loginlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `medicalinformation`
--
ALTER TABLE `medicalinformation`
  MODIFY `medInfoId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `msgId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nurse_receptionist`
--
ALTER TABLE `nurse_receptionist`
  MODIFY `nId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `patientappointment`
--
ALTER TABLE `patientappointment`
  MODIFY `pId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returnee_patient`
--
ALTER TABLE `returnee_patient`
  MODIFY `rpId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `walkinpatient`
--
ALTER TABLE `walkinpatient`
  MODIFY `walkInId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
