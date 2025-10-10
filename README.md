# Kids Collage - Laravel CMS Platform

A modern, full-featured Content Management System built with Laravel 12 and Livewire 3.

## 📚 Documentation

**Comprehensive documentation is available at:**
- Development: `http://localhost:8000/web/documentation`
- Documentation Files: `resources/docs/1.0/`
- Guidelines: See `DOCUMENTATION.md` and `.cursor/rules/documentation.mdc`

### Quick Links
- [Overview](http://localhost:8000/web/documentation/1.0/overview)
- [Project Architecture](http://localhost:8000/web/documentation/1.0/project-architecture)
- [Course Management](http://localhost:8000/web/documentation/1.0/course-management)
- [Error Handling](http://localhost:8000/web/documentation/1.0/error-handling)

---

## Project Setup and CI/CD for Laravel with VPS

This guide covers generating SSH keys, setting up a GitHub project, cloning a Laravel project on a VPS, configuring SSH for CI/CD with GitHub Actions, setting Laravel-specific permissions, and running the project locally. The steps are ordered to ensure SSH is set up before cloning the project.
1. Generate SSH Key

On your local machine, generate an SSH key pair:

ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

2. Copy Public Key
cat ~/.ssh/id_rsa.pub


Copy the output (public key).

3. Connect to VPS
ssh admin@your-vps-ip

4. Create SSH Directory on VPS (if not exists)
mkdir -p ~/.ssh
chmod 700 ~/.ssh

5. Add Public Key to authorized_keys on VPS
nano ~/.ssh/authorized_keys


Paste the public key into the file and save.

6. Set File Permissions on VPS
chmod 600 ~/.ssh/authorized_keys

7. Test SSH Connection

From your local machine, test the SSH connection:

ssh -i ~/.ssh/id_rsa admin@your-vps-ip

8. Add Private Key to GitHub Secrets
cat ~/.ssh/id_rsa


Copy the private key and add it to GitHub (Settings > Secrets and variables > Actions > Secrets) as SSH_PRIVATE_KEY.

9. Initialize Git Repository and Connect to GitHub

Create a new repository on GitHub (e.g., sajadsoft1/water-adora).
Initialize Git in your local Laravel project directory:

git init
git add .
git commit -m "Initial commit"
git remote add origin git@github.com:sajadsoft1/water-adora.git
git push -u origin main

10. Clone Project on VPS

On the VPS, install Git if not already installed (e.g., on Ubuntu):

sudo apt update
sudo apt install git


Clone the repository to the specified directory:

git clone git@github.com:sajadsoft1/water-adora.git /home/admin/domains/test.test/public_html

11. Set Project Permissions on VPS

Set ownership for the project directory to admin:apache:

sudo chown -R admin:apache /home/admin/domains/test.test/public_html


Set general permissions for the project directory:

sudo chmod -R 755 /home/admin/domains/test.test/public_html


Set specific permissions for Laravel storage and bootstrap/cache directories:

sudo chmod -R 775 /home/admin/domains/test.test/public_html/storage
sudo chmod -R 775 /home/admin/domains/test.test/public_html/bootstrap/cache

12. Install Laravel Dependencies on VPS

Ensure PHP, Composer, and required extensions are installed (e.g., on Ubuntu):

sudo apt install php php-cli php-mbstring php-xml php-mysql composer


Navigate to the project directory and install dependencies:

cd /home/admin/domains/test.test/public_html
composer install


Copy .env.example to .env and configure it (e.g., database settings):

cp .env.example .env
nano .env


Generate an application key:

php artisan key:generate

13. Run Project Locally

Clone the repository to your local machine:

git clone git@github.com:sajadsoft1/water-adora.git
cd water-adora


Install PHP, Composer, and required extensions locally (adjust for your OS).
Install dependencies:

composer install


Copy .env.example to .env and configure it (e.g., database settings):

cp .env.example .env
nano .env


Generate an application key:

php artisan key:generate


Run the Laravel development server:

php artisan serve


Open your browser and navigate to http://localhost:8000 (default Laravel port).

14. Access Documentation

After running the project, access the complete documentation at:

http://localhost:8000/web/documentation


The documentation includes:
- Project architecture and components
- Feature guides (SmartCache, Content Publishing, Course Management)
- API documentation
- Troubleshooting guides

Notes

Replace your-vps-ip with your actual VPS IP address.
Security: Never share your private key. Store it securely in GitHub Secrets.
Ensure Git, PHP, Composer, and required PHP extensions are installed on both the VPS and local machine.
Adjust permissions (755 or 775) based on security requirements. The storage and bootstrap/cache directories require 775 for write access by the web server (apache).
If using a web server like Apache or Nginx, configure it to point to /home/admin/domains/test.test/public_html/public as the document root.
For non-standard Laravel setups, adjust the "Run Project Locally" and "Install Laravel Dependencies on VPS" sections as needed.

