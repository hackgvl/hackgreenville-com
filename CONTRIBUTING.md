# Contributing to HackGreenville.com

## Contribution Guidelines

Please play nice. We follow this ["Code of Conduct"](https://codeforgreenville.org/about/code-of-conduct).

## How to Help

#### Be Part of the Conversation and Share Your Thoughts

You don't need to be a "coder" to contribute. Many issues have UI, UX, accessibility, SEO, content / copywriting, and all order of non-code related conversations and improvements to be discussed.

Our focused task-based discussions happen mostly within GitHub [Issues](https://github.com/codeforgreenville/hackgreenville-com/issues) and [Pull Requests](https://github.com/codeforgreenville/hackgreenville-com/pulls) (also known as PRs).

You can also ask questions and connect with the development team in a less structured venue by signing up for [HackGreenville Slack and join the #hg-labs channel](https://codeforgreenville.org)

Before starting a new issue, please review and / or search the [current "open" issues](https://github.com/codeforgreenville/hackgreenville-com/issues/) to avoid duplicates.

If you can't find what you were looking for then [open a new issue](https://github.com/codeforgreenville/hackgreenville-com/issues/new) to share your suggestions or bugs.

When in doubt, you can reach out to an active project contributor:

| Name          | GitHub                                   | Role                               |
|:--------------|:-----------------------------------------|:-----------------------------------|
| Zach          | [@zach2825](https://github.com/zach2825) | Technical Lead, Laravel            |
| Jim Ciallella | [@allella](https://github.com/allella)   | Bugs, Documentation, Newcomer Help |

#### Contribute Code

If you feel ready to contribute code to this project, then follow the below steps.

## Forking the Project and Getting it Running

<details><summary>Step 1: Fork the Repository on GitHub</summary>

['Forking'](https://help.github.com/articles/about-forks/) is a step where you get your own copy of the repository (a.k.a repo) on GitHub.

This is essential as it allows you to work on your own copy of the code. It allows you to request changes to be pulled into HackGreenville's main repository from your fork via a pull request.

Follow these steps to fork the `https://github.com/codeforgreenville/hackgreenville-com` repository:

1. Go to the HackGreenville.com (HG) repository on GitHub: https://github.com/codeforgreenville/hackgreenville-com>.
2. Click the "Fork" Button in the upper right-hand corner of the interface ([Need help?](https://help.github.com/articles/fork-a-repo/)).
3. After the repository has been forked, you will be taken to your copy of the repository at `https://github.com/YOUR_USER_NAME/hackgreenville-com`.

</details>
<details><summary>Step 2: Preparing the Development Environment</summary>

Install [Git](https://git-scm.com/) and a code editor of your choice. We recommend using [VS Code](https://code.visualstudio.com/).

Clone your forked copy of the Hackgreenville.com code. ['Cloning'](https://help.github.com/articles/cloning-a-repository/) is where you download a copy of the repository from a `remote` location to your local machine. Run these commands on your local machine to clone the repository:

1. Open a Terminal in a directory where you would like the HG project to reside.

2. Clone your fork of the HG code, make sure you replace `YOUR_USER_NAME` with your GitHub username:

   ```sh
   git clone https://github.com/YOUR_USER_NAME/hackgreenville-com.git
   ```

This will download the entire repository to a `hackgreenville-com` directory.

Now that you have downloaded a copy of your fork, you will need to set up an `upstream`. The main repository at `https://github.com/codeforgreenville/hackgreenville-com` is often referred to as the `upstream` repository. Your fork at `https://github.com/YOUR_USER_NAME/hackgreenville-com` is often referred to as the `origin` repository.

You need a reference from your local copy to the `upstream` repository in addition to the `origin` repository. This is so that you can sync changes from the `upstream` repository to your fork which is called `origin`. To do that follow the below commands:

1.  Change directory to the new hackgreenville-com directory:

    ```sh
    cd hackgreenville-com
    ```

2.  Add a remote reference to the main Hackgreenvill.com GitHub repository. We're refer to this as "HG" in the later steps.

    ```sh
    git remote add upstream https://github.com/codeforgreenville/hackgreenville-com.git
    ```

3.  Ensure the configuration looks correct:

        ```sh
        git remote -v
        ```

        The output should look something like below:
        ```sh
        origin    https://github.com/YOUR_USER_NAME/hackgreenville-com.git (fetch)
        origin    https://github.com/YOUR_USER_NAME/hackgreenville-com.git (push)
        upstream    https://github.com/codeforgreenville/hackgreenville-com.git (fetch)
        upstream    https://github.com/codeforgreenville/hackgreenville-com.git (push)
        ```

    </details>

<details><summary>Step 3: Launching Your Local Copy / Fork of the Project</summary>

#### System Requirements

- [Requirements of Laravel 10](https://laravel.com/docs/10.x/deployment#server-requirements), which include PHP 7.3+ or PHP 8+
- You'll need [composer](https://getcomposer.org/download/) as well.
- You'll need [yarn](https://yarnpkg.com/lang/en/docs/install/) as well.
- For running "tests", you'll need SQLite and its associated PHP extensions enabled.
  > The PHP install package names and commands will differ based on your operating system, source repository, and other variations. Here are examples:
  >
  > - RHEL / CentOS / Fedora: `yum install php-sqlite3 php-pdo_sqlite`
  > - Ubuntu / Debian / Mint: `apt install php-sqlite3`
- MariaDB 10+ / MySQL 5.6+ - MariaDB is a compatible fork of MySQL and the community version can be installed using [operating system repositories](https://mariadb.com/kb/en/mariadb-package-repository-setup-and-usage/).

#### Initial Setup / Configuration (Host Install)

You need to make a copy of the `.env.example` file and rename it to `.env` at your project root.

Edit the new .env file and set your database settings.

#### **Running the database**


If it does not already exist, you will need to create the `hack_greenville` database in your local MySQL server.

```bash
mysql --user="dbusername" --password -e "create database hack_greenville"
```

#### **Installing Dependencies and Seeding Database**

Run the following scripts to install dependencies and seed the database:

```bash
composer install
yarn install
php artisan migrate --seed
```

The `migrate --seed` command will generate a default user *admin@admin.com* with a password of _admin_ and fill the states table.

#### **Running the app**

In one terminal, run the following command to start the Vite local development server:

```bash
yarn dev
```

> Note: for production environments, `yarn prod` would be used.

In another terminal, run the following command to start the Laravel server (will open on port `8000`)

```bash
php artisan serve
```

The app should now be accessible by visting `http://localhost:8000` in your browser.

#### **Generate app key and seed events + organizations**

Once the app is running, run the following command to generate your [app encryption key](https://laravel.com/docs/10.x/encryption):

```bash
php artisan key:generate
```

Then, to seed events and organizations into your application, run the following to import events and organizations from the Open Upstate API:

```bash
php artisan pull:events
php artisan pull:orgs
```

#### Initial Setup (Docker)

The docker setup of this project should only be done for advanced users, or if
needed for runtime compatibility issues.

You need to make a copy of the `.env.docker` file and rename it to `.env` at the
project root.

In addition, you'll need to generate the Laravel Sail docker files.
You can do this by executing `composer install`, or if you want to avoid using `composer`, you can instead run `mkdir vendor`, `mkdir vendor/laravel` and `git clone https://github.com/laravel/sail.git vendor/laravel/sail/` from inside the project root.

The database will be created for you automatically by the mysql docker image.
To initialize the project, do `docker-compose pull` to pull the necessary files, and then `docker-compose up --build` to begin running the project later.

First, you'll have to run `composer install` with docker exec while the original container is running with `docker exec -it hackgreenville composer install`.

The `composer install` command will help build the project by running migrations and initializing yarn, but you can also do this manually by running `docker exec -it hackgreenville php artisan migrate --seed; yarn install; yarn prod`.

Now, shut down the container by hitting `ctrl-c` and re-build the container with `docker-compose up --build`.

On the first start, you will need to generate an `APP_KEY` secret, which you can do by `docker exec -it hackgreenville php artisan key:generate` while the original container is running.

Make sure to set this in your `.env` file!

If you get file permission errors, please make sure permissions are set the UID `1337` and the GUID specified in `.env` by `WWWGROUP`.
I.e. if there are errors opening the log file, run `sudo chown -R 1337:www-data storage/`, if `www-data` is the group specified by `WWWGROUP` in `.env`.

If you run into "The Mix manifest does not exist", then run `docker exec -it hackgreenville php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"` and `docker exec -it hackgreenville npm run dev`.

After that, hit Ctrl-C in the original docker-compose to stop the application, and do `docker-compose up --build` to run it again.

If there are any changes in the application code, you will need to run `docker-compose up --build` to recreate the container with your changes.

#### Interacting with Your Running Copy of the Project

As in the earlier setup steps, Laravel Artisan is heavily leveraged to execute framework and custom commands for development and administration tasks.

- Running tests `php artisan test`
- Refreshing events from the remote API: `php artisan pull:events`
- Refreshing organizations from the remote API: `php artisan pull:orgs`

</details>

## Contributing Code to the Project

- See the [HackGreenville style guide](https://hackgreenville.com/styles) for theming suggestions for fonts, headings, colors, and such.
- See the [Laravel installation documentation](https://laravel.com/docs/10.x/installation) for more details.
- Always follow the steps below when starting a new branch or pull request.\*\*

Contributions are made using [GitHub's Pull Request](https://docs.github.com/en/free-pro-team@latest/github/collaborating-with-issues-and-pull-requests/about-pull-requests) (aka PR) pattern. This allows anyone to suggest changes for review, commenting, and eventual apporval / merging into the main project's repo.

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
`sh
    git push origin docs/typos-in-readme
    `
</details>

<details><summary>Step 3: Proposing a Pull Request (PR)</summary>

1. Once a branch of your changes has been committed & pushed to your fork / origin you will automatically see a message when you visit your GitHub fork page.

The message will appear near the top of the page saying `Compare and Pull Request` which has a link to start a pull request based on your most recently pushed branch.

2. By default, all pull requests need to be matched against `base repository: codeforgreenville/hackgreenville-com` and `base: develop`, which should be the values set in the drop-downs on the left side of the "Comparing Changes" section at the top of the pull request creation page / form.

3. In the body of your PR include a more detailed summary of the changes you made and why.

   - Fill in the details as they seem fit to you. This information will be reviewed and a decision will be made whether or not your pull request is going to be accepted.

   - If the PR is meant to fix an existing bug/issue then, at the end of
     your PR's description, append the keyword `closes` and #xxxx (where xxxx
     is the issue number). Example: `closes #1337`. This tells GitHub to
     automatically close the existing issue, if the PR is accepted and merged.

You have successfully created a PR. Congratulations! :tada:

</details>

## Frequently Asked Questions

### What do we need help with right now?

See our issues queue and pull requests for current and previously discussed tasks.

### I found a typo. Should I report an issue before I can make a pull request?

For typos and other wording changes, you can directly open pull requests without first creating an issue. Issues are more for discussing larger problems associated with code or structural aspects of the application.

### I am new to GitHub and Open Source, where should I start?

Read freeCodeCamp's [How to Contribute to Open Source Guide](https://github.com/freeCodeCamp/how-to-contribute-to-open-source).

Then, come back and see our ["How to Help"](#how-to-help) section on how to specificially get involved in this project.

## Related Links

- [http://stylifyme.com/?stylify=https%3A%2F%2Fwww.greenvillesc.gov](http://stylifyme.com/?stylify=https%3A%2F%2Fwww.greenvillesc.gov)
- [https://sweetalert2.github.io/#examples](https://sweetalert2.github.io/#examples)
- [http://fullcalendar.io/docs](http://fullcalendar.io/docs)
- [https://vuejs.org/v2/guide/components.html](https://vuejs.org/v2/guide/components.html)
- [https://getbootstrap.com/docs/4.0/getting-started/introduction/](https://getbootstrap.com/docs/4.0/getting-started/introduction/)
- [https://lodash.com/](https://lodash.com/)
- [Plugin DatePicker](https://github.com/uxsolutions/bootstrap-datepicker)

## Kudos

Thanks to [freeCodeCamp's Chapter project](https://github.com/freeCodeCamp/chapter) for the template for this CONTRIBUTING.md.
