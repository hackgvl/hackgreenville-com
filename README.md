<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->

[![All Contributors](https://img.shields.io/badge/all_contributors-13-orange.svg?style=flat-square)](#contributors-)

<!-- ALL-CONTRIBUTORS-BADGE:END -->

![HackGreenville](https://stage.hackgreenville.com/img/logo-v2.png)

# HackGreenville.com

The official repository for Greenville, SC's tech community website, maintained by [HackGreenville Labs](https://github.com/codeforgreenville).

[https://hackgreenville.com](https://hackgreenville.com/join-slack)

## Purpose

Our goal is to connect people to the tech-related community in the Greenville area. We do this by providing information on events and organizations in the area, as well as directing people to [sign-up for the HackGreenville Slack](https://hackgreenville.com/join-slack).

The organization data is queried from HackGreenville Labs's [organizations API](https://github.com/codeforgreenville/OpenData/blob/master/ORGANIZATIONS_API.md).

Then, for all of these organization, the events can be are queried from the [events API](https://github.com/codeforgreenville/upstate_tech_cal_service).

## Forking and Contributing

- See this project's [CONTRIBUTING.md](CONTRIBUTING.md) before creating issues, forking, or submitting any pull requests.
- You can connect with the development team by signing up for [HackGreenville Labs's Slack and visiting the #hackgreenville channel](https://codeforgreenville.org/)

## Tech Stack Notes

This project uses the [Laravel PHP framework](https://laravel.com).

[Laravel's Artisan command line tools](https://laravel.com/docs/master/artisan) is used to import events and organizations. Run `php artisan` to see a full list of availabe commands. Select commands of note include:

- Manually import the latest events from the API: `php artisan pull:events`
- Manually import the latest organizations from the API: `php artisan pull:orgs`
- Completely erase and rebuild the database: `php artisan migrate:fresh --seed`

## Repo notes

We use an npm package called [pre-commit](https://www.npmjs.com/package/pre-commit). If you want to commit without running the pre-commit hook just add the switch `--no-verify` 

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="http://www.turtlebytes.com, https://storagetreasures.com/"><img src="https://avatars0.githubusercontent.com/u/4049321?v=4?s=100" width="100px;" alt=""/><br /><sub><b>The Zach</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=zach2825" title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=zach2825" title="Tests">⚠️</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/pulls?q=is%3Apr+reviewed-by%3Azach2825" title="Reviewed Pull Requests">👀</a> <a href="#ideas-zach2825" title="Ideas, Planning, & Feedback">🤔</a> <a href="#design-zach2825" title="Design">🎨</a> <a href="#question-zach2825" title="Answering Questions">💬</a></td>
    <td align="center"><a href="https://github.com/allella"><img src="https://avatars0.githubusercontent.com/u/1777776?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Jim Ciallella</b></sub></a><br /><a href="#maintenance-allella" title="Maintenance">🚧</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/pulls?q=is%3Apr+reviewed-by%3Aallella" title="Reviewed Pull Requests">👀</a> <a href="#question-allella" title="Answering Questions">💬</a> <a href="#ideas-allella" title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=allella" title="Documentation">📖</a> <a href="#infra-allella" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#financial-allella" title="Financial">💵</a> <a href="#content-allella" title="Content">🖋</a></td>
    <td align="center"><a href="https://github.com/magoun"><img src="https://avatars1.githubusercontent.com/u/6494252?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Creighton Magoun</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=magoun" title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3Amagoun" title="Bug reports">🐛</a> <a href="#ideas-magoun" title="Ideas, Planning, & Feedback">🤔</a></td>
    <td align="center"><a href="https://github.com/Jaaron0606"><img src="https://avatars1.githubusercontent.com/u/18074750?v=4?s=100" width="100px;" alt=""/><br /><sub><b>James Aaron</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=Jaaron0606" title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3AJaaron0606" title="Bug reports">🐛</a> <a href="#ideas-Jaaron0606" title="Ideas, Planning, & Feedback">🤔</a></td>
    <td align="center"><a href="https://github.com/kevindees"><img src="https://avatars1.githubusercontent.com/u/348368?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Kevin Dees</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=kevindees" title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3Akevindees" title="Bug reports">🐛</a></td>
    <td align="center"><a href="https://github.com/JSn1nj4"><img src="https://avatars1.githubusercontent.com/u/5084820?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Elliot Derhay</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=JSn1nj4 " title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3AJSn1nj4 " title="Bug reports">🐛</a> <a href="#ideas-JSn1nj4 " title="Ideas, Planning, & Feedback">🤔</a></td>
    <td align="center"><a href="http://twitter.com/fancybike"><img src="https://avatars0.githubusercontent.com/u/4888730?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Pamela</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=pamelawoodbrowne" title="Documentation">📖</a> <a href="#content-pamelawoodbrowne" title="Content">🖋</a> <a href="#ideas-pamelawoodbrowne" title="Ideas, Planning, & Feedback">🤔</a> <a href="#eventOrganizing-pamelawoodbrowne" title="Event Organizing">📋</a></td>
  </tr>
  <tr>
    <td align="center"><a href="http://linktr.ee/jeremywight"><img src="https://avatars1.githubusercontent.com/u/8245600?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Jeremy Wight</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=jeremywight" title="Documentation">📖</a> <a href="#content-jeremywight" title="Content">🖋</a> <a href="#ideas-jeremywight" title="Ideas, Planning, & Feedback">🤔</a> <a href="#eventOrganizing-jeremywight" title="Event Organizing">📋</a> <a href="#financial-jeremywight" title="Financial">💵</a></td>
    <td align="center"><a href="https://github.com/jadelbe418"><img src="https://avatars1.githubusercontent.com/u/5350758?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Jacob</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=jadelbe418" title="Documentation">📖</a></td>
    <td align="center"><a href="https://github.com/Mozillex"><img src="https://avatars2.githubusercontent.com/u/25697042?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Loren McClaflin</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3AMozillex" title="Bug reports">🐛</a></td>
    <td align="center"><a href="https://github.com/MarkMcDaniels"><img src="https://avatars3.githubusercontent.com/u/8277379?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Mark McDaniels</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=MarkMcDaniels" title="Code">💻</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/issues?q=author%3AMarkMcDaniels" title="Bug reports">🐛</a></td>
    <td align="center"><a href="https://github.com/bogdankharchenko"><img src="https://avatars.githubusercontent.com/u/32746389?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Bogdan Kharchenko</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=bogdankharchenko" title="Code">💻</a> <a href="#design-bogdankharchenko" title="Design">🎨</a></td>
    <td align="center"><a href="https://olivia.sculley.dev"><img src="https://avatars.githubusercontent.com/u/88074048?v=4?s=100" width="100px;" alt=""/><br /><sub><b>Olivia Sculley</b></sub></a><br /><a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=oliviasculley" title="Code">💻</a> <a href="#ideas-oliviasculley" title="Ideas, Planning, & Feedback">🤔</a> <a href="#infra-oliviasculley" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="https://github.com/codeforgreenville/hackgreenville-com/commits?author=oliviasculley" title="Documentation">📖</a> <a href="#content-oliviasculley" title="Content">🖋</a></td>
  </tr>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
