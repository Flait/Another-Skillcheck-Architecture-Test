# Another-Skillcheck-Architecture-Test
This is skillcheck for some company, focus is on OOP being prepared to switch for better technologies just by writing the clients/services.

To check how I handle docker, github actions and other stuff checkout my other repositories.

**Features:**
- Elastic & Mysql magic Drivers (These always return something to not deal with not found cases in this test)
- Switch of datasource usage through env variable 
- Simple file cache service with interface - ready to be changed for Redis
- Counter service that counts searches into file 
- Controller returning json
- One big application test for controller and whole functionality. I agree it would be nice to cover every service with unit test, but we will change them soon! :)
- MAX stan lvl

To run tests localy use makefile and setup the project by:
1. Installing php
2. Installing composer
3. running composer install
4. check makefile for stan and unit tests