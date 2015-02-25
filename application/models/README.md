## CODEIGNITER MODELS ##

The use of Codeigniter models in this application has been deprecated and their use has STRICTLY been discontinued. The reason for this is that CI's use of models diverges from the actual MVC implementation of many more modern frameworks such as Symfony, Laravel and Ruby on Rails: all of which encourage better programming practices in addition to being more OOP focused. In their most fundamental form, models are designed to model the busines objects of your application. A good way to think of a model is that it is a class that has one to one corellation with a table in the database. A user table would mean you would have a `User` model (class), and in effect a way to create an instance of the `User` object, hence an inherently more OOP-oriented way to code.

In this application, instead of using CI's models, business entities are to be accessed through the Doctrine ORM whose entities (the models in this application) can be found in src/EasyShop/Entities. The business logic code that CI encourages to be sloppily slapped together in the subclasses of CI_Models are instead placed in appropriate service classes which are in turn located in:
```
src/EasyShop. 
```
For example, custom logic for authentication can be placed in an `Authentication` class in 
```
src/EasyShop/Auth/Authentication.php 
```
rather than in a CI User_model.

In addition to this, re-usable database getter methods are placed in repositories following the repository pattern and can be found in `src/Easyshop/Repositories`. 

Please do not add any additional methods or classes in this directory other than bug fixes for old methods. This move was implemented in anticipation of the complete death of Codeigniter as a framework and the migration to a more modern PHP framework particularly Symfony.
