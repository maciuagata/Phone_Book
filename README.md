# Symfony 5.0.1
1. Download source code

## Installation
2. Install PHP 7.2.5 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations).
3. Install Composer, which is used to install PHP packages : https://getcomposer.org/download/
4. Install Symfony, which creates in your computer a binary called symfony that provides all the tools you need to develop your application locally.
Run this command :  wget https://get.symfony.com/cli/installer -O - | bash
5. The symfony binary provides a tool to check if your computer meets these requirements. Open your console terminal and run this command: symfony check:requirements
6. First, install Doctrine support via the orm Symfony pack, as well as the MakerBundle, which will help generate some code:  
 composer require symfony/orm-pack
 composer require --dev symfony/maker-bundle
7. Configuring the Database. The database connection information is stored as an environment variable called DATABASE_URL. For development, you can find and customize .env file.
8. Create database.
9. Run migrations:
 Open your console terminal and run this command: php bin/console make:migration 
 If everything worked run this command: php bin/console doctrine:migrations:migrate