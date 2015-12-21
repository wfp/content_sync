# Sync Data

Module for exporting and importing menu items and taxonomy terms etc.
You can import/export any type of entities using this module but filtering capabilities during export is implemented only for `menu_link_content` & `taxonomy_term` entities.

This module exposes [Drupal Console](https://drupalconsole.com/) commands. There is nothing configurable from the UI.

# Installation
Install it as any other Drupal module - [Installing contributed modules (Drupal 8)](https://www.drupal.org/documentation/install/modules-themes/modules-8)

This module uses `Drupal Console` so yoi need to have it installed too.

## Export
`drupal content_sync:export` - This is for all types of entities, it's an interactive wizard which will ask you to input entity type from suggested list of entities.

### Export Menu Items
If you choose `menu_link_content` from suggested entity types you will get list of menus from which you will chose the menu you want to export.
After you choose menu you have to input module name in which content will be exported.
Thats it, you will find exported JSON files in specified module - `modules/custom/{module_name}/content/`

`NOTE`: this exports only menu items, not menu itself, you have to export/import menu using [Configuration Manager](https://www.drupal.org/documentation/administer/config).

### Export Taxonomy Terms
If you choose `taxonomy_term` from suggested entity types you will get list of vocabularies from which you will choose the vocabulary you want to export.
After you choose vocabulary you have to input module name in which content will be exported.
Thats it, you will find exported JSON files in specified module - `modules/custom/{module_name}/content/`.

Options for basic export:

* content_type - entity type id (machine name) of an entity you want to export.
* module - Module machine name to which content will be exported.

Options for menu items export:

* content_type - entity type id - `menu_link_content`.
* module - Module machine name to which content will be exported.
* menu_name - name of the menu.

Options for menu texonomy terms export:

* content_type - entity type id - `taxonomy_term`.
* module - Module machine name to which content will be exported.
* vid - Vocabulary id



`NOTE:` this only exports Taxonomy terms as vocabularies are handled by CM in core.



## Import
`drupal content_sync:import` - This will import any type of entities generated in JSON format using `drupal content_sync:export`, it will ask module name from which content will be imported. It is expected that JSON files are stored in `content/{entity_type_id}` folder under module.

Options for importing:

* folder - path to the folder where JSON files reside.

`NOTE:`

It will try to import any JSON file found int `content` folder, so be careful and think what you want to achieve.

E.g. if you want to import menu items make sure that menu is already empty to prevent conflicting `uuid`s.




## Delete

There are 2 delete commands:

* `drupal content_sync:delete-taxonomy-terms`
* `drupal content_sync:delete-menu-items`
