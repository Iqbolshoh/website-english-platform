CREATE DATABASE english;

USE english;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word VARCHAR(255) NOT NULL,
    translation VARCHAR(255),
    definition TEXT(500)
);

CREATE TABLE sentences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    word_id INT,
    sentence TEXT(500) NOT NULL,
    translation TEXT(500) NOT NULL,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE
);

CREATE TABLE liked_words (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    word_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (word_id) REFERENCES words(id) ON DELETE CASCADE
);

INSERT INTO
    users (fullname, email, username, password)
VALUES
    (
        'Iqbolshoh Ilhomjonov',
        'iilhomjonov777@gmail.com',
        'iqbolshoh',
        '1'
    ),
    (
        'user',
        'user@gmail.com',
        'user',
        '1'
    );

INSERT INTO
    words (word, translation, definition)
VALUES
    (
        'apple',
        'olma',
        'A fruit that is usually round, red, green, or yellow and has a sweet taste.'
    ),
    (
        'ant',
        'akar',
        'A small insect known for living in colonies and being industrious.'
    ),
    (
        'art',
        'sanat',
        'The creation of visual, auditory, or performance artifacts that express the creators imagination.'
    ),
    (
        'avenue',
        'kocha',
        'A broad road in a city or town, often lined with trees.'
    ),
    (
        'airport',
        'havo limoni',
        'A place where airplanes take off and land.'
    ),
    (
        'book',
        'kitob',
        'A set of written, printed, or blank pages fastened together between a cover.'
    ),
    (
        'ball',
        'top',
        'A round object used in games and sports.'
    ),
    (
        'bike',
        'velosiped',
        'A vehicle with two wheels that you ride by pedaling.'
    ),
    (
        'bread',
        'non',
        'A common food made from flour and water and baked.'
    ),
    (
        'bottle',
        'shisha',
        'A container with a narrow neck, used to hold liquids.'
    ),
    (
        'cat',
        'mushuk',
        'A small domesticated carnivorous mammal with soft fur.'
    ),
    (
        'car',
        'avtomobil',
        'A road vehicle powered by an engine, used for transporting people.'
    ),
    (
        'chair',
        'stul',
        'A piece of furniture for sitting, usually having four legs and a back.'
    ),
    (
        'city',
        'shahar',
        'A large town or a significant urban area.'
    ),
    (
        'computer',
        'kompyuter',
        'An electronic device for storing and processing data.'
    ),
    (
        'dog',
        'it',
        'A domesticated carnivorous mammal that typically has a barking sound.'
    ),
    (
        'door',
        'esik',
        'A hinged, sliding, or revolving barrier at the entrance to a building.'
    ),
    (
        'dance',
        'raqqoslik',
        'A series of movements and steps performed to music.'
    ),
    (
        'desk',
        'stol',
        'A piece of furniture with a flat top and usually with drawers.'
    ),
    (
        'doctor',
        'shifokor',
        'A person who is qualified to treat people who are ill.'
    ),
    (
        'elephant',
        'fil',
        'A large mammal with a trunk and tusks.'
    ),
    (
        'egg',
        'tuxum',
        'An oval or round object laid by female birds, reptiles, and other animals.'
    ),
    (
        'engineer',
        'muhandis',
        'A person who designs, builds, or maintains engines, machines, or structures.'
    ),
    (
        'envelope',
        'konvert',
        'A flat, usually rectangular container for a letter.'
    ),
    (
        'education',
        'talim',
        'The process of receiving or giving systematic instruction.'
    ),
    (
        'fish',
        'baliq',
        'A cold-blooded vertebrate animal that lives in water.'
    ),
    (
        'flower',
        'gul',
        'The reproductive structure of flowering plants.'
    ),
    (
        'food',
        'ovqat',
        'Any nutritious substance that people or animals eat or drink.'
    ),
    (
        'fan',
        'ventilyator',
        'A device for creating a current of air.'
    ),
    (
        'fire',
        'ot',
        'A rapid oxidation process producing heat and light.'
    ),
    (
        'goat',
        'echki',
        'A domesticated ruminant animal with a beard and horns.'
    ),
    (
        'garden',
        'bog',
        'A plot of ground where plants are cultivated.'
    ),
    (
        'glove',
        'qolqop',
        'A covering for the hand with separate fingers.'
    ),
    (
        'guitar',
        'gitara',
        'A stringed musical instrument played by plucking or strumming.'
    ),
    (
        'grass',
        'moshak',
        'A common plant with narrow leaves.'
    ),
    (
        'house',
        'uy',
        'A building for human habitation.'
    ),
    (
        'hat',
        'shapka',
        'A head covering.'
    ),
    (
        'horse',
        'ot',
        'A large domesticated animal used for riding or work.'
    ),
    (
        'hospital',
        'kasalxona',
        'An institution providing medical and surgical treatment.'
    ),
    (
        'honey',
        'asal',
        'A sweet substance made by bees.'
    ),
    (
        'ice',
        'muz',
        'Frozen water.'
    ),
    (
        'insect',
        'hasharot',
        'A small arthropod animal with six legs.'
    ),
    (
        'island',
        'orol',
        'A piece of land surrounded by water.'
    ),
    (
        'ink',
        'moy',
        'A colored fluid used for writing or printing.'
    ),
    (
        'idea',
        'goya',
        'A thought or suggestion.'
    ),
    (
        'juice',
        'sharbati',
        'A liquid obtained by squeezing fruit.'
    ),
    (
        'jacket',
        'jaket',
        'A piece of clothing worn on the upper body.'
    ),
    (
        'jungle',
        'jangaldan',
        'A dense, tropical forest.'
    ),
    (
        'jewelry',
        'zargarlik',
        'Decorative items worn on the body.'
    ),
    (
        'jam',
        'murabbo',
        'A sweet spread made from fruit.'
    ),
    (
        'kite',
        'qanot',
        'A light framework covered with paper or cloth, flown in the wind.'
    ),
    (
        'key',
        'kalit',
        'A device used to open a lock.'
    ),
    (
        'king',
        'podsho',
        'A male ruler of a country.'
    ),
    (
        'kangaroo',
        'kangaroo',
        'A marsupial with powerful hind legs.'
    ),
    (
        'knife',
        'pichoq',
        'A cutting tool with a sharp blade.'
    ),
    (
        'lion',
        'sher',
        'A large, wild cat known as the king of beasts.'
    ),
    (
        'lamp',
        'chiroq',
        'A device for giving light.'
    ),
    (
        'letter',
        'xat',
        'A written or printed message.'
    ),
    (
        'laptop',
        'noutbuk',
        'A portable computer.'
    ),
    (
        'lake',
        'kol',
        'A large body of water surrounded by land.'
    ),
    (
        'monkey',
        'maymun',
        'A primate with a long tail.'
    ),
    (
        'mountain',
        'tog',
        'A large natural elevation of the earths surface.'
    ),
    (
        'milk',
        'sut',
        'A white liquid produced by mammals.'
    ),
    (
        'mirror',
        'oyna',
        'A reflective surface.'
    ),
    (
        'music',
        'musiqa',
        'The art of arranging sounds.'
    ),
    (
        'night',
        'tungi',
        'The time between sunset and sunrise.'
    ),
    (
        'nose',
        'burun',
        'The organ used for smelling and breathing.'
    ),
    (
        'notebook',
        'daftarchalar',
        'A book for writing notes.'
    ),
    (
        'nurse',
        'hamshira',
        'A person trained to care for the sick.'
    ),
    (
        'nature',
        'tabiat',
        'The physical world and everything in it.'
    ),
    (
        'orange',
        'apelsin',
        'A round, citrus fruit with a tough skin.'
    ),
    (
        'oven',
        'duxovka',
        'A device used for baking or roasting food.'
    ),
    (
        'ocean',
        'okean',
        'A vast body of saltwater.'
    ),
    (
        'octopus',
        'oktopus',
        'A marine animal with eight arms.'
    ),
    (
        'opera',
        'opera',
        'A dramatic work combining text and music.'
    ),
    (
        'pen',
        'ruchka',
        'A tool for writing with ink.'
    ),
    (
        'phone',
        'telefon',
        'A device for voice communication.'
    ),
    (
        'pencil',
        'qalam',
        'A tool for writing or drawing.'
    ),
    (
        'pizza',
        'pitsa',
        'A dish of Italian origin consisting of a flat, round base of leavened wheat-based dough.'
    ),
    (
        'park',
        'bog',
        'An area of land for public enjoyment.'
    ),
    (
        'plane',
        'samosyat',
        'An aircraft with fixed wings.'
    ),
    (
        'paint',
        'bo\'yoq',
        'A colored substance used to cover surfaces.'
    ),
    (
        'parrot',
        'toʻtiqush',
        'A bird known for its colorful feathers and ability to mimic sounds.'
    ),
    (
        'queen',
        'malika',
        'A female ruler of a country.'
    ),
    (
        'rose',
        'atirgul',
        'A fragrant flower with thorns.'
    ),
    (
        'radio',
        'radio',
        'A device for receiving or transmitting radio signals.'
    ),
    (
        'room',
        'xona',
        'A separate part of a building used for a particular purpose.'
    ),
    (
        'sand',
        'qum',
        'Tiny particles of rock found on beaches.'
    ),
    (
        'star',
        'yulduz',
        'A celestial body that emits light.'
    ),
    (
        'school',
        'maktab',
        'A place for educating children.'
    ),
    (
        'shoe',
        'poyabzal',
        'A covering for the foot.'
    ),
    (
        'sun',
        'quyosh',
        'The star at the center of our solar system.'
    ),
    (
        'shirt',
        'kofta',
        'A garment for the upper body.'
    ),
    (
        'tree',
        'daraxt',
        'A perennial plant with an elongated trunk.'
    ),
    (
        'television',
        'televizor',
        'A device for receiving television broadcasts.'
    ),
    (
        'train',
        'poezd',
        'A series of connected vehicles traveling on a track.'
    ),
    (
        'umbrella',
        'soya',
        'A device used for protection from rain or sun.'
    ),
    (
        'up',
        'yuqori',
        'In or to a higher position.'
    ),
    (
        'vase',
        'guldastalar',
        'A container for holding flowers.'
    ),
    (
        'volcano',
        'vulkan',
        'A mountain with an opening through which lava, ash, and gases are expelled.'
    ),
    (
        'water',
        'suv',
        'A clear liquid essential for life.'
    ),
    (
        'window',
        'deraza',
        'An opening in a wall for light and air.'
    ),
    (
        'yogurt',
        'qatiq',
        'A dairy product made by fermenting milk.'
    ),
    (
        'yarn',
        'ip',
        'Thread used for knitting or weaving.'
    ),
    (
        'zebra',
        'zebra',
        'A wild animal with black and white stripes.'
    ),
    (
        'zoo',
        'hayvonot bog\'i',
        'A place where animals are kept for public viewing.'
    );

INSERT INTO
    sentences (word_id, sentence, translation)
VALUES
    (
        1,
        'The apple fell from the tree.',
        'Olma daraxtdan tushdi.'
    ),
    (
        2,
        'Ants are hard workers.',
        'Chumolilar mehnatkash.'
    ),
    (
        3,
        'Art is a reflection of culture.',
        'Sanat madaniyatning aksidir.'
    ),
    (
        4,
        'He walked down the avenue.',
        'U ko‘chadan pastga qarab yurdi.'
    ),
    (
        5,
        'She arrived at the airport early.',
        'U aeroportga erta yetib keldi.'
    );