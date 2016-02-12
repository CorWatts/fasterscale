# The Faster Scale App
An online version of The Faster Scale

## Installation
1. Install PHP > 5.5
1. Install some kind of SQL. I use PostGRES but I think the Yii2 ORM supports lots of others. I also took a bit of care to make the SQL db agnostic.
1. Clone repo, customize config files, setup database user and password
1. Memcache might be a problem. The codebase assumes it's installed. I should probably make it just use the dummycache by default....
1. run all yii2 db migrations
1. signup a new user
1. log in, start workin


## Roadmap
#### Pressing needs
* Create reminder functionality detailed in [issue #13](https://github.com/CorWatts/fasterscale/issues/13)
* ~~Add ability to delete your account detailed in [issue #12](https://github.com/CorWatts/fasterscale/issues/12)~~

#### Lesser needs
* Make it a lot easier to install locally in a development environment
* the UI needs tons of work. Pretty darn ugly right now

#### 10000" view
* Move away from bootstrap (to material design?)
* move to Single Page App with one backend api and a frontend framework (Angular?)
