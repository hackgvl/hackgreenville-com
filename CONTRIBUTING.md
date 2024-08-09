- [Contribution Guidelines](#contribution-guidelines)
- [Ways to Help](#ways-to-help)
  - [Helping with Existing Issues](#helping-with-existing-issues)
  - [Reporting a Bug or New Idea](#reporting-a-bug-or-new-idea)
- [Forking the Project](#forking-the-project)
- [Running the App](#running-the-app)
  - [Prerequisites](#prerequisites)
  - [System Requirements](#system-requirements)
  - [Setup \& Configuration Options](#setup--configuration-options)
    - [Option 1 - Run via Native Host](#option-1---run-via-native-host)
      - [Running the Database](#running-the-database)
      - [Installing Dependencies and Seeding Database](#installing-dependencies-and-seeding-database)
      - [Starting the Vite Dev Tool](#starting-the-vite-dev-tool)
      - [Starting the Web Application](#starting-the-web-application)
      - [Generate App Key](#generate-app-key)
      - [Import / Seed the Organizations and Events Data](#import--seed-the-organizations-and-events-data)
    - [Option 2 - Run via VS Code and GitHub Codespaces Dev Container](#option-2---run-via-vs-code-and-github-codespaces-dev-container)
    - [Option 3 - Run via Docker \& Laravel Sail](#option-3---run-via-docker--laravel-sail)
      - [Copying Docker Environment Variables](#copying-docker-environment-variables)
      - [Installing the Dockerfile](#installing-the-dockerfile)
        - [Option A: Using Composer](#option-a-using-composer)
        - [Option B: Installing with Laravel Sail](#option-b-installing-with-laravel-sail)
      - [Running the Docker Services](#running-the-docker-services)
      - [Conditional: Install Application Dependencies](#conditional-install-application-dependencies)
      - [Seeding the Application Database](#seeding-the-application-database)
      - [Generating an Application Encryption Key](#generating-an-application-encryption-key)
      - [Starting Vite Development Server](#starting-vite-development-server)
      - [Troubleshooting](#troubleshooting)
      - [Import / Seed Organizations and Events Data](#import--seed-organizations-and-events-data)
- [Interacting with Your Running App](#interacting-with-your-running-app)
- [Environment Variables](#environment-variables)
  - [Events API Configuration](#events-api-configuration)
    - [Importing Events from Meetup GraphQL API](#importing-events-from-meetup-graphql-api)
- [Admin Panel](#admin-panel)
- [Synchronizing Your Fork with the Latest Development Code Changes](#synchronizing-your-fork-with-the-latest-development-code-changes)
- [Contributing Code to the Project](#contributing-code-to-the-project)
- [Frequently Asked Questions](#frequently-asked-questions)
- [Kudos](#kudos)


# Contribution Guidelines

Please play nice. We follow the ["Code of Conduct" mentioned on our Join Slack form](https://hackgreenville.com/join-slack).

# Ways to Help

You don't need to be a "coder" to contribute. Many issues have UI, UX, accessibility, SEO, content / copywriting, and all order of non-code related conversations and improvements to be discussed.

You can also ask questions and connect with the development team in the [HackGreenville Slack's #hg-labs channel](https://hackgreenville.com/join-slack)

<details><summary>Helping with Existing Issues</summary>

## Helping with Existing Issues

Our focused task-based discussions happen mostly within GitHub [Issues](https://github.com/hackgvl/hackgreenville-com/issues) and [Pull Requests](https://github.com/hackgvl/hackgreenville-com/pulls) (also known as PRs).

[Issues tagged with "Good First Issue"](https://github.com/hackgvl/hackgreenville-com/labels/good%20first%20issue) are typically an easy place to start.

If you feel ready to contribute code to this project, then follow the sections and steps below.
</details>

<details><summary>Reporting a Bug or New Idea</summary>

## Reporting a Bug or New Idea

Before starting a new issue, please review and / or search the [current "open" issues](https://github.com/hackgvl/hackgreenville-com/issues/) to avoid duplicates.

If you can't find what you were looking for then [open a new issue](https://github.com/hackgvl/hackgreenville-com/issues/new) to share your suggestions or bugs.

When in doubt, you can reach out to an active project contributor:

| Name          | GitHub                                                   | Role                               |
|:--------------|:---------------------------------------------------------|:-----------------------------------|
| Bogdan        | [@bogdankharchenko](https://github.com/bogdankharchenko) | Technical Lead, Laravel            |
| Zach          | [@zach2825](https://github.com/zach2825)                 | Technical Lead, Laravel            |
| Jim Ciallella | [@allella](https://github.com/allella)                   | Bugs, Documentation, Newcomer Help |
</details>


# Forking the Project

['Forking the Repository on GitHub'](https://help.github.com/articles/about-forks/) is a step where you get your own copy of the repository (a.k.a repo) on GitHub.

This is essential as it allows you to work on your own copy of the code. It allows you to request changes to be pulled into HackGreenville's main repository from your fork via a pull request.

Follow these steps to fork the `https://github.com/hackgvl/hackgreenville-com` repository:

1. Go to the HackGreenville.com (HG) repository on GitHub: https://github.com/hackgvl/hackgreenville-com.
2. Click the "Fork" Button in the upper right-hand corner of the interface ([Need help?](https://help.github.com/articles/fork-a-repo/)).
3. After the repository has been forked, you will be taken to your copy of the repository at `https://github.com/YOUR_USER_NAME/hackgreenville-com`.


# Running the App

## Prerequisites
You must have completed the steps above in the "Forking the Project" section before proceeding.

<details><summary>System Requirements</summary>

## System Requirements

- [Requirements of Laravel 10](https://laravel.com/docs/10.x/deployment#server-requirements), which include PHP 7.3+ or PHP 8+
- You'll need [composer](https://getcomposer.org/download/) as well.
- You'll need [yarn](https://yarnpkg.com/lang/en/docs/install/) as well.
- For running "tests", you'll need SQLite and its associated PHP extensions enabled.
  > The PHP install package names and commands will differ based on your operating system, source repository, and other variations. Here are examples:
  >
  > - RHEL / CentOS / Fedora: `yum install php-sqlite3 php-pdo_sqlite`
  > - Ubuntu / Debian / Mint: `apt install php-sqlite3`
- MariaDB 10+ / MySQL 5.6+ - MariaDB is a compatible fork of MySQL and the community version can be installed using [operating system repositories](https://mariadb.com/kb/en/mariadb-package-repository-setup-and-usage/).

</details>

<details><summary>Preparing the Development Environment</summary>

Install [Git](https://git-scm.com/) and a code editor of your choice. We recommend using [VS Code](https://code.visualstudio.com/).

Clone your forked copy of the Hackgreenville.com code. ['Cloning'](https://help.github.com/articles/cloning-a-repository/) is where you download a copy of the repository from a `remote` location to your local machine. Run these commands on your local machine to clone the repository:

1. Open a Terminal in a directory where you would like the HG project to reside.

2. Clone your fork of the HG code, make sure you replace `YOUR_USER_NAME` with your GitHub username:

   ```sh
   git clone https://github.com/YOUR_USER_NAME/hackgreenville-com.git
   ```

This will download the entire repository to a `hackgreenville-com` directory.

Now that you have downloaded a copy of your fork, you will need to set up an `upstream`. The main repository at `https://github.com/hackgvl/hackgreenville-com` is often referred to as the `upstream` repository. Your fork at `https://github.com/YOUR_USER_NAME/hackgreenville-com` is often referred to as the `origin` repository.

You need a reference from your local copy to the `upstream` repository in addition to the `origin` repository. This is so that you can sync changes from the `upstream` repository to your fork which is called `origin`. To do that follow the below commands:

1.  Change directory to the new hackgreenville-com directory:

```sh
cd hackgreenville-com
```

2.  Add a remote reference to the main Hackgreenvill.com GitHub repository. We're refer to this as "HG" in the later steps.

```sh
git remote add upstream https://github.com/hackgvl/hackgreenville-com.git
```

3.  Ensure the configuration looks correct:

```sh
git remote -v
```

The output should look something like below:
```sh
origin    https://github.com/YOUR_USER_NAME/hackgreenville-com.git (fetch)
origin    https://github.com/YOUR_USER_NAME/hackgreenville-com.git (push)
upstream    https://github.com/hackgvl/hackgreenville-com.git (fetch)
upstream    https://github.com/hackgvl/hackgreenville-com.git (push)
```
</details>

## Setup & Configuration Options

### Option 1 - Run via Native Host
<details><summary>Native Host Details</summary>

You need to make a copy of the `.env.example` file and rename it to `.env` at your project root.

Edit the new .env file and set your database settings.

#### Running the Database

If it does not already exist, you will need to create the `hack_greenville` database in your local MySQL server.

```bash
mysql --user="dbusername" --password -e "create database hack_greenville"
```

#### Installing Dependencies and Seeding Database

Run the following script to install dependencies, run database migrations, and run other optimizations:

```bash
sh scripts/handle-deploy-update.sh
```

The database migrations will generate a default user *admin@admin.com* with a password of _admin_ and fill the states table.

#### Starting the Vite Dev Tool

In one terminal, run the following command to start the Vite local development server:

```bash
yarn dev
```

> Note: for production environments, `yarn prod` would be used.

#### Starting the Web Application

In another terminal, run the following command to start the Laravel server (will open on port `8000`)

```bash
php artisan serve
```

The app should now be accessible by visting `http://localhost:8000` in your browser.

#### Generate App Key

Once the app is running, run the following command to generate your [app encryption key](https://laravel.com/docs/10.x/encryption):

```bash
php artisan key:generate
```

#### Import / Seed the Organizations and Events Data

Organization and events data comes from the [Organizations API](https://github.com/hackgvl/OpenData/blob/master/ORGANIZATIONS_API.md) and [Events API](/EVENTS_API.md). Without this step the application will have no data.


```bash
php artisan import:events
```

</details>

### Option 2 - Run via VS Code and GitHub Codespaces Dev Container
<details><summary>VS Code and GitHub Codespaces Dev Container Details</summary>

See [VS Code + GitHub Codespaces Dev Container documentation](https://github.com/microsoft/vscode-dev-containers#vs-code--github-codespaces-dev-container-definitions).

</details>

### Option 3 - Run via Docker & Laravel Sail
<details><summary>Docker / Laravel Sail Details</summary>

The Docker setup of this project should only be done for advanced users, or if needed for runtime compatibility issues.

#### Copying Docker Environment Variables

First, you need to make a copy of the `.env.docker` file and rename it to `.env` at the
project root. This can be accomplished by running `cp .env.docker .env` from the project root. 

#### Installing the Dockerfile

To run the Docker container for the web application, you'll need to generate the Laravel Sail docker files. You can generate the Laravel Sail docker files with either of the two options:

##### Option A: Using Composer
If you have `composer` installed on your machine, you can run the following script to install the application dependencies, including Laravel Sail.

```bash
composer install
```

##### Option B: Installing with Laravel Sail
If you do not have `composer` installed on your machine, you can install Laravel Sail directly using the following scripts:

```bash
mkdir -p vendor/laravel
git clone https://github.com/laravel/sail.git vendor/laravel/sail/
```

#### Running the Docker Services

To run the Docker services, run Docker Compose from the root directory:

```bash
docker-compose -f docker-compose.yml up --build
```

#### Conditional: Install Application Dependencies

If you followed `Option B` on the `Installing the Dockerfile` step, you'll need to run `composer install` on the web application Docker container to install the rest of the application dependencies. This can be done by running the following:

```bash
docker exec -it hackgreenville composer install
```

#### Seeding the Application Database

Now that we have the application dependencies installed, we can seed the MySQL database using the following command:

```bash
docker exec -it hackgreenville php artisan migrate --seed
```

#### Generating an Application Encryption Key

On the first start, you will need to generate an `APP_KEY` secret, which serve as your application encryption key. This can be generated running the following command:

```bash
docker exec -it hackgreenville php artisan key:generate
```

This command should populate the `APP_KEY` environment variable within your `.env` file.

#### Starting Vite Development Server

Each time the Docker container is restarted, the Vite development server will need to be running in order for the app's stylesheets to be compiled. You can run the Vite development server by running the following command:

```bash
docker exec -d hackgreenville yarn dev
```

#### Troubleshooting

If you get file permission errors, please make sure permissions are set the UID `1337` and the GUID specified in `.env` by `WWWGROUP`.
I.e. if there are errors opening the log file, run `sudo chown -R 1337:www-data storage/`, if `www-data` is the group specified by `WWWGROUP` in `.env`.

If you run into "The Mix manifest does not exist", then run `docker exec -it hackgreenville php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"` and `docker exec -it hackgreenville npm run dev`.

After that, hit Ctrl-C in the original docker-compose to stop the application, and do `docker-compose up --build` to run it again.

If there are any changes in the application code, you will need to run `docker-compose up --build` to recreate the container with your changes.

#### Import / Seed Organizations and Events Data

To seed events and organizations into your application, run the following to import events and organizations from the Open Upstate API:

```bash
docker exec "hackgreenville" /bin/bash -c "php artisan import:events"
```
</details>

# Interacting with Your Running App

[Laravel's Artisan](https://laravel.com/docs/master/artisan) command line tool is heavily leveraged to execute framework and custom commands for development and administration tasks.

- Run `php artisan` to see a full list of availabe commands.
- Running tests `php artisan test`
- Refreshing events from the remote API: `php artisan import:events`
- Run database migrations: `php artisan migrate --seed`
- Completely erase and rebuild the database: [Danger Zone] `php artisan migrate:fresh --seed` [/Danger Zone]

# Environment Variables

- The sample .env.example OR .env.docker is used as a template for new projects. A .env file must exist based on one of these files, based on how the app is running (Native or Docker)
- The .env.ci and .env.testing are used for their respective tasks.

## Events API Configuration
The Events API's responses are controlled by variables that may limit the data available to calling / consuming applications.

Contact [HackGreenville Labs](https://hackgreenville.com/labs) with any questions about these limits for the [HackGreenville.com Events API](/EVENTS_API.md)

Explanation of the .env defaults
`EVENT_IMPORTER_MAX_DAYS_IN_PAST=30` would limit the imported events saved in the Event API's database to no more than 30 days in the past
`EVENT_IMPORTER_MAX_DAYS_IN_FUTURE=365` would .env will limit the imported events saved in the Event API's database to no more than 365 days in the future
`EVENTS_API_DEFAULT_DAYS=1` would cause responses to include at least 1 day in the past. This variable is intended to help avoid ongoing events from disappearing from the API response until at least 24 hours after it started.

### Importing Events from Meetup GraphQL API
In order to import events from organizations with a `meetup_graphql` service type, you will need to setup the following environment variables:

`EVENT_IMPORTER_MEETUP_GRAPHQL_CLIENT_ID` - Your Meetup [OAuth client](https://www.meetup.com/api/oauth/list/)
`EVENT_IMPORTER_MEETUP_GRAPHQL_MEMBER_ID` - Your Meetup user ID. You can find this user ID in the URL of your profile page or by running `query { self { id name } }` in the Meetup [GraphQL playground](https://www.meetup.com/api/playground/#graphQl-playground).
`EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY_ID` - The ID of a private key associated to your OAuth client. You'll be using this private key to [sign the JWT](https://www.meetup.com/api/authentication/#p04-jwt-flow-section) to request access tokens.
`EVENT_IMPORTER_MEETUP_GRAPHQL_PRIVATE_KEY_ID` - The path to the Meetup OAuth private key.

The Meetup OAuth client private key file can be stored anywhere on your machine. If you put the private key in the repository root, you can put it under `*.pem` and the file will be ignored by version control. If placed in the repository root and you run the project in Docker, the private key path will be `/var/www/html/your_private_key.pem`.

**NOTE**: Meetup requires a [Pro account](https://www.meetup.com/meetup-pro/) in order to create an OAuth client.

# Admin Panel
The admin panel in this project is built in [Filament][filament_docs]. This package is also built on [Laravel Livewire][livewire_docs].

After seeding the DB as [described above](#interacting-with-your-running-app), you'll have a default set of login credentials found in the [UsersTableSeeder][users_seeder] class.

To view the admin panel routes, run: `artisan route:list --name=filament`

Filament provides commands for generating [CRUD resources][filament_resources] and [individual pages][filament_pages]. But you can also create pages from [Livewire components][livewire_components] that borrow tables or [forms][filament_advanced_forms] from Filament.

[filament_advanced_forms]: https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component
[filament_docs]: https://filamentphp.com/docs
[filament_pages]: https://filamentphp.com/docs/3.x/panels/pages
[filament_resources]: https://filamentphp.com/docs/3.x/panels/resources/getting-started
[filament_resource_authorization]: https://filamentphp.com/docs/3.x/panels/resources/listing-records#authorization
[laravel_policies]: https://laravel.com/docs/10.x/authorization#creating-policies
[livewire_docs]: https://livewire.laravel.com/docs/quickstart
[livewire_components]: https://livewire.laravel.com/docs/components
[users_seeder]: https://github.com/hackgvl/hackgreenville-com/blob/develop/database/seeders/UsersTableSeeder.php

# Synchronizing Your Fork with the Latest Development Code Changes
Be sure you're on the desired branch, usually `git checkout develop`, and change to the project's base directory.

Run the following update script, which is part of this repo's /scripts directory.

```bash
sh scripts/handle-deploy-update.sh
```

# Contributing Code to the Project

- See the [HackGreenville style guide](https://hackgreenville.com/styles) for theming suggestions for fonts, headings, colors, and such.
- See the [Laravel installation documentation](https://laravel.com/docs/10.x/installation) for more details.
- Always follow the steps below when starting a new branch or pull request.
- We use an npm package called [pre-commit](https://www.npmjs.com/package/pre-commit). If you want to commit without running the pre-commit hook just add the switch `--no-verify` 

Contributions are made using [GitHub's Pull Request](https://docs.github.com/en/free-pro-team@latest/github/collaborating-with-issues-and-pull-requests/about-pull-requests) (aka PR) pattern. This allows anyone to suggest changes for review, commenting, and eventual approval / merging into the main project's repo.

<details><summary>Step 1: Sync Up with the Upstream HackGreenville Repo</summary>

Before creating a new git "branch" you'll want to sync up with the "remote upstream", which is just a fancy way of saying the main Hackgreenville.com (HG) GitHub repo.

1.  Save any uncommitted changes using `git stash` because the following steps can possibly reset / delete things in order to stay in sync with the upstream.

2.  Validate that you are on the `develop` branch

    ```sh
    git status
    ```
    
    You should get an output like this:
    
    ```sh
    On branch develop
    Your branch is up-to-date with 'origin/develop'.
    
    nothing to commit, working directory clean
    ```
    
    If you are not on develop or your working directory is not clean, resolve any outstanding files/commits and checkout `develop`:
    
    ```sh
    git checkout develop
    ```

3.  Sync the latest changes from the HG upstream `develop` branch to your local develop branch.

    This is very important to avoid conflicts later.

    > **Note:** If you have any outstanding Pull Request that you made from the `develop` branch of your fork, you will lose them at the end of this step. You should ensure your pull request is merged by a moderator before performing this step. To avoid this scenario, you should *always* work on a branch separate from develop.

    This step **will sync the latest changes** from the main repository of HG.

    Update your local copy of the HG upstream repository:
    ```sh
    git fetch upstream
    ```

    Hard reset your develop branch with the HG develop:
    ```sh
    git reset --hard upstream/develop
    ```

    Push your develop branch to your origin to have a clean history on your fork on GitHub:
    ```sh
    git push origin develop --force
    ```

    You can validate if your current develop matches the upstream/develop or not by performing a diff:
    ```sh
    git diff upstream/develop
    ```

    If you don't get any output, you are good to go to the next step.

</details>

<details><summary>Step 2: Creating and Pushing a Fresh Branch</summary>
    
  Working on a separate branch for each issue helps you keep your local work copy clean. You should never work on the `develop` branch. This will soil your copy of HG and you may have to start over with a fresh clone or fork.
    
  All new branches / contributions should be made off of the `develop` branch, but not in it, as described below.

1. Clean up before starting
   It's also good practice to clean up any orphaned branches from time to time.

   ```sh
   git remote prune origin
   git gc --prune
   ```

2. Selecting a branch name
   Check that you are on `develop` as explained previously, and branch off from there by typing:
   ```sh
   git checkout -b fix/update-readme
   ```
   Your branch name should start with `fix/`, `feat/`, `docs/`, etc. Avoid using issue numbers in branches. Keep them short, meaningful and unique.

Some examples of good branch names are:
`    fix/update-nav-links
    fix/calendar-popup-css
    docs/typos-in-readme
    feat/add-sponsors
   `

3. Edit files and write code on your favorite editor. Then, check and confirm the files you are updating:

   ```sh
   git status
   ```

   This should show a list of `unstaged` files that you have edited.

   ```sh
   On branch docs/typos-in-readme
   Your branch is up to date with 'upstream/docs/typos-in-readme'.

   Changes not staged for commit:
   (use "git add/rm <file>..." to update what will be committed)
   (use "git checkout -- <file>..." to discard changes in working directory)

       modified:   CONTRIBUTING.md
       modified:   README.md
   ...
   ```

4. Stage the changes and make a commit

   In this step, you should only mark files that you have edited or added yourself. You can perform a reset and resolve files that you did not intend to change if needed.

   ```sh
   git add path/to/my/changed/file.ext
   ```

   Or you can add all the `unstaged` files to the staging area using the below handy command:

   ```sh
   git add .
   ```

   Only the files that were moved to the staging area will be added when you make a commit.

   ```sh
   git status
   ```

   Output:

   ```sh
   On branch docs/typos-in-readme
   Your branch is up to date with 'upstream/docs/typos-in-readme'.

   Changes to be committed:
   (use "git reset HEAD <file>..." to unstage)

       modified:   CONTRIBUTING.md
       modified:   README.md
   ```

   Now, you can commit your changes with a short message like so:

   ```sh
   git commit -m "fix: my short commit message"
   ```

   We highly recommend making a conventional commit message. This is a good practice that you will see on some of the popular Open Source repositories. As a developer, this encourages you to follow standard practices.

   Some examples of conventional commit messages are:

   ```md
   fix: update API routes
   feat: RSVP event
   fix(docs): update database schema image
   ```

   Keep your commit messages short. You can always add additional information in the description of the commit message.

5. Push the new branch to your fork / origin. For example, if the name of your branch is `docs/typos-in-readme`, then your command should be:
   ```sh
    git push origin docs/typos-in-readme
    ```
</details>

<details><summary>Step 3: Proposing a Pull Request (PR)</summary>

1. Once a branch of your changes has been committed & pushed to your fork / origin you will automatically see a message when you visit your GitHub fork page.

The message will appear near the top of the page saying `Compare and Pull Request` which has a link to start a pull request based on your most recently pushed branch.

2. By default, all pull requests need to be matched against `base repository: hackgvl/hackgreenville-com` and `base: develop`, which should be the values set in the drop-downs on the left side of the "Comparing Changes" section at the top of the pull request creation page / form.

3. In the body of your PR include a more detailed summary of the changes you made and why.

   - Fill in the details as they seem fit to you. This information will be reviewed and a decision will be made whether or not your pull request is going to be accepted.

   - If the PR is meant to fix an existing bug/issue then, at the end of
     your PR's description, append the keyword `closes` and #xxxx (where xxxx
     is the issue number). Example: `closes #1337`. This tells GitHub to
     automatically close the existing issue, if the PR is accepted and merged.

You have successfully created a PR. Congratulations! :tada:

</details>

# Frequently Asked Questions

<details><summary>I found a typo. Should I report an issue before I can make a pull request?</summary>

For typos and other wording changes, you can directly open a [pull request](https://docs.github.com/en/free-pro-team@latest/github/collaborating-with-issues-and-pull-requests/about-pull-requests) without first creating an issue.

Issues are more for discussing larger problems associated with code or structural aspects of the application.
</details>

<details><summary>I am new to GitHub and Open Source, where should I start?</summary>

Read freeCodeCamp's [How to Contribute to Open Source Guide](https://github.com/freeCodeCamp/how-to-contribute-to-open-source).

Then, come back and see our ["Ways to Help"](#ways-to-help) section on how to specificially get involved in this project.
</details>

# Kudos
- Thanks to our [project contributors](https://github.com/hackgvl/hackgreenville-com#contributors-)
- Thanks to [freeCodeCamp's Chapter project](https://github.com/freeCodeCamp/chapter) for the template for this CONTRIBUTING.md.
- [https://sweetalert2.github.io/#examples](https://sweetalert2.github.io/#examples)
- [http://fullcalendar.io/docs](http://fullcalendar.io/docs)
- [https://vuejs.org/v2/guide/components.html](https://vuejs.org/v2/guide/components.html)
- [https://getbootstrap.com/docs/4.0/getting-started/introduction/](https://getbootstrap.com/docs/4.0/getting-started/introduction/)
- [https://lodash.com/](https://lodash.com/)
- [Plugin DatePicker](https://github.com/uxsolutions/bootstrap-datepicker)
