﻿.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: Includes.txt

.. _start:

=============================================================================
Person Manager
=============================================================================


A TYPO3 extension to manage user/subscriber data.

Originally developed for the extension `newsletter`_.

Provides automatic subscription/registration and unsubscription/deregistration.

Issue Tracking at https://forge.typo3.org/projects/extension-person_manager.

-----------------------------------------------------------------------------

Configuration
=============================================================================


Please include the Plugin in you Main Template and then change to the Constant Editor.

Select PLUGIN.TX_PERSONMANAGER

Now you can check or uncheck fields, the plugin should use.



If you need additional Fields -> just rename a Freetext field in the locallang files.

If you want to use Categories, you have to add them in your default storage before testing the plugin.



Further options
-----------------------------------------------------------------------------


**Default storage PID (required)**
where the persons and the cateogries should be saved

**Double-Opt Out**
should the deregistration be confirmed by mail?

**Double-Opt In**
should the registration be confirmed by mail?

**URL or UID of the Registration (required)**
URL or UID of the Page, where the frontend plugin ``Person Manager Registration`` is used
eg. http://www.test.com/index.php?id=3&
make sure this URL ends with ? or &

**URL or UID of the Deregistration (required)**
URL or UID of the Page, where the frontend plugin ``Person Manager Deregistration`` is used
eg. http://www.test.com/index.php?id=3&
make sure this URL ends with ? or &

**Name of your webiste (required)**
used for automatic emails

**Email-Address of your website (required)**
used for automatic emails

-----------------------------------------------------------------------------



Frontend Plugins
=============================================================================


Just create a new ``Person Manager Registration`` or ``Person Manager Deregistration`` Plugin on the page where the user should subscribe.

Insert your Sender/Website Name and add a Signature if you want.


-----------------------------------------------------------------------------



Backend Plugin
=============================================================================


List
-----------------------------------------------------------------------------

Here you can add, search, show, edit or delete single Persons.

Import
-----------------------------------------------------------------------------

Here you can import excel or csv files of Persons.

First insert the columns you want to import.

Insert the seperator of your csv file **AND** the columns above.

Pick your file from the system and click ``Import``.

Check your data and finish by clicking ``Import`` again.

Export
-----------------------------------------------------------------------------

Here you can export excel or csv files of Persons.

Log
-----------------------------------------------------------------------------

Here you can see single actions what users of your website have done.

Blacklist
-----------------------------------------------------------------------------

Here you can import a file of Persons, that should be excluded from eg. sending a newsletter to.

It is similar to the normal Import but there must be only one column with the E-Mail Addresses.

-----------------------------------------------------------------------------



Working with the newsletter extension
=============================================================================

Get the persons
-----------------------------------------------------------------------------

To get the persons into a Recipient List just add a new one and choose the type ``SQL``.

Then past following SQL-Statement (or edit it):

``SELECT CASE salutation WHEN 1 THEN 'Dear Mr.' WHEN 2 THEN 'Dear Mrs.' ELSE 'Dear Mr./Mrs.' END AS salutation, firstname, lastname, email``

``FROM tx_personmanager_domain_model_person``

``WHERE deleted=0 AND hidden=0 AND unsubscribed=0 AND confirmed=1 AND active=1 AND email NOT LIKE "" AND email NOT IN (SELECT email FROM tx_personmanager_domain_model_blacklist)``

``GROUP BY email``

Unsubscription Link
-----------------------------------------------------------------------------

Just insert a link in your newsletter to the page with your ``Person Manager Deregistration`` Frontend Plugin.

<a href="https://www.test.com/index.php?id=100&mail=###email###">Unsubscribe</a>

<a href="https://www.test.com/index.php?id=100&mail=http://email">Unsubscribe</a>

Just replace the Domain and the Page ID


.. _newsletter: https://github.com/Ecodev/newsletter/