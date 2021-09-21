# Install

You will need the following extra plugins to work:

- HTTPS Must be working on the site / vagrant
- ACF PRO
- https://github.com/IORoot/wp-plugin__oauth--YouTube
- https://github.com/IORoot/wp-plugin__oauth--GMB


These plugins will allow you to do an OAUTH connection to the specific service. They are ACF buttons that will complete the hand-shake 
with the google APIs.

Thee following plugin will make the code boxes look better.
- https://github.com/IORoot/wp-plugin__acf--codemirror

## Steps before activating AUTH plugins

1. Make sure you do a `composer install` or `composer update` in each before activating 
1. Also, make sure the `client_secret.json` file is placed in the root of each OAUTH plugin. (See below)

There is a .gitignore on the file `client_secret.json`, so you don't commit it to a repo by accident.

## Check

If the auth plugins are working, you will see a new 'LOGIN WITH YOUTUBE' button on authentication tab on the exporter plugin for youtube.

## Google My Business

Is one of the only client-libraries that is NOT part of the standard `google/apiclient-services`, so you have to download it yourself.
You can get it here: https://developers.google.com/my-business/samples/previousVersions

Place it into the `/GMB` folder.

## OAUTH Keys

Head over the the google console https://console.cloud.google.com/ and create a project with the following APIs:

- YouTube Data API v3
- Google My Business

The google my business API is a private one and you need to request access from google for it.

Create an OAUTH 2.0 client ID and download the `client_secret.json` file: place it into the root of the plugin.

## Troubleshooting

### OAUTH Issues


1. Ensure HTTPS is working. (Make sure cert is created and added to keychain)


2. Ensure the address of the `Authorised redirect URIs` has the correct URIs in it's list.
```
https://dev.exporter.londonparkour.com/wp-admin/admin-ajax.php
```
Preferably at the top of the list.

3. Ensure `Authorised JavaScript origins` in the google console APIs 'credentials' has the 
domain:
```
https://dev.exporter.londonparkour.com
```