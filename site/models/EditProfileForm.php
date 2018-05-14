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
  public $timezone;
  public $expose_graph;
  public $send_email;
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
      ['timezone', 'string', 'min' => 2, 'max' => 255],
      ['timezone', 'in', 'range'=>DateTimeZone::listIdentifiers()],

      ['expose_graph', 'boolean'],
      ['send_email', 'boolean'],
      [['partner_email1', 'partner_email2', 'partner_email3'], 'email'],
      [['partner_email1'], 'required',
        'when' => function($model) { return $model->send_email; },
        'message' => "If you've elected to send email reports, at least one partner email must be set.",
        "whenClient" => "function(attribute, value) {
          return $('#editprofileform-send_email').is(':checked');
        }"]
    ];
  }

  /**
     * @codeCoverageIgnore
     */
  public function attributeLabels() {
    return [
      'partner_email1' => "Partner Email #1",
      'partner_email2' => "Partner Email #2",
      'partner_email3' => "Partner Email #3",
      'send_email'     => 'Send an email when I complete a check-in',
      'expose_graph'   => 'Share my scores graph via a link'
    ];
  }

  /**
   * saves user's profile info.
   *
   * @return User|null the saved model or null if saving fails
   */
  public function saveProfile() {
    if ($this->validate()) {
      $user  = $this->user;

      $graph = Yii::$container
        ->get(\common\components\Graph::class, [$this->user]);

      if($this->timezone) {
        $user->timezone = $this->timezone;
      }
      if($this->expose_graph) {
        $user->expose_graph = true;

        // generate scores graph image
        $checkins_last_month = (Yii::$container->get(\common\interfaces\UserBehaviorInterface::class))
                                               ->getCheckInBreakdown();

        // if they haven't done a check-in in the last month this will explode
        // because $checkins_last_month is an empty array
        if($checkins_last_month) {
          $graph->create($checkins_last_month, true);
        }
      } else {
        $user->expose_graph = false;
        // remove scores graph image
        $graph->destroy();
      }

      if($this->send_email) {
        $user->send_email = true;
        $user->partner_email1  = $this->partner_email1;
        $user->partner_email2  = $this->partner_email2;
        $user->partner_email3  = $this->partner_email3;
      } else {
        $user->send_email = false;
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
    $this->timezone        = $user->timezone;
    $this->partner_email1  = $user->partner_email1;
    $this->partner_email2  = $user->partner_email2;
    $this->partner_email3  = $user->partner_email3;
    $this->expose_graph    = $user->expose_graph;
    $this->send_email      = $user->send_email;
  }
}
