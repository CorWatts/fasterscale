<?php
namespace site\models;

use yii\base\Model;
use Yii;
use \DateTimeZone;

/**
 * edit profile form
 */
class EditProfileForm extends Model
{
    const DEFAULT_CATEGORY_THRESHOLD = 4;

    public $timezone;
    public $expose_graph;
    public $send_email;
    public $email_category;
    public $partner_email1;
    public $partner_email2;
    public $partner_email3;

    private $user;
    private $categories;

    /**
     * Creates a form model
     *
     * @param  object                          $user
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct(\common\models\User $user, $config = [])
    {
        $this->user = $user;
        $this->categories = \common\models\Category::getCategories();
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
      ['email_category', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],
      [['timezone', 'expose_graph', 'send_email'], 'required'],
      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],
      ['expose_graph', 'boolean'],
      ['send_email', 'boolean'],
      ['email_category', 'integer'],
      ['email_category', 'in', 'range'=>array_keys($this->categories)],
      [['partner_email1', 'partner_email2', 'partner_email3'], 'email'],
      [['partner_email1', 'email_category'], 'required',
        'when' => function ($model) {
            return $model->send_email;
        },
        'message' => "If you've elected to send email reports, at least one partner email must be set.",
        "whenClient" => "function(attribute, value) {
          return $('#editprofileform-send_email').is(':checked');
        }"]
    ];
    }

    /**
       * @codeCoverageIgnore
       */
    public function attributeLabels()
    {
        return [
      'partner_email1' => "Partner Email #1",
      'partner_email2' => "Partner Email #2",
      'partner_email3' => "Partner Email #3",
      'send_email'     => 'Send an email when I select behaviors at or above a specific FASTER category',
      'email_category' => 'FASTER Category',
      'expose_graph'   => 'Share my behaviors graph via a link'
    ];
    }

    /**
     * saves user's profile info.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function saveProfile()
    {
        if ($this->validate()) {
            $user  = $this->user;

            $graph = Yii::$container
        ->get(\common\components\Graph::class, [$this->user]);

            if ($this->timezone) {
                $user->timezone = $this->timezone;
            }
            if ($this->expose_graph) {
                $user->expose_graph = true;

                // generate behaviors graph image
                $checkins_last_month = (Yii::$container->get(\common\interfaces\UserBehaviorInterface::class))
                                               ->getCheckInBreakdown();

                // if they haven't done a check-in in the last month this will explode
                // because $checkins_last_month is an empty array
                if ($checkins_last_month) {
                    $graph->create($checkins_last_month, true);
                }
            } else {
                $user->expose_graph = false;
                // remove behaviors graph image
                $graph->destroy();
            }

            if ($this->send_email) {
                $user->send_email = true;
                $user->email_category = $this->email_category;
                $user->partner_email1 = $this->partner_email1;
                $user->partner_email2 = $this->partner_email2;
                $user->partner_email3 = $this->partner_email3;
            } else {
                $user->send_email = false;
                $user->email_category = 4; // default to "Speeding Up"
                $user->partner_email1 = null;
                $user->partner_email2 = null;
                $user->partner_email3 = null;
            }
            $user->save();
            return $user;
        }

        return null;
    }

    public function loadUser()
    {
        $user                  = $this->user;
        $this->timezone        = $user->timezone;
        $this->partner_email1  = $user->partner_email1;
        $this->partner_email2  = $user->partner_email2;
        $this->partner_email3  = $user->partner_email3;
        $this->expose_graph    = $user->expose_graph;
        $this->send_email      = $user->send_email;
        $this->email_category  = $user->email_category;
    }
}
