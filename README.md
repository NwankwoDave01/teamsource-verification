### 1. Environment Setup
Ensure you have a web environment running Apache and MySQL (either a local environment like XAMPP, or a live cPanel shared hosting account).

### 2. Upload the Repository
Upload the files from this repository into your server's root directory:
* **Local (XAMPP):** Place files in `C:\xampp\htdocs\verification-portal`
* **Live (cPanel):** Upload files into a folder inside your `public_html` directory using the File Manager.

### 3. Database Configuration
1. Open your database manager (e.g., `phpMyAdmin` in cPanel).
2. Create a new database named `teamsource_cert` (or your preferred live database name).
3. Select the database, go to the **SQL** tab, and run the following command to create the required table:

    ```sql
    CREATE TABLE graduates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        certificate_id VARCHAR(50) UNIQUE NOT NULL,
        student_name VARCHAR(100) NOT NULL,
        course_completed VARCHAR(150) NOT NULL,
        graduation_date DATE NOT NULL,
        passport_image VARCHAR(255) NOT NULL,
        certificate_file VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ```

### 4. Configure Live Database Connection (cPanel)
To connect your application to your live database on a cPanel hosting account:

1. Log in to your hosting **cPanel**.
2. Navigate to the **MySQL Databases** section.
3. Create a **New Database** (e.g., `yourdomain_certdb`).
4. Scroll down to create a **New User** and generate a strong password. Save this password.
5. Under **Add User to Database**, select your new user and new database, click **Add**, and check the box for **All Privileges**.
6. Open the `db.php` file on your server and update the credentials to match the ones you just created:

    ```php
    $host = 'localhost'; // This usually remains 'localhost' on shared hosting$dbname = 'yourdomain_certdb'; // The live database name you created
    $username = 'yourdomain_dbuser'; // The live database user you created$password = 'YourStrongPassword123!'; // The password for the database user
    ```

### 5. Create the Uploads Directory
Ensure there is a folder named `uploads` in the root of your project directory. 
* **Important for Live Servers:** Right-click the `uploads` folder in your cPanel File Manager, click **Change Permissions**, and verify it is set to `755` so PHP can securely save the uploaded certificates and images.
