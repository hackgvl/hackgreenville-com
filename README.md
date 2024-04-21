<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-17-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->

![HackGreenville](https://www.hackgreenville.com/img/logo-v2.png)

# [HackGreenville.com](https://hackgreenville.com)

The official repository for HackGreenville.com tech community website, maintained by the volunteers of [HackGreenville Labs](https://hackgreenville.com/labs).

# Purpose

Our goal is to connect people to the tech-related community in the Greenville area. We do this by supporting discovery applications to promote the area's events and organizations and hosting a vibrant [HackGreenville Slack](https://hackgreenville.com/join-slack).


# Forking, Contributing, and Synchronizing Changes

- See the [CONTRIBUTING.md](CONTRIBUTING.md) before creating issues, forking, or submitting any pull requests.
- You can connect with the development team by signing up for [HackGreenville Labs's Slack and visiting the #hg-labs channel](https://hackgreenville.com/join-slack)

# Running the App

See the [CONTRIBUTING.md](CONTRIBUTING.md) for the various options for running and administering the running application.

## Debugging

### Telescope (Laravel Debugging)

Telescope is a Laravel Debugging tool that allows you to see all the requests made to the application, and the responses they return.

If you want to enable it in your local environment, you can do so by setting the `TELESCOPE_ENABLED` environment variable to `true` in your `.env` file.

```bash
TELESCOPE_ENABLED=true
```

# Tech Stack Notes

## APIs
The organization data is queried from HackGreenville Labs' [Organizations API](https://github.com/hackgvl/OpenData/blob/master/ORGANIZATIONS_API.md).

The events data is queried through the [Events API](https://github.com/hackgvl/hackgreenville.com/EVENTS_API.md), which is now part of this repository.

## Laravel
This project uses the [Laravel PHP framework](https://laravel.com). The [CONTRIBUTING.md](CONTRIBUTING.md) goes into more technical details.

# Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="http://www.turtlebytes.com, https://storagetreasures.com/"><img src="https://avatars0.githubusercontent.com/u/4049321?v=4?s=100" width="100px;" alt="The Zach"/><br /><sub><b>The Zach</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=zach2825" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/commits?author=zach2825" title="Tests">⚠️</a> <a href="https://github.com/hackgvl/hackgreenville-com/pulls?q=is%3Apr+reviewed-by%3Azach2825" title="Reviewed Pull Requests">👀</a> <a href="#ideas-zach2825" title="Ideas, Planning, & Feedback">🤔</a> <a href="#design-zach2825" title="Design">🎨</a> <a href="#question-zach2825" title="Answering Questions">💬</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/allella"><img src="https://avatars0.githubusercontent.com/u/1777776?v=4?s=100" width="100px;" alt="Jim Ciallella"/><br /><sub><b>Jim Ciallella</b></sub></a><br /><a href="#maintenance-allella" title="Maintenance">🚧</a> <a href="https://github.com/hackgvl/hackgreenville-com/pulls?q=is%3Apr+reviewed-by%3Aallella" title="Reviewed Pull Requests">👀</a> <a href="#question-allella" title="Answering Questions">💬</a> <a href="#ideas-allella" title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/hackgvl/hackgreenville-com/commits?author=allella" title="Documentation">📖</a> <a href="#infra-allella" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#financial-allella" title="Financial">💵</a> <a href="#content-allella" title="Content">🖋</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/magoun"><img src="https://avatars1.githubusercontent.com/u/6494252?v=4?s=100" width="100px;" alt="Creighton Magoun"/><br /><sub><b>Creighton Magoun</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=magoun" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3Amagoun" title="Bug reports">🐛</a> <a href="#ideas-magoun" title="Ideas, Planning, & Feedback">🤔</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/Jaaron0606"><img src="https://avatars1.githubusercontent.com/u/18074750?v=4?s=100" width="100px;" alt="James Aaron"/><br /><sub><b>James Aaron</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=Jaaron0606" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3AJaaron0606" title="Bug reports">🐛</a> <a href="#ideas-Jaaron0606" title="Ideas, Planning, & Feedback">🤔</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/kevindees"><img src="https://avatars1.githubusercontent.com/u/348368?v=4?s=100" width="100px;" alt="Kevin Dees"/><br /><sub><b>Kevin Dees</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=kevindees" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3Akevindees" title="Bug reports">🐛</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/JSn1nj4"><img src="https://avatars1.githubusercontent.com/u/5084820?v=4?s=100" width="100px;" alt="Elliot Derhay"/><br /><sub><b>Elliot Derhay</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=JSn1nj4 " title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3AJSn1nj4 " title="Bug reports">🐛</a> <a href="#ideas-JSn1nj4 " title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/hackgvl/hackgreenville-com/commits?author=JSn1nj4 " title="Documentation">📖</a></td>
      <td align="center" valign="top" width="14.28%"><a href="http://twitter.com/fancybike"><img src="https://avatars0.githubusercontent.com/u/4888730?v=4?s=100" width="100px;" alt="Pamela"/><br /><sub><b>Pamela</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=pamelawoodbrowne" title="Documentation">📖</a> <a href="#content-pamelawoodbrowne" title="Content">🖋</a> <a href="#ideas-pamelawoodbrowne" title="Ideas, Planning, & Feedback">🤔</a> <a href="#eventOrganizing-pamelawoodbrowne" title="Event Organizing">📋</a></td>
    </tr>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="http://linktr.ee/jeremywight"><img src="https://avatars1.githubusercontent.com/u/8245600?v=4?s=100" width="100px;" alt="Jeremy Wight"/><br /><sub><b>Jeremy Wight</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=jeremywight" title="Documentation">📖</a> <a href="#content-jeremywight" title="Content">🖋</a> <a href="#ideas-jeremywight" title="Ideas, Planning, & Feedback">🤔</a> <a href="#eventOrganizing-jeremywight" title="Event Organizing">📋</a> <a href="#financial-jeremywight" title="Financial">💵</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/jadelbe418"><img src="https://avatars1.githubusercontent.com/u/5350758?v=4?s=100" width="100px;" alt="Jacob"/><br /><sub><b>Jacob</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=jadelbe418" title="Documentation">📖</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/Mozillex"><img src="https://avatars2.githubusercontent.com/u/25697042?v=4?s=100" width="100px;" alt="Loren McClaflin"/><br /><sub><b>Loren McClaflin</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3AMozillex" title="Bug reports">🐛</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/MarkMcDaniels"><img src="https://avatars3.githubusercontent.com/u/8277379?v=4?s=100" width="100px;" alt="Mark McDaniels"/><br /><sub><b>Mark McDaniels</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=MarkMcDaniels" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3AMarkMcDaniels" title="Bug reports">🐛</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/bogdankharchenko"><img src="https://avatars.githubusercontent.com/u/32746389?v=4?s=100" width="100px;" alt="Bogdan Kharchenko"/><br /><sub><b>Bogdan Kharchenko</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=bogdankharchenko" title="Code">💻</a> <a href="#design-bogdankharchenko" title="Design">🎨</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://olivia.sculley.dev"><img src="https://avatars.githubusercontent.com/u/88074048?v=4?s=100" width="100px;" alt="Olivia Sculley"/><br /><sub><b>Olivia Sculley</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=oliviasculley" title="Code">💻</a> <a href="#ideas-oliviasculley" title="Ideas, Planning, & Feedback">🤔</a> <a href="#infra-oliviasculley" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="https://github.com/hackgvl/hackgreenville-com/commits?author=oliviasculley" title="Documentation">📖</a> <a href="#content-oliviasculley" title="Content">🖋</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://irby.io/"><img src="https://avatars.githubusercontent.com/u/10983811?v=4?s=100" width="100px;" alt="Matthew H. Irby"/><br /><sub><b>Matthew H. Irby</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=irby" title="Code">💻</a> <a href="https://github.com/hackgvl/hackgreenville-com/pulls?q=is%3Apr+reviewed-by%3Airby" title="Reviewed Pull Requests">👀</a> <a href="https://github.com/hackgvl/hackgreenville-com/issues?q=author%3Airby" title="Bug reports">🐛</a> <a href="https://github.com/hackgvl/hackgreenville-com/commits?author=irby" title="Tests">⚠️</a></td>
    </tr>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/AbolfazlAkhtari"><img src="https://avatars.githubusercontent.com/u/68465524?v=4?s=100" width="100px;" alt="Abolfazl Akhtari"/><br /><sub><b>Abolfazl Akhtari</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=AbolfazlAkhtari" title="Code">💻</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/Alex-Grimes"><img src="https://avatars.githubusercontent.com/u/66704965?v=4?s=100" width="100px;" alt="Alexander Grimes"/><br /><sub><b>Alexander Grimes</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=Alex-Grimes" title="Code">💻</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/alhelwany"><img src="https://avatars.githubusercontent.com/u/115778766?v=4?s=100" width="100px;" alt="Mhd Ghaith Alhelwany"/><br /><sub><b>Mhd Ghaith Alhelwany</b></sub></a><br /><a href="https://github.com/hackgvl/hackgreenville-com/commits?author=alhelwany" title="Code">💻</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
