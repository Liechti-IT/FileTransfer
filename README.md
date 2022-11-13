# "FileUpload"


Welcome to the "FileUpload" project, an [Open-Source software](https://en.wikipedia.org/wiki/Open-source_software).

"FileUpload" is a "one-click-filesharing": Select your file, upload, share a link. That's it.


![Screenshot1](/Screenshots/screenshot1.JPG?raw=true "Screenshot 1")

## Main features

- One upload → One download link & one delete link
- Send any large files (thanks to the HTML5 file API → PHP post_max_size limit not relevant)
- Shows progression: speed, percentage and remaining upload time
- Preview content in browser (if possible)
- Optional password protection (for uploading or downloading)
- Set expiration time for downloads
- Option to self-destruct after first download
- Shortened URLs using base 64 encoding
- Maximal upload size configurable
- NO database, only use basic PHP
- Simple language support with a lot of langages (help us on [weblate](https://hosted.weblate.org/engage/"FileUpload"/)!)
- File level [Deduplication](http://en.wikipedia.org/wiki/Data_deduplication) for storage optimization (does store duplicate files only once, but generate multiple links)
- Optional data encryption
- Small administration interface
- CLI script to remove expired files automatically with a cronjob
- Basic, adaptable »Terms Of Service« page
- Basic API
- Bash script to upload files via command line
- Themes

"FileUpload" is a fork of the project [Jirafeau](https://gitlab.com/mojo42/Jirafeau) based on the 4.5 version with **some** modifications.


## Screenshots

- [Installation - Step 1](/Screenshots/installscreenshot1.JPG)
- [Installation - Step 2](/Screenshots/installscreenshot2.JPG)
- [Installation - Step 3](/Screenshots/installscreenshot3.JPG)
- [Upload - Step 1](/Screenshots/screenshot1.JPG)
- [Upload - Step 2](/Screenshots/screenshot2.JPG)
- [Upload - Step 3](/Screenshots/screenshot3.JPG)
- [Admin Interface](/Screenshots/screenshot4.JPG)

## Installation

This shows how to install "FileUpload" by your own.

System requirements:
- PHP >= 5.6
- No database required, no mail required

Installation steps:
- Download the latest [release](https://github.com/Liechti-IT/FileUpload) from GitHub onto your webserver
- Set owner & group according to your webserver
- A) Setup with the installation wizard (web):
  - Open your browser and go to your installed location, eg. ```https://example.com/fileupload/```
  - The script will redirect to you to a minimal installation wizard to set up all required options
  - All optional parameters may be set in ```lib/config.local.php```, take a look at ```lib/config.original.php``` to see all default values
- B) Setup without the installation wizard (cli):
  - Just copy ```lib/config.original.php``` to ```lib/config.local.php``` and customize it


### Troubleshooting

If you have some troubles, consider the following cases

- Check your ```/lib/config.local.php``` file and compare it with ```/lib/config.original.php```, the configuration syntax or a parameter may have changed
- Check owner & permissions of your files
- set `debug` option to `true` to check any warning or error

## Security

```var``` directory contains all files and links. It is randomly named to limit access but you may add better protection to prevent un-authorized access to it.
You have several options:
- Configure a ```.htaccess```
- Move var folder to a place on your server which can't be directly accessed
- Disable automatic listing on your web server config or place a index.html in var's sub-directory (this is a limited solution)

If you are using Apache, you can add the following line to your configuration to prevent people to access to your ```var``` folder:

```RedirectMatch 301 ^/var-.* http://my.service.fileupload ```

If you are using nginx, you can add the following to your $vhost.conf:

```nginx
location ~ /var-.* {
    deny all;
    return 404;
}
```

If you are using lighttpd, you can deny access to ```var``` folder in your configuration:

```
$HTTP["url"] =~ "^/var-*" {
         url.access-deny = ("")
}
```

You should also remove un-necessessary write access once the installation is done (ex: configuration file).
An other obvious basic security is to let access users to the site by HTTPS (make sure `web_root` in you `config.local.php` is set with https).

## Server side encryption

Data encryption can be activated in options. This feature makes the server encrypt data and send the decryt key to the user (inside download URL).
The decrypt key is not stored on the server so if you loose an url, you won't be able to retrieve file content.
Encryption is configured to use AES256 in OFB mode.
In case of security troubles on the server, attacker won't be able to access files.

By activating this feature, you have to be aware of few things:
-  Data encryption has a cost (cpu) and it takes more time for downloads to complete once file sent.
-  During the download, the server will decrypt on the fly (and use resource).
-  This feature needs to have the mcrypt php module.
-  File de-duplication will stop to work (as we can't compare two encrypted files).
-  Be sure your server do not log client's requests.
-  Don't forget to enable https.

In a next step, encryption will be made by the client (in javascript), see issue #10.

## License

GNU Affero General Public License v3 (AGPL-3.0).

The GNU Affero General Public License can be found at https://www.gnu.org/licenses/agpl.html.

Please note: If you decide do make adaptions to the source code and run a service with these changes incorporated,
you are required to provide a link to the source code of your version in order to obey the AGPL-3.0 license.
To do so please add a link to the source (eg. a public Git repository or a download link) to the Terms of Service page.
Take a look at the FAQ to find out about how to change the ToS.


## FAQ

### How can I limit upload access?

There are two ways to limit upload access (but not download):
- you can set one or more passwords in order to access the upload interface, or/and
- you can configure a list of authorized IP ([CIDR notation](https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing#CIDR_notation)) which are allowed to access to the upload page

Check documentation of ```upload_password``` and ```upload_ip``` parameters in [lib/config.original.php](https://github.com/Liechti-it/FileUpload/lib/config.original.php).

### How can I automatize the cleaning of old (expired) files?

You can call the admin.php script from the command line (CLI) with the ```clean_expired``` or ```clean_async``` commands: ```sudo -u www-data php admin.php clean_expired```.

Then the command can be placed in a cron file to automatize the process. For example:
```
# m h dom mon dow user  command
12 3    * * *   www-data  php /path/to/"FileUpload"/admin.php clean_expired
16 3    * * *   www-data  php /path/to/"FileUpload"/admin.php clean_async
```

### How can I change the theme?

You may change the default theme to any of the existing ones or a custom.

Open your ```lib/config.local.php``` and change setting in the »`style`« key to the name of any folder in the ```/media``` directory.

Hint: To create a custom theme just copy the »courgette« folder and name your theme »custom« (this way it will be ignored by git and not overwritten during updates). You are invited to enhance the existing themes and send pull requests however.


### How to set maximum file size?

If your browser supports HTML5 file API, you can send files as big as you want.

For browsers who does not support HTML5 file API, the limitation come from PHP configuration.
You have to set [post_max_size](https://php.net/manual/en/ini.core.php#ini.post-max-size) and [upload_max_filesize](https://php.net/manual/en/ini.core.php#ini.upload-max-filesize) in your php configuration. Note that Nginx setups may requiere to configure `client_max_body_size`.

If you don't want to allow unlimited upload size, you can still setup a maximal file size in "FileUpload"'s setting (see ```maximal_upload_size``` in your configuration)

### How can I edit an option?

Documentation of all default options are located in [lib/config.original.php](https://github.com/Liechti-it/FileUpload/lib/config.original.php).
If you want to change an option, just edit your ```lib/config.local.php```.

### How can I change the Terms of Service?

The license text on the "Terms of Service" page, which is shipped with the default installation, is based on the »[Open Source Initiative Terms of Service](https://opensource.org/ToS)«.

To change this text simply copy the file [/lib/tos.original.txt](https://github.com/Liechti-it/FileUpload/lib/tos.original.txt), rename it to ```/lib/tos.local.txt``` and adapt it to your own needs.

If you update the installation, then only the ```tos.original.txt``` file may change eventually, not your ```tos.local.txt``` file.

### How can I access the admin interface?

Just go to ```/admin.php```.

### How can I use the scripting interface (API)?

Simply go to ```/script.php``` with your web browser.

### My downloads are incomplete or my uploads fails

Be sure your PHP installation is not using safe mode, it may cause timeouts.

If you're using nginx, you might need to increase `client_max_body_size` or remove the restriction altogether. In your nginx.conf:

```nginx
http {
    # disable max upload size
    client_max_body_size 0;
    # add timeouts for very large uploads
    client_header_timeout 30m;
    client_body_timeout 30m;
}
```

### What about this file deduplication thing?

"FileUpload" can use a very simple file level deduplication for storage optimization.

This mean that if some people upload several times the same file, this will only store one time the file and increment a counter.

If someone use his/her delete link or an admin cleans expired links, this will decrement the counter corresponding to the file.

When the counter falls to zero, the file is destroyed.

In order to know if a newly uploaded file already exist, "FileUpload" will hash the file using md5 by default but other methods are available (see `file_hash` documentation in `lib/config.original.php`).

This feature is disabled by default and can be enabled through the `file_hash` option.

### What is the difference between "delete link" and "delete file and links" in admin interface?

When file deduplication feature is enabled, files with the same hash are not duplicated and a reference counter stores the number of links pointing to a single file.
So:
- The button "delete link" will delete the reference to the file but might not destroy the file.
- The button "delete file and links" will delete all references pointing to the file and will destroy the file.
