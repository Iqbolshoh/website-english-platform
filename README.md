# English Learning Platform

This is an English learning platform designed to help users improve their vocabulary, sentence structure, and reading skills. The platform allows users to register, add their own vocabulary, sentences, and texts, and also provides exercises to enhance learning.

## Features

- **User Registration & Login**: Users can sign up, log in, and manage their profiles.
- **Vocabulary**: Users can add new words along with their translations and definitions.
- **Sentences**: Users can create sentences using the words they added and add translations.
- **Texts**: Users can upload and translate texts for reading practice.
- **Exercises**: 
  - **Vocabulary Test**: Tests where words are scrambled, and users must choose the correct answer from four options.
  - **Sentence Test**: Users reorder scrambled sentences to form a grammatically correct sentence.
- **Download PDFs**: Users can download their vocabulary, sentences, and texts in PDF format.
- **Search**: A search function to find words, sentences, and texts easily.
- **Favorite Words, Sentences, and Texts**: Users can save their favorite words, sentences, and texts.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/Iqbolshoh/website-english-platform.git
```

2. Navigate to the project directory:

```bash
cd website-english-platform
```

3. Install the necessary dependencies (if applicable):

```bash
npm install
```

4. Set up the database:

- Create a database called `english`.
- Import the provided `database.sql` file:

```sql
CREATE DATABASE english;

USE english;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- (Add the rest of your database creation queries here)
```

5. Configure `config.php` with your database credentials:

```php
<?php
class Query
{
    private $conn;

    public function __construct()
    {
        $servername = "localhost";
        $username = "milliyto_shop";
        $password = "X?t&e#iF3Fc*";
        $dbname = "milliyto_english";

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
```

6. Start the server:

- If you're using XAMPP or MAMP, place the project folder in the `htdocs` folder.
- Access the platform in your browser by navigating to `http://localhost/website-english-platform`.

## Usage

1. **Register and Login** to start using the platform.
2. Add words, sentences, and texts.
3. Participate in exercises to improve your English.
4. Download your learning materials as PDFs.

## Project Structure

- **css/**: Contains the CSS files for styling the platform.
- **js/**: Contains JavaScript files for the platform's functionality.
- **config.php**: Database configuration file.
- **database.sql**: SQL file for creating the necessary tables.
- **index.php**: Main entry point for the platform.
- **exercise/**: Contains the files for Vocabulary and Sentence tests.

## Contribution

Feel free to contribute by forking the repository and creating pull requests. For major changes, please open an issue first to discuss what you would like to change.
