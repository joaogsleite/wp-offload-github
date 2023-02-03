# WP Offload Github

A Wordpress plugin to offload all attachments to a GitHub repo. 

## Usage

1. Download this repo/plugin as zip 
2. Install plugin by uploading the zip to your wordpress installation
3. Create an empty github repo (this is where all wordpress attachments will be uploaded to) 
3. Define required parameters on wp-config.php (see next section)
4. Start uploading new attachments to your wordpress gallery (everything will be offloaded to the github repo)

## Required parameters

Define these values on your `wp-config.php`

> Read here how to create a github token: https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token

```php
define('GITHUB_REPO', 'owner/repo');
define('GITHUB_BRANCH', 'uploads');
define('GITHUB_TOKEN', '....');
```
