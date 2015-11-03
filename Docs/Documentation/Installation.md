Installation
============

To install the plugin, place the files in a directory labelled "Tags/" in your "app/Plugin/" directory.

Then, include the following line in your `app/Config/bootstrap.php` to load the plugin in your application.

```
CakePlugin::load('Tags');
```

Git Submodule
-------------

If you're using git for version control, you may want to add the **Tags** plugin as a submodule on your repository. To do so, run the following command from the base of your repository:

```
git submodule add git@github.com:CakeDC/tags.git app/Plugin/Tags
```

After doing so, you will see the submodule in your changes pending, plus the file ".gitmodules". Simply commit and push to your repository.

To initialize the submodule(s) run the following command:

```
git submodule update --init --recursive
```

To retreive the latest updates to the plugin, assuming you're using the "master" branch, go to "app/Plugin/Tags" and run the following command:

```
git pull origin master
```

If you're using another branch, just change "master" for the branch you are currently using.

If any updates are added, go back to the base of your own repository, commit and push your changes. This will update your repository to point to the latest updates to the plugin.

Composer
--------

The plugin also provides a "composer.json" file, to easily use the plugin through the Composer dependency manager.

Database Setup
--------------

The recommended way to install and maintain the database is using the [CakeDC Migrations](https://github.com/cakedc/migrations) plugin.

To set up the **Tags** plugin tables run this command:

```
./Console/cake migrations.migration run all -p Tags
```

Alternately you can use the build in [Schema Shell](http://book.cakephp.org/2.0/en/console-and-shells/schema-management-and-migrations.html) of CakePHP:

```
./Console/cake schema create tag --plugin Tags

```



