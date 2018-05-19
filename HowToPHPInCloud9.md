# Getting Started
## Configuring Cloud 9 for the SC Codes PHP Track

The HowTo may also be useful in setting up a VM profile (Docker / Homestead) image for later on.

-----
# Bits and Pieces
Making Apache work better with the Run Project button:
Step 1:
`sudo nano /etc/apache2/sites-enabled/001-cloud9.conf`

Step 2:
// Change this line
DocumentRoot /home/ubuntu/workspace

// To following
DocumentRoot /home/ubuntu/workspace/public

Git Aliases:
* Easy access with `c9 open ~/.gitconfig`
* Recommend adding the following:
```
[alias]
    ls = log --date=local --pretty=format:"%C(yellow)%h\\ %C(cyan)%ad%Cred%d\\ %Creset%s%Cblue"
    s = status
    a = add
    c = commit -m
```
Side note: `git ls` can take additional flags (like `git ls -5` to limit the list to 5 items).
* Also, running `export TZ=EST` will specify Eastern time in from `git ls`
* You can add this to your ~/.bashrc if you don't want to remember it later.g

Installing PHP7.2:
```
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

sudo apt install php7.2 php7.2-curl php7.2-cli php7.2-dev php7.2-gd php7.2-intl php7.2-json php7.2-mysql php7.2-opcache php7.2-bcmath php7.2-mbstring php7.2-soap php7.2-xml php7.2-zip -y

sudo mv /etc/apache2/envvars /etc/apache2/envvars.bak
sudo apt-get remove libapache2-mod-php5 -y
sudo apt-get install libapache2-mod-php7.0 -y
sudo cp /etc/apache2/envvars.bak /etc/apache2/envvars
```

Installing Composer:
* Requires PHP first.
* Install script from [composer website](https://getcomposer.org/download/):
    ```
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"  
    ```
* Make the install global (mirrors the CodeSchool usage): 
    ```
    sudo mv composer.phar /usr/local/bin/composer
    ```

Installing Laravel:
* Requires composer first.
* Use composer to install Laravel: 
    ```
    composer global require laravel/installer
    ```
* Add Laravel install location to PATH for ease of use:
    ```
    sed -i -e '$a\' ~/.bashrc
    echo "" >> ~/.bashrc
    echo '# Add composer packages to global PATH (mainly for Laravel)' >> ~/.bashrc
    echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.bashrc
    source ~/.bashrc
    ```

# TODO list
- [ ] Explain the scripts line by line?
- [ ] Add info on using the standard php -S server and php artisan serve on C9 (0.0.0.0:8080)
- [ ] Explain how to use Run Project instead of `php -S` or `php artisan serve`