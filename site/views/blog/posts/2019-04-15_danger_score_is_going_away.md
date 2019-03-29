---------
author: "Corey Watts"
title: "We're Removing the Score (aka. Danger Score)"
---------

#### What's happening?

Check-in scores are going away. Related things like the Email Threshold or the "Last Month's Scores" graph are either going away or significantly changing.

#### Why?  
Over the years I've received many messages regarding the score. Most have fallen into two categories:  
1. how does the formula work  
2. concerns about the score being misleading (either too high or low)  

It's fairly clear that many users aren't quite sure how the score works. It's also clear that there are many who think a specific check-in's score doesn't quite _feel right_ for the behaviors they were exhibiting. I think those concerns are completely valid -- I have them too.

I've released tweaks and optimizations to the score several times and rejected many changes that never saw the light of day. Despite the time and effort I've put into creating a decent scoring formula I am still not satisfied with it. That feeling also comes through in the emails that users send me.

As I've slowly trained myself to recognize my behaviors and emotional state by using the Faster Scale, I've come to see the score the tool produces as distracting.

One of the core purposes of the Faster Scale is to help the person learn to name their feelings, to see the patterns in their behavior, and to foresee where they will go if action is not quickly taken. Adding a shortcut to that (like an easy numerical score) is self-defeating. It short-circuits the learning process the person needs to go through.

Adding a score to the Faster Scale was not actually an improvement to Dye's creation. At best, it was a distraction. That's why I intend to remove the "Danger Score" and all related functionality from the application.


#### What are the effects?
The most visible change involves the "Email Threshold". This feature sends an email report to the user's partners when they score above a value. Since I am removing the score, the "Email Threshold" needs to go as well. For now, sending partner reports will be a simple yes/no checkbox to ALWAYS or NEVER send a partner report. 
    
**When this change is rolled out, users that have enabled partner emails will be switched to always send reports _regardless of their Email Threshold value_. Likewise, users that do not have partner emails enabled (thus no set Email Threshold) will never have them sent.**

Ripping out the score-related features of the App will also have the effect of simplifying the codebase. Certain portions will be significantly simpler. This means I'll have an easier time implementing new features. I already have several updates in the pipeline that have been simplified with the score removal and I'm excited to get those out to all of our users. 

#### When will this go live?
If all goes well, I'm hoping to roll this out within the next week or two.

Do you think I'm making a mistake? Please send me any concerns or thoughts via the contact form or mailing list. I'd love to hear your feedback.