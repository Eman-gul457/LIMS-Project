# Mini LIMS (Laboratory Information Management System)

Mini LIMS is a simple web system to manage laboratory samples, tests, results, and reports.
It is built with:
- PHP (backend logic)
- MySQL (database)
- HTML/CSS/JS (frontend)
- Apache (web server)


## 1. What this project does

This system helps a lab user:
- Add new samples
- View all samples
- Assign tests to samples
- Enter test results
- See reports in one place

## 2. Main pages

- `index.php`: Dashboard (main home screen)
- `add_sample.php`: Add a new sample
- `view_samples.php`: List/search/filter samples
- `add_test.php`: Assign test to sample
- `add_result.php`: Enter result for a sample
- `reports.php`: View combined sample + test + result report

## 3. Folder structure

```text
mini-lims/
├── index.php
├── add_sample.php
├── view_samples.php
├── add_test.php
├── add_result.php
├── reports.php
├── config/
│   └── database.php
├── database/
│   └── mini_lims.sql
├── includes/
│   ├── db.php
│   ├── functions.php
│   ├── header.php
│   └── footer.php
└── assets/
    ├── css/style.css
    └── js/app.js
```

## 4. What each file contains (simple explanation)

- `index.php`: Shows project title, menu, quick buttons, and sample status counts.
- `add_sample.php`: Form to save sample details (patient name, sample type, test name, date).
- `view_samples.php`: Table of all samples with search and status filter.
- `add_test.php`: Form to assign a test + technician and update sample status.
- `add_result.php`: Form to save result value/date/technician/approval, then marks sample completed.
- `reports.php`: Final report view combining data from sample, test, and result tables.

- `includes/db.php`: Connects PHP code to MySQL database.
- `includes/functions.php`: Helper functions (safe text output, status color class, redirect with message).
- `includes/header.php`: Top menu and common page header UI.
- `includes/footer.php`: Common footer and JS include.

- `config/database.php`: Database connection settings (host, DB name, username, password).
- `database/mini_lims.sql`: SQL script to create tables: `samples`, `tests`, `results`.
- `assets/css/style.css`: Full visual styling of pages.
- `assets/js/app.js`: Small behavior script (auto-fade success alerts).

## 5. Software required

Install these first:
- Apache2
- PHP + MySQL extension (`php-mysql`)
- MySQL Server

On Ubuntu/Debian:

```bash
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php php-mysql mysql-server unzip
sudo systemctl enable --now apache2 mysql
```

## 6. Database setup (all commands)

Run these commands:

```bash
sudo mysql
```

Inside MySQL shell:

```sql
CREATE DATABASE IF NOT EXISTS mini_lims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'mini_lims_user'@'localhost' IDENTIFIED BY 'YourStrongPasswordHere';
GRANT ALL PRIVILEGES ON mini_lims.* TO 'mini_lims_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Import tables:

```bash
mysql -u mini_lims_user -p mini_lims < database/mini_lims.sql
```

## 7. Localhost run steps

1. Put project in web root (for Apache):
   - Linux: `/var/www/html/mini-lims`
2. Open `config/database.php`
3. Set your real DB username and password.
4. Start Apache and MySQL.
5. Open browser:
   - `http://localhost/mini-lims`

## 8. EC2 deployment steps (all commands)

### A. Connect server

```bash
ssh -i "serverless-key.pem" ubuntu@<YOUR_PUBLIC_IP>
```

### B. Install server packages

```bash
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php php-mysql mysql-server unzip
sudo systemctl enable --now apache2 mysql
```

### C. Upload project from your local PC

Run this from local machine:

```bash
scp -i "serverless-key.pem" -r mini-lims ubuntu@<YOUR_PUBLIC_IP>:/home/ubuntu/
```

### D. Move into Apache folder

```bash
ssh -i "serverless-key.pem" ubuntu@<YOUR_PUBLIC_IP>
sudo rm -rf /var/www/html/mini-lims
sudo mv /home/ubuntu/mini-lims /var/www/html/mini-lims
sudo chown -R www-data:www-data /var/www/html/mini-lims
```

### E. Create DB + user + import SQL

```bash
sudo mysql -e "CREATE DATABASE IF NOT EXISTS mini_lims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'mini_lims_user'@'localhost' IDENTIFIED BY 'YourStrongPasswordHere';"
sudo mysql -e "GRANT ALL PRIVILEGES ON mini_lims.* TO 'mini_lims_user'@'localhost'; FLUSH PRIVILEGES;"
sudo bash -c "mysql mini_lims < /var/www/html/mini-lims/database/mini_lims.sql"
```

### F. Update app DB config

Edit file:

```bash
sudo nano /var/www/html/mini-lims/config/database.php
```

Set:
- host: `127.0.0.1`
- database: `mini_lims`
- username: `mini_lims_user`
- password: your real password

### G. Restart Apache

```bash
sudo systemctl restart apache2
sudo systemctl status apache2 --no-pager
```

### H. Open in browser

```text
http://<YOUR_PUBLIC_IP>/mini-lims
```

## 9. Security notes (important)

- Never commit real passwords to GitHub.
- Keep `config/database.php` with placeholder password in repo.
- Use strong DB password on real server.
- Restrict EC2 Security Group:
  - `22` (SSH) only from your IP
  - `80` (HTTP) public if needed
  - `443` (HTTPS) public if SSL enabled

## 10. Basic testing checklist

1. Add sample
2. Confirm sample appears in View Samples
3. Add test for that sample
4. Add result for that sample
5. Open Reports and verify status/result values

If all 5 work, core system is working.

## 11. GitHub push commands

```bash
git init
git add .
git commit -m "Initial Mini LIMS implementation"
git branch -M main
git remote add origin <YOUR_GITHUB_REPO_URL>
git push -u origin main
```


