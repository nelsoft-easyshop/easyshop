Once you have a custom DQL function, you need to register it with the entity manager. 

$config->addCustomStringFunction('DATEDIFF', 'EasyShop\Doctrine\Query\MySql\DateDiff');

You can find dql extensions here: https://github.com/beberlei/DoctrineExtensions/tree/master/lib/DoctrineExtensions/Query/Mysql


