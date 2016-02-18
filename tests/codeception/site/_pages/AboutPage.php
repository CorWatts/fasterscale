<?php

namespace tests\codeception\site\_pages;

use yii\codeception\BasePage;

/**
 * Represents about page
 * @property \codeception_site\AcceptanceTester|\codeception_site\FunctionalTester $actor
 */
class AboutPage extends BasePage
{
    public $route = 'site/about';
}
