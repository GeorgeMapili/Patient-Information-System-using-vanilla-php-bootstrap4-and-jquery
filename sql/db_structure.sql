/* Create a database */
CREATE DATABASE pis;

USE pis;

/* Create admin table */
CREATE TABLE admin(
`ad_id` int(11) NOT NULL AUTO_INCREMENT,
`ad_name` varchar(255) NOT NULL,
`ad_email` varchar(255) NOT NULL,
`ad_pass` varchar(255) NOT NULL,
PRIMARY KEY(`ad_id`)
);

/* Create appointment table */
CREATE TABLE appointment(
`aId` int(11) NOT NULL AUTO_INCREMENT,
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
`aMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`pPrescription` varchar(255) NOT NULL,
`pTotalPay` int(5) NOT NULL,
`pDischarge` int(11) NOT NULL DEFAULT 0,
`pMedicineFee` int(11) NOT NULL,
`pAmountPay` int(11) NOT NULL,
`pChange` int(11) NOT NULL,
`patientStatus` varchar(50) NOT NULL,
`dischargedOn` varchar(50) NOT NULL,
PRIMARY KEY(`aId`)
);

/* Create bloodtype table */
CREATE TABLE bloodtype(
`bloodType_id` int(11) NOT NULL AUTO_INCREMENT,
`bloodType` varchar(50) NOT NULL,
PRIMARY KEY(`bloodType_id`)
);

/* Create discharged_patient table */
CREATE TABLE discharged_patient(
`dpId` int(11) NOT NULL AUTO_INCREMENT,
`pId` int(11) NOT NULL,
`pName` varchar(255) NOT NULL,
`pEmail` varchar(255) NOT NULL,
`pAddress` varchar(255) NOT NULL,
`pMobile` varchar(255) NOT NULL,
`pRoomNumber` int(5) NOT NULL,
`pDoctor` varchar(255) NOT NULL,
`pPrescription` TEXT NOT NULL,
`pDisease` varchar(255) NOT NULL,
`pTotalAmount` int(11) NOT NULL,
`pStatus` varchar(100) NOT NULL,
`pMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`pAmountPay` int(11) NOT NULL,
`pChange` int(11) NOT NULL,
PRIMARY KEY(`dpId`)
);

/* Create diseases_treatment table */
CREATE TABLE diseases_treatment(
`dtId` int(11) NOT NULL AUTO_INCREMENT,
`dtName` varchar(255) NOT NULL,
`dtMeaning` TEXT NOT NULL,
`dtSymptoms` TEXT NOT NULL,
`dtPrevention` TEXT NOT NULL,
`dtTreatment` TEXT NOT NULL,
`dtMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(`dtId`)
);

/* Create doctor table */
CREATE TABLE doctor(
`dId` int(11) NOT NULL AUTO_INCREMENT,
`dName` varchar(255) NOT NULL,
`dEmail` varchar(255) NOT NULL,
`dAddress` varchar(255) NOT NULL,
`dMobile` varchar(255) NOT NULL,
`dSpecialization` varchar(255) NOT NULL,
`dSpecializationInfo` TEXT NOT NULL,
`dProfileImg` varchar(255) NOT NULL,
`dFee` int(11) NOT NULL,
`dMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`dPassword` varchar(255) NOT NULL,
PRIMARY KEY(`dId`)
);

/* Create ffmedicaldisease table */
CREATE TABLE ffmedicaldisease(
`md_id` int(11) NOT NULL AUTO_INCREMENT,
`md_name` varchar(255) NOT NULL,
PRIMARY KEY(`md_id`)
);

/* Create loginlog table */
CREATE TABLE loginlog(
`id` int(11) NOT NULL AUTO_INCREMENT,
`ip_address` varchar(255) NOT NULL,
`try_time` bigint(20) NOT NULL,
`patient_id` int(11) NOT NULL,
PRIMARY KEY(`id`) 
);

/* Create medicalinformation table */
CREATE TABLE medicalinformation(
`medInfoId` int(11) NOT NULL AUTO_INCREMENT,
`pId` int(11) NOT NULL,
`pName` varchar(255) NOT NULL,
`pAge` int(11) NOT NULL,
`pBloodType` varchar(255) NOT NULL,
`pWeight` int(11) NOT NULL,
`pHeight` int(11) NOT NULL,
`pAllergy` varchar(255) NOT NULL,
`pMedicalInfo` varchar(255) NOT NULL,
`pValidInfo` int(11) NOT NULL,
PRIMARY KEY(`medInfoId`)
);

/* Create message table */
CREATE TABLE message(
`msgId` int(11) NOT NULL AUTO_INCREMENT,
`msgPatientId` int(11) NOT NULL,
`msgPatientName` varchar(255) NOT NULL,
`msgContent` TEXT NOT NULL,
`msgMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(`msgId`)
);

/* Create nurse_receptionist table */
CREATE TABLE nurse_receptionist(
`nId` int(11) NOT NULL AUTO_INCREMENT,
`nName` varchar(255) NOT NULL,
`nEmail` varchar(255) NOT NULL,
`nAddress` varchar(255) NOT NULL,
`nMobile` varchar(255) NOT NULL,
`nProfileImg` varchar(255) NOT NULL,
`nPassword` varchar(255) NOT NULL,
PRIMARY KEY(`nId`)
);

/* Create patientappointment table */
CREATE TABLE patientappointment(
`pId` int(11) NOT NULL AUTO_INCREMENT,
`pName` varchar(255) NOT NULL,
`pEmail` varchar(255) NOT NULL,
`pAddress` varchar(255) NOT NULL,
`pAge` int(11) NOT NULL,
`pGender` varchar(255) NOT NULL,
`pMobile` varchar(255) NOT NULL,
`pPassword` varchar(255) NOT NULL,
`pProfile` varchar(255) NOT NULL,
`pMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(`pId`)
);

/* Create returnee_patient table */
CREATE TABLE returnee_patient(
`rpId` int(11) NOT NULL AUTO_INCREMENT,
`pId` int(11) NOT NULL,
`pName` varchar(100) NOT NULL,
`pEmail` varchar(100) NOT NULL,
`pAddress` varchar(255) NOT NULL,
`pMobile` varchar(100) NOT NULL,
`pRoomNumber` int(5) NOT NULL,
`pDoctor` varchar(100) NOT NULL,
`pPrescription` varchar(255) NOT NULL,
`pDisease` varchar(100) NOT NULL,
`pTotalAmount` int(11) NOT NULL,
`pStatus` varchar(100) NOT NULL,
`pAmountPay` varchar(11) NOT NULL,
`pChange` int(11) NOT NULL,
`rpMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(`rpId`)
);

/* Create rooms table */
CREATE TABLE rooms(
`room_id` int(11) NOT NULL AUTO_INCREMENT,
`room_number` int(11) NOT NULL,
`room_fee` int(11) NOT NULL,
`room_status` varchar(100) NOT NULL DEFAULT 'available',
PRIMARY KEY(`room_id`)
);

/* Create walkinpatient table */
CREATE TABLE walkinpatient(
`walkInId` int(11) NOT NULL AUTO_INCREMENT,
`walkInName` varchar(255) NOT NULL,
`walkInEmail` varchar(255) NOT NULL,
`walkInAddress` varchar(255) NOT NULL,
`walkInAge` int(11) NOT NULL,
`walkInGender` varchar(255) NOT NULL,
`walkInMobile` varchar(255) NOT NULL,
`walkInDoctor` varchar(255) NOT NULL,
`walkInPrescription` TEXT NOT NULL,
`walkInDisease` varchar(255) NOT NULL,
`walkInTotalPay` varchar(255) NOT NULL,
`walkInDischarged` varchar(255) NOT NULL DEFAULT 0,
`walkInRoomNumber` int(5) NOT NULL,
`walkInStatus` varchar(100) NOT NULL,
`walkInMadeOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
`doctorFee` int(11) NOT NULL,
`roomFee` int(11) NOT NULL,
`medicineFee` int(11) NOT NULL,
PRIMARY KEY(`walkInId`)
);

-- Add data in the admin table
INSERT INTO admin(ad_name,ad_email,ad_pass) VALUES('George Mapili', 'adminofficial@admin.com', '$2y$10$e/zvqHjfz/4MKCaPbHp7ZupjFrDre7smW0.4wyciRsSn65ap4OVA6');

-- Add data in the bloodtype table
INSERT INTO bloodtype(bloodType)VALUES 
('A+'),
('A-'),
('B+'),
('B-'),
('O+'),
('O-'),
('AB+'),
('AB-');

-- Add data in the diseases_treatment table
INSERT INTO diseases_treatment(dtName,dtMeaning,dtSymptoms,dtPrevention,dtTreatment) VALUES
('Fever', "A fever is a temporary increase in your body temperature, often due to an illness. Having a fever is a sign that something out of the ordinary is going on in your body.

For an adult, a fever may be uncomfortable, but usually isn't a cause for concern unless it reaches 103 F (39.4 C) or higher. For infants and toddlers, a slightly elevated temperature may indicate a serious infection.

Fevers generally go away within a few days. A number of over-the-counter medications lower a fever, but sometimes it's better left untreated. Fever seems to play a key role in helping your body fight off a number of infections.", "Sweating 
Chills and shivering
Headache
Muscle aches
Loss of appetite
Irritability
Dehydration
General weakness", "Wash your hands often and teach your children to do the same, especially before eating, after using the toilet, after spending time in a crowd or around someone who's sick, after petting animals, and during travel on public transportation.
Show your children how to wash their hands thoroughly, covering both the front and back of each hand with soap and rinsing completely under running water.
Carry hand sanitizer with you for times when you don't have access to soap and water.
Try to avoid touching your nose, mouth or eyes, as these are the main ways that viruses and bacteria can enter your body and cause infection.
Cover your mouth when you cough and your nose when you sneeze, and teach your children to do likewise. Whenever possible, turn away from others when coughing or sneezing to avoid passing germs along to them.
Avoid sharing cups, water bottles and utensils with your child or children.", "In the case of a high fever, or a low fever that's causing discomfort, your doctor may recommend an over-the-counter medication, such as acetaminophen (Tylenol, others) or ibuprofen (Advil, Motrin IB, others).

Use these medications according to the label instructions or as recommended by your doctor. Be careful to avoid taking too much. High doses or long-term use of acetaminophen or ibuprofen may cause liver or kidney damage, and acute overdoses can be fatal. If your child's fever remains high after a dose, don't give more medication; call your doctor instead.

Don't give aspirin to children, because it may trigger a rare, but potentially fatal, disorder known as Reye's syndrome."),
('Allergies', "Allergies occur when your immune system reacts to a foreign substance such as pollen, bee venom or pet dander or a food that doesn't cause a reaction in most people.

Your immune system produces substances known as antibodies. When you have allergies, your immune system makes antibodies that identify a particular allergen as harmful, even though it isn't. When you come into contact with the allergen, your immune system's reaction can inflame your skin, sinuses, airways or digestive system.

The severity of allergies varies from person to person and can range from minor irritation to anaphylaxis — a potentially life-threatening emergency. While most allergies can't be cured, treatments can help relieve your allergy symptoms.", "Because there are so many possible causes, the symptoms of allergies vary widely. Airborne allergens, like pollen and pet dander, are likely to cause:

Eye irritation
Runny nose
Stuffy nose
Puffy, watery eyes
Sneezing
Inflamed, itchy nose and throat", "Prevention
Avoid the outdoors between 5-10 a.m. and save outside activities for late afternoon or after a heavy rain, when pollen levels are lower.
Keep windows in your living spaces closed to lower exposure to pollen.
To keep cool, use air conditioners and avoid using window and attic fans.
Wear a medical alert bracelet or other means to communicate to others about your allergy in case of a reaction.
Discuss a prescription for epinephrine (e.g., EpiPen) with your healthcare provider, if you have risk of serious allergic reaction.
Review product labels carefully before buying or consuming any item
Know what you are eating or drinking.", "The easiest and most effective way to treat allergies is to get rid of or avoid the cause. Where unavoidable, some lifestyle changes can reduce your allergy symptoms. For example, if you are allergic to dust mites, make an effort to keep your room clean and free of dust by frequent vacuuming, dusting, and washing of bedding.

For pollen allergies, avoid being outside when pollen counts are high and keep the windows to your room shut.

Because it is very difficult to avoid certain allergens, medication may be necessary to lessen symptoms caused by allergens, other than food and drugs."),
('Cold and Flu', "Colds and influenza (flu) are the most common illnesses among college students. 

Both of these illnesses are upper respiratory infections, meaning they involve your nose, throat, and lungs. Viruses cause both colds and flu by increasing inflammation of the membranes in the nose and throat.", "Flu symptoms come on suddenly and affect the body all over. Flu symptoms are usually more serious than a cold and include:

headache,
more intense pain and fatigue, and
more severe, often dry cough.", "The flu is probably only contagious during the first three days of illness, and the incubation period is 24-72 hours, meaning you might not show symptoms for three days after contracting the virus. It is rare to catch a cold virus through the air – most transmission occurs via hand-to-hand contact. To prevent colds, flu, and other illnesses, follow these tips:

Wash your hands often (which is good advice for keeping healthy in any situation). Keep them away from your nose, eyes, and mouth. Use an instant hand sanitizer when you can’t wash your hands.
Get regular exercise and eat well.
Follow good sleep habits.
Get a flu shot each fall (offered to all students at a lower cost by UHS each fall) ", "If any problem is causing you discomfort, you should seek medical care.

Seek medical attention promptly if you have:

a fever of 102° F or greater (which may indicate a more serious infection),
a persisting cough, especially with a significant fever (which could indicate pneumonia),
a persistent sore throat (especially if runny nose does not develop - which could indicate a strep infection), or
any cold lasting more than 10 days."),
('Diarrhea', "Everyone occasionally has diarrhea — loose, watery and more-frequent bowel movements. You might also have abdominal cramps and produce a greater volume of stool. Diarrhea varies in specific symptoms, severity and duration.

Acute diarrhea, which lasts from two days to two weeks, is typically caused by a bacterial, viral or parasitic infection of some sort.

Chronic diarrhea lasts longer than does acute diarrhea, generally more than four weeks. Chronic diarrhea can indicate a serious disorder, such as ulcerative colitis or Crohn's disease, or a less serious condition, such as irritable bowel syndrome.", "watery, loose stools
frequent bowel movements
cramping or pain in the abdomen, nausea, bloating
possibly fever or bloody stools, depending on the cause", "Avoid foods that are milk-based, greasy, high-fiber, or very sweet because these are likely to aggravate diarrhea.
Avoid caffeine and alcohol.
Do not eat solid food if you have signs of dehydration (thirst, light-headed, dark urine). Instead, drink about 2 cups of clear fluids per hour (if vomiting isn’t present), such as sports drinks and broth. Water alone is not enough because your body needs sodium and sugar to replace what it’s losing.", "Begin eating normal meals within 12 hours, but stick to food that is bland and won’t irritate your intestine. Some doctors suggest the “BRAT“ diet which includes foods that are low in fiber, fat, and sugar. BRAT stands for Bananas, Rice, Applesauce, and Toast.
Use over-the-counter lactobacillus acidophilus capsules or tablets. These bacteria help maintain a healthy intestine, and are found in yogurt with live active cultures.
Decrease level of exercise until symptoms are gone.
Over-the-counter drugs, such as Imodium A-D, should only be used if absolutely necessary because it is important to let diarrhea flush out the bacteria or parasite that’s causing the infection."),
('Headaches', "Everyone suffers the occasional mild headache, but if you experience debilitating pain and/or abnormally frequent headaches, you probably want to find relief. There are countless causes of headaches, which differ for each person, so you’ll have to do some experimenting to figure out the cause of your pain. Fortunately, the vast majority of headaches are primary headaches, not the result of underlying medical conditions. The three most common types are cluster, tension-type, and migraine.

A cluster headache affects a specific point of the head, often the eye, and is characterized by sharp, piercing pain. Migraine and tension-type headaches are far more common. “Tension“ headaches are now called “tension-type“ headaches because pain is not only caused by stress, but also poor posture, depression, and even sexual activity. In fact, recent studies have shown a connection between low serotonin levels and so-called “tension“ headaches.", "Just as the causes vary for each headache sufferer, so do the symptoms and severity of pain. Health professionals can often diagnose the type of headache you suffer based on your symptoms.

Symptoms of a migraine:

pulsing or throbbing quality
begins with intense pain on one side of the head, which eventually spreads
felt on one or both sides of the head
lasts several hours
severe enough to interfere with routine activities
may be accompanied by nausea or vomiting
sometimes preceded by visual changes, such as an aura of zigzag lines or flashes of light
light and noise can make the headache worse, while sleep tends to relieve symptoms", "Be aware of early symptoms so you can try to stop the headache as soon as it begins.
Don’t smoke, and if you do, quit.
Don’t skip meals.
Cut down on caffeine and alcohol (reduce caffeine intake gradually because withdrawal may cause headaches).
Stop all over-the-counter medicines and herbal remedies.
Maintain a regular eating and sleeping schedule.
Exercise regularly.
Incorporate relaxation activities into your daily routine, such as meditation, yoga, stretching exercises, and massage
Improve your posture, possibly by adjusting your workstation.", "Ice pack held over the eyes or forehead
Heating pad set on low or hot shower to relax tense neck and shoulder muscles
Sleep, or at least resting in a dark room
Taking breaks from stressful situations
Regular exercise to increase endorphin levels and relax muscles. Even if you already have a headache, exercising may relieve the pain. However, intense exercise may bring on a headache.
Occasional use of over-the-counter medicines such as acetaminophen, ibuprofen, or aspirin can relieve both migraine and tension headaches. *
Prescription drugs for severe headaches"),
('Mononucleosis', "Mononucleosis is an illness caused by the Epstein-Barr virus (EBV), which is spread through saliva.", "The most well known symptom of “mono“ is extreme fatigue, forcing the infected person to nap frequently. If you experience such extreme fatigue accompanied by other symptoms, such as swollen lymph glands and spleen, sore throat, fever, loss of appetite, and muscle aches, you may want to get tested for mono. The basis for testing whether you have mono is the presence of antibodies produced by white blood cells. Many people infected with mono don’t get sick, or have such mild symptoms they don’t know they have it. EBV is usually in the body 30-50 days before an infected person develops symptoms. Surprisingly, 80-95% of adults in the US have been infected by the time they’re 40, but only about 20% know they’ve had mono.", "Avoiding someone with mono can be hard because infected individuals often do not show symptoms. Because the incubation period is so long, a person can be contagious 1-2 months before showing any symptoms, and some people don’t show symptoms at all. Even after signs of mono have disappeared, a person may still be producing the virus. A strong immune system, maintained by healthy diet, exercise, and adequate sleep, can help you from getting ill. Fortunately, mono is not very contagious, and is usually only passed through intimate contact, such as kissing. [top]", "Mono is a virus, so antibiotics won’t help. Make sure you get plenty of rest, eat healthy foods, avoid alcohol (because your liver may be inflamed and drinking weakens immune responses), drink plenty of fluids, take aspirin or an aspirin substitute to reduce pain and fever, gargle salt water to relieve sore throat, and avoid strenuous activity. Because your spleen may be swollen, it is important not to engage in contact sports which could rupture your spleen. Returning to normal activity too quickly increases your chances of relapse.");

-- Add data in the doctor table
INSERT INTO doctor(dName,dEmail,dAddress,dMobile,dSpecialization,dSpecializationInfo,dProfileImg,dFee,dPassword) VALUES
('Dr. Bailey Sheehan', 'bailey@gmail.com', '12345 St.', '09510192919', 'Cardiologists', "They’re experts on the heart and blood vessels. You might see them for heart failure, a heart attack, high blood pressure, or an irregular heartbeat.", "18060193915fd70717276e0.jpeg", 5000, "$2y$10$QvPidR/ro2fmrJK9jWLIe.6qbMybpqPoNXK1P5bNuFI9zEKWh18g6"),
('Dr. Ayaz Lewis', 'ayaz@gmail.com', '123456 St.', '09223345131', 'Dermatologists', "Have problems with your skin, hair, nails? Do you have moles, scars, acne, or skin allergies? Dermatologists can help.", "868689955fd707dcc8dee.png", 6000 , "$2y$10$lhCSeb2zogA2tT8m6jC7MOZSh.1A0nM1JDCrHUe.DbdM/e2TXVGum"),
('Dr. Caspar Mclean', 'caspar@gmail.com', '123 St.', '09123463542', 'Immunologists', "They treat immune system disorders such as asthma, eczema, food allergies, insect sting allergies, and some autoimmune diseases.", "16468818425fd7082062b8e.jpeg", 5000, "$2y$10$m6nt2dTAXqqiDTBcxNyCWeyCEU2m6t.V1f7ioxBzw10Sl2RBkyo2C"),
('Dr. Tom Corona', 'tom@gmail.com', '1234567 St.', '0953435731', 'Anesthesiologists', "These doctors give you drugs to numb your pain or to put you under during surgery, childbirth, or other procedures. They monitor your vital signs while you’re under anesthesia.", "16668826975fd70893d22b4.jpeg", 8000, "$2y$10$9Crs9UQWxFPs8hamyRkRJugPZlIibKLh8.1u54xCG2ZzKwHvZGktq"),
('Dr. Lola Whelan', 'lola@gmail.com', '12345678 St.', '09123578324', 'Neurologists', "These are specialists in the nervous system, which includes the brain, spinal cord, and nerves. They treat strokes, brain and spinal tumors, epilepsy, Parkinson's disease, and Alzheimer's disease.", "17884721315fd708eba9ddc.jpeg", 10000, "$2y$10$89V7nKL7iLjUmG1utso2CeTpHUPd/tlWs16qges4ekQ8oSz1Yk2r."),
('Dr. Anoushka Delacruz', 'anoushka@gmail.com', '987546 St.', '09563214578', 'Endocrinologists', "These are experts on hormones and metabolism. They can treat conditions like diabetes, thyroid problems, infertility, and calcium and bone disorders.", "1243365995fd7095809583.jpeg", 8000, "$2y$10$ZMPvLQWdMBjbT1CWOqqdoOwYqHVU9WEkltk9ubNLc2shS/cje1Fy."),
('Dr. Reilly Leblanc', 'reilly@gmail.com', '3475893749 St.', '09564257815', 'Gastroenterologists', "They’re specialists in digestive organs, including the stomach, bowels, pancreas, liver, and gallbladder. You might see them for abdominal pain, ulcers, diarrhea, jaundice, or cancers in your digestive organs. They also do a colonoscopy and other tests for colon cancer.", '14631379195fd709cb51bc6.jpeg', 7000, "$2y$10$n.CI6ZgCQnjmCvnhi9jRs.g30C7okiiirSVEsRE6COOwTY4CHG6ny"),
('Dr. Lexie Robins', 'lexie@gmail.com', '457883 St.', '09456432172', 'Colon Surgeons', "You would see these doctors for problems with your small intestine, colon, and bottom. They can treat colon cancer, hemorrhoids, and inflammatory bowel disease.", '5867497055fd70a1e4daf0.jpeg', 13000, "$2y$10$zd2wmk5mHCjVUVHoqBHAtOAwlNDocDgDkzU9FXXNQzlruhD.N/v.u"),
('Dr. Jose Rizal', 'jose@gmail.com', '12345 Main St.', '+639550596633', 'Psychiatrist', "A psychiatrist is a physician who specializes in psychiatry, the branch of medicine devoted to the diagnosis, prevention, study, and treatment of mental disorders.", '15001381415fe6e74ee9ace.jpeg', 6000 , "$2y$10$BsR7bZefAuDa6GzgGSHFeu.eXLv5s9LmEBneCI7vG8xLFGE6DRqpO");

-- Add data in the ffmedicaldisease table
INSERT INTO ffmedicaldisease(md_name)VALUES
('diabetes'),
('hypertension'),
('cancer'),
('stroke'),
('heartTrouble'),
('arthritis'),
('convulsion'),
('bleeding'),
('acuteInfections'),
('venereal'),
('hereditary');

-- Add data in the nurse_receptionist
INSERT INTO nurse_receptionist(nName,nEmail,nAddress,nMobile,nProfileImg,nPassword)VALUES
('Mary Jane Watson','jane@gmail.com', '123457 Main St.', '09523575333', '11452238395fe6d14e22426.jpeg', "$2y$10$WI5HxWrQWttkSNTE0bVqZevDNJR7soF9OhhdRUihEpkDL4CzWxspG"),
('Test', 'test@gmail.com', '12345 Main St.', '+639550596223', '11249402855fdc3dde397b0.jpeg', "$2y$10$Gw.Y7WrvEiNfm0RxX1jkw.4YbJFi/Whfk4cVOTHcM78pqTckbBg56");

-- Add data in the patientappointment table
INSERT INTO patientappointment(pName,pEmail,pAddress,pAge,pGender,pMobile,pPassword,pProfile)VALUES
('Test', 'test@gmail.com', '123456789 Main St.', 26, 'female', '+639550596633', '$2y$10$s2LbH.I72xrA68JMp/7uK.0gMJ3BUlETlo6DWc.q49BQCdQEh4nNe','11253286695fe0a42101412.jpeg'),
('TestOne', 'testone@gmail.com', '123456789 Main St.', 15, 'female', '+639550594444', '$2y$10$QYyTcuxC./LPr3UnRFUEoeBhnU0ZNr.RAyMFhhDsUte0qqwQZw61a', '16910215945fe0a47d78480.jpeg'),
('TestTwo', 'test2@gmail.com', '123456789 Main St.', 8, 'male', '+639550596888', '$2y$10$hHZ0EqFSfa/0XZBfyj5m2e9Yuv7N4ONW/eUgBimoyBKBKkrYCjq.G', '10026098655fe0a48ab83dd.jpeg'),
('TestThree', 'testthree@gmail.com', '123456789 Main St.', 45, 'male', '+639550596898', '$2y$10$wUYS46EsspVTmMtCM/3lYu6vaXGa.gXCV.nx9zypbv/Te2DzBMu2e', '3510427535fe0cb65b7d83.jpeg'),
('TestFour', 'testfour@gmail.com', '1234567 Main St.', 25, 'male', '+639550596333', '$2y$10$BspJN3qZ6koPLZH04tWi5eY6urpiYgAk3.7ZlfEuE39/M5.pJSY0.', '7576211125fe1c4d923b50.jpeg');

-- Add data in the rooms table 
INSERT INTO rooms(room_number,room_fee,room_status)VALUES
(101,1500,'available'),
(102,1500,'available'),
(103,1500,'available'),
(104,1500,'available'),
(105,1500,'available'),
(106,1500,'available'),
(107,1500,'available'),
(108,1500,'available'),
(109,1500,'available'),
(110,1500,'available'),
(201,1500,'available');