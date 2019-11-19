
  
![HackGreenville](https://stage.hackgreenville.com/img/logo-v2.png)  
  
# HackGreenville 

The official repository for Greenville, SC's tech community website, maintained by [Code for Greenville](https://github.com/codeforgreenville).    
    
https://hackgreenville.com    
    
Find us on [Slack](https://hackgreenville.slack.com/)!    
    
## Getting started  


### Launch the HackGreenville project    
 Fork this repository, then clone your fork, and run this in your newly created directory:    
 
 The command might look something like this
 ```bash
 git clone git@github.com:codeforgreenville/hackgreenville-com.git
 ```
    
You need to make a copy of the `.env.example` file and rename it to `.env` at your project root. 

Edit the new .env file and set your database settings.   

You will need to create the database. This is a sample of the command you can run.  
```bash  
mysql --user="dbusername" --password -e"create database hack_greenville"  
```  

``` bash    
composer install
php artisan db:seed
```   

That `db:seed` command will create a default user *admin@admin.com* with a password of *admin* and fill the states table. 
    
Run the following command to generate your app key:    
    
``` php 
artisan key:generate    
```   

Then start your server:    
    
```bash  
php artisan serve    
```    
 The HackGreenville project is now up and running! You should be able to open [localhost:8000](localhost:8000) in your browser.    

The composer install command will run `php artisan migrate --seed; yarn install; yarn prod` which will build the project. 
To develop you'll want to run `php artisan serve` to start the applications php server and in another terminal you'll want to run `yarn watch` to watch for frontend resource changes and re-build them when detected. 
    
## Purpose 

Our goal is to connect people to the tech-related community in the Greenville area. We currently do this by providing information on meetups and tech organizations in the area, as well as directing people to the HackGreenville slack signup page.    
    
The meetup information is queried from the [Upstate Tech Cal Service](https://github.com/codeforgreenville/upstate_tech_cal_service).    
    
The organization information is queried from Code for Greenville's [API](https://github.com/codeforgreenville/OpenData/issues/17).    
    
Got some ideas on how to make the site better? Catch us at a [Code for Greenville](http://codeforgreenville.org/) meetup, on Slack, or open an issue!    
    
Want to contribute? Keep reading!    
    
## Contributions welcome! 

System requirements

* This site was built with PHP 7 and Laravel.   
* You'll need [composer](https://getcomposer.org/download/) as well.
* You'll need [yarn](https://yarnpkg.com/lang/en/docs/install/) as well.    
    
Now you're ready to start tackling issues. Feel free to send us a pull request!    
    
## Links 

- [http://stylifyme.com/?stylify=https%3A%2F%2Fwww.greenvillesc.gov](http://stylifyme.com/?stylify=https%3A%2F%2Fwww.greenvillesc.gov)    
- [https://sweetalert2.github.io/#examples](https://sweetalert2.github.io/#examples)  
- [http://fullcalendar.io/docs](http://fullcalendar.io/docs)  
- [https://vuejs.org/v2/guide/components.html](https://vuejs.org/v2/guide/components.html)  
- [https://getbootstrap.com/docs/4.0/getting-started/introduction/](https://getbootstrap.com/docs/4.0/getting-started/introduction/)  
- [https://lodash.com/](https://lodash.com/)
