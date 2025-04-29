## HRM

### Overview:

The HRM application is the core component of your HRM SaaS solution.


### Installation:

1. Prerequisites:

   Ensure you have a local development environment set up listed prerequisites:
    
    - PHP >= 8.2
    - php-curl
    - php-xml
    - php-mysql
    - php-mbstring
    - Laravel >= 11.0
    - MySql
    - Node >= 20.0
    - NPM >= 6.0
    - Composer >= 2.0

2. Clone the Repository:
     ```Bash
     git clone git@bitbucket.org:nonditosoft/hrm.git
     ```  

3. Install Dependencies:
     ```Bash
     cd hrm-central-admin
     composer install
     npm install
     ```
4. **Configuration:**

    - Database Configuration:

      Copy `env.example` file and Edit the `.env` file to configure your database connection details (`host, database name, username, password`).
      Consider using a secure environment variable management solution for production environments.

    - Application Configuration:

      Review the **config** directory for any additional application-specific configuration files.

5. **Database Migrations:**

   Run the following command to create the database tables:**
    ```bash
    php artisan migrate
    ```
6. **Set the APP_ENV:**

    Edit the `.env` to set `APP_ENV`.
    For the production server, set APP_ENV=production
    For the staging server, set APP_ENV=staging
    For local or development server, set APP_ENV=local

7. **Start the Application:**

   Run the following command to start the application:
    ```bash
    php artisan serve --port=8002
    ```
   The application will be accessible at `http://localhost:8002`.

8. **HRM Tenant Integration:**
   Configure how the Central Admin application interacts with the HRM Tenant service to serve the database credentials for individual companies. This may involve API calls or other defined communication mechanisms. 

### Code Structure
- Have to strictly follow SOLID and DRY principles.
- Method should not have more than 15 lines of code with valid exception. Comment and line break will not be counted.
- Business logic will be on service class. There will be multiple services under namespaces if required


### Usage:

- The HRM application is the core component of your HRM SaaS solution. It is responsible for managing the core HRM functionalities such as employee management, payroll, attendance, and leave management.


### Custom css
resources/css/assets/sass/components/_variables.custom.scss
resources/css/assets/sass/custom.scss


### Register ServiceProvider
ServiceProviders need to be registered in bootstrap/providers.php


### Register Middleware
Middlewares need to be registered in bootstrap/app.php
