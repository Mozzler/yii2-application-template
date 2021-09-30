Elastic Beanstalk
===================


In order to provide fast scaling, fault tolerance and other infrastructure resilience we use AWS's Elastic Beanstalk system.
It allows true cloud computing.

In Sept 2021 we [migrated](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/using-features.migration-al.html) from PHP7.2 on Amazon Linux 1 to PHP 7.4 on Amazon Linux 2.
The upgrade required a lot of changes and fixes.

One of the biggest changes is that we now have a .platform folder which contains scripts which are run at different points.
We are also using NGINX not Apache and had to update to a newer version of PHP.

General Usage
================================

You want to use the `eb` command from the main app folder.
You'll want to have the .ebextensions, .elasticbeanstalk and .platform files available in the git repo for that environment.

At the minimum the staging and main (or master) git repos should point to a different environment listed in `.elasticbeanstalk/config.yml`

The main thing you'll be doing is running `eb deploy`

Example successful deploy:

    /app$ eb deploy
    Creating application version archive "app-v2_2_1-staging-338-g6c2e-210930_151208".
    Uploading: [##################################################] 100% Done...
    2021-09-30 05:42:25    INFO    Environment update is starting.
    2021-09-30 05:43:07    INFO    Deploying new version to instance(s).
    2021-09-30 05:45:56    INFO    Instance deployment completed successfully.


Example commands that can be run from eb

    /app$ eb
    usage: eb (sub-commands ...) [options ...] {arguments ...}
    
    Welcome to the Elastic Beanstalk Command Line Interface (EB CLI).
    For more information on a specific command, type "eb {cmd} --help".
    
    commands:
    abort        Cancels an environment update or deployment.
    appversion   Listing and managing application versions
    clone        Clones an environment.
    codesource   Configures the code source for the EB CLI to use by default.
    config       Modify an environment's configuration. Use subcommands to manage saved configurations.
    console      Opens the environment in the AWS Elastic Beanstalk Management Console.
    create       Creates a new environment.
    deploy       Deploys your source code to the environment.
    events       Gets recent events.
    health       Shows detailed environment health.
    init         Initializes your directory with the EB CLI. Creates the application.
    labs         Extra experimental commands.
    list         Lists all environments.
    local        Runs commands on your local machine.
    logs         Gets recent logs.
    open         Opens the application URL in a browser.
    platform     Commands for managing platforms.
    printenv     Shows the environment variables.
    restore      Restores a terminated environment.
    scale        Changes the number of running instances.
    setenv       Sets environment variables.
    ssh          Opens the SSH client to connect to an instance.
    status       Gets environment information and status.
    swap         Swaps two environment CNAMEs with each other.
    tags         Allows adding, deleting, updating, and listing of environment tags.
    terminate    Terminates the environment.
    upgrade      Updates the environment to the most recent platform version.
    use          Sets default environment.
    
    optional arguments:
    -h, --help            show this help message and exit
    --debug               toggle debug output
    --quiet               suppress all output
    -v, --verbose         toggle verbose output
    --profile PROFILE     use a specific profile from your credential file
    -r REGION, --region REGION
    use a specific region
    --no-verify-ssl       don't verify AWS SSL certificates
    --version             show application/version info
    
    To get started type "eb init". Then type "eb create" and "eb open"



You'll mainly use `eb deploy`, `eb ssh`, `eb logs`, `eb health` and `eb status`

`eb deploy` works by using git's archive function to create a zip file then upload it from your machine. This means that you need to commit the changes you want, at least locally.
A PHP specific note is that if there's a `vendor/` folder then EB won't automatically run `composer install` as it'll expect you've already run that locally. It's usually easier to ensure you've got a good composer.json and composer.lock file and let the composer install run on the server so you don't have to send the vendor folder, smaller file uploads.



`eb ssh` will let you connect to an instance. If there's only 1 then it'll connect to that, if there's multiple it'll ask which you want to connect to.

**Scaling**

`eb scale` is useful if there's lots of traffic you might need to add more servers (or reduce the number after you've dealt with a spike).
You can simply add or remove servers as needed. Note that going from 1 to 2 or more servers means EB will create an AWS Load Balancer for farming the requests out.


`eb terminate` will destroy the environment. Unlikely something you want to do, unless maybe you've already created a new one using `eb create` in which case you might want to look into running `eb swap` for swapping the environment CNAMEs so you don't have to update the DNS.

You can use `eb setenv` to update the `DB_DSN` if the MongoDB Atlas database needs updating.
As of Sept 2021 you'll still want to use the non-srv version of the connection string as the Yii2 MongoDB library doesn't parse `mongodb+srv://` correctly

Example `eb setenv` :

    eb setenv "DB_DSN=mongodb://drivible:PASSWORD_GOES_HERE@ds05505-a0.mongolab.com:31003,ds05505-a1.mongolab.com:31003/drivible-staging?replicaSet=rs-ds05505"




-------------------------


Local EB CLI Setup
===============================

It's best you check the AWS docs on how to install the EB CLI (Elastic Beanstalk command line) tool on your machine.
Ideally it should be installed on your Vagrant VM or some sort of Linux like machine.


Creating an EB application and environment
==============================================================

You'll first need to create an AWS account and likely need to add a credit card to it, verify a mobile number, etc..

You'll also want to setup a [MongoDB Atlas](https://www.mongodb.com/cloud/atlas) account and cluster for running the Database.
Most likely the MongoDB instance will cost more than AWS but most of the developer / sys admin time will be spent dealing with Elastic Beanstalk.
Obviously keep the MongoDB instances in the same region as your AWS servers to reduce latency.

In AWS You can check your billing info fairly easily:

![images/AWS-account-menu.png](images/AWS-account-menu.png)


Login to [https://console.aws.amazon.com/](https://console.aws.amazon.com/)

Select your region
-----------------------------

In this example it's based in Australia so we select `ap-southeast-2` (Sydney)
You'll want to note down which region you select as it's where we are spinning up the servers, etc..

Note that the cheapest hosting is often in US East (e.g Ohio or North Virginia) but you want hosting that's closest to your customers otherwise they won't use it due to the latency.

![select-region.png](images/AWS-select-region.png)


Access Key
--------------------

First thing you want to do is create an Access Key.
This is used by the EB CLI and some of the online tools to make API calls to AWS.

1. Click your account name dropdown in the top right of the Nav Bar (`[Drivible]` in this example)
2. Click `[My Security Credentials]`
3. Alternatively Go directly to https://console.aws.amazon.com/iam/home?region=ap-southeast-2#/security_credential
4. Click `[Access keys (access key ID and secret access key)]` to open up the accordion section
5. Click `[Create New Access Key]` You'll want to Download the Key File and save the `Access Key ID` and `Secret Access Key` in a safe place.

Note: A better option is to create an IAM account with just the required permissions instead of root access.

![images/security-credentials-access-keys.png](images/AWS-security-credentials-access-keys.png)


SSH Key
--------------------
Now create an SSH key so you can login to the servers that'll be created.

Note: This is created for you via the EB CLI if you create an environment that way.

1. From the Nav bar Services dropdown (or search box) click `[EC2]`
2. Scroll down on the left hand side to **Network and Security** and click `[Key Pairs]`
3. Enter a name and click `[Create key pair]`
4. Download the file(s) and keep them somewhere safe. You'll need to install them to your machine later.

Note: If you downloaded a .ppk version for use in Putty then you'll need to use PuttyGen to convert it to a public file and private OpenSSH version.



![images/AWS-menu.png](AWS-menu.png)


![AWS-keypair-creation.png](images/AWS-keypair-creation.png)



Create the EB Application
===============================

An EB Application is a very basic container. You likely only need one application.
The environments are where most of the important settings are.

1. In the menu go to Elastic Beanstalk or directly to https://ap-southeast-2.console.aws.amazon.com/elasticbeanstalk/home?region=ap-southeast-2#/environments
2. Click `[Create a new environment]`
3. Set the name and description and click `[Create]` 

![AWS-eb-create-new-application.png](images/AWS-eb-create-new-application.png)


Create the EB Environment
===============================
The environment is where you set the important settings.
You'll likely want two environments, one for Staging and one for Production.
Most likely the staging server will just be a single instance, whilst Prod will be 2+ instances.


A Worker environment would require special work to setup and use. You can work that out yourself if needed. e.g For video encoding.

Select **Web server environment** 

![AWS-select-environment-tier.png](images/AWS-select-environment-tier.png)


Set the environment name and domain as needed

![AWS-environment-information.png](images/AWS-environment-information.png)


To create the local file for the Upload your code (Source code) you'll want to run the following from your Vagrant VM:

    git archive -v -o drivible-staging.zip --format=zip HEAD

Update the zip name as needed.
This does essentially the same as what `eb deploy` does to package your app.
Obviously upload the zip file.

We still need to configure the DB_DSN and some other things so click `[Configure more options]` (if needed you can configure the options later but you won't get a working site).

Note that you have to be very careful here as you can easily click the Navigation breadcrumb at the top or something on the sidebar and lose the application environment you were just creating and have to configure everything again.

![AWS-EB-configure-Environment-more-options-menu.png](images/AWS-EB-configure-Environment-more-options-menu.png)

**Prod changes:**

You can change the Configuration Presets if you are creating the Prod deploy. e.g **High availability**.
Note that Spot instances are ones that are based on free resources at AWS and can be terminated fairly quickly. That's mostly useful for really large processing. 
For Prod you'll likely just want 2, maybe 3 On-Demand instances. Checking the Capacity section and increasing the EC instance types to being larger can also help here.


In the Software section click `[Edit]`

Set the Document Root to `/web`

If you are on staging you'll want Display Errors to be `On`

You can set any other settings as you want.

For Environment Properties add a new one to tell the server where the database is and how to connect to it.


**Name**: `DB_DSN`

**Value**: You'll need to get this from MongoDB Atlas or however you connect to your DB, e.g `mongodb://drivible:PASSWORD_GOES_HERE@ds05505-a0.mongolab.com:31003,ds05505-a1.mongolab.com:31003/drivible-staging?replicaSet=rs-ds05505`

Click `[Save]`

![AWS-EB-Environment-modify-software.png](images/AWS-EB-Environment-modify-software.png)

In the Security section click `[Edit]`

Select the Virtual Machine permissions 'EC2 key pair' you created previously in the EC2 step (you should have a .pem or .ppk file from that step)

Click `[Save]`

![AWS-EB-Environment-modify-security.png](images/AWS-EB-Environment-modify-security.png)

You can configure other things as needed in the various sections.

Click `[Create environment]` when you are complete.

You should now see some streaming log output as the instance(s) are spun up and the application deployed.
It'll also create the various IAM roles, S3 bucket and various other things.

![AWS-eb-creating-environment.png](images/AWS-eb-creating-environment.png)

Once complete you should see the Elastic Beanstalk environment 
![AWS-EB-completed-creation.png](images/AWS-EB-completed-creation.png)


and you should be able to view the environment and see the health status

![AWS-eb-view-environment-healthy.png](images/AWS-eb-view-environment-healthy.png)


The URL provided should work. If the app works then you'll want to `eb ssh` into the server, go to /var/app/current and then run `./yii deploy/init`

This isn't scripted because it should only need to be run once. It sets up the default user account to login with and any other data you need initially inserted (based on the `config/preload-data.php` file)

You'll now want to update the DNS for the domain and set something like staging.example.com (whatever domain you've got) with a Cname entry to the provided EB domain.

Note that for tricky deploys or complex migrations there's a process of creating new environments in the same application, e.g call it prod2
You can then spin it up, get everything working, migrate database or whatever is needed then run the swap cname action and swap your Prod to Prod2.
This means you shouldn't need to do any DNS changes on your side.

You will however need to update the DNS if you are changing to a new account or something like that where you can't create an environment in the same application.


Setup eb and SSH
------------------------

If you've created the environment then you'll need to let the `eb` tool know.

You'll want to run `eb init` although depending on your preferences you might want to discard the .gitignore changes it attempts to make which make it hard to share with other devs.

For SSH access you'll want to set the files in your Vagrant VM's ~/.ssh/ folder

The filename should be the name of the EC2 keypair with .pub for the public and without extension for the very important private key file

e.g `~/.ssh/drivible.pub` should contain the public key (similar in format to authorized_keys)
You might need to use PuttyGen or some other software to export it to different versions if needed.
Or if it's a PEM file it might just need splitting up? Google it.

    ssh-rsa AAAAB3Nba...extra.content.here...BdET drivible


Then the main private key file is without a file extension

e.g `~/.ssh/drivible`

    -----BEGIN RSA PRIVATE KEY-----
    MIIHpAIBAAKCAQEAz6+ycqG6EQ+2ZaSLLdqj41HApxD+9cciK5zwLD35hRVdyRsl
    ....extra lines here...
    /8i1fL6qkM5zcyXkMTMD5KhYeSyF66MB4Eq1wWe+8F63AvAjG/ltMA==
    -----END RSA PRIVATE KEY-----

 

Potential issues
===============================


### Failed Deploy

Example failed deploy:

    eb deploy
    Creating application version archive "app-v2_2_1-staging-337-g34d0f-210930_150010".
    Uploading: [##################################################] 100% Done...
    2021-09-30 05:30:22    INFO    Environment update is starting.
    2021-09-30 05:31:03    INFO    Deploying new version to instance(s).
    2021-09-30 05:33:15    ERROR   Instance deployment failed. For details, see 'eb-engine.log'.
    2021-09-30 05:33:19    ERROR   [Instance: i-0efbdcf69182ec996] Command failed on instance. Return code: 1 Output: Engine execution has encountered an error..
    2021-09-30 05:33:19    INFO    Command execution completed on all instances. Summary: [Successful: 0, Failed: 1].
    2021-09-30 05:33:19    ERROR   Unsuccessful command execution on instance id(s) 'i-0efbdcf69182ec996'. Aborting the operation.
    2021-09-30 05:33:20    ERROR   Failed to deploy application.
    
    ERROR: ServiceError - Failed to deploy application.

If it fails you'll want to run `eb logs`

If the error is in a command or container_command or some other part it'll show an error about cloud formation and needing to check `/var/log/cfn-init.log`

Example:

    2021/09/27 15:41:55.221586 [INFO] Running command /bin/sh -c /opt/aws/bin/cfn-init -s arn:aws:cloudformation:ap-southeast-2:516147470969:stack/awseb-e-hnj6dkryd6-stack/34d3e131-1f92-11ec-b675-02df9a699d06 -r AWSEBAutoScalingGroup --region ap-southeast-2 --configsets Infra-EmbeddedPreBuild
    2021/09/27 15:41:59.218234 [ERROR] An error occurred during execution of command [app-deploy] - [PreBuildEbExtension]. Stop running the command. Error: EbExtension build failed. Please refer to /var/log/cfn-init.log for more details.

    2021/09/27 15:41:59.218255 [INFO] Executing cleanup logic
    2021/09/27 15:41:59.218363 [INFO] CommandService Response: {"status":"FAILURE","api_version":"1.0","results":[{"status":"FAILURE","msg":"Engine execution has encountered an error.","returncode":1,"events":[{"msg":"Instance deployment failed. For details, see 'eb-engine.log'.","timestamp":1632757319,"severity":"ERROR"}]}]}


If that's the case you'll need to `eb ssh` into the machine and run `cat /var/log/cfn-init.log` to see what's wrong.


Example /var/log/cfn-init.log 

    2021-09-30 05:33:14,951 [INFO] -----------------------Starting build-----------------------
    2021-09-30 05:33:14,960 [INFO] Running configSets: Infra-EmbeddedPostBuild
    2021-09-30 05:33:14,963 [INFO] Running configSet Infra-EmbeddedPostBuild
    2021-09-30 05:33:14,967 [INFO] Running config postbuild_0_drivible_staging
    2021-09-30 05:33:14,984 [INFO] Command 10-custom-php-ini succeeded
    2021-09-30 05:33:15,073 [ERROR] Command 20-redeploy-app (./yii deploy/redeploy --force) failed
    2021-09-30 05:33:15,073 [ERROR] Error encountered during build of postbuild_0_drivible_staging: Command 20-redeploy-app failed
    Traceback (most recent call last):
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 573, in run_config
    CloudFormationCarpenter(config, self._auth_config).build(worklog)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 273, in build
    self._config.commands)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/command_tool.py", line 127, in apply
    raise ToolError(u"Command %s failed" % name)
    cfnbootstrap.construction_errors.ToolError: Command 20-redeploy-app failed
    2021-09-30 05:33:15,076 [ERROR] -----------------------BUILD FAILED!------------------------
    2021-09-30 05:33:15,076 [ERROR] Unhandled exception during build: Command 20-redeploy-app failed
    Traceback (most recent call last):
    File "/opt/aws/bin/cfn-init", line 176, in <module>
    worklog.build(metadata, configSets)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 135, in build
    Contractor(metadata).build(configSets, self)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 561, in build
    self.run_config(config, worklog)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 573, in run_config
    CloudFormationCarpenter(config, self._auth_config).build(worklog)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/construction.py", line 273, in build
    self._config.commands)
    File "/usr/lib/python3.7/site-packages/cfnbootstrap/command_tool.py", line 127, in apply
    raise ToolError(u"Command %s failed" % name)
    cfnbootstrap.construction_errors.ToolError: Command 20-redeploy-app failed

This example shows that the `20-redeploy-app` command failed. In this case that command is specified in the `.ebextensions/05-environment.config` file
as container_commands -> 20-redeploy-app with the command: `./yii deploy/redeploy --force`

Running that yii command in the  `/var/app/staging` directory on the server showed the error (in this example it was an incorrect initialCredentials with a missing required field, updating the entry in the config/console.php file fixed it).
Note that any non-zero exit code will cause the deploy to fail.


### Updating the deployment processing

It should be highlighted that working through EB deploy issues can be time consuming because it can take a while for the eb deploy, eb logs, discovering the issue (sometimes spending a while googling other answers), fixing the issue, then running eb deploy again only to find another problem.
Although this is also true of Ansible and other similar deployment systems. 

Some key learnings:

* The .platform/hooks/pre*/*.sh files (e.g `.platform/hooks/prebuild/00-chmod-other-scripts.sh`) are run from the /var/app/staging directory and aren't run as root (doesn't seem to have a user), so use sudo
* You login with `ec2-user` but the main webapp e.g /var/app/current is owned by webapp:webapp
* In Amazon Linux 1 we used Apache, in Linux 2 it's using NGINX
* A non-zero exit code from a command or script will cause the deploy to fail.
* Running `eb deploy` re-runs the commands and prebuild hooks so you'll often want to check if something has already been installed before trying to install it, a great example of this is using pecl to install mongodb or imagick. Put lots of that code in `if [ ... ]; then ... fi` sections.
* File and folder permissions can be tricky to get right if using Windows, see the section on Executable Scripts, but this applies to runtime folders, etc..
* Find useful reference implementations e.g https://github.com/rennokki/laravel-aws-eb helped greatly
* You'll probably want to adjust the static file entries


Primary Reading Resources:

* [https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/Welcome.html](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/Welcome.html) - Official docs, intro to "What is Amazon Elastic Beanstalk?"
* [https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/platforms-linux-extend.html](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/Welcome.html) - Official docs on Extending Elastic Beanstalk Linux platforms
* [https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/create_deploy_PHP.container.html](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/create_deploy_PHP.container.html) - Official Docs on what the configuration options are
* [https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/environments-cfg-softwaresettings.html#environments-cfg-softwaresettings-console](https://docs.aws.amazon.com/elasticbeanstalk/latest/dg/environments-cfg-softwaresettings.html#environments-cfg-softwaresettings-console) - Offical Docs talking about the Environment Properties 
* [https://github.com/rennokki/laravel-aws-eb](https://github.com/rennokki/laravel-aws-eb) The code from this repo helped greatly, especially when upgrading from Amazon Linux 1 to 2.
 
You'll want to reference the deployment steps to know where to run different scripts:

![AWS-EB-platforms-linux-extend-order](images/AWS-EB-platforms-linux-extend-order.png)



### Executable Scripts

The .platform scripts need to be executable (chmod +x) otherwise they won't run properly.
Whilst `.platform/hooks/prebuild/00-chmod-other-scripts.sh` attempts to set this on the other scripts, but that file itself can't run without being executable.

If you get permission errors try running the following command to ensure the script files are executable and to tell git :

    cd /app
    chmod ugo+x .platform/hooks/*/*.sh
    git update-index --chmod=+x .platform/hooks/*/*.sh
    git add .platform/hooks/*/*.sh
    git commit -m "* Ensuring the .platform/hooks/*/*.sh are executable"


### Gotcha's

* Mozzler base specific - Currently we can't upgrade to composer v2 as the "fxp/composer-asset-plugin" doesn't seem to be compatible and so bootstrap fails. That's a project to tackle eventually... Might even need updating to a newer version of bootstrap


