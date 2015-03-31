
## Easyshop.ph ##


Easyshop.ph is an e-commerce application developed on top of Codeigniter 2.1.4 along with some symfony components and Doctrine.


***What is CodeIgniter***

>CodeIgniter is an Application Development Framework - a toolkit - for people
who build web sites using PHP. Its goal is to enable you to develop projects
much faster than you could if you were writing code from scratch, by providing
a rich set of libraries for commonly needed tasks, as well as a simple
interface and logical structure to access these libraries. CodeIgniter lets
you creatively focus on your project by minimizing the amount of code needed
for a given task.


Have a look at the Codeigniter source code at https://github.com/EllisLab/CodeIgniter.


***The Good Stuff that CodeIgniter is Bad At***

Some of the good stuff that was added to the application: 
- [Composer](https://getcomposer.org/) PHP Package Manager
- Dependency Injection using [Pimple](http://pimple.sensiolabs.org/)
- OOP using Services
- Repository Pattern
- Object Relational Mapping using [Doctrine](http://www.doctrine-project.org/projects/orm.html)

***A FOOTNOTE TO CODEIGNITER MODELS***

The use of Codeigniter models in this application has been deprecated and their use has STRICTLY been discontinued. The reason for this is that CI's use of models diverges from the actual MVC implementation of many more modern frameworks such as Symfony, Laravel and Ruby on Rails: all of which encourage better programming practices in addition to being more OOP focused. In their most fundamental form, models are designed to model the busines objects of your application. A good way to think of a model is that it is a class that has one to one corellation with a table in the database. A user table would mean you would have a `User` model (class), and in effect a way to create a `User` object instance. This inherently encourages a more OOP-oriented way to code.

In this application, instead of using CI's models, business entities are to be accessed through the Doctrine ORM whose entities (the models in this application) can be found in
```
src/EasyShop/Entities
```
The business logic code that CI encourages to be sloppily slapped together in the subclasses of CI_Models are instead placed in appropriate service classes which are located in:
```
src/EasyShop
```
For example, custom logic for authentication can be placed in an `Authentication` class in 
```
src/EasyShop/Auth/Authentication.php 
```
rather than in a CI User_model.

In addition to this, re-usable database getter methods are placed in repositories following the repository pattern and can be found in 
```
src/Easyshop/Repositories
```


