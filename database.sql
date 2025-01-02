CREATE DATABASE english;

USE english;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(30) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    word VARCHAR(150) NOT NULL,
    translation VARCHAR(150),
    definition VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    word_id INT NOT NULL,
    sentence VARCHAR(255) NOT NULL,
    translation VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE
);

CREATE TABLE texts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    content VARCHAR(2000) NOT NULL,
    translation VARCHAR(2000) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE liked_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    word_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE
);

CREATE TABLE liked_sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    sentence_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sentence_id) REFERENCES sentences(id) ON DELETE CASCADE
);

CREATE TABLE liked_texts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    text_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (text_id) REFERENCES texts(id) ON DELETE CASCADE
);

INSERT INTO
    users (fullname, email, username, password)
VALUES
    (
        'iqbolshoh ilhomjonov',
        'iilhomjonov777@gmail.com',
        'iqbolshoh',
        '29cbffe112a766305c4a49a61e27d7e117c2efc0b2bd31451b3a200c24fd565b'
    ),
    (
        'admin',
        'admin@iqbolshoh.uz',
        'admin',
        '0c138cbe7d1f479abb449366f3cb3dddd52bc104596ff91813c6674cd016896a'
    );

INSERT INTO `words` (`user_id`, `word`, `translation`, `definition`) VALUES
(2, 'apple', 'olma', 'A fruit that is usually round, red, green, or yellow and has a sweet taste.'),
(2, 'ant', 'akar', 'A small insect known for living in colonies and being industrious.'),
(2, 'art', 'sanat', 'The creation of visual, auditory, or performance artifacts that express the creators imagination.'),
(2, 'avenue', 'kocha', 'A broad road in a city or town, often lined with trees.'),
(2, 'airplane', 'samolyot', 'A vehicle with wings and engines that flies in the sky.'),
(2, 'alarm', 'signal', 'A warning sound or signal for alert.'),
(2, 'book', 'kitob', 'A set of written, printed, or blank pages fastened together between a cover.'),
(2, 'ball', 'top', 'A round object used in games and sports.'),
(2, 'bike', 'velosiped', 'A vehicle with two wheels that you ride by pedaling.'),
(2, 'bread', 'non', 'A common food made from flour and water and baked.'),
(2, 'bottle', 'shisha', 'A container with a narrow neck, used to hold liquids.'),
(2, 'box', 'quti', 'A container with flat surfaces and a lid or cover.'),
(2, 'building', 'bino', 'A structure with walls and a roof.'),
(2, 'cat', 'mushuk', 'A small domesticated carnivorous mammal with soft fur.'),
(2, 'car', 'avtomobil', 'A road vehicle powered by an engine, used for transporting people.'),
(2, 'chair', 'stul', 'A piece of furniture for sitting, usually having four legs and a back.'),
(2, 'city', 'shahar', 'A large town or a significant urban area.'),
(2, 'computer', 'kompyuter', 'An electronic device for storing and processing data.'),
(2, 'cake', 'kek', 'A sweet baked food made from flour, sugar, and eggs.'),
(2, 'coat', 'palto', 'A piece of clothing worn over other clothes to keep warm.'),
(2, 'dog', 'it', 'A domesticated carnivorous mammal that typically has a barking sound.'),
(2, 'door', 'esik', 'A hinged, sliding, or revolving barrier at the entrance to a building.'),
(2, 'dance', 'raqqoslik', 'A series of movements and steps performed to music.'),
(2, 'desk', 'stol', 'A piece of furniture with a flat top and usually with drawers.'),
(2, 'doctor', 'shifokor', 'A person who is qualified to treat people who are ill.'),
(2, 'drink', 'ichimlik', 'A liquid for drinking.'),
(2, 'elephant', 'fil', 'A large mammal with a trunk and tusks.'),
(2, 'egg', 'tuxum', 'An oval or round object laid by female birds, reptiles, and other animals.'),
(2, 'engineer', 'muhandis', 'A person who designs, builds, or maintains engines, machines, or structures.'),
(2, 'envelope', 'konvert', 'A flat, usually rectangular container for a letter.'),
(2, 'education', 'talim', 'The process of receiving or giving systematic instruction.'),
(2, 'eye', 'koz', 'An organ that enables vision.'),
(2, 'ear', 'quloq', 'The organ that detects sound.'),
(2, 'fish', 'baliq', 'A cold-blooded vertebrate animal that lives in water.'),
(2, 'flower', 'gul', 'The reproductive structure of flowering plants.'),
(2, 'food', 'ovqat', 'Any nutritious substance that people or animals eat or drink.'),
(2, 'fan', 'ventilyator', 'A device for creating a current of air.'),
(2, 'fire', 'ot', 'A rapid oxidation process producing heat and light.'),
(2, 'fork', 'vilkalar', 'A utensil with two or more prongs for eating or serving food.'),
(2, 'flag', 'bayroq', 'A piece of fabric with a distinctive design used as a symbol.'),
(2, 'goat', 'echki', 'A domesticated ruminant animal with a beard and horns.'),
(2, 'glove', 'qolqop', 'A covering for the hand with separate fingers.'),
(2, 'guitar', 'gitara', 'A stringed musical instrument played by plucking or strumming.'),
(2, 'grass', 'mushak', 'A common plant with narrow leaves.'),
(2, 'gate', 'darvoza', 'A barrier used to close an entrance or exit.'),
(2, 'gold', 'oltin', 'A yellow precious metal.'),
(2, 'game', 'oyin', 'An activity with rules played for entertainment or competition.'),
(2, 'house', 'uy', 'A building for human habitation.'),
(2, 'hat', 'shapka', 'A head covering.'),
(2, 'horse', 'ot', 'A large domesticated animal used for riding or work.'),
(2, 'hospital', 'kasalxona', 'An institution providing medical and surgical treatment.'),
(2, 'honey', 'asal', 'A sweet substance made by bees.'),
(2, 'hair', 'soch', 'The strands growing from the skin of the head or body.'),
(2, 'hand', 'qol', 'The prehensile extremity at the end of the arm.'),
(2, 'ice', 'muz', 'Frozen water.'),
(2, 'insect', 'hasharot', 'A small arthropod animal with six legs.'),
(2, 'island', 'orol', 'A piece of land surrounded by water.'),
(2, 'ink', 'moy', 'A colored fluid used for writing or printing.'),
(2, 'idea', 'goya', 'A thought or suggestion.'),
(2, 'instrument', 'asbob', 'A tool or device for performing a task.'),
(2, 'information', 'malumot', 'Data communicated or received.'),
(2, 'juice', 'sharbati', 'A liquid obtained by squeezing fruit.'),
(2, 'jacket', 'jaket', 'A piece of clothing worn on the upper body.'),
(2, 'jungle', 'jangaldan', 'A dense, tropical forest.'),
(2, 'jewelry', 'zargarlik', 'Decorative items worn on the body.'),
(2, 'jam', 'murabbo', 'A sweet spread made from fruit.'),
(2, 'jump', 'sakrash', 'To propel oneself upward.'),
(2, 'jar', 'bankka', 'A container with a lid used for preserving food.'),
(2, 'key', 'kalit', 'A device used to open a lock.'),
(2, 'king', 'podsho', 'A male ruler of a country.'),
(2, 'kangaroo', 'kangaroo', 'A marsupial with powerful hind legs.'),
(2, 'knife', 'pichoq', 'A cutting tool with a sharp blade.'),
(2, 'kitchen', 'oshxona', 'A room where food is prepared.'),
(2, 'kite', 'qanot', 'A light framework covered with paper or cloth, flown in the wind.'),
(2, 'keyboard', 'klaviatura', 'A set of keys for typing or playing music.'),
(2, 'lion', 'sher', 'A large, wild cat known as the king of beasts.'),
(2, 'lamp', 'chiroq', 'A device for giving light.'),
(2, 'letter', 'xat', 'A written or printed message.'),
(2, 'laptop', 'noutbuk', 'A portable computer.'),
(2, 'lake', 'kol', 'A large body of water surrounded by land.'),
(2, 'lemon', 'limon', 'A yellow citrus fruit with a sour taste.'),
(2, 'light', 'yoruglik', 'The natural agent that stimulates sight and makes things visible.'),
(2, 'monkey', 'maymun', 'A primate with a long tail.'),
(2, 'moon', 'oy', 'The natural satellite of the Earth.'),
(2, 'mountain', 'tog', 'A large landform that rises prominently above its surroundings.'),
(2, 'mouse', 'sichqon', 'A small rodent or a computer input device.'),
(2, 'milk', 'sut', 'A white liquid produced by mammals.'),
(2, 'man', 'erkak', 'An adult human male.'),
(2, 'market', 'bozor', 'A place where goods and services are sold.'),
(2, 'medicine', 'dori', 'A substance used to treat illness.'),
(2, 'moon', 'oy', 'The natural satellite of the Earth.'),
(2, 'milk', 'sut', 'A white liquid produced by mammals.'),
(2, 'mouse', 'sichqon', 'A small rodent or a computer input device.'),
(2, 'man', 'erkak', 'An adult human male.'),
(2, 'market', 'bozor', 'A place where goods and services are sold.'),
(2, 'medicine', 'dori', 'A substance used to treat illness.'),
(2, 'newspaper', 'gazeta', 'A printed publication containing news.'),
(2, 'name', 'ism', 'A word by which a person or thing is known.'),
(2, 'notebook', 'daftar', 'A book with blank pages for writing in.'),
(2, 'night', 'tungi', 'The period of darkness between sunset and sunrise.'),
(2, 'nurse', 'hamshira', 'A person trained to care for the sick or infirm.'),
(2, 'phone', 'telefon', 'A device used to make calls or send messages.'),
(2, 'pen', 'qalam', 'A tool used for writing with ink.'),
(2, 'penguin', 'penguin', 'A flightless bird that lives in the southern hemisphere.'),
(2, 'party', 'partiya', 'A social gathering or celebration.'),
(2, 'pizza', 'pizza', 'A dish consisting of a round, flat base of leavened wheat-based dough topped with tomatoes, cheese, and often other ingredients.'),
(2, 'plane', 'samolyot', 'A vehicle with wings that flies in the air.'),
(2, 'pot', 'qozon', 'A container used for cooking food.'),
(2, 'pencil', 'qaragay', 'A tool for writing or drawing, consisting of a thin stick of graphite enclosed in a wooden or plastic case.'),
(2, 'queen', 'malika', 'A female ruler of a country.'),
(2, 'rain', 'yomgir', 'Water droplets falling from the sky.'),
(2, 'river', 'daryo', 'A large natural stream of water flowing towards an ocean, sea, or lake.'),
(2, 'school', 'maktab', 'An institution for educating children.'),
(2, 'shoe', 'poyabzal', 'A covering for the foot.'),
(2, 'sun', 'quyosh', 'The star at the center of our solar system.'),
(2, 'sand', 'qum', 'Tiny particles of rock found on beaches and deserts.'),
(2, 'star', 'yulduz', 'A celestial body that generates light and heat through nuclear reactions.'),
(2, 'sunglasses', 'kozoynak', 'Glasses tinted to protect the eyes from sunlight.'),
(2, 'table', 'stol', 'A piece of furniture with a flat top and one or more legs.'),
(2, 'tree', 'daraxt', 'A perennial plant with a trunk and branches.'),
(2, 'train', 'poezd', 'A series of connected vehicles traveling on railways.'),
(2, 'water', 'suv', 'A clear, colorless liquid essential for life.'),
(2, 'wind', 'shamol', 'Air in motion relative to the surface of the Earth.'),
(2, 'yellow', 'sariq', 'The color between green and orange in the spectrum of visible light.'),
(2, 'zebra', 'zebra', 'An African wild horse with black-and-white stripes.'),
(2, 'zoo', 'hayvonot bogi', 'A park where animals are kept for public viewing.');

