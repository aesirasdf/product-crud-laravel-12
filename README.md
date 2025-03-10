# Project Setup Instructions
Follow the steps below to set up and run the project locally:

1. **Rename `.env.example` to `.env`**  
   Copy the example environment configuration file and rename it to `.env`:  
   `mv .env.example .env`

2. **Install Composer Dependencies**  
   Run the following command to install the necessary Composer dependencies:  
   `composer install`

3. **Install NPM Dependencies**  
   Run the following command to install the necessary NPM dependencies:  
   `npm install`

4. **Run Database Migrations and Seeders**  
   Run the following command to apply database migrations and seed the database:  
   `php artisan migrate --seed`

5. **Run the Development Environment**  
   Finally, run the development environment using Composer:  
   `composer run dev`

Once these steps are completed, your project should be set up and ready to run locally.
