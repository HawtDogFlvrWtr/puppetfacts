# puppetfacts
puppetfacts is a php framework, wrapped with a bootstrap interface. The framework provides a webservice that can be used to provide fact information for puppet, to automate the installation of a system based on it's MAC address. Because the MAC is used, it can be used to automate a somewhat, baremetal installation, so long as the system has access to call the api and already has puppet installed. 

## Table of contents
* [Setup](#setup)
  * [Software](#software)
* [Usage](#usage)
  * [Api](#api)
  * [HTML Interface](#html-interface)
* [Screenshots](#screenshots)
* [Known Issues and Limitations](#known-issues-and-limitations)

## Setup
### Software
Required:
* Apache 2.4.X +
* Php 5.4.16 +
* Python 2 + (For password hash generation)

To install, simply clone this repository into the folder of your choice, and configure apache to access it. Ensure that all files and folders have the correct permission for apache to write files. For simplicity sake, the web application creates files on disk rather than in a database.

## Usage
### Api
To access the puppet facts, you'll need to access the api located at /getInfo.php. To gain information about a system, you provide it the macAddress of the system. (/getinfo.php?macAddress=00:00:00:00:00:00)
This will provide a json output, that you can parse as a factor on the puppetized system side, with the language of your choice. In the future, we will provide example fact executables that can be used to capture the facts that you've created.
### HTML Interface
Upon opening the webservice for the first time, you'll be reminded that you must set a default root and recovery user password, to ensure you can access puppetized systems. To do this, use the Generate Credentials page. Once this has been completed, you can begin creating, editing and deleting system configurations via the All Systems, and Add Systems links at the top of the page. 
## Screenshots
![Home](/images/home.png)
![AddSystem](/images/add_system.png)
![AllSystems](/images/all_systems.png)
![GenerateCreds](/images/generate_creds.png)
![Api](/images/api_json.png)
## Known Issues and Limitations
* PHP permissions must be set as such, that you're able to create local files, and execute a bash script.
* Currently only works under linux
