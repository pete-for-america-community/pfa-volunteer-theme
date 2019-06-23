# Pete For America

**Grassroots Website WordPress theme**
Version: 0.1.0

A WordPress theme designed to help catapult Pete Buttigieg into the White House, by empowering our grassroots volunteer network to collaboarate nationally, and publicize their effors. This theme is built with the goal of leveraging our volunteer coding team in a flexible and easy-to-manipulate framework, without the minimum required complexity of tooling.

## Getting Started

All you need to get started, is a local server running WordPress!

### Wordpress [Prerequisites](https://codex.wordpress.org/Template:Server_requirements)

- PHP (>7.0)
- Most recent version of Apache or NGINX
- MySQL, or MariaDB

- No packagae management or build tool is currently required

### Installing

1. Install a local web server with [the basic minimum specs](https://codex.wordpress.org/Template:Server_requirements)

   _There are several all-in-one-packages which handle the installation and maintenance of these applications:_

   - [MAMP](https://www.mamp.info/en/) is a recommended all-in-one package for OSX (and now Windows)
   - [WAMP](http://www.wampserver.com/en/) is an alternate all-in-one package for Windows 10
   - [Taskel](https://help.ubuntu.com/community/Tasksel) is a Debian/Ubuntu-based all-in-one package for Linux

2. Install the most recent version of [WordPress](https://wordpress.org/download/) (> 5.1.1) _in a separate directory from where this project is stored_.

   - A quick start guide can be found [here](https://codex.wordpress.org/Installing_WordPress)
   - A quick reference of the outlined steps follows:

3. Create a new database in MySQL/MariaDB

4. Visit the locally website instance in a web browser

5. The WordPress installation process will begin.

6. Fill in your database credentials, create a default local user login

7. Transfer the /pfa-theme/ directory found within the /public/theme/ directory into the /theme/ directory of your new WordPress site. It should be a sibling of the /twenty-nineteen/ default Wordpress theme folder

8. Log in to the back end of your local WordPress installation, using the credentials you created in step 6

9. Visit the Appearance -> Themes link in the left hand sidebar

10. Click the "Activate" button on the PFA Theme card, which also shows the PFA logo

11. Visit the front end by clicking your site's title in the upper left hand corner.

_Voila! You're now running the PFA Theme locally._

## Folder Structure

- /public/ -- all public code meant for deployment
  - /theme/pfa-theme/ -- all code meant to be deployed at the /wp-content/themes/ directory
    - /lib should hold any core functionality, classes, or third-party PHP assets (in /vendor/)
    - /partials/ should hold partial template files, intended to be rendered by many templates ie
      - sidebar.php
      - searchform.php
      - reusable/complex usage of the [WordPress Loop](https://codex.wordpress.org/The_Loop))
      - reusable/complex SQL queries
  - /img/ will hold any image files meant to be deployed to the /wp-content/uploads/ folder. IE, media assets created by the WordPress Media Uploader
  - /plugins/ any folders here should be deployed to the WordPress /wp-content/plugins/ directory
- /reference/ holds any documents created by the team to inform our intended direction
- /database/ holds any database backups, or database partials worked on to be imported (for example, a list of navigation items, or a list of custom slider posts)
- /tests/ will hold any PHPUnit testing we choose to include
- /vendor/ will hold any third-party packages managed by a package manager
- /private/ should house any files needed for your local machine, but which are not meant to be contributed to the project

## Running the Theme tests

Theme unit testing will likely be a requirement for contributing in the future, but currently it is not.

_Placeholder for theme unit tests_

_Placeholder for theme coding standards_

## Deployment

To deploy your changes to the live PFA Volunteer site, please contact [@bguggs here](https://github.com/bguggs), or on Slack for SSH access.

## Contributing

We welcome contributions! In fact, that's the point of this theme - to be quick, painless, and easy to follow.

- Placeholder for the team's CONTRIBUTING.md document, which may eventually include details on our code of conduct, and the process for submitting pull requests to us.

## Authors

- **Jared Parmenter** - _Initial work_ - [jagp](https://github.com/jagp)
- **Brian Guggenheim** - _Setup and Administration_ - [bguggs](https://github.com/bguggs)
- **Amy Rosenthal** - _Frontend Design_
- **Lynn Nichols** - WordPress Implementation
- **Lori Boyer** - WordPress Implementation
- **Daniel Sturman** - EventsMapper plugin - [dcsturman](https://github.com/dcsturman)
- **Shreyes Seshasai**

## License

While the team has not made an official decision on the theme license, this project will _likely_ be licensed under the MIT License

- Official License placeholder

## Acknowledgements

- The PFA Software Tools Team on our Grassroots Slack
