# Online Examination System

A web-based examination platform that allows students to register, log in, take online examinations, and view their results.

## Features

- Student registration and login.
- Secure password hashing.
- Student dashboard.
- Available examinations.
- Multiple-choice questions.
- Countdown timer.
- Automatic examination grading.
- Automatic result calculation.
- Pass/fail status.
- Examination history.

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL / MariaDB
- XAMPP
- phpMyAdmin

## Project Structure

```text
OnlineExamination/
│
├── config/
│   └── database.php
│
├── css/
│   └── style.css
│
├── js/
│   └── exam.js
│
├── index.php
├── register.php
├── login.php
├── dashboard.php
├── logout.php
├── exam.php
├── submit_exam.php
├── result.php
├── my_results.php
├── online_examination.sql
└── README.md
```

## Database Structure

The project uses four database tables:

1. **users:** Stores student account information.
2. **exams:** Stores examination details.
3. **questions:** Stores questions, answer options, and correct answers.
4. **results:** Stores students' examination scores.

## Installation and Setup

1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Copy the project folder into:

   `C:\xampp\htdocs\`

4. Open phpMyAdmin:

   `http://localhost/phpmyadmin/`

5. Create a database named `online_examination`.
6. Import the `online_examination.sql` file.
7. Check the database connection settings in `config/database.php`.
8. Open the project in your browser:

   `http://localhost/OnlineExamination/`

## How It Works

1. Students create an account.
2. Students log in to access the dashboard.
3. Students select an available examination.
4. They answer multiple-choice questions before the timer expires.
5. The system automatically checks their answers.
6. Students receive their scores and can view their previous results.

## Developer

Omar Al Haji

Computer Science Student
