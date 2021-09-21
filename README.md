# Install

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