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
  public $email;
  public $timezone;
  public $expose_graph;
  public $send_email;
  public $email_threshold;
  public $partner_email1;
  public $partner_email2;
  public $partner_email3;

  private $user;

  /**
   * Creates a form model
   *
   * @param  object                          $user
   * @param  array                           $config name-value pairs that will be used to initialize the object properties
   * @throws \yii\base\InvalidParamException if token is empty or not valid
   */
  public function __construct(\common\models\User $user, $config = []) {
    $this->user = $user;
    parent::__construct($config);
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      ['email', 'filter', 'filter' => 'trim'],
      ['email', 'filter', 'filter' => 'strtolower'],
      ['email', 'email'],
      ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.', 'filter' => "id <> ".Yii::$app->user->id],


      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],

      ['expose_graph', 'boolean'],
      ['send_email', 'boolean'],
      ['email_threshold', 'integer'],
      ['email_threshold', 'required', 'when'=> function($model) {
        return $model->send_email;
      }, 'message' => "If you've elected to send email reports, you must set a threshold.", "whenClient" => "function(attribute, value) {
        return $('#signupform-send_email').is(':checked');
  }"],
    [['partner_email1', 'partner_email2', 'partner_email3'], 'email'],
    [['partner_email1'], 'required', 'when' => function($model) {
      return $model->send_email;
    }, 'message' => "If you've elected to send email reports, at least one partner email must be set.", "whenClient" => "function(attribute, value) {
      return $('#signupform-send_email').is(':checked');
  }"]
  ];
  }

  public function attributeLabels() {
    return [
      'partner_email1' => "Partner Email #1",
      'partner_email2' => "Partner Email #2",
      'partner_email3' => "Partner Email #3",
      'send_email'     => 'Send an email when I score above a certain threshold',
      'expose_graph'   => 'Share my scores graph via a link'
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
        ->get('common\components\Graph', [$this->user]);

      if($this->email)
        $user->email = $this->email;
      if($this->timezone)
        $user->timezone = $this->timezone;
      if($this->expose_graph) {
        $user->expose_graph = true;

        // generate scores graph image
        $scores_last_month = (Yii::$container->get('common\interfaces\UserBehaviorInterface'))->calculateScoresOfLastMonth();
        // if they haven't done a check-in in the last month this
        // will explode because $scores_last_month is an empty
        // array
        if($scores_last_month) {
          $graph->create($scores_last_month, true);
        }
      } else {
        $user->expose_graph = false;
        // remove scores graph image
        $graph->destroy();
      }
      if($this->send_email) {
        $user->email_threshold = $this->email_threshold;
        $user->partner_email1  = $this->partner_email1;
        $user->partner_email2  = $this->partner_email2;
        $user->partner_email3  = $this->partner_email3;
      } else {
        $user->email_threshold = null;
        $user->partner_email1  = null;
        $user->partner_email2  = null;
        $user->partner_email3  = null;
      }
      $user->save();

      return $user;
    }

    return null;
  }

  public function loadUser() {
    $user                  = $this->user;
    $this->email           = $user->email;
    $this->timezone        = $user->timezone;
    $this->email_threshold = $user->email_threshold;
    $this->partner_email1  = $user->partner_email1;
    $this->partner_email2  = $user->partner_email2;
    $this->partner_email3  = $user->partner_email3;
    $this->expose_graph    = $user->expose_graph;
    $this->send_email      = (isset($user->email_threshold) && array_filter($user->getPartnerEmails()));
  }
}
