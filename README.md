# Anti Framework Framework (AFF)
## Preface
### Framework
Low-code, PWA, API’s: all hot items in software development. 
Above those items there is another trend: frameworks. We know frameworks for specific languages: Laravel for php, Angular for Javascript and a lot more. But there are also frameworks for Low-code application development. Inside them you can use one or more languages, even language frameworks as Vue Js.
With the frameworks you can make PWA’s and API’s. Or monolithic legacy systems, whatever you want.

### Do we need them?
But, the main question is, do we realy need frameworks? Is a third generation language as Javascript, Java, Phyton, Php not already a kind of framework, above assembly languages?
Yes and no.
Yes, if we want to make an application with several forms and overviews that must be have the same look and feel, it’s not wise to program each screen by hand in a third generation language. All things that repeat, you better generate. So, it’s handy to have a framework.
No, frameworks have several disadvantages: vendor lock in, learn curve or effort to get trained resources, update work (include conversion) and lack of performance. And you know: frameworks come and go. Third generation languages do live much longer. If you want to switch to another framework, it cost you a lot of money/time (selection, training, conversion).

### Alternative
What is the alternative? Create your own framework!
Is it hard to do? Yes, it cost a view weeks or months. But I use my own framework more than 15 years. It’s still in production, several clients use the framework for five different applications.
Yes, I did update the framework several times. Little or bigger changes. But’s it is worth it. I create new forms/overviews in seconds or minutes, depending on the difficulty. And if I must at code, of course, then I spend also hours on a certain functionality. But always within the framework, also using functionality of the framework. So, it works rapidly.

### Architectural preferences
Beside low-code, PWA en API-based, I have some other architectural preferences. 
XMLHttpRequest: I hate applications that load the whole html page after each action. Only the part that must change should be refreshed. It can be done with XMLHttpRequest’s.
Css: I hate hard coded style (external css-files, internal or inline css). Style is in my opinion client data. You must be able to change the style on the fly. For example for a specific application, a specific customer, a specific role, etc.. So, style must be stored in the database and pushed to the browser by your server-code, for example Php.
Javascript: I hate external js-files. There are different types of Javascript. First you want to have a simple html-file for the start of your application, which contain a little internal Javascript for start actions. The Javascript that will be used for common task, can be loaded in the head. And form specific Javascript can be loaded with the actual page. And last but not least, you can simply switch code on the server, depending on the role, the customer, the application and so on. You have more control over it than with external files.
Classes: I hate classes. They make it unnecessary complex. I can do everything with functions. Ok, I wrote some classes. Once I rewrote my framework to Laravel, with almost only classes. But at the end the performance was bad and I did not see any advantages. So, I stopped that project. And I never wrote a class again. 
MVC: I hate code all over the place. I love structures. May to much. But I embrace MVC. It’s a beautiful way of organization code.

### New-new-new
Last summer (2019) I wrote a completely new framework. I did it for fun, because of new insights. Maybe I do complete it and replace my current production framework. I don’t know yet.
Because the global principles of my low code framework did not change, it can be simple convert. I did this often before.

I did never share my framework. Until now. The reason is that I’m a teacher ICT now (since 2018) and sharing give me a lot of pleasure and I can learn from the reactions.
Don’t copy only my code: it is far from production state. It is not bug-free, it’s not technical perfect. It’s my way of programming.
What I want to give is a thought, a way of set up code, an architecture. Use what you want, but make it your own framework!


## The main process
### Handy
It can be handy to download the code before looking at the pictures and reading the text. But, I leave it to you. You can start with app_site. It’s less complex: no login, no ini generation functionality.

### Main process
This is the main process of my framework:
### Step 1

![p1](https://www.johnnyhogenbirk.nl/items/aff/images/p1.jpg)
 
The browser ask for the main page: index.html.
It’s a very simple html-page, some meta tags in de head, some Javascript for start actions and very little html. 

### Step 2

![p2](https://www.johnnyhogenbirk.nl/items/aff/images/p2.jpg)

In the index.html page, at the end, a few Javascript functions will be called: register the Progressive Web App and ask for Javascript, style and Html. (The Html after the Javascript en style is loaded). The call is made to api.php. On the webserver the api.php code gets the data and send it back. The browser stores it, in the head (Javascript and style) of body (Html).

### Step 3

![p3](https://www.johnnyhogenbirk.nl/items/aff/images/p3.jpg)

In the data that is loaded, there was also a little Javascript: ask for the starting page. That question-code will be used every time the browser want to have a page.
Api.php opens the ini file of the view. In that ini file can be specifications for data and Javascript. When that’s the case, api.php does collect also the data and Javascript.

### Step 4 and next
 ![p4](https://www.johnnyhogenbirk.nl/items/aff/images/p4.jpg)
 
After the start, the situation can be repeated: if the browser want to have a view, it will ask for it. 
But, there is another situation possible: the user want to store data (submit form). In that case api.php first store the data, then send back (if noted in the view) a message and Javascript that will ask for the next page.


## File structure
Now, in more detail, the specification of api.php and the code behind it.

### Code structure, main map:

![p5](https://www.johnnyhogenbirk.nl/items/aff/images/p5.jpg)

As I mentioned, I love MVC, as you can see at the main structure. Only manifest.json and the service-worker.js I did store in the main map. I don’t know if that is necessary, but I like it this way. It’s very different from the rest of the code.

The main code is api.php.
It can be only used with a get-call with the parameter 'view_name’ ($_GET['view_name']) or with a post-call with the parameter ‘f_view_name’ ($_POST['f_view_name']) and ‘f_button_id ($_POST["f_button_id"]).
It can be called by the browser, but also by other webservers. 
If 'view_name’ or 'f_view_name+f_button_id' is present, it include some functions and starts the session.
After that, there are two situations that can be occur (not both at the same call):
Post: Then it handles the post.
Get: Then it handles the delivery of the view.
I will talk you through those processes later (in ‘generation’). 

In the map control I have very little, only the general functions for all php-files.

Mode map
The map model is more complex:

![p6](https://www.johnnyhogenbirk.nl/items/aff/images/p6.jpg)

The file model_functions_get.php will always be loaded by api.php, because those functions are almost always needed.
The file model_functions_put.php will only be loaded by api.php if there is a posting.

In the map data I did stored some csv-files. Ok, I called it .txt, because it are text files, but the structure is csv. Normally this data will be stored in a database. But for demo/share purposes I did use text files.

I also split the map models in two: get and put. Both contain text files (in csv structure). I will handle this later, in ‘generation’.

In de map php there also two maps: get and put. These files are ‘the code’ in the low-code model. I will talk you through later (in ‘generation’).

View map

![p7](https://www.johnnyhogenbirk.nl/items/aff/images/p7.jpg)

The last map: the map view. You find three typical view maps in this map: fonts, images and javascript. The map javacript contains two maps: Javascript witch is needed at the start of the application (step 2, so the map is called ‘start’) and Javascript needed in the next steps, stored in the map ‘operation’.
There is one special map: views. It contains like text files (in csv-structure) that describe the views.
I will talk you through later (in ‘generation’).


## MVC
We can merge the process and file structure to a kind of MVC picture. I want to go in more detail than the usual three balloons with arrows. So, I split it in two pictures. One for the data store action and one for the ask view action.

Data store

![p8](https://www.johnnyhogenbirk.nl/items/aff/images/p8.jpg)

Ask view

![p9](https://www.johnnyhogenbirk.nl/items/aff/images/p9.jpg)

As I said, there are several pictures of MVC-models, with tree balloons and arrows. But each picture has arrows between different balloons or different direction. Mostly I don’t understand why they paint it the way they do.

In my opinion the webserver make the call to the controller. So, only the controller can talk back. Not the view or the model, as some pictures from others show.
And if the question from the webserver is to store data, there is no view present in de loop. The only data that is given back, is a message (data stored or something like that) and a kind of advice: Javascript with a script that ask for the next view.

Note: it’s the webserver that talks to the controller, not the browser! The caller can be a browser or backend code. The caller ask the webserver(!) for information of give it data to store. The application code is stored on a application server and the database on a database server (physically on another server or on a shared server, that’s not interesting for the architectural software concept).


## Generation, overview
As a mentioned in Prefaces, low-code frameworks are hot and framework has there advantages. But, it better can be your own framework.
The main framework principle is double loop generation:
Generation of view- and model-specifications, stored in ini files.
Generation of html pages with the ini files.

Note: the ini files are text files, with a certain structure. I use csv, but json or xml are also possible.

I admit, It’s a little complex: the generation of ini files are mainly done with views and models that are generated in a previous stage. Generation by the generator. Sometime I’m confused myself.
I said ‘mainly’, because they need some help from code (in my case, php-code).
That’s also a main issue: I prefer low-code over no-code. It is not possible to generate complex applications with a no-code framework. No-code is nice for end users, like a spreadsheet. But the software I’m focusing on, can never be generated by a no-code framework. So, in the ini files, you can point at the php that must be executed.

Let’s look in more detail at the generation. I start with the second stage of the generation: generation of html pages with the ini files. After that, I resume with the first step: generation of view- and model-specifications, stored in ini files.


## Html generation
First, a view is asked by the browser (read: webserver). The api detects that and does call the ‘start-functions’ (step 2) or the operation function, called ‘f_v_get_view’.
The start functions do focus on getting the main Javascript, style and Html. You can look at it for yourself, I don’t think it needs explanation.

The php function ‘f_v_get_view’ is more complex. The function takes tree steps: get the view, the Javascript and data. Only the view is always needed, the Javascript and data can be needed. 

In the ‘Get the view’ step the ini file will be read and stored in a array with index ‘view’. It’s a copy of the ini file. It will be send back by api.php via json, to the browser.
At the start (step 2), the Javascript function ‘f_get_content’ was loaded. That function did the call for a view. After getting the view specs (the copy of the ini file), the function ‘f_get_content_catch_reply’ will be called. This function handles (if present) the alert, include the Javascript and execute the Javascript. But the main functions that will be called are ‘f_show_form’ and ‘f_show_overview’. They transfer the spec’s from the view to Html code.

In the ‘Get the Javascript’ and ‘Get the data’ steps the php-code checks if in the view specs is registered that Javascript or data is needed. If so, the php read the javascript c.q. data and stores it in the indexes ‘javascript’ and data.

I will go in details in the section ‘Content and connection views, models, Javascript, etc.’


## Ini generation
Thirst, you don’t have to generate the ini files. You can also use a text editor. But, as software deveopers, we want to make our live easy: we generate the ini files.
As mentioned, I use the generation of html pages process to generate the ini file. 
In the example aff_app I have stored all view that generate (of mutate) ini files. Their names start with ‘sys_’. 
(Ok, I did ad ‘v_’ before all files. I like to make distinction: all files have a prefix, so you can easily see in the editor on with file you are working.)
In the example aff_site I deleted all sys files. So, you can see what code is necessary for only Html generation.

Back to the ini generation. If you login with admin, admin, you have extra menu options. I talk you through the options.

### CP-Views
The first one is a overview of all views. You can change them, if you click on ‘Change’. Then you see an overview of all lines in that view. After a click on ‘Change’ you get a form, where you can changes all settings.
In fact, it is not very different from changing the view ini file with the text editor. There is one advantage: the Javascript in that form hide and show the settings you (don’t) need for a specific type row. Because of this support, a non-developer can generate and change views. In fact, I did sold my framework a view times to customers. I did give a training of a few day’s to non-developers. After the training, they could make their own views and overviews.

Ok, the terms views. forms and overviews are a little confusing. Technically there are only views. But, sometimes you want a screen with a table. Then I call that view an overview. In other cases you want to let the user add, mutate or delete data. I give that views the name forms. Like the Html-tag form.
But, you can combine forms and overviews: in a view there is a column named ‘type’. You can choose between ‘Row’, ‘Column’ and ‘Javascript start’. After that choice you can select an element. The elements differ by type. As you select ‘Column’, you can only choose the elements ‘Data field’ and ‘Change link‘. So, there we have the table/overview.
But, forms and overviews do mostly have head text, buttons, etc. So, when you make a table/overview, you can add rows, to choose head text, buttons, etc.
When you make a form, you only choose the types ‘Row’ and maybe ‘Javascript start’. 
The ‘Javascript start’ is needed when you want to make elements invisible, depending on certain values in other elements. Like the view where you can make changes to a view row. Yes, you can change the functionality of generating ini files for a view with a view.

### CP-Models
In the menu the next options are ‘Models get’ and ‘Models put’. The way of mutate them is the same as views. So, in the first overview you see al models, after ‘Change’ you see the rows and after ‘Change’ you can modify a certain row.

### CP-Changes
Because the framework work with ini files, it is simple to changes thing over the whole application. If you change the functionality of the framework, you can make mutation over all ini files. After that, you must change your generation code. For example: you want to have a max length for textboxes. It’s not present in de view. You can add a column in all views by one action. After that changes, you must change the javascript you use to generate textboxes: you can add ‘length=…’.

### CP-Generate
This is the most difficult option: you can generate views, models and combinations with one click. Only the main field will be filled. After the generation, you must mutate the views and models. But, it’s nice to have the main structure in a little time. This is the main future of low-code principle!


## Content and connection views, models, Javascript, etc.
In this section a zoom in on the connection between all parts off the Html-generation. Thirst I describe the generation of the view, after that the way data is stored after a posting.

### Get view
You can open a ini file in the map views/view by a text editor.
The first seven columns are clear, not difficult to understand. 
The eight column is called ‘dataset_name’. In the current version, there is only one value possible. You cannot have two datasets. Some rows don’t need a dataset: the headtext and button are typically rows that don’t need a dataset.
The next column is ‘dataset_field’. Its clear, this is a column from the dataset.
The next four columns is specifically for dropdownlist: you can register the dataset, the column that must be store in ‘option’ in de ‘select’ element, the column that must be showed and finally you can register if a blank (first) row must be added in the dropdownlist. The value in ‘dataset_name. dataset_field’ will be selected in the dropdownlist (if present in the list).
The columns ‘check’, ‘blur’ and ‘start’ can contain javascript that must be used on posting, on leaving a element or at the start of the Html-generation.
The column ‘action’ is specific for buttons and the last column for overviews.

After loading the view ini, php does check if datasets of javascript is needed.

Datasets: the php collects the set in ‘dataset_name’ and the sets in ‘ddl_ds_value’. Then the models (ini files!) in the map models/model/get were loaded.
In that files there is registered where the data must be collected: the type can be ‘txt’, ‘mysql’ or ‘php’. If ‘txt’, the data is read from models/data. If ‘mysql’ there will be al call to the mysql database. If php, the php-file in models/php/get will be includes en de function is called. 

Javascript: when there is need of Javascript, the files will be read from views/javascript/dynamic. The way it is stored, depending on the type (check, blur, start).

### Post data
As mentioned, the browser (or another webserver) can post data to api.php. There must be posted a view name and a button id.
Each view_name does have a ‘put model’. In de map models/model/put you can find them. The name is the same as the view name. Only the view starts with ‘v_’ (view), the model with ‘mp_’ ( ‘model put’).
In de model put ini files is per button registered what the actions must be. The php-code walk trough these actions. I find that not much explanation is needed. There are different actions: in the current php-code only mysql, txt, next_view and php are supported. You can make more. In the actions mysql and txt the data is stored. They need of course the spec’s in the columns source_name, ref_id_db, ref_id_form, store_id_db and store_id_form. The action next_view need the spec’s in the column next_view and alert. The action php needs only source_name. This is the code in low-code! You can do anything you want with php. But, if you often mail, you can make a new action: mail. The more you can generate, the better. But things that will be not used more than once, you can use php for that action.


## Finish
I hope it is become clear. You can study all code yourself and make your own framework.
If you have comments or questions, email me at email@johnnyhogenbirk.nl.


