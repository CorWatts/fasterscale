---------
author: "Corey Watts"
title: "We have News"
---------

We have news! No, literally, we now have "news feed" functionality on this site. We spent a bit of time to create a new extension for [Yii2](https://yiiframework.com) (the framework this site is built in). It provides an easy way to write posts in a simple format _(Markdown)_ that is then automatically converted to HTML markup. You can take a look at this extension here: [yii2-markdown-files](https://github.com/CorWatts/yii2-markdown-files).

With this addition, announcing larger changes is easier to do. Thus, we hope you won't be blindsided by the large and semi-large changes we have in the pipeline right now. They should be released soon _(hopefully...)_.

As a teaser:  

- COMPLETED <s>We're in the process of radically improving the formula for calculating the "Danger Score". This new formula should make more sense and be on a 1-100 scale. **What?! The current scale is NOT on a 0-100 scale?** It's a dirty little secret but it's true. It will be much better, but the numbers you're used to will change.</s>  
- COMPLETED <s>We've almost completed major refactoring of some of the deeper code in this application. We're no longer storing what is essentially static data in the database. Instead, it's stored in memory. This will make certain parts of this application easier to test and will hopefully increase performance by a small amount.</s>