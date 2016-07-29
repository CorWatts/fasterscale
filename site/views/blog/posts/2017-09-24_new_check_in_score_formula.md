---------
author: "Corey Watts"  
title: "New Check-In Score Formula"  
---------

With this release we've updated the check-in score formula. A significant amount of refactoring and various code improvements have also been included. As a result, the score calculation code is cleaner and easier to read and test. Most importantly, however, the formula is now *mostly* sane.

We had considered eliminating the check-in score altogether. It is difficult to create a satisfying formula and this new version isn't entirely satisfying. Please, as a reminder, do not take your check-in scores as gospel. 

**The score now has a maximum value of 100.** The scores you now receive will be lower numbers than what they used to be...that is because the maximum score value was previously 1000.

Please direct questions or comments to our new mailing list. You can subscribe [on this page](https://www.freelists.org/list/fsa-discuss).

#### <u>The New Formula</u>

1. For each category, calculate the percentage of selected behaviors and double that decimal number: `($selected_behaviors / $all_behaviors) * 2`. Then take whichever is smaller: that number or 1.
2. Normalize each of the [category weights](https://github.com/CorWatts/fasterscale/blob/master/common/models/Category.php#L16): `($category_weight * 100) / $sum_of_category_weights`. Then, for each category, multiply its weight by its score calculated in step 1.
3. Sum the category scores calculated in step 2. That's your overall score.

#### <u>Rationalizations</u>

##### Percentages
It doesn't matter which specific behaviors a user selects within a category, only the number of behaviors selected. However, each category has a different number of selectable behaviors. To avoid giving a larger category a naturally higher potential score, we calculate the percentage of behaviors selected in each category.

##### Doubling selected behavior percentage
Selecting just one or two behaviors in a category is a big deal as you move down the scale. To reflect this, we double the percentage of selected behaviors in a category. Remember, we have a normalized weight for each category -- essentially the maximum point value a category can have. We multiply that maximum point value by the percentage of selected behaviors. Example:

A person selects two behaviors in the `Relapsed/Moral Failure` category. That is 2/11 *(18%)* of the behaviors selected. If the `Relapsed/Moral Failure` category has a normalized weight of 32 points, they would receive `.18 * 32` or just under 6 points added to their score. To enable each category to properly affect the score we double the percentage of selected behaviors -- instead of
`18% * 32` we calculate `36% * 32`. Increasing the score by 12 (rounded) points is more appropriate.

The minimum of that doubled percentage or 1 is then multiplied by the category score. We don't want the `Relapsed/Moral Failure` category to supply more than it's assigned weight to the overall score, so the *maximum* it can contribute is `1 * 32` points.

