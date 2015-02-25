## CODEIGNITER MODELS ##


The use of Codeigniter models has been deprecated and will no longer be supported as decided by the development team. 
Instead business entities are to be accessed using the Doctrine ORM which can be found in src/EasyShop/Entities.
Business logic at the same time is to be placed in appropriate classes/services which are also located in 
src/EasyShop. In addition to this, re-usable getter methods are to be placed in repositories following the repository
pattern and can be found in src/Easyshop/Repositories. 

Please do not add any additional methods or classes in this directory other than bug fixes for old methods. This move
was implemented in anticipation of the complete death of Codeigniter as a framework and the migration to a more modern
PHP framework particularly Symfony.
