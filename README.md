# Laravel Project Setup Instructions

## Prerequisites

Before setting up the project, ensure that you have the following installed on your system:

- **PHP (>= 7.4)**
- **Composer**
- **MySQL or any other preferred database**

## Step-by-Step Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/your-repository.git
   cd your-repository

2. **Install PHP Dependencies**
   composer install
   
4. **Create a .env file**
   Create a .env file by copying the example .env.example

5. **Open the .env file and set the correct values for your database configuration, such as:**
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

6. **Generate Application Key Run the following command to generate the application key**
   php artisan key:generate

7. **Run Database Migrations To set up the database structure, run the migrations:**
   php artisan migrate

8. **Run the Laravel Development Server Start the local development server by running:**
   php artisan serve

9. **Scraping News Articles**
    This project includes a command to scrape news articles from three different sources: NewsAPI, The Guardian, and BBC News.
    To scrape news articles, run the following command:
   php artisan scrape:news

**Notes:** As mentioned earlier, I attempted to Dockerize this project but encountered issues, so the docker-compose setup may not work as expected.
