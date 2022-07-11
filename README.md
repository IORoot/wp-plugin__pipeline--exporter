
<div id="top"></div>

<div align="center">

<img src="https://svg-rewriter.sachinraja.workers.dev/?url=https%3A%2F%2Fcdn.jsdelivr.net%2Fnpm%2F%40mdi%2Fsvg%406.7.96%2Fsvg%2Fexport.svg&fill=%23A855F7&width=200px&height=200px" style="width:200px;"/>

<h3 align="center">Pipeline : Exporter</h3>

<p align="center">
    Output results of the pipeline project to 3rd party services. Google-my-business and Youtube. Part of the Pipeline Project.
</p>    
</div>

##  1. <a name='TableofContents'></a>Table of Contents


* 1. [Table of Contents](#TableofContents)
* 2. [About The Project](#AboutTheProject)
	* 2.1. [Built With](#BuiltWith)
	* 2.2. [A note about the creative-studio integration.](#Anoteaboutthecreative-studiointegration.)
	* 2.3. [Installation](#Installation)
	* 2.4. [Steps before activating AUTH plugins](#StepsbeforeactivatingAUTHplugins)
	* 2.5. [Check](#Check)
	* 2.6. [Google My Business](#GoogleMyBusiness)
	* 2.7. [OAUTH Keys](#OAUTHKeys)
* 3. [Usage](#Usage)
	* 3.1. [Authentication](#Authentication)
	* 3.2. [Job](#Job)
	* 3.3. [Content](#Content)
	* 3.4. [Export](#Export)
	* 3.5. [Housekeep](#Housekeep)
	* 3.6. [Schedule](#Schedule)
* 4. [Exporters](#Exporters)
	* 4.1. [Google My Business](#GoogleMyBusiness-1)
		* 4.1.1. [Call-To-Action](#Call-To-Action)
		* 4.1.2. [Events](#Events)
	* 4.2. [YouTube](#YouTube)
	* 4.3. [Creator Studio](#CreatorStudio)
	* 4.4. [Trello](#Trello)
	* 4.5. [REST Endpoints](#RESTEndpoints)
* 5. [Customising](#Customising)
* 6. [Troubleshooting](#Troubleshooting)
	* 6.1. [OAUTH Issues](#OAUTHIssues)
* 7. [Contributing](#Contributing)
* 8. [License](#License)
* 9. [Contact](#Contact)
* 10. [Changelog](#Changelog)



##  2. <a name='AboutTheProject'></a>About The Project

The pipeline project was built to automatically build, process and export generated posts. This plugin is that last part of the process. The exporter connects with various endpoints and APIs to send those generated posts to.

Originally I wanted all social media, but eventually whittled it down to YouTube, Google-My-Business, Trello, Creator Studio and REST Endpoints.

<p align="right">(<a href="#top">back to top</a>)</p>



###  2.1. <a name='BuiltWith'></a>Built With

This project was built with the following frameworks, technologies and software.

* [ACF Pro](https://advancedcustomfields.com/)
* [Composer](https://getcomposer.org/)
* [PHP](https://php.net/)
* [Wordpress](https://wordpress.org/)

You will need the following extra plugins to work:

- HTTPS Must be working on the site / vagrant
- [ACF Pro](https://advancedcustomfields.com/)
- [https://github.com/IORoot/wp-plugin__oauth--YouTube](https://github.com/IORoot/wp-plugin__oauth--YouTube)
- [https://github.com/IORoot/wp-plugin__oauth--GMB](https://github.com/IORoot/wp-plugin__oauth--GMB)
- [https://github.com/IORoot/wp-plugin__acf--inline-datetime-field](https://github.com/IORoot/wp-plugin__acf--inline-datetime-field)

These plugins will allow you to do an OAUTH connection to the specific service. They are ACF buttons that will complete the hand-shake 
with the google APIs. These need to be configured and running (with a `client_secret.json` created) for the authentication to work.

(optional) The following plugin will make the code boxes look better.
- [https://github.com/IORoot/wp-plugin__acf--codemirror](https://github.com/IORoot/wp-plugin__acf--codemirror)


###  2.2. <a name='Anoteaboutthecreative-studiointegration.'></a>A note about the creative-studio integration.

This is purely a test interface to my other project [https://github.com/IORoot/docker__puppeteer--facebook](https://github.com/IORoot/docker__puppeteer--facebook).
That project is a  docker container that hosts a webserver to a puppeteer script. That script will navigate to creator-studio and attempt to post your video or image to Facebook and Instagram.
I created this to circumvent the lack of API to those services.

This plugin has an authentication and exporter method for that service, but you'll have to host that yourself. You can build the container and host it at a particular IP address. The authentication in this plugin will export to that address.

<p align="right">(<a href="#top">back to top</a>)</p>



###  2.3. <a name='Installation'></a>Installation

> This was built with ACF PRO - Please make sure it is installed before installing this plugin.

These are the steps to get up and running with this plugin.

1. Clone the repo into your wordpress plugin folder
    ```bash
    git clone https://github.com/IORoot/wp-plugin__pipeline--exporter ./wp-content/plugins/pipeline-exporter
    ```
1. Activate the plugin.


<p align="right">(<a href="#top">back to top</a>)</p>


###  2.4. <a name='StepsbeforeactivatingAUTHplugins'></a>Steps before activating AUTH plugins

1. Make sure you do a `composer install` or `composer update` in each before activating 
1. Also, make sure the `client_secret.json` file is placed in the root of each OAUTH plugin. (See below)

There is a .gitignore on the file `client_secret.json`, so you don't commit it to a repo by accident.

###  2.5. <a name='Check'></a>Check

If the auth plugins are working, you will see a new 'LOGIN WITH YOUTUBE' button on authentication tab on the exporter plugin for youtube.

###  2.6. <a name='GoogleMyBusiness'></a>Google My Business

Is one of the only client-libraries that is NOT part of the standard `google/apiclient-services`, so you have to download it yourself.
You can get it here: https://developers.google.com/my-business/samples/previousVersions

Place it into the `/GMB` folder.

###  2.7. <a name='OAUTHKeys'></a>OAUTH Keys

Head over the the google console https://console.cloud.google.com/ and create a project with the following APIs:

- YouTube Data API v3
- Google My Business

The google my business API is a private one and you need to request access from google for it.

Create an OAUTH 2.0 client ID and download the `client_secret.json` file: place it into the root of the plugin.

##  3. <a name='Usage'></a>Usage

This usage documentation is split between the main plugin and the exporters. This is because there are multiple exporters, each with their own interface which work differently. Therefore, see that section to find out how they work.

###  3.1. <a name='Authentication'></a>Authentication

The authentication tab connects the plugin to the third-party services. There are currently four different methods:

1. YouTube. Uses the ACF OAUTH Button for youtube.
2. Google-My-Business. Uses the OAUTH Button for GMB.
3. Trello. This requires:
    - API Key. A valid API key obtained from trello.
    - Token. A valid token obtained from trello.
4. IG-Scheduler. This connects to the [https://github.com/IORoot/docker__puppeteer--facebook](https://github.com/IORoot/docker__puppeteer--facebook) project hosted in a docker container. It needs:
    - Username. Facebook CreatorStudio account with a linked Instagram account.
    - Password. Facebook password for creator studio.
    - API Key. Set in the `auth.json` file in the [https://github.com/IORoot/docker__puppeteer--facebook](https://github.com/IORoot/docker__puppeteer--facebook) project.
    - IP Address. Where the container is being hosted and the express.js webserver is being run.

![authentication](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/authentication.png?raw=true)

###  3.2. <a name='Job'></a>Job

A job allows you to select variations of settings to run.

- Enabled. Turn on or off.
- Job ID. Identifier to destinguish between jobs.
- Content. Pick which content to use for this job.
- Export. Pick which export method to use for this job.
- Housekeep. Pick which housekeep method to use for this job.
- Schedule. Pick which schedule to use for this job.

![Job](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/Job.png?raw=true)

###  3.3. <a name='Content'></a>Content

The content is the body of the post you are going to use when exporting. This will be the text in your video or image post.

- Enabled. List this as an option to use in 'jobs'. Turn on or off.
- Content ID. The identifier to use when listed in the 'jobs' tab.
- Content Input. 
    - Query. Use a WP_QUERY array to select which posts to export.
    - Posts. Manually select which posts to export.

![Content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/Content.png?raw=true)

###  3.4. <a name='Export'></a>Export

This is where you configure the specific exporters to use and the targets. Please see the next section for a description of each exporter.

- Enabled. List in the 'jobs' tab or not.
- Export ID. The name of the export instance to list in the 'jobs' tab.
- Export Target Mapping. Add multiple targets:
    - Google My Business
    - YouTube
    - Trello
    - REST
    - Creator Studio

- Available Moustaches. These are special variables you can use in the textboxes and textareas on the exporters. For instance, you could use `{{0_post_name}}` in the `Title` field of your youtube exporter and it will substitute the first (0) post's `post_name` into the title.

> Note that since the query will return an array, the variable name will have a prefix of the index. `{{0_post_name}}`, `{{1_post_name}}`, `{{2_post_name}}` ...
> The meta-fields have a prefix with double underscores since they, themselves, are arrays.

![Export](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/Export.png?raw=true)

###  3.5. <a name='Housekeep'></a>Housekeep 

One the exporters have run, you might want to remove posts from the system so they don't get exported again. The housekeeping tab allows you to do that.

- Enabled. List in the 'jobs' tab or not.
- Housekeep ID. The name of the housekeeping instance to list in the 'jobs' tab.
- Housekeep Action. What to do with the posts that are returned from the query.
    - Bin Posts. Soft delete - can be recovered in the trash.
    - Delete Posts. Hard delete - cannot be recovered.
    - Bin Posts & Images. Soft delete - can be recovered in the trash.
    - Delete Posts & Images. Hard delete - cannot be recovered.
- Housekeep Query. A WP_Query array to query the database of posts. these posts then have the 'action' performed on them.

![Housekeep](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/Housekeep.png?raw=true)

###  3.6. <a name='Schedule'></a>Schedule 

- Enabled. List in the 'jobs' tab or not.
- Save ID. The name of the scheduled instance to list in the 'jobs' tab.
- Schedule List (row)
    - Schedule Label. Identifier.
    - Schedule Repeats. How often you want this schedule to repeat.
    - Schedule Starts. When to start this particular schedule. Note that the ACF [https://github.com/IORoot/wp-plugin__acf--inline-datetime-field](https://github.com/IORoot/wp-plugin__acf--inline-datetime-field) plugin needs to be installed for this.

![Schedule](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/Schedule.png?raw=true)

##  4. <a name='Exporters'></a>Exporters

###  4.1. <a name='GoogleMyBusiness-1'></a>Google My Business

####  4.1.1. <a name='Call-To-Action'></a>Call-To-Action 

##### Instance

- Enabled. Run this exporter or not.

- Instance Description. Any extra notesfor yourself to remember about this exporter. What it does, where it goes, etc...

![export-gmb-cta-instance](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-cta-instance.png?raw=true)

##### Account

- LocationID. As per the Google-My-Business API, [https://developers.google.com/my-business/ref_overview](https://developers.google.com/my-business/ref_overview) you'll need to query the API to get your locationID.

It should be in the format:
```php
accounts/{accountID}/locations/{locationID}

e.g.
accounts/1234567890123455678901/locations/12345678901234567890
```

![export-gmb-cta-account](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-cta-account.png?raw=true)

##### Content

- Summary. The text to be the main body of the post. You can use any of the available `{{moustache}}` variables. 

![export-gmb-cta-content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-cta-content.png?raw=true)

##### Call To Action

- CTA Type. The type of button to use. This will determine the words used on the call-to-action button.

- CTA URL. The URL to send the user if the button is pressed.

![export-gmb-cta-CTA](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-cta-CTA.png?raw=true)

##### Media

- Media Type. The available types of media (Photo / Video / etc) are listed here.

- Media Source URL. Where the media is hosted so that it can be downloaded to be posted onto google-my-business. You can use any of the available `{{moustache}}` variables. 

- Media Category. When the media is being uploaded to google my business, pick which category to file it under.

![export-gmb-cta-media](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-cta-media.png?raw=true)




####  4.1.2. <a name='Events'></a>Events

##### Instance 

- Enabled. Run this exporter or not.

- Instance Description. Any extra notesfor yourself to remember about this exporter. What it does, where it goes, etc...

![export-gmb-event-instance](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-instance.png?raw=true)
##### Account

- LocationID. As per the Google-My-Business API, [https://developers.google.com/my-business/ref_overview](https://developers.google.com/my-business/ref_overview) you'll need to query the API to get your locationID.

It should be in the format:
```php
accounts/{accountID}/locations/{locationID}

e.g.
accounts/1234567890123455678901/locations/12345678901234567890
```

![export-gmb-event-account](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-account.png?raw=true)

##### Content

- Summary. The text to be the main body of the post. You can use any of the available `{{moustache}}` variables. 

![export-gmb-event-content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-content.png?raw=true)

##### Event

- Start DateTime. Pick when the event is starting. 

- End DateTime. Pick when the event is finishing.

![export-gmb-event-event](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-event.png?raw=true)

##### Media

- Media Type. The available types of media (Photo / Video / etc) are listed here.

- Media Source URL. Where the media is hosted so that it can be downloaded to be posted onto google-my-business.

- Media Category. When the media is being uploaded to google my business, pick which category to file it under.

![export-gmb-event-media](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-media.png?raw=true)

##### Button

- Button Type. The type of button to use. This will determine the words used on the button.

- Button URL. The URL to send the user if the button is pressed.

![export-gmb-event-button](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-gmb-event-button.png?raw=true)



###  4.2. <a name='YouTube'></a>YouTube

##### Instance

- Enabled. Run this exporter or not.

- Instance Description. Any extra notes for yourself to remember about this exporter. What it does, where it goes, etc...

![export-youtube-instance](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-youtube-instance.png?raw=true)

##### Content

- Title. The title of the video being uploaded. You can use any of the available `{{moustache}}` variables.

- Description. The video description being uploaded.  You can use any of the available `{{moustache}}` variables. 

- Tags. A comma separated list of metatags to link to the video for searching.

![export-youtube-content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-youtube-content.png?raw=true)

##### Video

- Video Path. The local path of when the video is. This will be uploaded to GMB. You can use any of the available `{{moustache}}` variables.  Note: meta fields are arrays. {{video}} should be {{video_0}}.

- Thumbnail Path. The local path of the thumbnail image of the video being uploaded. This will be uploaded to GMB. You can use any of the available `{{moustache}}` variables.  Note: meta fields are arrays. {{path}} should be {{path_0}}

![export-youtube-video](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-youtube-video.png?raw=true)

##### Settings

- Embeddable. Set whether viewers can embed you video or not.

- Category. Pick the video category on youtube.

- Privacy Status. The default state of the video once uploaded.

- License. Which license to associate with the video.

![export-youtube-settings](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-youtube-settings.png?raw=true)

##### Published At 

- Published At. Pick a future date for when the video will be published.

![export-youtube-publish-at](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-youtube-publish-at.png?raw=true)





###  4.3. <a name='CreatorStudio'></a>Creator Studio

The creator studio exporter will directly communicate with your own hosted docker image of the [https://github.com/IORoot/docker__puppeteer--facebook](https://github.com/IORoot/docker__puppeteer--facebook) project.

This is a puppeteer script with an express.js webserver controlling it. You can use the web interface to tell the puppeteer script how to work. The puppeteer script will login to Creator studio as you, create a new post, upload your video, schedule it and then post.

This exporter will connect to the express.js API and send the details required to kick off the puppeteer script.


##### Instance 

- Enabled. Run this exporter or not.

- Instance Description. Any extra notesfor yourself to remember about this exporter. What it does, where it goes, etc...

![export-instagram-instance](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-instance.png?raw=true)

##### Content

- Post Caption. The main body of the post. You can use any of the available `{{moustache}}` variables.

![export-instagram-content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-content.png?raw=true)

##### Geo

- Location. Pick a world location. You can use any of the available `{{moustache}}` variables.

![export-instagram-geo](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-geo.png?raw=true)

##### Video

- Content URL. The URL of the video or image to download which will then be uploaded to creator studio. You can use any of the available `{{moustache}}` variables.

- Content Image URL. The thumbnail URL for a video. You can use any of the available `{{moustache}}` variables.


![export-instagram-video](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-video.png?raw=true)

##### Schedule

- Schedule. Using a PHP DateTime format, specify the relative date (+1 day, +2 weeks, +3600sec, etc...) of when to publish the post.

- Specific. Pick a specific DateTime to publish.

- Publish. Immediately publish now.

![export-instagram-schedule](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-schedule.png?raw=true)

##### Crosspost

- Crosspost to Facebook. Any post will be sent to both Instagram and Facebook.

![export-instagram-crosspost](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-crosspost.png?raw=true)

##### Admin

- NOOP. No-Operation. Does all steps except for publish post. Good for debugging the steps.

- Screenshots. Turn on screenshot debugging. The puppeteer script can take screenshots of every stage and output the results into the /screenshots path URL. This means you can debug when anything is not working before putting Chrome into "HEAD" mode and running locally in a DEV environment.

- Clear Cookies. By default, the system will save cookies - this is so you don't have consistent login problems. Facebook/Instagram have hard login protections and without cookies set, you'll get issues with logging in.

- Cookie Filename (.json). Name of the file to save the cookies to. `cookies.json`

- Video Filename. Filename of the video file that will be placed into the /videos folder. `video.mp4`

- Cover Image Filename. Filename of the cover image to be downloaded. Will be put in the /images folder. `cover.jpg`

![export-instagram-admin](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-instagram-admin.png?raw=true)








###  4.4. <a name='Trello'></a>Trello

##### Instance 

- Enabled. Run this exporter or not.

- Instance Description. Any extra notesfor yourself to remember about this exporter. What it does, where it goes, etc...

![export-trello-instance](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-trello-instance.png?raw=true)

##### Board & List

- Board. Which Trello board to add your card to. This field is auto populated when logged in.  (Auto-populated field)

- List. Which Trello list to add your card to. This field is auto populated when logged in.  (Auto-populated field)

![export-trello-board-list](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-trello-board-list.png?raw=true)

##### Content

- Name. Title of the card to add to trello. You can use any of the available `{{moustache}}` variables. 

- Description. The description content added to the card. You can use any of the available `{{moustache}}` variables. 

- Due Date. DateTime of when the card is due.

- Labels. Pick multiple labels from your existing label list.  (Auto-populated field)

![export-trello-content](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-trello-content.png?raw=true)

##### Image

- Source URL. The location of the image to to use on the card. You can use any of the available `{{moustache}}` variables. 

![export-trello-image](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-trello-image.png?raw=true)

##### Custom Fields

If you use the "custom fields" add-on within Trello, you can also specify the values to add to those fields activated on the cards.

Any Fields found will be auto-populated in the 'fields' column dropdown box.

- Custom Fields. (row)
    - Field. The name of the custom-field to fill in. (auto-populated dropdown - if none are listed, then custom-fields are not used on this board / list)
    - Value. The value you want to add to this custom field. You can use any of the available `{{moustache}}` variables. 

![export-trello-custom-fields](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-trello-custom-fields.png?raw=true)








###  4.5. <a name='RESTEndpoints'></a>REST Endpoints

##### Description 

- Description. Any extra notes for yourself to remember about this exporter. What it does, where it goes, etc...

![export-rest-description](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-rest-description.png?raw=true)

##### Endpoints

- Endpoint Route. Declare the name of the endpoint you are going to create. This URL can then be used to query for the post you are exporting. 
```
domain.com/wp-json/pipeline/v1/exporter/[ENDPOINT_ROUTE_NAME]
```
You can use IFTTT.com or Zapier.com to query this new endpoint for the posts.

- WP_Query Arguments. This is an array to pass into a 'get_posts()' function. The query here will be used, NOT the query in the 'content' section of the exporter.
The results will be displayed on the endpoint to be parsed.

An example:

```php
[
    'post_type' => 'exporter',
    'post_status' => 'publish',
    'order' => 'DESC',
    'numberposts' => 2,
]
```



![export-rest-endpoints](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-rest-endpoints.png?raw=true)

##### Help

Extra details for reference.

###### REST API
This exporter instance will declare a new REST endpoint for services like zapier.com or IFTTT.com to point at.

###### Endpoint Route
The endpoint route is the word or url path to be declared by WordPress. Any service that points to this new URL with a GET request will have the results of the content query returned.

###### WP_Query Arguments
This query will run when the endpoint is hit. Note that the query in the `content` tab of the exporter will NOT be used. This overrides it.

###### Enabled
Make sure that the export group is enabled. Otherwise the REST endpoint will not work.

Due to the code, all endpoints WILL be defined and will work if the the export is enabled, regardless of whether there is a job associated with it.

![export-rest-help](https://github.com/IORoot/wp-plugin__pipeline--exporter/blob/main/files/docs/export-rest-help.png?raw=true)






##  6. <a name='Troubleshooting'></a>Troubleshooting

###  6.1. <a name='OAUTHIssues'></a>OAUTH Issues


1. Ensure HTTPS is working. (Make sure cert is created and added to keychain)


2. Ensure the address of the `Authorised redirect URIs` has the correct URIs in it's list.
```
https://dev.exporter.londonparkour.com/wp-admin/admin-ajax.php
```
Preferably at the top of the list.

3. Ensure `Authorised JavaScript origins` in the google console APIs 'credentials' has your correct domain. 
domain:
e.g.
```
https://dev.exporter.londonparkour.com
```


<p align="right">(<a href="#top">back to top</a>)</p>


##  7. <a name='Contributing'></a>Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue.
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>



##  8. <a name='License'></a>License

Distributed under the MIT License.

MIT License

Copyright (c) 2022 Andy Pearson

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

<p align="right">(<a href="#top">back to top</a>)</p>



##  9. <a name='Contact'></a>Contact

Author Link: [https://github.com/IORoot](https://github.com/IORoot)

<p align="right">(<a href="#top">back to top</a>)</p>

##  10. <a name='Changelog'></a>Changelog

- v1.0.0 - Initial Commit
