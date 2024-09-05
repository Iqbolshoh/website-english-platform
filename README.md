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

## Connect with Me

I love connecting with new people and exploring new opportunities. Feel free to reach out to me through any of the platforms below:

<table>
    <tr>
        <td>
            <a href="https://github.com/iqbolshoh">
                <img src="https://raw.githubusercontent.com/rahuldkjain/github-profile-readme-generator/master/src/images/icons/Social/github.svg"
                    height="48" width="48" alt="GitHub" />
            </a>
        </td>
        <td>
            <a href="https://t.me/iqbolshoh_777">
                <img src="https://github.com/gayanvoice/github-active-users-monitor/blob/master/public/images/icons/telegram.svg"
                    height="48" width="48" alt="Telegram" />
            </a>
        </td>
        <td>
            <a href="https://www.linkedin.com/in/iiqbolshoh/">
                <img src="https://github.com/gayanvoice/github-active-users-monitor/blob/master/public/images/icons/linkedin.svg"
                    height="48" width="48" alt="LinkedIn" />
            </a>
        </td>
        <td>
            <a href="https://instagram.com/iqbolshoh_777" target="blank"><img align="center"
                    src="https://raw.githubusercontent.com/rahuldkjain/github-profile-readme-generator/master/src/images/icons/Social/instagram.svg"
                    alt="instagram" height="48" width="48" /></a>
        </td>
        <td>
            <a href="https://wa.me/qr/22PVFQSMQQX4F1">
                <img src="https://github.com/gayanvoice/github-active-users-monitor/blob/master/public/images/icons/whatsapp.svg"
                    height="48" width="48" alt="WhatsApp" />
            </a>
        </td>
        <td>
            <a href="https://x.com/iqbolshoh_777">
                <img src="https://img.shields.io/badge/X-000000?style=for-the-badge&logo=x&logoColor=white" height="48"
                    width="48" alt="Twitter" />
            </a>
        </td>
        <td>
            <a href="mailto:iilhomjonov777@gmail.com">
                <img src="https://github.com/gayanvoice/github-active-users-monitor/blob/master/public/images/icons/gmail.svg"
                    height="48" width="48" alt="Email" />
            </a>
        </td>
    </tr>
</table>

